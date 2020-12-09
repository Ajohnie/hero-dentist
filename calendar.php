<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include 'includes/csslibs.php' ?>
    <link rel="stylesheet" href="static/css/calendar.css" />
</head>

<body>
    <?php include 'includes/navbar.php' ?>

    <div class="container-fluid" id="calendar-wrapper">
        <div class="row">
            <div class="col-2 px-0" id="sidebar-container">
                <?php include 'includes/sidebar.php' ?>
            </div>
            <div class="col-10" id="data-panel">
                <div class="container bg-light" id="todays-appoinment">
                    <div class="row">
                        <div class="col-12">
                            <h5>Today's Appoinments</h5>
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Dentist Name</th>
                                        <th>Patient Name</th>
                                        <th>Phone</th>
                                        <th>Appoinment Date</th>
                                        <th>Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Dr. Khaled Faysal</td>
                                        <td>Sara Ibrahim</td>
                                        <td>0501337776</td>
                                        <td>Nov 14, 2020</td>
                                        <td>9:00 AM</td>
                                        <td>
                                            <span class="fa fa-pencil-square-o text-success"></span>
                                            <span class="fa fa-remove text-danger"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="container bg-light" id="calendar-container">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-row">
                                <div class="form-group col-4">
                                    <label>Select Dentist</label>
                                    <select class="form-control">
                                        <option>Dr. Khaled Faysal</option>
                                    </select>
                                </div>
                                <div class="form-group-3 d-flex align-items-end">
                                    <button class="btn btn-secondary mb-3">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="month-display" class="col-4 offset-4 d-flex">
                            <h5 class="text-center">
                                <?php
                                    $month =  isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
                                    $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
                                    $month_start = strtotime($year.'-'.$month.'-1');
                                    $prev_month = explode('-', date('Y-m', strtotime("-1 months", $month_start)));
                                    $next_month = explode('-', date('Y-m', strtotime("+1 months", $month_start)));
                                ?>
                                <a
                                    href="calendar.php?year=<?php echo $prev_month[0]; ?>&month=<?php echo $prev_month[1]; ?>"><span
                                        class="fa fa-chevron-left"></span></a>
                                <?php
                                    echo date('F', $month_start);
                                ?>
                                <a
                                    href="calendar.php?year=<?php echo $next_month[0]; ?>&month=<?php echo $next_month[1]; ?>"><span
                                        class="fa fa-chevron-right"></span></a>
                            </h5>
                        </div>
                        <div class="col-4">
                            <select class="form-control">
                                <option>Month</option>
                            </select>
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
                                    <tbody>
                                        <?php
                                        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                                        //sample data
                                        $appoinments = array(
                                            '2020-10-3' => array(
                                                array(
                                                    "time" => "9.00 AM",
                                                    "dentist" => "Dr. Khaled"
                                                )
                                            ),
                                            '2020-10-4' => array(
                                                array(
                                                    "time" => "11.00 AM",
                                                    "dentist" => "Dr.khaled"
                                                )
                                            ),
                                            '2020-10-5' => array(
                                                array(
                                                    "time" => "11.00 AM",
                                                    "dentist" => "Dr.khaled"
                                                )
                                            ),
                                            '2020-10-6' => array(
                                                array(
                                                    "time" => "11.00 AM",
                                                    "dentist" => "Dr.khaled"
                                                )
                                            ),
                                            '2020-10-7' => array(
                                                array(
                                                    "time" => "11.00 AM",
                                                    "dentist" => "Dr.khaled"
                                                )
                                            ),
                                            '2020-10-8' => array(
                                                array(
                                                    "time" => "11.00 AM",
                                                    "dentist" => "Dr.khaled"
                                                )
                                            )
                                            ,
                                            '2020-10-9' => array(
                                                array(
                                                    "time" => "11.00 AM",
                                                    "dentist" => "Dr.khaled"
                                                )
                                            )
                                        );
                                    ?>
                                        <?php
                                        for ($i=1; $i <= $days; ) { 
                                            echo '<tr>';
                                            for ($k=0; $k < 7; $k++) { 
                                                if($i > $days){
                                                    echo '<td></td>';
                                                    continue;
                                                }
                                                $cell_date = $year.'-'.$month.'-'.$i;
                                                $day_index = date('w', strtotime($cell_date));

                                                if($day_index == $k){
                                                    if(array_key_exists($cell_date, $appoinments)){
                                                        echo '<td>'.$i;
                                                        foreach ($appoinments[$cell_date] as $appoinment) {
                                                            echo '<span class="badge badge-primary" data-toggle="modal" data-target="#appoinmentDetailsModal">'.$appoinment['time'].' '.$appoinment['dentist'].'</span>';
                                                        }
                                                        echo '</td>';
                                                    } else {
                                                        echo '<td>'.$i.'</td>';
                                                    }   
                                                    $i++;
                                                } else {
                                                    echo '<td></td>';
                                                }                                                
                                            }
                                            echo '</tr>';
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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


    <?php include 'includes/jslibs.php' ?>
</body>

</html>