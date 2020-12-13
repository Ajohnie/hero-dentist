<?php include (dirname(__FILE__, 3)) . '/shared/constants.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include CSS ?>
    <link rel="stylesheet" href="<?= HOME . 'assets/css/dashboard.css' ?>" />
</head>
<body>
    <?php include NAV_BAR ?>

    <div class="container-fluid" id="dashboard-wrapper">
        <div class="row">
            <div class="col-2 px-0" id="sidebar-container">
                <?php include SIDEBAR ?>
            </div>
            <div class="col-10">

            </div>
        </div>
    </div>
    

    <?php include JS ?>
</body>
</html>
