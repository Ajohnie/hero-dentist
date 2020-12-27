<?php include (dirname(__FILE__, 3)) . '/shared/constants.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include CSS ?>
    <link rel="stylesheet" href="<?= HOME . 'assets/css/appointment-list.css' ?>"/>
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
                <div class="row mt-4 mb-3">
                    <div class="col-12">
                        <h4>Appointment List</h4>
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
                                                    "All Dentists",
                                                );
                                                foreach ($dentist_list as $dentist) {
                                                    echo "<option>" . $dentist . "</option>";
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
                        <?php include SEARCH_INPUT ?>
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered" id="searchTable">
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
                                    <tbody id="list">
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

<script>
    // set handler of ajax calls, this will be checked in javascript included javascript.php
    let controllerUrl = '<?= APPOINTMENTS_CONTROLLER ?>';
</script>
<?php include JS ?>
</body>
</html>
