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
<style>
    /* STYLE THE PROGRESS NOTE PAGE, INPUTS, TABLE ETC */
    fieldset {
        border: solid #ead3d3 1px;
    }

    .mainLabel {
        margin-left: 1rem;
        font-weight: 500;
        width: 100%
    }

    th {
        font-weight: 400;
        width: 30%;
        background-color: #bbcfd2
    }

    /* table input */
    .testInput {
        height: 2rem;
        width: 7rem;
        margin-left: 0.2rem
    }
</style>
<div class="container-fluid" id="dashboard-wrapper">
    <div class="row">
        <div class="col-2 px-0" id="sidebar-container">
            <?php include SIDEBAR ?>
        </div>
        <div class="col-10" id="data-panel">
            <div class="container">
                <div class="row mt-4 mb-3">
                    <div class="col-12">
                        <h4><span id="title">Add</span> Progress Note</h4>
                    </div>
                </div>
                <div class="row" id="form-container">
                    <form action="<?= PATIENTS_CONTROLLER ?>" id="theForm" class="w-100"
                          formFields="FileNumber,PatientName,PatientNo,DOB,FirebaseId,Restoration,Crown,Discolouration,OpenApex,Caries,Perforation,Note">
                        <?php include FIREBASE_ID_INPUT ?>
                        <div class="row justify-content-center">
                            <div class="col-2">
                                <div class="form-group">
                                    <label class="row">File Number</label>
                                    <input type="text" name="FileNumber" id="FileNumber" class="form-control row"
                                           required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label class="row">Name</label>
                                    <input type="text" name="PatientName" id="PatientName" class="form-control row"
                                           required>
                                </div>
                            </div>
                            <div class="col-3 mr-1">
                                <div class="form-group row">
                                    <label class="col-3">Phone</label>
                                    <input type="number" id="PatientNo" name="PatientNo" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group row">
                                    <label class="col-12">Date of Birth</label>
                                    <input type="date" name="DOB" id="DOB"
                                           class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-5">
                                <fieldset>
                                    <label class="mainLabel">Clinical Findings(Tooth)</label>
                                    <div class="row justify-content-around">
                                        <label class="float-left">Restoration</label>
                                        <label class="radio-inline" style="margin-left: 2.8rem">
                                            <input type="radio" name="Restoration" id="Restoration" value="Yes"
                                                   required>Yes</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="Restoration" id="Restoration" value="No" required>No</label>
                                    </div>
                                    <div class="row justify-content-around">
                                        <label class="float-left mr-5">Crown</label>
                                        <label class="radio-inline" style="margin-left: 1.8rem">
                                            <input type="radio" name="Crown" id="Crown" value="Yes" required>Yes</label>
                                        <label class="radio-inline"><input type="radio" name="Crown" id="Crown"
                                                                           value="No" required>No</label>
                                    </div>
                                    <div class="row justify-content-around">
                                        <label class="float-left">Discolouration</label>
                                        <label class="radio-inline ml-4">
                                            <input type="radio" name="Discolouration" id="Discolouration" value="Yes"
                                                   required>Yes</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="Discolouration" id="Discolouration" value="No"
                                                   required>No</label>
                                    </div>
                                </fieldset>
                                <fieldset class="mt-5">
                                    <label class="mainLabel">Radiographic
                                        Findings(Tooth)</label>
                                    <div class="row justify-content-around">
                                        <label class="float-left">Open Apex</label>
                                        <label class="radio-inline" style="margin-left: 1.8rem">
                                            <input type="radio" name="OpenApex" id="OpenApex" value="Yes"
                                                   required>Yes</label>
                                        <label class="radio-inline"><input type="radio" name="OpenApex" id="OpenApex"
                                                                           value="No" required>No</label>
                                    </div>
                                    <div class="row justify-content-around">
                                        <label class="float-left mr-5">Caries</label>
                                        <label class="radio-inline" style="margin-left: 0.8rem">
                                            <input type="radio" name="Caries" id="Caries" value="Yes"
                                                   required>Yes</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="Caries" id="Caries" value="No" required>No</label>
                                    </div>
                                    <div class="row justify-content-around">
                                        <label class="float-left">Perforation</label>
                                        <label class="radio-inline" style="margin-left: 1.8rem">
                                            <input type="radio" name="Perforation" id="Perforation" value="Yes"
                                                   required>Yes</label>
                                        <label class="radio-inline"><input type="radio" name="Perforation" value="No"
                                                                           required>No</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-6 ml-4">
                                <fieldset>
                                    <label class="mainLabel">Diagnose Test</label>
                                    <div class="row mt-3">
                                        <div class="col-10 ml-5">
                                            <table class="table table-bordered table-sm" id="DiagnoseTest">
                                                <tbody>
                                                <tr class="ToothNo">
                                                    <th>Tooth No</th>
                                                    <td><input type="text" name="ToothNo[0]" id="ToothNo"
                                                               style="width: 7rem;margin-left: 0.2rem"
                                                               class="form-control testInput"></td>
                                                    <td><input type="text" name="ToothNo[1]" id="ToothNo"
                                                               class="form-control testInput"></td>
                                                </tr>
                                                <tr class="Ept">
                                                    <th>EPT</th>
                                                    <td><input type="text" name="Ept[0]" id="Ept"
                                                               class="form-control testInput"></td>
                                                    <td><input type="text" name="Ept[1]" id="Ept1"
                                                               class="form-control testInput"></td>
                                                </tr>
                                                <tr class="Heat">
                                                    <th>Heat</th>
                                                    <td><input type="text" name="Heat[0]" id="Heat"
                                                               class="form-control testInput"></td>
                                                    <td><input type="text" name="Heat[1]" id="Heat"
                                                               class="form-control testInput"></td>
                                                </tr>
                                                <tr class="Percussion">
                                                    <th>Percussion</th>
                                                    <td><input type="text" name="Percussion[0]" id="Percussion"
                                                               class="form-control testInput"></td>
                                                    <td><input type="text" name="Percussion[1]" id="Percussion"
                                                               class="form-control testInput"></td>
                                                </tr>
                                                <tr class="Palpation">
                                                    <th>Palpation</th>
                                                    <td><input type="text" name="Palpation[0]" id="Palpation"
                                                               class="form-control testInput"></td>
                                                    <td><input type="text" name="Palpation[1]" id="Palpation"
                                                               class="form-control testInput"></td>
                                                </tr>
                                                <tr class="ProbeDptLoc">
                                                    <th>Probe Dpt/Loc</th>
                                                    <td><input type="text" name="ProbeDptLoc[0]" id="ProbeDptLoc"
                                                               class="form-control testInput"></td>
                                                    <td><input type="text" name="ProbeDptLoc[1]" id="ProbeDptLoc"
                                                               class="form-control testInput"></td>
                                                </tr>
                                                <tr class="Mobility">
                                                    <th>Mobility</th>
                                                    <td><input type="text" name="Mobility[0]" id="Mobility"
                                                               class="form-control testInput"></td>
                                                    <td><input type="text" name="Mobility[1]" id="Mobility"
                                                               class="form-control testInput"></td>
                                                </tr>
                                                <tr class="SpecialTests">
                                                    <th>Special tests</th>
                                                    <td><input type="text" name="SpecialTests[0]" id="SpecialTests"
                                                               class="form-control testInput"></td>
                                                    <td><input type="text" name="SpecialTests[1]" id="SpecialTests"
                                                               class="form-control testInput"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="form-group row justify-content-end pt-1 pb-1">
                                                <button type="button" name="add" id="add"
                                                        onclick="addColumnToTestTable()"
                                                        class="btn btn-outline-secondary btn-sm col-3 mr-1">Add Column
                                                </button>
                                                <button type="button" name="remove" id="remove"
                                                        onclick="removeColumnToTestTable()"
                                                        class="btn btn-outline-secondary btn-sm col-3">
                                                    Remove Column
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-8 ml-5">
                                            <label class="col-3 mainLabel">Note</label>
                                            <textarea rows="0" type="text"
                                                      placeholder="Type your Note Here" id="Note"
                                                      name="Note" class="form-control">
                                            </textarea>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>


                        <div class="form-group row justify-content-center pt-4 pb-2">
                            <button type="submit" class="btn btn-secondary btn-sm col-3 btn-block">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include JS ?>
</body>
</html>
