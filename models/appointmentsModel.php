<?php
/** FUNCTIONS TO VIEW, ADD, EDIT, REMOVE APPOINTMENTS TO AND FROM THE DATABASE*/
include 'dentistsModel.php';


/**add appointment to the database
 * @param $dentistName
 * @param $patientName
 * @param $phoneNo
 * @param $date
 * @param $time
 * @return array
 */
function addAppointmentToDatabase($dentistName, $patientName, $phoneNo, $date, $time)
{
    $result = ['result' => false, 'message' => 'Unable to save appointment !'];
    if (!$dentistName || !$patientName || !$phoneNo || !$date || !$time) {
        $result['message'] = "Please Enter All Details";
        return $result;
    }
    $isAppointmentDataValid = isAppointmentDataValid($dentistName, $patientName, $phoneNo, $date, $time);
    if (!$isAppointmentDataValid['result']) {
        $result['message'] = $isAppointmentDataValid['message'];
        return $result;
    }
    $appointments = getAppointments();
    $appointmentExists = false;
    // good for adding sorting before searching
    foreach ($appointments as $appointment) {
        $dateTimeExist = $appointment['AppointmentDate'] === $date && $appointment['AppointmentTime'] === $time;
        $peopleExist = $appointment['PatientName'] === $patientName && $appointment['DentistName'] === $dentistName;
        if ($dateTimeExist && $peopleExist) {
            $appointmentExists = true;
            break;
        }
    }
    if ($appointmentExists) {
        $result['message'] = "Similar Appointment Already Exists !";
        return $result;
    }
    $data = [
        'DentistName' => $dentistName,
        'PatientName' => $patientName,
        'PatientNo' => $phoneNo,
        'AppointmentDate' => $date,
        'AppointmentTime' => $time
    ];
    $appObject = getAppointmentObject($data);
    $appObject = setAppointmentId($appObject);
    $appointmentSaved = saveRecord(APPOINTMENTS_COLLECTION, $appObject, true);
    if ($appointmentSaved) {
        $result['message'] = "Appointment Saved Successfully";
        $result['result'] = true;
        return $result;
    }
    return $result;
}

/**modify appointment in the database
 * @param $dentistName
 * @param $patientName
 * @param $phoneNo
 * @param $date
 * @param $time
 * @param $firebaseId
 * @return array
 */
function editAppointmentInDatabase($dentistName, $patientName, $phoneNo, $date, $time, $firebaseId)
{
    $result = ['result' => false, 'message' => 'Unable to edit appointment !'];
    if (!$dentistName || !$patientName || !$phoneNo || !$date || !$time) {
        $result['message'] = "Please Enter All Details";
        return $result;
    }
    if (!$firebaseId) {
        $result['message'] = "Please Select table row Again";
        return $result;
    }
    $isAppointmentDataValid = isAppointmentDataValid($dentistName, $patientName, $phoneNo, $date, $time);
    if (!$isAppointmentDataValid['result']) {
        $result['message'] = $isAppointmentDataValid['message'];
        return $result;
    }
    $data = [];
    $appointment = getAppointments(['param' => 'FirebaseId', 'operator' => '==', 'value' => $firebaseId], true);
    if (count($appointment) > 0) {
        $data = $appointment; // old data
    }
    $data['DentistName'] = $dentistName;
    $data['PatientName'] = $patientName;
    $data['PatientNo'] = $phoneNo;
    $data['AppointmentDate'] = $date;
    $data['AppointmentTime'] = $time;
    $data['FirebaseId'] = $firebaseId;

    $appObject = getAppointmentObject($data); // fill in missing values
    $appObject = setAppointmentId($appObject);
    $appointmentSaved = saveRecord(APPOINTMENTS_COLLECTION, $appObject, true);
    if ($appointmentSaved) {
        $result['message'] = "Appointment Saved Successfully";
        $result['result'] = true;
        return $result;
    }
    return $result;
}

/**remove existing appointment
 * @param $firebaseId
 * @return null
 */
function deleteAppointmentFromDatabase($firebaseId)
{
    $result = ['result' => false, 'message' => 'Unable to remove appointment !'];
    if (!$firebaseId) {
        $result['message'] = "Please Enter Valid Id";
        return $result;
    }
    $query = ['param' => 'FirebaseId', 'operator' => '==', 'value' => $firebaseId];
    $appointmentSaved = removeRecord(APPOINTMENTS_COLLECTION, $query);
    if ($appointmentSaved) {
        $result['message'] = LIST_APPOINTMENT; // redirect to list appointment in javascript
        $result['result'] = true;
        return $result;
    }
    return $result;
}

/**get appointments existing in the database
 * filters:- name, AppointmentNo, AppID
 * @param array $filters
 * @param bool $getOne
 * @return array
 */
function getAppointments($filters = [], $getOne = false)
{
    $appointments = getRecords(APPOINTMENTS_COLLECTION, $filters, $getOne);
    if ($getOne) {
        return $appointments;
    }

    // remove duplicates
    return array_unique($appointments, SORT_REGULAR);
}

/**get appointments existing in the database
 * @param $dentistName
 * @param $patientName
 * @param $phoneNo
 * @param $date
 * @param $time
 * @return array
 */
function isAppointmentDataValid($dentistName, $patientName, $phoneNo, $date, $time)
{
    $result = ['result' => false, 'message' => 'Unable to save appointment !'];
    if (!$dentistName || $dentistName === '') {
        $result['message'] = "Please Enter Valid Dentist Name";
        return $result;
    }
    if (!$patientName || $patientName === '') {
        $result['message'] = "Please Enter Valid Patient Name";
        return $result;
    }
    $phoneNoIsValid = isPhoneNoValid($phoneNo);
    if (!$phoneNoIsValid) {
        $result['message'] = "Please Enter Valid Phone No";
        return $result;
    }
    $isDateOfBirthValid = isDateValid($date);
    if (!$isDateOfBirthValid) {
        $result['message'] = "Please Enter a Valid Date";
        return $result;
    }
    $isTimeValid = isTimeValid($time);
    if (!$isTimeValid) {
        $result['message'] = "Please Enter Valid Time";
        return $result;
    }
    $result['result'] = true;
    return $result;
}

/** get all dentists and get all appointments
 * foreach dentist read their schedule then find
 * all appointments whose AppointmentTime is equal to the schedule time
 * extract hour field from time value and compare it with dentist time
 * @param null $date
 * @return array
 */
function getSchedule($date = null)
{
    if ($date) {
        $query = ['param' => 'AppointmentDate', 'operator' => '==', 'value' => $date];
    } else {
        $query = [];
    }
    $appointments = getAppointments($query);
    if (count($appointments) === 0) {
        return getDefaultSchedule();
    }
    $dentists = getDentists();
    if (count($dentists) === 0) {
        return [];
    }

    $schedules = []; // ['name'=>'', slots=>['time'=>'','status']];
    foreach ($dentists as $dentist) {
        $schedule = [
            'name' => $dentist['name'],
            'slots' => getSlots($dentist, $appointments)
        ];
        $schedules[] = $schedule;
    }
    return $schedules;
}

function getDefaultSchedule()
{
    $dentists = getDentists();
    if (count($dentists) === 0) {
        return [];
    }
    $schedules = []; // ['name'=>'', slots=>['time'=>'','status']];
    foreach ($dentists as $dentist) {
        $_defSlots = [];
        foreach ($dentist['slots'] as $slot) {
            $_defSlots[] = ['time' => $slot, 'status' => 'available', 'FirebaseId' => ''];
        }
        $schedule = [
            'name' => $dentist['name'],
            'slots' => $_defSlots
        ];
        $schedules[] = $schedule;
    }
    return $schedules;
}

/** get schedule time slots
 * @param array $dentist
 * @param array $appointments
 * @return array|string[]
 */
function getSlots(array $dentist, array $appointments)
{
    if (!isset($dentist)) {
        return [];
    }
    if (!isset($appointments)) {
        return [];
    }
    $slots = $dentist['slots'];
    if (!isset($slots) || count($slots) === 0) {
        return [];
    }
    $timeSlots = [];

    foreach ($slots as $slot) { // e.g 8AM
        $searchCallback = static function ($appointment) use ($slot, $dentist) {
            return searchCallback($appointment, $slot, $dentist);
        };

        $schedule = ['time' => $slot, 'status' => 'available'];
        $firebaseId = '';
        // i could have used array_filter but it produces unexpected results
        // array keys are not consistent
        $key = searchArray($appointments, $searchCallback);
        if ($key !== false) {
            $schedule['status'] = 'busy';
            $app = $appointments[$key];
            if (is_array($app) && isset($app['FirebaseId'])) {
                $firebaseId = $app['FirebaseId'];
            }
        }
        $schedule['FirebaseId'] = $firebaseId;
        $timeSlots[] = $schedule;
    }
    return $timeSlots;
}

/** callback function for Array search algorithm
 * @param $appointment
 * @param $slot
 * @param $dentist
 * @return bool
 */
function searchCallback($appointment, $slot, $dentist)
{
    if (!isset($appointment['AppointmentTime'])) {
        return false;
    }
    $appointmentTime = $appointment['AppointmentTime'];
    if (!isset($appointmentTime) || $appointmentTime === '') {
        return false;
    }
    $dentistName = $appointment['DentistName'];
    if (!isset($dentistName) || $dentistName === '') {
        return false;
    }

    if (stripos($dentistName, $dentist['name']) === false) {
        return false;
    }
    return stripos($appointmentTime, $slot) !== false;
}

/** group appointments by date
 * @param $dentist
 * @return array
 */
function getAppointmentDates($dentist = null)
{

    $appointmentDates = [];
    if (!$dentist || $dentist === "") {
        $query = [];
    } else {
        $query = ['param' => 'DentistName', 'operator' => '==', 'value' => $dentist];
    }
    $appointments = getAppointments($query);
    foreach ($appointments as $appointment) {
        $key = $appointment['AppointmentDate'];
        $time = $appointment['AppointmentTime'];
        $name = $appointment['DentistName'];
        $firebaseId = isset($appointment['FirebaseId']) ? $appointment['FirebaseId'] : '';
        $appointmentDate = array(
            $key => array(
                "FirebaseId" => $firebaseId,
                "time" => $time,
                "dentist" => $name
            )
        );
        $appointmentDates[] = $appointmentDate;
    }
    return $appointmentDates;
}
