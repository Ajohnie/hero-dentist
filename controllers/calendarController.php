<?php
/** FUNCTIONS TO SHOW CALENDAR */
include (dirname(__FILE__, 2)) . '/shared/constants.php';

include 'utilities.php';
include CALENDAR_MODEL;

/** call all methods so that Ajax calls can locate them */
renderAjaxCalendar();

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

/** receives ajax calls to filter calendar appointments and then render a modified calendar*/
function renderAjaxCalendar()
{
    $dentist = getRequestData('calendarDentist');
    $calendarMonthSelect = getRequestData('calendarMonthSelect');
    if ($dentist || $calendarMonthSelect) {
        showCalendar($dentist, $calendarMonthSelect);
    }
}

/** show calendar with appointments
 * @param null|string $dentist
 * @param null|string $calendarMonth
 * @return null
 */
function showCalendar($dentist = null, $calendarMonth = null)
{
    $month = getRequestData('month');
    if (!$month) {
        $month = date('m');
    }
    $year = getRequestData('year');
    if (!$year) {
        $year = date('Y');
    }
    $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $navigatorHtml = null;
    if ($calendarMonth) {
        $getMonthIndex = array_search($calendarMonth, MONTHS);
        if ($getMonthIndex !== false) {
            // in MONTHS array, months are listed in ascending order beginning from index 0
            // so add 1 to get month as a digit
            $month = $getMonthIndex + 1;
        }
    }
    if ($calendarMonth || $dentist) {
        $navigatorHtml = getCalendarNavigator($month, $year);
    }
    $appointmentDates = getAppointmentDates($dentist);
    $tableRows = '';
    for ($i = 1; $i <= $days;) {
        $tableRows .= '<tr>';
        for ($k = 0; $k < 7; $k++) {
            if ($i > $days) {
                $tableRows .= '<td></td>';
                continue;
            }
            $cell_date = $year . '-' . $month . '-' . $i;
            $day_index = date('w', strtotime($cell_date));

            if ($day_index == $k) {
                $indices = checkInArray($appointmentDates, $cell_date);
                if ($indices !== false) {
                    $tableRows .= '<td>';
                    $tableRows .= '<span class="calendarBadge">' . $i . '</span>';
                    foreach ($indices as $index) {
                        foreach ($appointmentDates[$index] as $appointment) {
                            // use class calenderBadge in ajax call
                            $tableRows .= '<span class="badge badge-primary calendarBadge" data-toggle="usedAjaxInstead" data-target="usingAjaxInstead" FirebaseId="' . $appointment['FirebaseId'] . '">' . $appointment['time'] . ' ' . $appointment['dentist'] . '</span>';
                        }
                    }
                    $tableRows .= '</td>';
                } else {
                    $tableRows .= '<td>' . $i . '</td>';
                }
                $i++;
            } else {
                $tableRows .= '<td></td>';
            }
        }
        $tableRows .= '</tr>';
    }
    if ($navigatorHtml) {
        // request was sent via ajax so, encode navigator html too
        echo json_encode(['navigatorHtml' => $navigatorHtml, 'tableRows' => $tableRows]);
    } else {
        // user just visited calendar.php so out put raw html
        echo $tableRows;
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
 * @return string|void
 */
function showCalendarNavigator()
{
    echo getCalendarNavigator();
}

/** get calendar navigator with arrows
 * @param null $month
 * @param null $year
 * @return string|void
 */
function getCalendarNavigator($month = null, $year = null)
{
    $month = !$month ? getRequestData('month') : $month;
    if (!$month) {
        $month = date('m');
    }
    $year = !$year ? getRequestData('year') : $year;
    if (!$year) {
        $year = date('Y');
    }
    $month_start = strtotime($year . '-' . $month . '-1');
    $prev_month = explode('-', date('Y-m', strtotime("-1 months", $month_start)));
    $next_month = explode('-', date('Y-m', strtotime("+1 months", $month_start)));
    $html = '<a href="calendar.php?year=' . $prev_month[0] . '&month=' . $prev_month[1] . '" id="monthLeft"><span class="fa fa-chevron-left"></span></a>';
    $html .= '<span id="monthName" style="text-transform: capitalize">' . date('F', $month_start) . '</span>';
    $html .= '<a href="calendar.php?year=' . $next_month[0] . '&month=' . $next_month[1] . '" id="monthRight"><span class="fa fa-chevron-right"></span></a>';
    return $html;
}

function showDentistSelect()
{
    $dentists = getDentists();
    echo '<option value="">All Dentists</option>';
    foreach ($dentists as $dentist) {
        echo "<option value='" . $dentist['name'] . "'>" . $dentist['name'] . "</option>";
    }
}

function showMonthSelect()
{
    echo '<option value="">Select Month</option>';
    $months = MONTHS;
    foreach ($months as $month) {
        echo "<option value='" . $month . "'>" . $month . "</option>";
    }
}
