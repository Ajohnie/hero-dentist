<?php include (dirname(__FILE__, 3)) . '/shared/constants.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include CSS ?>
    <link rel="stylesheet" href="<?= HOME . 'assets/css/add-appointment.css' ?>"/>
</head>
<body>
<?php include NAV_BAR ?>

<div class="container-fluid" id="dashboard-wrapper">
    <div class="row">
        <div class="col-2 px-0" id="sidebar-container">
            <?php include SIDEBAR ?>
        </div>
        <div class="col-10" id="data-panel">
            <div class="container">
                <div class="row form-container">
                    <div class="col-12 py-4">
                        <div class="form-row">
                            <div class="form-group col-3">
                                <label>Dentist</label>
                                <select class="form-control">
                                    <option>All-Dentists</option>
                                </select>
                            </div>
                            <div class="form-groupcol-3">
                                <label>Date</label>
                                <input type="date" class="form-control"/>
                            </div>
                            <div class="form-group d-flex align-items-end col-3">
                                <button type="submit" class="btn btn-sm btn-secondary mb-1 ml-2">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-container">
                    <div class="col-12">
                        <div class="row justify-content-center">
                            <div class="col-3">
                                <?php
                                $month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
                                $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
                                $day = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('j');
                                $timestamp = strtotime($year . '-' . $month . '-' . $day);

                                $_date = strftime('%B,%d %Y', $timestamp);
                                $prev_day = explode('-', date('Y-m-j', strtotime("-1 days", $timestamp)));
                                $next_day = explode('-', date('Y-m-j', strtotime("+1 days", $timestamp)));
                                ?>
                                <a href="add-appointment.php?year=<?php echo $prev_day[0]; ?>&month=<?php echo $prev_day[1]; ?>&day=<?php echo $prev_day[2]; ?>"><span
                                            class="fa fa-chevron-left"></span></a>
                                <h5 class="d-inline"><?php echo $_date; ?></h5>
                                <a href="add-appointment.php?year=<?php echo $next_day[0]; ?>&month=<?php echo $next_day[1]; ?>&day=<?php echo $next_day[2]; ?>"><span
                                            class="fa fa-chevron-right"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-container py-3">
                    <div class="col-12" id="schedule">
                        <!-- this will be populated by showAppointmentSchedule when page loads-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addAppointmentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button class="modal-close-button" data-dismiss="modal"><i class="fa fa-times-circle-o"></i></button>
                <div class="form-row">
                    <div class="form-group col-6">
                        <label>Enter patient phone</label>
                        <!--value sent to DB on click in js-->
                        <input class="text-control" name="PatientNo" id="PatientNo">
                    </div>
                    <div class="form-group col-4 d-flex flex-column-reverse">
                        <!--click event is attached here in js-->
                        <button class="btn btn-sm btn-secondary" id="ReservationBtn">Make Reservation</button>
                    </div>
                </div>

                <h6 id="DentistNameReserve"></h6>
            </div>
        </div>
    </div>
</div>
<?php include APPOINTMENT_POPUP ?>


<script>
    // set handler of ajax calls, this will be checked in javascript included javascript.php
    let controllerUrl = '<?= APPOINTMENTS_CONTROLLER ?>';
</script>
<?php include JS ?>
</body>
</html>
