<?php
/** FUNCTIONS TO VIEW, ADD, EDIT, REMOVE APPOINTMENTS*/
include (dirname(__FILE__, 2)) . '/shared/constants.php';

include 'utilities.php';
include APPOINTMENTS_MODEL;

/** call all methods so that Ajax calls can locate them */
addAppointment();
editAppointment();
deleteAppointment();
viewAppointments();
viewAppointmentSchedule();

/**add appointment to the database
 * @return null
 */
function addAppointment()
{
    $addAppointment = getRequestData('add', 'string', 'post');
    if ($addAppointment) {
        $dentistName = getRequestData('DentistName');
        $patientName = getRequestData('PatientName');
        $phoneNo = getRequestData('PhoneNo');
        $date = getRequestData('AppointmentDate');
        $time = getRequestData('AppointmentTime');
        $result = addAppointmentToDatabase($dentistName, $patientName, $phoneNo, $date, $time);
        if ($result['result']) {
            $result['message'] = LIST_APPOINTMENT; // redirect do appointment-list
        }
        echo json_encode($result);
    }
    return null;
}

/**modify existing appointment
 * @return null
 */
function editAppointment()
{
    $editAppointment = getRequestData('edit', 'string', 'post');
    if ($editAppointment) {
        $dentistName = getRequestData('DentistName');
        $patientName = getRequestData('PatientName');
        $phoneNo = getRequestData('PhoneNo');
        $date = getRequestData('Date');
        $time = getRequestData('Time');
        $firebaseId = getRequestData('FirebaseId');
        $result = editAppointmentInDatabase($dentistName, $patientName, $phoneNo, $date, $time, $firebaseId);
        if ($result['result']) {
            $result['message'] = LIST_APPOINTMENT; // redirect do appointment-list
        }
        echo json_encode($result);
    }
    return null;
}

/**remove existing appointment
 * @return null
 */
function deleteAppointment()
{
    $deleteAppointment = getRequestData('delete', 'string', 'post');
    if ($deleteAppointment === 'delete') {
        $firebaseId = getRequestData('FirebaseId');
        $result = deleteAppointmentFromDatabase($firebaseId);
        echo json_encode($result);
    }
    return null;
}

/**show appointments existing in the database
 * @return null
 */
function viewAppointments()
{
    $viewAppointment = getRequestData('view', 'string', 'post');
    // make sure request is for appointment-list not progress-note-list
    if ($viewAppointment === 'view') {
        $year = getRequestData('year');
        $month = getRequestData('month');
        $day = getRequestData('day');
        $query = [];

        $searchDate = getRequestData('searchDate');
        if ($year && $month && $day) {
            $query = ['param' => 'AppointmentDate', 'operator' => '==', 'value' => $year . '-' . $month . '-' . $day];
        } elseif ($searchDate) {
            $query = ['param' => 'AppointmentDate', 'operator' => '==', 'value' => $searchDate];
        }
        $appointments = getAppointments($query);
        $tableRows = '';
        foreach ($appointments as $appointment) {
            $rowData = json_encode($appointment);
            $rowId = $appointment['FirebaseId'];

            // set parameters for ajax calls
            $deleteActionUrl = APPOINTMENTS_CONTROLLER;
            $nextUrl = ADD_APPOINTMENT;
            $editAction = 'storeTableRowData(' . $rowData . ',"' . $nextUrl . '")';
            $deleteAction = 'deleteTableRowData("' . $rowId . '","' . $deleteActionUrl . '")';

            $id = $appointment['AppointmentId'];
            $dentistName = $appointment['DentistName'];
            $patientName = $appointment['PatientName'];
            $phoneNo = $appointment['PatientNo'];
            $date = $appointment['AppointmentDate'];
            $time = $appointment['AppointmentTime'];
            $tableRows .= "<tr>
                    <td>" . $id . "</td>
                   <td>" . $dentistName . "</td>
                    <td>" . $patientName . "</td>
                    <td>" . $phoneNo . "</td>
                    <td>" . $date . "</td>
                    <td>" . $time . "</td>
                     <td><span class='fa fa-pencil-square-o text-success' id='edit' onclick='$editAction'></span>
                       <span class='fa fa-remove text-danger' id='delete' onclick='$deleteAction'></span>
                     </td>
                 </tr>";
        }
        echo json_encode($tableRows);
    }
    return null;
}

/**show appointment schedule when you visit add-appointment
 * @return null
 */
function viewAppointmentSchedule()
{
    $viewAppointment = getRequestData('view', 'string', 'post');
    // make sure request is for appointment schedule
    if ($viewAppointment === 'schedule') {

        $year = getRequestData('year');
        $month = getRequestData('month');
        $day = getRequestData('day');
        $query = null;
        // this is set by the date filter on add-appointment.php and sent via ajax
        $appointmentDate = getRequestData('AppointmentDate');
        $searchDate = getRequestData('searchDate');
        if ($appointmentDate) {
            $searchDate = $appointmentDate;
        }
        if ($year && $month && $day) {
            $query = $year . '-' . $month . '-' . $day;
        } elseif ($searchDate) {
            $query = $searchDate;
        }
        // get schedule from model and display it
        $schedules = getSchedule($query);
        if (count($schedules) === 0) {
            return null;
        }
        $html = '';
        $names = '<option value="All-Dentists">All-Dentists</option>';
        foreach ($schedules as $schedule) {
            $names .= '<option value="' . $schedule['name'] . '">' . $schedule['name'] . '</option>>';
            // id attribute will be used by dentist filter to hide other dentists
            // and leave only those with id= dentist name
            $html .= '<div class="row dentist-container mb-3" id="' . $schedule['name'] . '"><div class="col-10 offset-1"><h5>' . $schedule['name'] . '</h5><div class="badge-container">';
            foreach ($schedule['slots'] as $slot) {
                $html .= '<span onclick="showTimeSlot()"' . ' DentistName="' . $schedule['name'] . '"Time="' . $slot['time'] . '" FirebaseId="' . $slot['FirebaseId'] . '" status="' . $slot['status'] . '" class="badge ' . $slot['status'] . '" data-toggle="use-js-instead" data-target="using-js' . ($slot['status'] == 'busy' ? 'appointmentDetailsModal' : 'addAppointmentModal') . '">' . $slot['time'] . '</span>';
            }
            $html .= '</div></div></div>';
        }
        echo json_encode(['appointments' => $html, 'names' => $names]);
    }
    if ($viewAppointment === 'showAppointment') {
        $firebaseId = getRequestData('FirebaseId', 'string', 'post');
        //get PatientName, AppointmentDate, PatientNo
        $appointment = getAppointments(['param' => 'FirebaseId', 'operator' => '==', 'value' => $firebaseId], true);

        echo json_encode([
            'PatientName' => $appointment['PatientName'],
            'DentistName' => $appointment['DentistName'],
            'AppointmentDate' => $appointment['AppointmentDate'],
            'AppointmentTime' => $appointment['AppointmentTime'],
            'PatientNo' => $appointment['PatientNo'],
        ]);
    }
    if ($viewAppointment === 'addAppointment') {
        // PatientNo: PatientNo, Time: Time, DentistName: DentistName, appointmentDate
        $phoneNo = getRequestData('PatientNo', 'string', 'post');
        $time = getRequestData('Time', 'string', 'post');
        $dentistName = getRequestData('DentistName', 'string', 'post');
        $date = getRequestData('appointmentDate', 'string', 'post');
        if (!$date) {
            $date = getDefaultDate();
        }
        $appointment = getAppointments(['param' => 'PatientNo', 'operator' => '==', 'value' => $phoneNo], true);
        if (count($appointment) > 0) {
            $patientName = $appointment['PatientName'];
        } else {
            $patientName = 'New Patient';
        }

        $result = addAppointmentToDatabase($dentistName, $patientName, $phoneNo, $date, $time);
        if ($result['result']) {
            $result['message'] = LIST_APPOINTMENT; // redirect do appointment-list
        }
        echo json_encode($result);
    }
    return null;
}
