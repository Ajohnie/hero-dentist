<?php
/** FUNCTIONS TO SHOW CALENDAR */
include (dirname(__FILE__, 2)) . '/shared/constants.php';

include 'utilities.php';
include CALENDAR_MODEL;

/** call all methods so that Ajax calls can locate them */


/** filter passed appointments and
 * show only for today
 * @param array appointments
 *
 * @return null
 */
function showTodayAppointments()
{
    // make sure request is for appointment-list not progress-note-list
    $today = getDefaultDate();
    $appointments = getAppointments(['param' => 'AppointmentDate', 'operator' => '==', 'value' => $today]);
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
    echo $tableRows;
    return null;
}

/** show calendar with appointments
 * @return null
 */
function showCalendar()
{
    $month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
    $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
    $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $appointmentDates = getAppointmentDates();
    for ($i = 1; $i <= $days;) {
        echo '<tr>';
        for ($k = 0; $k < 7; $k++) {
            if ($i > $days) {
                echo '<td></td>';
                continue;
            }
            $cell_date = $year . '-' . $month . '-' . $i;
            $day_index = date('w', strtotime($cell_date));

            if ($day_index == $k) {
                $indices = checkInArray($appointmentDates, $cell_date);
                if ($indices !== false) {
                    echo '<td>';
                    echo '<span class="calendarBadge">' . $i . '</span>';
                    foreach ($indices as $index) {
                        foreach ($appointmentDates[$index] as $appointment) {
                            // use class calenderBadge in ajax call
                            echo '<span class="badge badge-primary calendarBadge" data-toggle="usedAjaxInstead" data-target="usingAjaxInstead" FirebaseId="' . $appointment['FirebaseId'] . '">' . $appointment['time'] . ' ' . $appointment['dentist'] . '</span>';
                        }
                    }
                    echo '</td>';
                } else {
                    echo '<td>' . $i . '</td>';
                }
                $i++;
            } else {
                echo '<td></td>';
            }
        }
        echo '</tr>';
    }
    return null;
}

function checkInArray($appointments, $cellDate)
{
    $indices = [];
    foreach ($appointments as $index => $appointment) {
        if (isset($appointment[$cellDate])) {
            $indices[] = $index;
        }
    }
    if (count($indices) === 0) {
        return false;
    }
    return $indices;
}

/** show calendar navigator with arrows
 * @return null
 */
function showCalendarNavigator()
{
    $month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
    $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
    $month_start = strtotime($year . '-' . $month . '-1');
    $prev_month = explode('-', date('Y-m', strtotime("-1 months", $month_start)));
    $next_month = explode('-', date('Y-m', strtotime("+1 months", $month_start)));
    echo '<a href="calendar.php?year=' . $prev_month[0] . '&month=' . $prev_month[1] . '"><span class="fa fa-chevron-left"></span></a>';
    echo date('F', $month_start);
    echo '<a href="calendar.php?year=' . $next_month[0] . '&month=' . $next_month[1] . '"><span class="fa fa-chevron-right"></span></a>';
}

function showDentistSelect()
{
    $dentists = getDentists();
    echo '<option>All Dentists</option>';
    foreach ($dentists as $dentist) {
        echo "<option>" . $dentist['name'] . "</option>";
    }
}

function showMonthSelect()
{
    echo '<option>Select Month</option>';
    $months = MONTHS;
    foreach ($months as $month) {
        echo "<option>" . $month . "</option>";
    }
}
