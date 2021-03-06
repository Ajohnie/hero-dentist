<?php include (dirname(__FILE__, 3)) . '/controllers/calendarController.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include CSS ?>
    <link rel="stylesheet" href="<?= HOME . 'assets/css/calendar.css' ?>"/>
</head>

<body>
<?php include NAV_BAR ?>
<style>
    .calendarBadge {
        display: table;
        margin-bottom: 0.4rem;
        height: 1rem;
        width: 80%
    }
</style>
<div class="container-fluid" id="calendar-wrapper">
    <div class="row">
        <div class="col-2 px-0" id="sidebar-container">
            <?php include SIDEBAR ?>
        </div>
        <div class="col-10" id="data-panel">
            <div class="container bg-light" id="todays-appointment">
                <div class="row">
                    <div class="col-12">
                        <h5>Today's Appointments</h5>
                        <table class="table table-bordered table-sm">
                            <thead>
                            <tr>
                                <th>Appointment ID</th>
                                <th>Dentist Name</th>
                                <th>Patient Name</th>
                                <th>Phone</th>
                                <th>Appointment Date</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php showTodayAppointments() ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="container bg-light" id="calendar-container">
                <div class="row">
                    <div class="col-12">
                        <form action="<?= CALENDAR_CONTROLLER ?>" id="calendarForm">
                            <div class="form-row">
                                <div class="form-group col-4">
                                    <label>Select Dentist</label>
                                    <select class="form-control" name="calendarDentist" id="calendarDentist">
                                        <?php showDentistSelect() ?>
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <label>Select Month</label>
                                    <select class="form-control" name="calendarMonthSelect" id="calendarMonthSelect">
                                        <?php
                                        showMonthSelect();
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group-3 d-flex align-items-end">
                                    <button class="btn btn-secondary mb-3">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div id="month-display" class="col-4 offset-4 d-flex">
                        <h5 class="text-center" id="navigatorHtml">
                            <?php
                            showCalendarNavigator();
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Sun</th>
                                    <th>Mon</th>
                                    <th>Tues</th>
                                    <th>Wed</th>
                                    <th>Thurs</th>
                                    <th>Fri</th>
                                    <th>Sat</th>
                                </tr>
                                </thead>
                                <tbody id="calendarTable">
                                <?php showCalendar(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APPOINTMENT_POPUP ?>
<script>
    // set handler of ajax calls, this will be checked in javascript included javascript.php
    let controllerUrl = '<?php echo CALENDAR_CONTROLLER ?>';
</script>
<?php include JS ?>
<script>
    let appointmentUrl = '<?php echo APPOINTMENTS_CONTROLLER ?>';
    let calendarUrl = '<?php echo CALENDAR_CONTROLLER ?>';
    $('.calendarBadge').on('click', function () {
        const FirebaseId = event.target.getAttribute('FirebaseId');
        showAppointmentPopup(FirebaseId, appointmentUrl);
    });
</script>
<script>
    $('#calendarForm').on('submit', function (event) {
        event.preventDefault();
        const dentist = document.getElementById('calendarDentist').value;
        const month = document.getElementById('calendarMonthSelect').value;

        function calendarCallback(response) {
            if (response) {
                const results = JSON.parse(response);
                const navigatorHtml = document.getElementById('navigatorHtml');
                const calendarTable = document.getElementById('calendarTable');
                if (results.tableRows) {
                    calendarTable.innerHTML = results.tableRows;
                }
                if (results.navigatorHtml) {
                    navigatorHtml.innerHTML = results.navigatorHtml;
                }
            }
        }

        makeAjaxRequest(calendarUrl, {calendarDentist: dentist, calendarMonthSelect: month}, calendarCallback);
    });
    // fill dentist selection list
    // showDentistList();
</script>
</body>

</html>
