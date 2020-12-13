<?php
/** FUNCTIONS TO VIEW, ADD, EDIT, REMOVE DENTISTS TO AND FROM THE DATABASE*/
include 'connection.php';


/**add dentist to the database
 * @param $name
 * @param $email
 * @param $phoneNo
 * @param $slots // 8AM-6PM
 * @return array
 */
function addDentistToDatabase($name, $email, $phoneNo, $slots)
{
    $result = ['result' => false, 'message' => 'Unable to save dentist !'];
    if (!$name || !$email || !$phoneNo) {
        $result['message'] = "Please Enter All Details";
        return $result;
    }
    $phoneNoIsValid = isPhoneNoValid($phoneNo);
    if (!$phoneNoIsValid) {
        $result['message'] = "Please Enter Valid Phone No";
        return $result;
    }
    $emailValid = isEmailValid($email);
    if (!$emailValid) {
        $result['message'] = "Please Enter a Valid google Email";
        return $result;
    }

    $slotsValid = count($slots) > 0;
    if (!$slotsValid) {
        $result['message'] = "Please Select At least one Work Hour";
        return $result;
    }
    $dentists = getDentists();
    $dentistExists = false;
    // good for adding sorting before searching
    foreach ($dentists as $dentist) {
        $valuesExist = $dentist['email'] === $email || $dentist['id'] === $phoneNo || $dentist['name'] === $name;
        if ($valuesExist) {
            $dentistExists = true;
            break;
        }
    }
    if ($dentistExists) {
        $result['message'] = "Similar Dentist Already Exists !";
        return $result;
    }
    $dentistSaved = saveRecord(DENTISTS_COLLECTION, ['name' => $name, 'email' => $email, 'id' => $phoneNo, 'slots' => $slots], true);
    if ($dentistSaved) {
        $result['message'] = "Dentist Saved Successfully";
        $result['result'] = true;
        return $result;
    }
    return $result;
}

/**modify dentist in the database
 * @param $name
 * @param $email
 * @param $phoneNo
 * @param $firebaseId
 * @param $slots // 8AM-6PM
 * @return array
 */
function editDentistInDatabase($name, $email, $phoneNo, $firebaseId, $slots)
{
    $result = ['result' => false, 'message' => 'Unable to edit dentist !'];
    if (!$name || !$email || !$phoneNo) {
        $result['message'] = "Please Enter All Details";
        return $result;
    }
    $phoneNoIsValid = isPhoneNoValid($phoneNo);
    if (!$phoneNoIsValid) {
        $result['message'] = "Please Enter Valid Phone No";
        return $result;
    }
    $emailValid = isEmailValid($email);
    if (!$emailValid) {
        $result['message'] = "Please Enter a Valid google Email";
        return $result;
    }
    if (!$firebaseId) {
        $result['message'] = "Please Select Dentist Again";
        return $result;
    }
    $data = [];
    $dentist = getDentists(['param' => 'FirebaseId', 'operator' => '==', 'value' => $firebaseId], true);
    if (count($dentist) > 0) {
        $data = $dentist; // old data
    }
    $data['slots'] = $slots;
    $data['name'] = $name;
    $data['email'] = $email;
    $data['id'] = $phoneNo;
    $data['FirebaseId'] = $firebaseId;
    $dentistSaved = saveRecord(DENTISTS_COLLECTION, $data);
    if ($dentistSaved) {
        $result['message'] = "Dentist Saved Successfully";
        $result['result'] = true;
        return $result;
    }
    return $result;
}

/**remove existing dentist
 * @param $firebaseId
 * @return null
 */
function deleteDentistFromDatabase($firebaseId)
{
    $result = ['result' => false, 'message' => 'Unable to remove dentist !'];
    if (!$firebaseId) {
        $result['message'] = "Please Enter Valid Id";
        return $result;
    }
    $query = ['param' => 'FirebaseId', 'operator' => '==', 'value' => $firebaseId];
    $dentistSaved = removeRecord(DENTISTS_COLLECTION, $query);
    if ($dentistSaved) {
        $result['message'] = LIST_DENTIST; // redirect to list dentist in javascript
        $result['result'] = true;
        return $result;
    }
    return $result;
}

/**get dentists existing in the database
 * filters:- name, email, phoneNo
 * @param array $filters
 * @param bool $getOne
 * @return array
 */
function getDentists($filters = [], $getOne = false)
{
    return getRecords(DENTISTS_COLLECTION, $filters, $getOne);
}
