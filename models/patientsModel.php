<?php
/** FUNCTIONS TO VIEW, ADD, EDIT, REMOVE PATIENTS TO AND FROM THE DATABASE*/
include APPOINTMENTS_MODEL;


/**add patient to the database
 * @param $fileNumber
 * @param $name
 * @param $phoneNo
 * @param $dateOfBirth
 * @param $progressNotes
 * @return array
 */
function addPatientToDatabase($fileNumber, $name, $phoneNo, $dateOfBirth, $progressNotes = null)
{
    $result = ['result' => false, 'message' => 'Unable to save patient !'];
    $isPatientDataValid = isPatientDataValid($fileNumber, $name, $phoneNo, $dateOfBirth, $progressNotes);
    if (!$isPatientDataValid['result']) {
        $result['message'] = $isPatientDataValid['message'];
        return $result;
    }
    $patients = getPatients();
    $patientExists = false;
    // good for adding sorting before searching
    foreach ($patients as $patient) {
        $valuesExist = $patient['FileNumber'] === $fileNumber || ($patient['PatientNo'] === $phoneNo && $patient['PatientName'] === $name);
        if ($valuesExist) {
            $patientExists = true;
            break;
        }
    }
    if ($patientExists) {
        $result['message'] = "Similar Patient Already Exists !";
        return $result;
    }
    $data = [
        'FileNumber' => $fileNumber,
        'PatientName' => $name,
        'PatientNo' => $phoneNo,
        'DOB' => $dateOfBirth,
    ];
    if ($progressNotes) {
        $data['ProgressNotes'] = $progressNotes;
    }
    $appObject = getAppointmentObject($data);
    $appObject = setAppointmentId($appObject);
    $patientSaved = saveRecord(PATIENTS_COLLECTION, $appObject, true);
    if ($patientSaved) {
        $result['message'] = "Patient Saved Successfully";
        $result['result'] = true;
        return $result;
    }
    return $result;
}

/**modify patient in the database
 * @param $fileNumber
 * @param $name
 * @param $phoneNo
 * @param $dateOfBirth
 * @param $firebaseId
 * @param array $progressNotes
 * @return array
 */
function editPatientInDatabase($fileNumber, $name, $phoneNo, $dateOfBirth, $firebaseId, $progressNotes = null)
{
    $result = ['result' => false, 'message' => 'Unable to edit patient !'];

    $isPatientDataValid = isPatientDataValid($fileNumber, $name, $phoneNo, $dateOfBirth, $progressNotes);
    if (!$isPatientDataValid['result']) {
        $result['message'] = $isPatientDataValid['message'];
        return $result;
    }
    if (!$firebaseId) {
        $result['message'] = 'Please Select Patient Again';
        return $result;
    }

    $data = [];
    $appointment = getAppointments(['param' => 'FirebaseId', 'operator' => '==', 'value' => $firebaseId], true);
    if (count($appointment) > 0) {
        $data = $appointment; // old data
    }
    $data['FileNumber'] = $fileNumber;
    $data['PatientName'] = $name;
    $data['PatientNo'] = $phoneNo;
    $data['DOB'] = $dateOfBirth;

    if ($progressNotes) {
        $data['ProgressNotes'] = $progressNotes;
    }
    $appObject = getAppointmentObject($data);
    $appObject = setAppointmentId($appObject);
    //['result' => false, 'message' => '']
    $patientSaved = saveRecord(PATIENTS_COLLECTION, $appObject, false);
    if ($patientSaved) {
        $result['message'] = "Patient Saved Successfully";
        $result['result'] = true;
        return $result;
    }
    return $result;
}

/**remove existing patient
 * @param $firebaseId
 * @return null
 */
function deletePatientFromDatabase($firebaseId)
{
    $result = ['result' => false, 'message' => 'Unable to remove patient !'];
    if (!$firebaseId) {
        $result['message'] = "Please Enter Valid Id";
        return $result;
    }
    $query = ['param' => 'FirebaseId', 'operator' => '==', 'value' => $firebaseId];
    $patientSaved = removeRecord(PATIENTS_COLLECTION, $query);
    if ($patientSaved) {
        $result['message'] = LIST_PATIENT; // redirect to list patient in javascript
        $result['result'] = true;
        return $result;
    }
    return $result;
}

/**get patients existing in the database
 * filters:- name, PatientNo, AppID
 * @param array $filters
 * @param bool $allPatients
 * @return array
 */
function getPatients($filters = [], $allPatients = false)
{
    $patients = getRecords(PATIENTS_COLLECTION, $filters);
    if ($allPatients) {
        return $patients;
    }
    // remove duplicates since patients collection is same as appointments collection
    return array_unique($patients, SORT_REGULAR);
}

/**get patients from database and extract patient notes
 * patientData:- patient object
 * @param $patientData // format is  [PatientName, DentistName, AppointmentDate, ProgressNotes]
 * @return array
 */
function getPatientNotes($patientData)
{
    if (!isset($patientData)) {
        return [];
    }
    $decodedData = json_decode($patientData, true);

    // first sanitize key value pairs
    $noteData = sanitizeArray($decodedData);

    // find all patient records for this patient
    $search = ['param' => 'PatientName', 'operator' => '==', 'value' => $noteData['PatientName']];
    $patientRecords = getPatients($search, true);
    $notes = [];
    foreach ($patientRecords as $record) {
        $note = getPatientNote($record);
        if ($note) {
            $notes[] = $note;
        }
    }

    return $notes;
}

/** get note to show on notes table
 * patientRecord:- patient object
 * @param $patientRecord // format is  [PatientName, DentistName, AppointmentDate, ProgressNotes]
 * @return array | null
 */
function getPatientNote($patientRecord)
{
    // only show dentist note, viewing details like crown and carries requires clicking edit
    // then viewing notes edit page with all data filled in
    if (!isset($patientRecord['ProgressNotes'])) {
        return null;
    }
    $progressNotes = $patientRecord['ProgressNotes'];
    if (!is_array($progressNotes)) {
        return null;
    }

    $date = getDefaultDate();
    if (isset($patientRecord['AppointmentDate'])) {
        if (dateIsCorrupted($patientRecord['AppointmentDate'])) {
            $patientRecord['AppointmentDate'] = $date; // set to default
        } else { // otherwise
            $date = $patientRecord['AppointmentDate'];
        }
    }
    // set supported date format for date input(yyyy-mm-dd)
    $patientRecord['AppointmentDate'] = getNiceDate($date, 'Y-m-t');

    // set value for date column to format Dec 31, 2021
    $date = getNiceDate($date);
    return [
        'PatientName' => $patientRecord['PatientName'],
        'DentistName' => $patientRecord['DentistName'],
        'PatientNo' => $patientRecord['PatientNo'],
        'FileNumber' => $patientRecord['FileNumber'],
        'DOB' => $patientRecord['DOB'],
        'Date' => $date,
        'Note' => $progressNotes['Note'],
        'AppointmentDate' => $patientRecord['AppointmentDate'],
        'FirebaseId' => $patientRecord['FirebaseId'],
        'ProgressNotes' => $progressNotes,
    ];
}

/**get patients existing in the database
 * filters:- name, email, phoneNo
 * @param $fileNumber
 * @param $name // not used but could need validation in future
 * @param $phoneNo
 * @param $dateOfBirth
 * @param $progresNotes
 * @return array
 */
function isPatientDataValid($fileNumber, $name, $phoneNo, $dateOfBirth, $progresNotes)
{
    $result = ['result' => false, 'message' => 'Unable to save patient !'];
    $isFileNumberValid = isFileNumberValid($fileNumber);
    if (!$isFileNumberValid && !$progresNotes) {
        $result['message'] = "Please Enter a Valid File No";
        return $result;
    }
    $phoneNoIsValid = isPhoneNoValid($phoneNo);
    if (!$phoneNoIsValid) {
        $result['message'] = "Please Enter Valid Phone No";
        return $result;
    }
    $isDateOfBirthValid = isDateOfBirthValid($dateOfBirth);
    if (!$isDateOfBirthValid) {
        $result['message'] = "Please Enter a Valid Date of Birth";
        return $result;
    }
    $result['result'] = true;
    return $result;
}
