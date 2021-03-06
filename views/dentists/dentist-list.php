<?php include (dirname(__FILE__, 3)) . '/shared/constants.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include CSS ?>
    <link rel="stylesheet" href="<?= HOME . 'assets/css/dentist-list.css' ?>"/>
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
                        <h4>Dentist List</h4>
                    </div>
                </div>
                <div class="row" id="list-container">
                    <div class="col-12 py-4">
                        <?php include SEARCH_INPUT ?>
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered" id="searchTable">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Work Hours</th>
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
    let controllerUrl = '<?= DENTISTS_CONTROLLER ?>';
</script>
<?php include JS ?>
</body>
</html>
