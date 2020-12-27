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
    $isPatientDataValid = isPatientDataValid($fileNumber, $name, $phoneNo, $dateOfBirth);
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
        $appObject = getAppointmentObject($data);
        $appObject = setAppointmentId($appObject);
        // $appObject['FirebaseId'] = null;
        $patientSaved = saveRecord(APPOINTMENTS_COLLECTION, $appObject, true);
    } else {
        $patientSaved = saveRecord(PATIENTS_COLLECTION, $data, true);
    }
    if ($patientSaved) {
        $result['message'] = "Patient Saved Successfully";
        $result['result'] = true;
        return $result;
    }
    return $result;
}

/**add patient to the database
 * @param $fileNumber
 * @param $name
 * @param $phoneNo
 * @param $dateOfBirth
 * @param $progressNotes
 * @param $dentist
 * @return array
 */
function addPatientNotesToDatabase($fileNumber, $name, $phoneNo, $dateOfBirth, $progressNotes, $dentist)
{
    $result = ['result' => false, 'message' => 'Unable to save patient Notes !'];
    $isPatientDataValid = isPatientDataValid($fileNumber, $name, $phoneNo, $dateOfBirth);
    if (!$isPatientDataValid['result']) {
        $result['message'] = $isPatientDataValid['message'];
        return $result;
    }
    $data = [
        'FileNumber' => $fileNumber,
        'PatientName' => $name,
        'PatientNo' => $phoneNo,
        'DOB' => $dateOfBirth,
        'DentistName' => $dentist,
    ];
    $data['AppointmentDate'] = getDefaultDate();
    $data['AppointmentTime'] = getDefaultDate(null, true);
    $isDentistAvailable = isDentistAvailable($data['DentistName'], $data['AppointmentTime']);
    if (!$isDentistAvailable['result']) {
        $result['message'] = $isDentistAvailable['message'];
        return $result;
    }
    $data['ProgressNotes'] = $progressNotes;
    $appObject = getAppointmentObject($data);
    $appObject = setAppointmentId($appObject);
    $patientSaved = saveRecord(APPOINTMENTS_COLLECTION, $appObject, true);

    if ($patientSaved) {
        $result['message'] = "Patient Notes Saved Successfully";
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
function editPatientNotesInDatabase($fileNumber, $name, $phoneNo, $dateOfBirth, $firebaseId, $progressNotes)
{
    $result = ['result' => false, 'message' => 'Unable to edit patient notes !'];

    $isPatientDataValid = isPatientDataValid($fileNumber, $name, $phoneNo, $dateOfBirth);
    if (!$isPatientDataValid['result']) {
        $result['message'] = $isPatientDataValid['message'];
        return $result;
    }
    if (!$firebaseId) {
        $result['message'] = 'Please Select Patient Again';
        return $result;
    }

    $data = [];
    $patient = getAppointments(['param' => 'FirebaseId', 'operator' => '==', 'value' => $firebaseId], true);
    if (count($patient) > 0) {
        $data = $patient; // old data
    }
    $data['FirebaseId'] = $firebaseId;
    $data['FileNumber'] = $fileNumber;
    $data['PatientName'] = $name;
    $data['PatientNo'] = $phoneNo;
    $data['DOB'] = $dateOfBirth;

    $data['ProgressNotes'] = $progressNotes;
    $appObject = getAppointmentObject($data);
    $appObject = setAppointmentId($appObject);
    $patientSaved = saveRecord(APPOINTMENTS_COLLECTION, $appObject, false);

    if ($patientSaved) {
        $result['message'] = "Patient Notes Saved Successfully";
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
 * @return array
 */
function editPatientInDatabase($fileNumber, $name, $phoneNo, $dateOfBirth, $firebaseId)
{
    $result = ['result' => false, 'message' => 'Unable to edit patient !'];

    $isPatientDataValid = isPatientDataValid($fileNumber, $name, $phoneNo, $dateOfBirth);
    if (!$isPatientDataValid['result']) {
        $result['message'] = $isPatientDataValid['message'];
        return $result;
    }
    if (!$firebaseId) {
        $result['message'] = 'Please Select Patient Again';
        return $result;
    }

    $data = [];
    $patient = getPatients(['param' => 'FirebaseId', 'operator' => '==', 'value' => $firebaseId], false, true);
    if (count($patient) > 0) {
        $data = $patient; // old data
    }
    $data['FirebaseId'] = $firebaseId;
    $data['FileNumber'] = $fileNumber;
    $data['PatientName'] = $name;
    $data['PatientNo'] = $phoneNo;
    $data['DOB'] = $dateOfBirth;

    $patientSaved = saveRecord(PATIENTS_COLLECTION, $data, false);
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

/**remove existing patient notes
 * @param $firebaseId
 * @return null
 */
function deletePatientNotesFromDatabase($firebaseId)
{
    $result = ['result' => false, 'message' => 'Unable to remove patient !'];
    if (!$firebaseId) {
        $result['message'] = "Please Enter Valid Id";
        return $result;
    }
    $query = ['param' => 'FirebaseId', 'operator' => '==', 'value' => $firebaseId];
    $patientSaved = removeRecord(APPOINTMENTS_COLLECTION, $query);
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
 * @param bool $getOne
 * @return array
 */
function getPatients($filters = [], $allPatients = false, $getOne = false)
{
    return getPatientInfo($filters, $allPatients, $getOne);
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
    // were searching using patient name since multiple family members might use same phone Number
    // so I removed searching using with PatientNo, which would have been better since patient names can
    // change and access to patient history then becomes unreachable
    // an even better reference parameter would be the patient firebase id
    // (store it in appointments when adding them)
    // but I am not using that to maintain compatibility with old code
    $search = ['param' => 'PatientName', 'operator' => '==', 'value' => $noteData['PatientName']];
    // $search = ['param' => 'PatientNo', 'operator' => '==', 'value' => $noteData['PatientNo']];

    // get all previous patient records
    $patientRecords = getAppointments($search, false);
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
 * appointmentRecord:- appointment object
 * @param $appointmentRecord // format is  [PatientName, DentistName, AppointmentDate, ProgressNotes]
 * @return array | null
 */
function getPatientNote($appointmentRecord)
{
    // only show dentist note, viewing details like crown and carries requires clicking edit
    // then viewing notes edit page with all data filled in
    if (!isset($appointmentRecord['ProgressNotes'])) {
        return null;
    }
    $progressNotes = $appointmentRecord['ProgressNotes'];
    if (!is_array($progressNotes)) {
        return null;
    }

    $date = getDefaultDate();
    if (isset($appointmentRecord['AppointmentDate'])) {
        if (dateIsCorrupted($appointmentRecord['AppointmentDate'])) {
            $appointmentRecord['AppointmentDate'] = $date; // set to default
        } else { // otherwise
            $date = $appointmentRecord['AppointmentDate'];
        }
    }
    // set supported date format for date input(yyyy-mm-dd)
    $appointmentRecord['AppointmentDate'] = getNiceDate($date, 'Y-m-d');

    // set value for date column to format Dec 31, 2021
    $date = getNiceDate($date);
    $appointmentRecord['Date'] = $date;
    $appointmentRecord['ProgressNotes'] = $progressNotes;
    $appointmentRecord['Note'] = isset($progressNotes['Note']) ? $progressNotes['Note'] : 'edit to add notes';
    return $appointmentRecord;
    /* use commented array if keys in appointment record are not similar to keys required
     by calling method
     * [
        'PatientName' => $appointmentRecord['PatientName'],
        'DentistName' => $appointmentRecord['DentistName'],
        'PatientNo' => $appointmentRecord['PatientNo'],
        'FileNumber' => $appointmentRecord['FileNumber'],
        'DOB' => $appointmentRecord['DOB'],
        'Date' => $date,
        'Note' => $progressNotes['Note'],
        'AppointmentDate' => $appointmentRecord['AppointmentDate'],
        'FirebaseId' => $appointmentRecord['FirebaseId'],
        'ProgressNotes' => $progressNotes,
    ]*/
}

/**get patients existing in the database
 * filters:- name, email, phoneNo
 * @param $fileNumber
 * @param $name // not used but could need validation in future
 * @param $phoneNo
 * @param $dateOfBirth
 * @return array
 */
function isPatientDataValid($fileNumber, $name, $phoneNo, $dateOfBirth)
{
    $result = ['result' => false, 'message' => 'Unable to save patient !'];
    $isFileNumberValid = isFileNumberValid($fileNumber);
    if (!$isFileNumberValid) {
        $result['message'] = "Please Enter a Valid File No";
        return $result;
    }
    if (!$name) {
        $result['message'] = "Please Enter Patient Name";
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

/** check if selected dentist is available at specified time
 * @param $dentist
 * @param $time
 * @return array
 */
function isDentistAvailable($dentist, $time)
{
    $result = ['result' => false, 'message' => 'Dentist Not Available At this time !'];
    $search = ['param' => 'name', 'operator' => '==', 'value' => $dentist];
    $dentist = getDentists($search);
    if (count($dentist) === 0) {
        return $result;
    }
    if (!in_array($time, $dentist[0]['slots'], false)) {
        $result['message'] .= "($time)";
        return $result;
    }
    $result['result'] = true;
    return $result;
}
