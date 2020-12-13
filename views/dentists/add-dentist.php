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
                        <h4><span id="title">Add</span> Dentist</h4>
                    </div>
                </div>
                <div class="row" id="form-container">
                    <div class="col-12 py-4">
                        <div class="row justify-content-center">
                            <div class="col-8">
                                <!-- set form fields attribute that will used by functions in javascript.php
                                 make id similar to the value of name attribute -->
                                <form action="<?= DENTISTS_CONTROLLER ?>" id="theForm"
                                      formFields="name,email,id,slots,FirebaseId">
                                    <?php include FIREBASE_ID_INPUT ?>
                                    <div class="form-group row pt-4">
                                        <label class="col-3">Name</label>
                                        <input type="text" name="name" id="name" class="form-control col-8">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3">Phone No</label>
                                        <input type="number" name="phoneNo" id="id" class="form-control col-8">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3">Email</label>
                                        <input type="email" class="form-control col-8" name="email" id="email">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-4">
                                            <label class="row">Time When Available</label>
                                            <label class="row"><small>(press Ctrl and select)</small></label>
                                        </div>
                                        <select class="form-control col-2" name="slots"
                                                id="slots" multiple>
                                            <option value="8AM">8AM</option>
                                            <option value="9AM">9AM</option>
                                            <option value="10AM">10AM</option>
                                            <option value="11AM">11AM</option>
                                            <option value="12AM">12AM</option>
                                            <option value="1PM">1PM</option>
                                            <option value="2PM">2PM</option>
                                            <option value="3PM">3PM</option>
                                            <option value="4PM">4PM</option>
                                            <option value="5PM">5PM</option>
                                            <option value="6PM">6PM</option>
                                        </select>
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
<script>
    function selectOption() {
        const selectionInput = event.target;
        const selectedTimes = selectionInput.selectedOptions;
        const times = [];
        for (let index = 0; index < selectedTimes.length; index++) {
            times.push(selectedTimes[index].value);
        }
        // selectionInput.value = times;
        console.log('event.target');
        console.log(times);
    }
</script>

<?php include JS ?>
</body>
</html>
