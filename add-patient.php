<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include 'includes/csslibs.php' ?>
    <link rel="stylesheet" href="static/css/add-patient.css" />
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
                            <h4>Add Patient</h4>
                        </div>
                    </div>
                    <div class="row" id="form-container">
                        <div class="col-12 py-4">
                            <div class="row justify-content-center">
                                <div class="col-8">
                                    <form action="">
                                        <div class="form-group row pt-4">
                                            <label class="col-3">File Number</label>
                                            <input type="text" class="form-control col-8">
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">First Name</label>
                                            <input type="text" class="form-control col-8">
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Last Name</label>
                                            <input type="text" class="form-control col-8">
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Phone</label>
                                            <input type="number" class="form-control col-8">
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Date of Birth</label>
                                            <input type="date" class="form-control col-8">
                                        </div>
                                        <div class="form-group row justify-content-center pt-4 pb-2">
                                            <button type="submit" class="btn btn-secondary btn-sm">Add</button>
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
    

    <?php include 'includes/jslibs.php' ?>
</body>
</html>