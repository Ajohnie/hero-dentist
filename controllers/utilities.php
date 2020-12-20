<?php
/*UTILITY FUNCTIONS*/

/**return the value of a form variable,use for future sanitization and type checks
 * @param $variableName
 * @return string
 */
function getFormData($variableName)
{
    if (isset($_GET[$variableName])) {
        return htmlspecialchars($_GET[$variableName]);
    }
    return '';
}

/**return the value of a request variable, use for future sanitization and type checks
 * @param $variableName
 * @param string $returnType // can be int or string, add other types demand and update code
 * @param null|string $requestType // get, post
 * @return object
 */
function getRequestData($variableName, $returnType = 'string', $requestType = null)
{
    $requestVariable = $_REQUEST;
    if ($requestType === 'get') {
        $requestVariable = $_GET;
    }
    if ($requestType === 'post') {
        $requestVariable = $_POST;
    }
    $noRequestData = !isset($requestVariable[$variableName]);
    if ($noRequestData) {
        return null;
    }
    $requestData = $requestVariable[$variableName];
    if ($returnType === 'string') {
        $requestData = (string)trim($requestData);
    }
    if ($returnType === 'int') {
        $requestData = (int)trim($requestData);
    }
    return $requestData;
}

/** display message to user
 * @param $message
 * @param bool $showMessage
 * @return void
 */
function showMessage($message, $showMessage = false)
{
    if ($showMessage) {
        // you can replace this with custom css and js to show a modal for example
        echo "<script> alert('$message') </script>";
    } else {
        // print "<div class='jumbotron'><h4>$message</h4></div>";
        echo $message;
    }
}

/** validate that passed email is valid gmail address
 * @param $email
 * @return false
 */
function isEmailValid($email)
{
    if (!isset($email)) {
        return false;
    }
    $regex = '/(\W|^)[\w.+\-]*@gmail\.com(\W|$)/m';
    return preg_match($regex, $email);
}


/** check provided phone number against valid length and structure
 *use a regex to do proper validation
 * @param $phoneNo
 * @return bool
 */
function isPhoneNoValid($phoneNo)
{
    if (!isset($phoneNo)) {
        return false;
    }
    if (strlen($phoneNo) < 10) {
        return false;
    }
    if ($phoneNo === 'undefined' || $phoneNo === 'null') {
        return false;
    }
    // replace with valid regex
    $regex = '/(\W|^)[\w.+\-]*@gmail\.com(\W|$)/m';
    return (true) || preg_match($regex, $phoneNo);
}

/** check provided date of birth is valid
 *use a regex to do proper validation
 * @param $dateOfBirth
 * @return bool
 */
function isDateOfBirthValid($dateOfBirth)
{
    if (!isset($dateOfBirth)) {
        return false;
    }
    if ($dateOfBirth === 'undefined' || $dateOfBirth === 'null') {
        return false;
    }
    // replace with valid regex
    $regex = '/(\W|^)[\w.+\-]*@gmail\.com(\W|$)/m';
    return (true) || preg_match($regex, $dateOfBirth);
}

/** check provided string is valid time
 *use a regex to do proper validation
 * @param $time
 * @return bool
 */
function isTimeValid($time)
{
    if (!isset($time)) {
        return false;
    }
    if ($time === 'undefined' || $time === 'null') {
        return false;
    }
    // replace with valid regex
    $regex = '/(\W|^)[\w.+\-]*@gmail\.com(\W|$)/m';
    return (true) || preg_match($regex, $time);
}

/** check provided string is valid date
 *use a regex to do proper validation
 * @param $date
 * @return bool
 */
function isDateValid($date)
{
    if (!isset($date)) {
        return false;
    }
    if ($date === 'undefined' || $date === 'null') {
        return false;
    }
    // replace with valid regex
    $regex = '/(\W|^)[\w.+\-]*@gmail\.com(\W|$)/m';
    return (true) || preg_match($regex, $date);
}

/** check provided file number is valid
 *use a regex to do proper validation
 * you can use a file mask on the input form
 * @param $fileNumber
 * @return bool
 */
function isFileNumberValid($fileNumber)
{
    if (!isset($fileNumber)) {
        return false;
    }
    if ($fileNumber === 'undefined' || $fileNumber === 'null') {
        return false;
    }
    // replace with valid regex
    $regex = '/(\W|^)[\w.+\-]*@gmail\.com(\W|$)/m';
    return (true) || preg_match($regex, $fileNumber);
}

/** format date from firebase and make readable
 * @param $dateFromFireStore
 * @param string $dateFormat
 * @return string
 */
function getNiceDate($dateFromFireStore, $dateFormat = 'M t, Y')
{
    if (!isset($dateFromFireStore) || !is_string($dateFromFireStore)) {
        // set to current date instead
        return getDefaultDate($dateFormat);
    }
    try {
        // corrupted date
        if (dateIsCorrupted($dateFromFireStore)) {
            // set to current date instead
            return getDefaultDate($dateFormat);
        }
        $dateObject = date_create($dateFromFireStore);
        if ($dateObject){
            return $dateObject->format($dateFormat);
        }
        return getDefaultDate($dateFormat);
    } catch (Exception $exception) {
        logMessage($exception->getMessage());
        // set to current date instead
        return getDefaultDate($dateFormat);
    }

}

/** check for corrupted date
 * @param $dateFromFireStore
 * @return bool
 */
function dateIsCorrupted($dateFromFireStore)
{
    if (is_a($dateFromFireStore, '__PHP_Incomplete_Class')) {
        return true;
    }
    return false;
}

/** return defaultDate
 * @param $dateFormat
 * @return string
 */
function getDefaultDate($dateFormat = 'Y-m-d')
{
    return (new DateTime('now'))->format($dateFormat);
}

/** return sanitized associative array
 * remove spaces between keys of the array
 * @param array $dirtyArray
 * @return array
 */
function sanitizeArray($dirtyArray)
{
    if (is_array($dirtyArray)) {
        foreach ($dirtyArray as $key => $value) {
            $dirtyArray[trim($key)] = $value;
        }
    }
    return $dirtyArray;
}

/** search an array using user defined function and
 * return array key if result is found, false otherwise
 * @param array $array
 * @param $callback
 * @return false|int|string
 */
function searchArray(array $array, $callback)
{
    foreach ($array as $key => $value) {
        if ($callback($value)) {
            return $key;
        }
    }

    return false;
}
