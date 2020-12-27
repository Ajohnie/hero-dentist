<?php
/** FUNCTIONS TO VIEW, ADD, EDIT, REMOVE DENTISTS*/
include (dirname(__FILE__, 2)) . '/shared/constants.php';

include 'utilities.php';
include DENTISTS_MODEL;

/** call all methods so that Ajax calls can locate them */
addDentist();
editDentist();
deleteDentist();
viewDentists();
showDentistSelect();

/**add dentist to the database
 * @return null
 */
function addDentist()
{
    $addDentist = getRequestData('add', 'string', 'post');
    if ($addDentist) {
        $slots = json_decode(getRequestData('slots'), false);
        $name = getRequestData('name');
        $email = getRequestData('email');
        $phoneNo = getRequestData('id');
        $result = addDentistToDatabase($name, $email, $phoneNo, $slots);
        if ($result['result']) {
            $result['message'] = LIST_DENTIST; // redirect do dentist-list
        }
        echo json_encode($result);
    }
    return null;
}

/**modify existing dentist
 * @return null
 */
function editDentist()
{
    $editDentist = getRequestData('edit', 'string', 'post');
    if ($editDentist) {
        $slots = json_decode(getRequestData('slots'), false);
        $name = getRequestData('name');
        $email = getRequestData('email');
        $phoneNo = getRequestData('id');
        $firebaseId = getRequestData('FirebaseId');
        $result = editDentistInDatabase($name, $email, $phoneNo, $firebaseId, $slots);
        if ($result['result']) {
            $result['message'] = LIST_DENTIST; // redirect do dentist-list
        }
        echo json_encode($result);
    }
    return null;
}

/**remove existing dentist
 * @return null
 */
function deleteDentist()
{
    $deleteDentist = getRequestData('delete', 'string', 'post');
    if ($deleteDentist) {
        $firebaseId = getRequestData('FirebaseId');
        $result = deleteDentistFromDatabase($firebaseId);
        echo json_encode($result);
    }
    return null;
}

/**show dentists existing in the database
 * @return null
 */
function viewDentists()
{
    $viewDentist = getRequestData('view', 'string', 'post');
    if ($viewDentist) {
        $dentists = getDentists();
        $rows = '';
        $deleteActionUrl = DENTISTS_CONTROLLER;
        $nextUrl = ADD_DENTIST;
        foreach ($dentists as $dentist) {
            $rowData = json_encode($dentist);
            $rowId = $dentist['FirebaseId'];
            $editAction = 'storeTableRowData(' . $rowData . ',"' . $nextUrl . '")';
            $deleteAction = 'deleteTableRowData("' . $rowId . '","' . $deleteActionUrl . '")';

            if (isset($dentist['slots'])) {
                $slots = implode(',', $dentist['slots']);
            } else {
                $slots = '';
            }
            if (isset($dentist['name'])) {
                $name = $dentist['name'];
            } else {
                $name = '';
            }
            if (isset($dentist['id'])) {
                $id = $dentist['id'];
            } else {
                $id = '';
            }
            if (isset($dentist['email'])) {
                $email = $dentist['email'];
            } else {
                $email = '';
            }
            $rows .= "<tr><td>" . $name . "</td>
                    <td>" . $id . "</td>
                    <td>" . $email . "</td>
                    <td>" . $slots . "</td>
                    <td><span class='fa fa-pencil-square-o text-success' id='edit' onclick='$editAction'></span>
                       <span class='fa fa-remove text-danger' id='delete' onclick='$deleteAction'></span>
                     </td>
                 </tr>";
        }
        echo json_encode($rows);
    }
    return null;
}

function showDentistSelect()
{
    $viewDentist = getRequestData('viewDentist', 'string', 'post');
    if ($viewDentist) {
        $dentists = getDentists();
        $options = '<option value="">All Dentists</option>';
        foreach ($dentists as $dentist) {
            $name = $dentist['name'];
            $options .= "<option value='$name'>" . $name . "</option>";
        }
        echo $options;
    }
}
