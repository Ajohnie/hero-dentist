<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include 'includes/csslibs.php' ?>
    <link rel="stylesheet" href="static/css/add-appoinment.css" />
</head>
<body>
    <?php include 'includes/navbar.php' ?>

    <div class="container-fluid" id="dashboard-wrapper">
        <div class="row">
            <div class="col-2 px-0" id="sidebar-container">
                <?php include 'includes/sidebar.php' ?>
            </div>
            <div class="col-10" id="data-panel">
                <div class="container">
                    <div class="row form-container">
                        <div class="col-12 py-4">
                            <div class="form-row">
                                <div class="form-group col-3">
                                    <label>Dentist</label>
                                    <select class="form-control">
                                        <option>Dr. Khaled</option>
                                    </select>
                                </div>
                                <div class="form-groupcol-3">
                                    <label>Date</label>
                                    <input type="date" class="form-control" />
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
                                        $month =  isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
                                        $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
                                        $day = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('j');
                                        $timestamp = strtotime($year.'-'.$month.'-'.$day);

                                        $_date = strftime('%B,%d %Y', $timestamp);
                                        $prev_day = explode('-', date('Y-m-j', strtotime("-1 days", $timestamp)));
                                        $next_day = explode('-', date('Y-m-j', strtotime("+1 days", $timestamp)));
                                    ?>
                                    <a href="add-appoinment.php?year=<?php echo $prev_day[0]; ?>&month=<?php echo $prev_day[1]; ?>&day=<?php echo $prev_day[2]; ?>"><span class="fa fa-chevron-left"></span></a>
                                    <h5 class="d-inline"><?php echo $_date; ?></h5>
                                    <a href="add-appoinment.php?year=<?php echo $next_day[0]; ?>&month=<?php echo $next_day[1]; ?>&day=<?php echo $next_day[2]; ?>"><span class="fa fa-chevron-right"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-container py-3">
                        <div class="col-12">
                            <?php
                                //sample data
                                $data = array(
                                    array(
                                        "name" => "Dr. Khaled",
                                        "slots" => array(
                                            array(
                                                "time" => "10.00 AM",
                                                "status" => "available"
                                            ),
                                            array(
                                                "time" => "11.00 AM",
                                                "status" => "busy"
                                            ),
                                            array(
                                                "time" => "2.00 PM",
                                                "status" => "available"
                                            )
                                        )
                                    ),
                                    array(
                                        "name" => "Dr. Eman",
                                        "slots" => array(
                                            array(
                                                "time" => "10.00 AM",
                                                "status" => "available"
                                            ),
                                            array(
                                                "time" => "11.00 AM",
                                                "status" => "busy"
                                            ),
                                            array(
                                                "time" => "2.00 PM",
                                                "status" => "available"
                                            )
                                        )
                                    )
                                );

                                foreach ($data as $dentist) {
                                    echo '<div class="row dentist-container mb-3">
                                            <div class="col-10 offset-1">
                                                <h5>'.$dentist['name'].'</h5>
                                                <div class="badge-container">';
                                                    foreach ($dentist['slots'] as $slot) {
                                                        echo '<span class="badge '.$slot['status'].'" data-toggle="modal" data-target="#'.($slot['status'] == 'busy' ? 'appoinmentDetailsModal' : 'addAppoinmentModal').'">'.$slot['time'].'</span>';
                                                    }
                                                echo '</div>
                                            </div>
                                        </div>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addAppoinmentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="modal-close-button" data-dismiss="modal"><i class="fa fa-times-circle-o"></i></button>
                    
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label>Enter patient phone</label>
                            <input class="text-control">
                        </div>
                        <div class="form-group col-4 d-flex flex-column-reverse">
                            <button class="btn btn-sm btn-secondary">Make Reservation</button>
                        </div>
                    </div>

                    <h6>Shahad ali</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="appoinmentDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="modal-close-button" data-dismiss="modal"><i class="fa fa-times-circle-o"></i></button>
                    <h6>Appoinment Details</h6>
                    <hr>
                    <table class="table">
                        <tr>
                            <td>Name</td>
                            <td>Sara Ibrahim</td>
                        </tr>
                        <tr>
                            <td>Appoinment</td>
                            <td>Nov 14, 2020</td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>0501337776</td>
                        </tr>
                        <tr>
                            <td>Time</td>
                            <td>09:00 AM</td>
                        </tr>
                        <tr>
                            <td>Time booked for</td>
                            <td>Dr. Khaled Faysal</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <style>
        .modal-body {
            padding: 10px;
            background: rgb(219,226,237);
        }
        .modal-close-button {
            border: 0px;
            background: transparent;
            position: absolute;
            right: 0;
            top: 0;
            padding: 0px 10px 10px 10px;
            font-size: 22px;
            color: #d43939;
        }
    </style>
    

    <?php include 'includes/jslibs.php' ?>
</body>
</html>