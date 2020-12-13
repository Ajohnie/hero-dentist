<?php include (dirname(__FILE__, 3)) . '/shared/constants.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include CSS ?>
    <link rel="stylesheet" href="<?= HOME . 'assets/css/add-patient.css' ?>"/>
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
                        <h4><span id="title">Add</span> Patient</h4>
                    </div>
                </div>
                <div class="row" id="form-container">
                    <div class="col-12 py-4">
                        <div class="row justify-content-center">
                            <div class="col-8">
                                <form action="<?= PATIENTS_CONTROLLER ?>" id="theForm"
                                      formFields="FileNumber,PatientName,DOB,PatientNo,FirebaseId">
                                    <?php include FIREBASE_ID_INPUT ?>
                                    <div class="form-group row pt-4">
                                        <label class="col-3">File Number</label>
                                        <input type="text" name="FileNumber" id="FileNumber" class="form-control col-8">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3">Name</label>
                                        <input type="text" name="PatientName" id="PatientName" class="form-control col-8">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3">Phone</label>
                                        <input type="number" id="PatientNo" name="PatientNo" class="form-control col-8">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3">Date of Birth</label>
                                        <input type="date" name="DOB" id="DOB"
                                               class="form-control col-8">
                                    </div>
                                    <div class="form-group row justify-content-center pt-4 pb-2">
                                        <button type="submit" class="btn btn-secondary btn-sm">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include JS ?>
</body>
</html>
