<?php
/** FUNCTIONS TO VIEW, ADD, EDIT, REMOVE USERS*/
include (dirname(__FILE__, 2)) . '/shared/constants.php';

include 'utilities.php';
include USERS_MODEL;

/** call all methods so that Ajax calls can locate them */
signIn();
addUser();
editUser();
deleteUser();

/**sign in user
 * @return null
 */
function signIn()
{
    $signIn = getRequestData('signIn', 'string', 'post');
    if ($signIn) {
        $email = getRequestData('email', 'string', 'post');
        $password = getRequestData('password', 'string', 'post');
        $result = signInUser($email, $password);
        // set redirect url for ajax call
        if ($result['result']) {
            $result['message'] = LIST_APPOINTMENT;
        }
        echo json_encode($result);
    }
    $signOut = getRequestData('signOut', 'string', 'post');
    if ($signOut) {
        signOutUser();
        $result['result'] = true;
        $result['message'] = HOME;
        echo json_encode($result);
    }
    return null;
}

/**add user to the database
 * @return null
 */
function addUser()
{
    $addUser = getRequestData('signUp', 'string', 'post');
    if ($addUser) {
        $name = getRequestData('name');
        $email = getRequestData('email');
        $password = getRequestData('password');
        $result = addUserToDatabase($name, $email, $password);
        if ($result['result']) {
            $result['message'] = SIGN_IN;
        }
        echo json_encode($result);
    }
    return null;
}

/**modify existing user
 * @return null
 */
function editUser()
{
    $editUser = getRequestData('edit', 'string', 'post');
    if ($editUser) {
        $name = getRequestData('name');
        $email = getRequestData('email');
        $phoneNo = getRequestData('phoneNo');
        $result = editUserInDatabase($name, $email, $phoneNo);
        echo json_encode($result);
    }
    return null;
}

/**remove existing user
 * @return null
 */
function deleteUser()
{
    $deleteUser = getRequestData('delete', 'string', 'post');
    if ($deleteUser) {
        $phoneNo = getRequestData('phoneNo');
        $result = deleteUserFromDatabase($phoneNo);
        echo json_encode($result);
    }
    return null;
}

/**show users existing in the database
 * @return null
 */
function viewUsers()
{
    $viewUser = getRequestData('view', 'string', 'post');
    if ($viewUser) {
        $users = getUsers();
        $rows = '';
        foreach ($users as $duser) {
            $rows .= "<tr>
                    <td>" . $duser['name'] . "</td>
                    <td>" . $duser['phone'] . "</td>
                    <td>" . $duser['email'] . "</td>
                    <td>
                       <span class='fa fa-pencil-square-o text-success' id='edit'></span>
                       <span class='fa fa-remove text-danger' id='delete'></span>
                     </td>
                 </tr>";
        }
        echo json_encode($rows);
    }
    return null;
}
