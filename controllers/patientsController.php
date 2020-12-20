<?php
/** FUNCTIONS TO VIEW, ADD, EDIT, REMOVE PATIENTS*/
include (dirname(__FILE__, 2)) . '/shared/constants.php';

include 'utilities.php';
include PATIENTS_MODEL;

/** call all methods so that Ajax calls can locate them */
addPatient();
editPatient();
deletePatient();
viewPatients();
addPatientNotes();
editPatientNotes();
viewPatientNotes();
deletePatientNote();

/**add patient to the database
 * @return null
 */
function addPatient()
{
    $addPatient = getRequestData('add', 'string', 'post');
    if ($addPatient === 'add') {
        $fileNumber = getRequestData('FileNumber');
        $name = getRequestData('PatientName');
        $phoneNo = getRequestData('PatientNo');
        $dateOfBirth = getRequestData('DOB');
        $result = addPatientToDatabase($fileNumber, $name, $phoneNo, $dateOfBirth);
        if ($result['result']) {
            $result['message'] = LIST_PATIENT; // redirect do patient-list
        }
        echo json_encode($result);
    }
    return null;
}

/**modify existing patient
 * @return null
 */
function editPatient()
{
    $editPatient = getRequestData('edit', 'string', 'post');
    if ($editPatient === 'edit') {
        $fileNumber = getRequestData('FileNumber');
        $name = getRequestData('PatientName');
        $phoneNo = getRequestData('PatientNo');
        $dateOfBirth = getRequestData('DOB');
        $firebaseId = getRequestData('FirebaseId');
        $result = editPatientInDatabase($fileNumber, $name, $phoneNo, $dateOfBirth, $firebaseId);
        if ($result['result']) {
            $result['message'] = LIST_PATIENT; // redirect do patient-list
        }
        echo json_encode($result);
    }
    return null;
}

/**remove existing patient
 * @return null
 */
function deletePatient()
{
    $deletePatient = getRequestData('delete', 'string', 'post');
    if ($deletePatient === 'delete') {
        $firebaseId = getRequestData('FirebaseId');
        $result = deletePatientFromDatabase($firebaseId);
        echo json_encode($result);
    }
    return null;
}

/**show patients existing in the database
 * @return null
 */
function viewPatients()
{
    $viewPatient = getRequestData('view', 'string', 'post');
    // make sure request is for patient-list not progress-note-list
    if ($viewPatient === 'view') {
        $patients = getPatients();
        $tableRows = '';
        foreach ($patients as $patient) {
            $patientData = $patient;
            $fileNumber = isset($patientData['FileNumber']) ? $patientData['FileNumber'] : '';
            // checks should be removed when variable naming is made consistent in firebase
            $name = $patientNo = '';

            if (isset($patientData['PatientName'])) {
                $name = $patientData['PatientName'];
            } else if (isset($patientData['patientName'])) {
                $name = $patientData['patientName'];
            }
            if (isset($patientData['PatientNo'])) {
                $patientNo = $patientData['PatientNo'];
            } else if (isset($patientData['patientNo'])) {
                $patientNo = $patientData['patientNo'];
            }
            $patientData['PatientName'] = $name; // make field uniform for serialization
            $patientData['PatientNo'] = $patientNo; // make field uniform
            $dob = getDefaultDate();

            if (isset($patientData['DOB'])) {
                if (dateIsCorrupted($patientData['DOB'])) {
                    $patientData['DOB'] = $dob; // set to default
                } else { // otherwise
                    $dob = $patientData['DOB'];
                }
            }
            // set supported date format for date input(yyyy-mm-dd)
            $patientData['DOB'] = getNiceDate($dob, 'Y-m-t');

            // set value for date column to format Dec 31, 2021
            $dob = getNiceDate($dob);

            $rowData = json_encode($patientData);
            $rowId = $patientData['FirebaseId'];

            // set parameters for ajax calls
            $deleteActionUrl = PATIENTS_CONTROLLER;
            $nextUrl = ADD_PATIENT;
            $viewNoteUrl = LIST_PROGRESS_NOTE;
            $addNoteUrl = ADD_PROGRESS_NOTE;
            $editAction = 'storeTableRowData(' . $rowData . ',"' . $nextUrl . '")';
            $deleteAction = 'deleteTableRowData("' . $rowId . '","' . $deleteActionUrl . '")';
            $viewNoteAction = 'storeTableRowData(' . $rowData . ',"' . $viewNoteUrl . '")';
            $addNoteAction = 'storeTableRowData(' . $rowData . ',"' . $addNoteUrl . '")';

            // show user if progress note has been set
            if (isset($patientData['ProgressNotes']) && count($patientData['ProgressNotes'])) {
                $progressNoteAvailable = ''; // there are no notes, remove view link
            } else {
                $progressNoteAvailable = 'display:none';
            }

            $tableRows .= "<tr><td>" . $fileNumber . "</td>
                    <td>" . $name . "</td>
                    <td>" . $patientNo . "</td>
                    <td>" . $dob . "</td>
                    <td>
                    <span><a href='#' class='progress-note' id='addNote' onclick='$addNoteAction'>Add</a></span>
                    <span><a href='#' class='progress-note' id='viewNote' onclick='$viewNoteAction' style='$progressNoteAvailable'>View</a></span>
                     </td>
                     <td><span class='fa fa-pencil-square-o text-success' id='edit' onclick='$editAction'></span>
                       <span class='fa fa-remove text-danger' id='delete' onclick='$deleteAction'></span>
                     </td>
                 </tr>";
        }
        echo json_encode($tableRows);
    }
    return null;
}


/**show patients notes on patient object
 * @return null
 */
function viewPatientNotes()
{
    $viewPatientNotes = getRequestData('view', 'string', 'post');
    if ($viewPatientNotes === 'viewNote') {
        $patientData = getRequestData('rowData', 'string', 'post');
        $patientDataWasNotSet = !isset($patientData);
        if ($patientDataWasNotSet) {
            return '';
        }
        $notes = getPatientNotes($patientData);
        $tableRows = '';
        foreach ($notes as $note) {
            $rowData = json_encode($note);
            $rowId = $note['FirebaseId'];

            // set parameters for ajax calls
            $deleteActionUrl = PATIENTS_CONTROLLER;
            $nextUrl = ADD_PROGRESS_NOTE;
            $editAction = 'storeTableRowData(' . $rowData . ',"' . $nextUrl . '")';
            $deleteAction = 'deleteTableRowData("' . $rowId . '","' . $deleteActionUrl . '")';
            $tableRows = '';

            $tableRows .= "<tr><td>" . $note['PatientName'] . "</td>
                    <td>" . $note['DentistName'] . "</td>
                    <td>" . $note['Date'] . "</td>
                    <td>" . $note['Note'] . "</td>
                     <td><span class='fa fa-pencil-square-o text-success' id='edit' onclick='$editAction'></span>
                       <span class='fa fa-remove text-danger' id='delete' onclick='$deleteAction'></span>
                     </td>
                 </tr>";
        }
        echo json_encode($tableRows);
    }
    return null;
}


/**add patient notes to patient object and save to database
 * @return null
 */
function addPatientNotes()
{
    $addNote = getRequestData('add', 'string', 'post');
    if ($addNote === 'addNote') {
        $progressNotes = getProgressNotes();
        $fileNumber = getRequestData('FileNumber');
        $name = getRequestData('PatientName');
        $phoneNo = getRequestData('PatientNo');
        $dateOfBirth = getRequestData('DOB');
        $result = addPatientToDatabase($fileNumber, $name, $phoneNo, $dateOfBirth, $progressNotes);
        if ($result['result']) {
            $result['message'] = LIST_PATIENT; // redirect do patient-list
        }
        echo json_encode($result);
    }
    return null;
}

function getProgressNotes()
{
    $tests = $_REQUEST['Tests'];
    $crown = getRequestData('Crown');
    $discolouration = getRequestData('Discolouration');
    $openApex = getRequestData('OpenApex');
    $caries = getRequestData('Caries');
    $restoration = getRequestData('Restoration');
    $perforation = getRequestData('Perforation');
    $note = getRequestData('Note');
    return [
        'Restoration' => $restoration,
        'Crown' => $crown,
        'Discolouration' => $discolouration,
        'OpenApex' => $openApex,
        'Caries' => $caries,
        'Perforation' => $perforation,
        'Note' => $note,
        'Test' => [
            'ToothNo' => getTestValue($tests['ToothNo']),
            'Ept' => getTestValue($tests['Ept']),
            'Heat' => getTestValue($tests['Heat']),
            'Percussion' => getTestValue($tests['Percussion']),
            'Palpation' => getTestValue($tests['Palpation']),
            'ProbeDptLoc' => getTestValue($tests['ProbeDptLoc']),
            'Mobility' => getTestValue($tests['Mobility']),
            'SpecialTests' => getTestValue($tests['SpecialTests'])
        ]
    ];
}

/**edit patient notes to patient object and save to database
 * @return null
 */
function editPatientNotes()
{
    $editNote = getRequestData('edit', 'string', 'post');
    if ($editNote === 'editNote') {
        $progressNotes = getProgressNotes();
        $fileNumber = getRequestData('FileNumber');
        $firebaseId = getRequestData('FirebaseId');
        $name = getRequestData('PatientName');
        $phoneNo = getRequestData('PatientNo');
        $dateOfBirth = getRequestData('DOB');
        $result = editPatientInDatabase($fileNumber, $name, $phoneNo, $dateOfBirth, $firebaseId, $progressNotes);
        if ($result['result']) {
            $result['message'] = LIST_PATIENT; // redirect do patient-list
        }
        echo json_encode($result);
    }
    return null;
}

/**remove patient note from notes field of patient object
 * @return null
 */
function deletePatientNote()
{
    $deleteNote = getRequestData('delete', 'string', 'post');
    if ($deleteNote === 'deleteNote') {
        $firebaseId = getRequestData('FirebaseId');
        $result = deletePatientFromDatabase($firebaseId);
        echo json_encode($result);
    }
    return null;
}
