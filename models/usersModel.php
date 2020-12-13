<?php
/** FUNCTIONS TO VIEW, ADD, EDIT, REMOVE USERS TO AND FROM THE DATABASE*/
include 'connection.php';


/** log user out
 */
function signOutUser()
{
    setSessionKey();
}

/** authenticate user
 * @param $email
 * @param $password
 * @return array
 */
function signInUser($email, $password)
{
    $result = ['result' => false, 'message' => 'Unable to sign in user !'];
    if (!$email || !$password) {
        $result['message'] = "Please Enter Email and Password";
        return $result;
    }
    $emailValid = isEmailValid($email);
    if (!$emailValid) {
        $result['message'] = "Please Enter a Valid google Email";
        return $result;
    }

    $query = ['param' => 'email', 'operator' => '==', 'value' => $email];
    $user = getSessionKey();
    if (!$user) {
        $user = getUsers($query, true);
    }
    $userExists = isset($user['email']) && ($user['email'] === $email);
    if (!$userExists) {
        $result['message'] = "The Email address you entered does not exist !";
        return $result;
    }
    $systemPassword = $user['password'];
    $passwordExists = isValidPassword($password, $systemPassword);
    if ($passwordExists) {
        $result['message'] = "You have been successfully signed In";
        $result['result'] = true;
        setSessionKey($email, $password);
    } else {
        $result['message'] = "Wrong Password !";
    }
    return $result;
}

/** return current user email and password from session
 * @return array | bool
 * */
function getSessionKey()
{
    if (!isset($_SESSION[USER_SESSION_KEY])) {
        return false;
    }
    return $_SESSION[USER_SESSION_KEY];
}

/** set current user email and password to session
 * @param $email
 * @param $password
 */
function setSessionKey($email = null, $password = null)
{
    // clear session key if non if provided
    if (!$email || !$password) {
        $_SESSION[USER_SESSION_KEY] = null;
        return;
    }
    $_SESSION[USER_SESSION_KEY] = ['email' => $email, 'password' => $password];
}

/** check provided password against password from the database
 * use hash checker if password was hashed
 * @param $userPassword
 * @param $systemPassword
 * @return bool
 */
function isValidPassword($userPassword, $systemPassword)
{
    return (string)$userPassword === (string)$systemPassword;
}

/**add user to the database
 * @param $name
 * @param $email
 * @param $password
 * @return array
 */
function addUserToDatabase($name, $email, $password)
{
    $result = ['result' => false, 'message' => 'Unable to save user !'];
    if (!$name || !$email || !$password) {
        $result['message'] = "Please Enter All Details";
        return $result;
    }
    $emailValid = isEmailValid($email);
    if (!$emailValid) {
        $result['message'] = "Please Enter a Valid google Email";
        return $result;
    }
    $userExists = userExists($email);

    if ($userExists) {
        $result['message'] = "Similar User Already Exists !";
        return $result;
    }

    $hashedPassword = hashUserPassword($password);
    $userSaved = saveRecord(USERS_COLLECTION, ['name' => $name, 'email' => $email, 'password' => $hashedPassword], true);
    if ($userSaved) {
        $result['message'] = "User Saved Successfully";
        $result['result'] = true;
        return $result;
    }
    return $result;
}

/** generate a hash of the user password
 * @param $password
 * @return array
 */
function hashUserPassword($password)
{
    return $password;
}

/** compare hashed passwords, they must both be hashed with the same algorithm
 * @param $newPlainPassword
 * @param $oldHashedPassword
 * @return bool
 */
function isPasswordValid($newPlainPassword, $oldHashedPassword)
{
    return hashUserPassword($newPlainPassword) === $oldHashedPassword;
}

/**modify user in the database
 * @param $name
 * @param $email
 * @param $password
 * @return array
 */
function editUserInDatabase($name, $email, $password)
{
    $result = ['result' => false, 'message' => 'Unable to edit user !'];
    if (!$name || !$email) {
        $result['message'] = "Please Enter All Details";
        return $result;
    }
    $emailValid = isEmailValid($email);
    if (!$emailValid) {
        $result['message'] = "Please Enter a Valid google Email";
        return $result;
    }
    $userExists = userExists($email);

    if (!$userExists) {
        $result['message'] = "User Not Found !";
        return $result;
    }
    // check if password was modified
    $userPassword = '';
    if ($password) {
        $currentHashedPassword = $userExists['password'];
        $passwordWasNotModified = isPasswordValid($password, $currentHashedPassword);
        if ($passwordWasNotModified) {
            $userPassword = $currentHashedPassword;
        } else {
            $userPassword = hashUserPassword($password);
        }
    }
    $userSaved = saveRecord(USERS_COLLECTION, ['name' => $name, 'email' => $email, 'password' => $userPassword]);
    if ($userSaved) {
        $result['message'] = "User Saved Successfully";
        $result['result'] = true;
        return $result;
    }
    return $result;
}

/**remove existing user
 * @param $firebaseId
 * @return null
 */
function deleteUserFromDatabase($firebaseId)
{
    $result = ['result' => false, 'message' => 'Unable to remove user !'];
    if (!$firebaseId) {
        $result['message'] = "Please Enter Email";
        return $result;
    }
    if (!$firebaseId) {
        $result['message'] = "Please Enter Valid Id";
        return $result;
    }
    $query = ['param' => 'FirebaseId', 'operator' => '==', 'value' => $firebaseId];
    $userSaved = removeRecord(USERS_COLLECTION, $query);
    if ($userSaved) {
        $result['message'] = "User Saved Successfully";
        $result['result'] = true;
        return $result;
    }
    return $result;
}


/**get users existing in the database
 * filters:- email
 * @param array $filters
 * @param bool $getOne
 * @return array
 */
function getUsers($filters = [], $getOne = false)
{
    return getRecords(USERS_COLLECTION, $filters, $getOne);
}

/** check if user exists in the database
 * filters:- email
 * @param $email
 * @return bool | array
 */
function userExists($email)
{
    $query = ['param' => 'email', 'operator' => '==', 'value' => $email];
    $user = getUsers($query, true);
    if (count($user) === 0) {
        return false;
    }
    return $user;
}
