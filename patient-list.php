<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include 'includes/csslibs.php' ?>
    <link rel="stylesheet" href="static/css/patient-list.css" />
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
                            <h4>Patient List</h4>
                        </div>
                    </div>
                    <div class="row" id="list-container">
                        <div class="col-12 py-4">
                            <div class="row justify-content-end">
                                <div class="col-4 pt-2">
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
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Phone</th>
                                                <th>Date of Birth</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                // Sample data
                                                $list = array(
                                                    array(
                                                        "id" => 1,
                                                        "firstName" => "Sara",
                                                        "lastName" => "Ibrahim",
                                                        "phone" => "0501337776",
                                                        "dateOfBirth" => "Nov 14, 2020"
                                                    ),
                                                    array(
                                                        "id" => 2,
                                                        "firstName" => "Nawaf",
                                                        "lastName" => "Ali",
                                                        "phone" => "0518886006",
                                                        "dateOfBirth" => "Nov 23, 2020"
                                                    )
                                                );

                                                foreach ($list as $row) {
                                                    echo "<tr>
                                                        <td>".$row['firstName']."</td>
                                                        <td>".$row['lastName']."</td>
                                                        <td>".$row['phone']."</td>
                                                        <td>".$row['dateOfBirth']."</td>
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