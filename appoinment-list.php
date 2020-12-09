<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include 'includes/csslibs.php' ?>
    <link rel="stylesheet" href="static/css/appoinment-list.css" />
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
                    <div class="row mt-4 mb-3">
                        <div class="col-12">
                            <h4>Appoinment List</h4>
                        </div>
                    </div>
                    <div class="row" id="list-container">
                        <div class="col-12 py-4">
                            <div class="row">
                                <div class="col-12 pt-2">
                                    <form>
                                        <div class="form-row">
                                            <div class="form-group col-3">
                                                <label>From Date</label>
                                                <input type="date" class="form-control">
                                            </div>
                                            <div class="form-group col-3">
                                                <label>To Date</label>
                                                <input type="date" class="form-control">
                                            </div>
                                            <div class="form-group col-3">
                                                <label>Select Dentist</label>
                                                <select class="form-control">
                                                    <?php
                                                        // sample data
                                                        $dentist_list = array(
                                                            "All Dentist"
                                                        );
                                                        foreach ($dentist_list as $dentist) {
                                                            echo "<option>".$dentist."</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-3 d-flex align-items-end">
                                                <button type="submit" class="btn btn-secondary mb-1 ml-2">Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-4 pt-1">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <span class="fa fa-search"></span>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Search">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <table class="table table-bordered">
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
                                            <?php
                                                // Sample data
                                                $list = array(
                                                    array(
                                                        "id" => 1,
                                                        "dentistName" => "Dr. khaled Faysal",
                                                        "patientName" => "Sara Ibrahim",
                                                        "phone" => "0501337776",
                                                        "appoinmentDate" => "Nov 14, 2020",
                                                        "time" => "9:00 AM"
                                                    ),
                                                    array(
                                                        "id" => 2,
                                                        "dentistName" => "Dr. Eman Ali",
                                                        "patientName" => "Nawaf Ali",
                                                        "phone" => "0518886006",
                                                        "appoinmentDate" => "Nov 23, 2020",
                                                        "time" => "11:00 AM"
                                                    )
                                                );

                                                foreach ($list as $row) {
                                                    echo "<tr>
                                                        <td>".$row['dentistName']."</td>
                                                        <td>".$row['patientName']."</td>
                                                        <td>".$row['phone']."</td>
                                                        <td>".$row['appoinmentDate']."</td>
                                                        <td>".$row['time']."</td>
                                                        <td>
                                                            <span class='fa fa-pencil-square-o text-success'></span>
                                                            <span class='fa fa-remove text-danger'></span>
                                                        </td>
                                                    </tr>";
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
    </div>
    

    <?php include 'includes/jslibs.php' ?>
</body>
</html>