<?php
/** HANDLE ALL DATABASE OPERATIONS */
include_once(dirname(__FILE__, 2) . '/vendor/autoload.php');

use Google\Cloud\Firestore\DocumentSnapshot;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

define('FIREBASE_CONFIG', array(
    'databaseURL' => 'https://dental-hero-1e694.firebaseio.com/',
    "type" => "service_account",
    "project_id" => "dental-hero-1e694",
    "private_key_id" => "802b6ea6ac8c5fde3e4d755c919d4fd947614742",
    "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDACJJ/ZBibIsKG\nznE1EU8rTQYfMipcgACQLKIwmaKmYVuviZkjxG1Yq0/4vxpI8ZISN8Yo7VgRH0rI\nPeaCz2NsPCmlngTTJPFs/qC7utRStw0EoeiXJKPyQs8QjKQe7PkFmlCUUluxkSdZ\nmN3CBOrKtrqFobummGXb9P+8NZ1GNrmB1/EaFM5RRKL1v5LLbRLR+6IIBcnJLfV1\n8SGm7XaBHC3Ph+THlNXC0ZBK7r17OEcSKBTvSw3cb+5LK7K3r5HPGYJEgyOpl1pz\n1AY2Jo4QW+4SXNa8jerkO2roF0RiHsrOK95x0wiiFRoJmextB4HZOMYoFZI0MUX/\n1Zotoe9tAgMBAAECggEAIRwpRDE6Q99fqnYkU0nPfHAMnurbnnTMhA9KDLaksmrF\nb/P51U6sKtZo1MMAcXTDYoQ+1LYtkdAzKOjxWnsPEa2i4aUoAE7VmJ0jkhLBwpxA\nsJyI+axt3xqxUJFrR9DEk/h6LcSwfiKApiRjEe2enQKaEOnkrLIIl/77bBRTcHQ4\nqpFF613YY1fwKWhuSx8pEjUt7Ervalv5L3XPnCUgjYTiVLh58boqX7PNDUz5Kv/d\nDFYAqtjaI9sacUZixloVbQANU4Muz7QPQnU1iwI3t5NyZPFOD18oW342iG1RhfX7\nRjgUv3iqUg9YVTsjCEn5+/MmvItFZ9VoqIlSYYxZeQKBgQD3COCUtWBaScacDh3L\n1WAniX63ZEiaRWGy2K/hQEYAO+iHodPF8tsLE1TvBQO2tcHZFfCtK39uyca8wR/L\nlp/OgQyUs1Xt1CYvXdQOBxiIAxeHi3ASDWtACIHMobYxBafT7Ra1jMKvW+7+IilM\nJ3ONvJ7FIvLDLW3y3q3fJZgPxQKBgQDHALIpDw5SQdV0NBYHVc3uysSEEWwqUH3d\nr6U9TFcZUseHoBm/qZfVsAspFBNgWe2eUlQNj2puHZefiKg2YjF8G8XoSAFWJJSH\nTG4byCMJe64Zi+7QGTJ/kmHj8sXe2Grzoo0r8NRienNgp90vQ0isxv1SJnHh+x3d\n+WPKtjxziQKBgQCYXsJfFwyqRA0b3l0kh6T6XgVpypI5jYf4T00Ui5dht5Hybq93\nzharWy4tC5PB05QQeUbtGQJN7GkHrbsV0P8a2WBQK9lZi1xOJPqR+HB6HCYcNbZX\nfC1l3plpBWmiDRH6VqeGLZ87mGHuIFYGoeLVTr1DbQ39svy5K/5kwARt3QKBgFB6\nr5J7TEdn6HM+PUpqIQZZanAeu6fBNaXZyvGnVfIf0CHBVah1Zbo9n8XVIKDuvXIx\nyVxSi7BMt20FT0HvJCQU90yhEilMUeUFu+HwZnsHUblzJflyt0FwabZOEEe3Lkmp\ni34hv0K0UsTcHk9m3pVBRGFcuuGxNLr4iKsKWBhBAoGAdH5kCUo45KaarN7FQ6I/\nVvs8ksubKNXKAWHcv2zGKIv+qVf0L9UPZPM7rzjZYm2D/of/p5GkTgQN8XnvuvbD\nLfIjl6AJqAu45CgurodXQWt9b/YmjBISiKoK3nS5MA3D/HdXqEzcsR4iS/+lgPak\nEX/Pe8WVF6CL8Oap59Rswsc=\n-----END PRIVATE KEY-----\n",
    "client_email" => "dental-hero-1e694@appspot.gserviceaccount.com",
    "client_id" => "101395232055487550918",
    "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
    "token_uri" => "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
    "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/dental-hero-1e694%40appspot.gserviceaccount.com"
));

define('USERS_COLLECTION', 'Admin');
define('PATIENTS_COLLECTION', 'Appointment');
define('APPOINTMENTS_COLLECTION', 'Appointment');
define('DENTISTS_COLLECTION', 'Dentist');

define('USER_SESSION_KEY', 'LoggedInUser');
define('CACHE_KEY', 'Records');
if (session_status() === PHP_SESSION_NONE) {
    setCorsHeaders();
    session_start();
}

function getCollection($collection)
{
    return getDatabaseReference()->collection($collection);
}

/** get database connection
 */
function getDatabaseReference()
{
    $serviceAccount = ServiceAccount::fromValue(FIREBASE_CONFIG);
    $fb = (new Factory)->withServiceAccount($serviceAccount)->withDatabaseUri(FIREBASE_CONFIG['databaseURL'])->createFirestore();
    return $fb->database();
}

/** retrieve database records
 * @param $queryParams array // array with query params, [param,operator,value], ['param'=>'id','operator'=>'==','value'=>1]
 * @param bool $getOneRecord
 * @param $collection // name of firestore collection
 * @return array
 */
function getRecords($collection, array $queryParams, $getOneRecord = false)
{
    $cacheKey = $collection . CACHE_KEY;
    $cache = getCache($cacheKey);
    if ($cache) {
        if (!$getOneRecord) {
            return $cache;
        }
        $param = $queryParams['param'];
        $value = $queryParams['value'];
        foreach ($cache as $row) {
            if ($row[$param] === $value) {
                return $row;
            }
        }
        return [];
    }
    $db = getCollection($collection);
    $documents = $db;
    if (count($queryParams) > 0) {
        $param = $queryParams['param'];
        $operator = $queryParams['operator'];
        $value = $queryParams['value'];
        if ($param === 'FirebaseId') {
            $documents = $db->document($value);
            return setFireBaseId($documents->snapshot());
        }

        $documents = $db->where((string)$param, (string)$operator, (string)$value);
    }
    if ($getOneRecord) {
        $documents = $documents->limit(1)->documents();
        if ($documents->size() === 0) {
            return [];
        }
        $row = $documents->rows()[0];
        return setFireBaseId($row);
    }
    $records = [];
    foreach ($documents->documents()->rows() as $row) {
        $records[] = setFireBaseId($row);
    }
    setCache($cacheKey, $records);
    return $records;
}

/** for use when editing and deleting records
 * remove if object id is the same as firebase id
 * @param $row DocumentSnapshot // firebase collection element
 */
function setFireBaseId($row)
{
    $result = $row->data();
    $result['FirebaseId'] = $row->id();
    return $result;
}

/** add, update database record
 * @param $collection // name of firestore collection
 * @param $data array // array with data to persist to firebase
 * @param bool $newRecord // whether in edit or add mode
 * @return bool
 */
function saveRecord($collection, array $data, $newRecord = false)
{
    $cacheKey = $collection . CACHE_KEY;
    $db = getCollection($collection);
    if ($newRecord) {
        $writeResult = $db->newDocument()->set($data);
        setCache($cacheKey, null); //clear cache
        return array_key_exists('updateTime', $writeResult);
    }
    if (isset($data['FirebaseId'])) {
        // remove snapshot;
        $db = getCollection($collection);
        $removedFireBaseId = array_diff_assoc($data, ['FirebaseId' => $data['FirebaseId']]);
        $writeResult = $db->document($data['FirebaseId'])->set($removedFireBaseId);
        setCache($cacheKey, null);// clear cache
        return array_key_exists('updateTime', $writeResult);
    }
    // search using params and update
    return false;
}

/** delete database record
 * @param $collection // name of firestore collection
 * @param $queryParams array // array with query params, [param,operator,value], ['param'=>'id','operator'=>'==','value'=>1]
 * @return bool
 */
function removeRecord($collection, array $queryParams)
{
    if (isset($queryParams['param']) && (((string)$queryParams['param']) === 'FirebaseId')) {
        $cacheKey = $collection . CACHE_KEY;
        // remove snapshot;
        $db = getCollection($collection);
        $writeResult = $db->document($queryParams['value'])->delete();
        $success = array_key_exists('transformResults', $writeResult);
        if ($success) {
            setCache($cacheKey, null);// clear cache
        }
        return $success;
    }
    // search record using query params and delete it
    return false;
}

/** send client side error messages to admin
 * @param $msg // message object
 */
function logMessage($msg)
{
    $time = date("F jS Y, H:i", time() + 25200);
    $file = 'errors.txt';
    $open = fopen($file, 'ab');
    fwrite($open, $time . '  :  ' . json_encode($msg) . "\r\n");
    fclose($open);
}

/** cache request data
 * i am using session variable for now but a more robust caching scheme
 * can be implemented, the interface of the function does not change
 * @param $key string
 * @param $value mixed
 */
function setCache($key, $value)
{
    // $_SESSION[$key] = $value;
}

/** get cached request data
 * @param $key string
 * @return false|mixed
 */
function getCache($key)
{
    /*if (isset($_SESSION[$key])) {
        return $_SESSION[$key];
    }*/
    return false;
}

/** increment appointment id
 * @param $appointmentObject array
 * @return array
 */
function setAppointmentId($appointmentObject)
{
    $idIsAlreadySet = isset($appointmentObject['AppointmentId']) && ((int)$appointmentObject['AppointmentId']) > 0;
    if ($idIsAlreadySet) {
        return $appointmentObject;
    }
    $appointments = getRecords(APPOINTMENTS_COLLECTION, []);
    // sort in descending order with the latest at the top
    uksort($appointments, 'sortArray');
    $lastAppointment = $appointments[0];
    $lastId = $lastAppointment['AppointmentId'];
    $appointmentObject['AppointmentId'] = ((int)$lastId) + 1;
    return $appointmentObject;
}

function sortArray($a, $b)
{
    $idA = (int)$a['AppointmentId'];
    $idB = (int)$b['AppointmentId'];
    if ($idA == $idB) return 0;
    return ($idA < $idB) ? -1 : 1;
}

/** set cors headers*/
function setCorsHeaders()
{
    // headers already sent so abort
    if (headers_sent()) {
        return;
    }

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        }

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }

        exit(0);
    }
}


/** return full appointment object
 *  used to prevent writing document to firebase
 * that has some fields missing
 * @param $values
 * @return array
 */
function getAppointmentObject(array $values)
{
    $keys = [
        'PatientName',
        'DentistName',
        'AppointmentId',
        'PatientNo',
        'AppointmentDate',
        'AppointmentTime',
        'DOB',
        'FileNumber',
        'FirebaseId',
        // 'ProgressNotes'
    ];
    $app = setObject($keys, $values);
    $app['ProgressNotes'] = getProgressNoteObject($values);
    return $app;
}

/** return full progress note object
 *  used to prevent writing document to firebase
 * that has some fields missing
 * @param $values
 * @return array
 */
function getProgressNoteObject(array $values)
{
    $keys = [
        'Restoration',
        'Crown',
        'Discolouration',
        'OpenApex',
        'Carries',
        'Perforation',
        'Note',
        // 'Test'
    ];
    $not = getObjectValue('ProgressNotes', $values);
    if ($not === '') {
        return [];
    }
    $note = setObject($keys, $not);
    $note['Test'] = getTestObject($values);
    return $note;
}


/** return full Diagnose Test object
 *  used to prevent writing document to firebase
 * that has some fields missing
 * @param $values
 * @return array
 */
function getTestObject(array $values)
{
    $keys = [
        'ToothNo',
        'Ept',
        'Heat',
        'Percussion',
        'Palpation',
        'ProbeDptLoc',
        'Mobility',
        'SpecialTests'
    ];
    $test = getObjectValue('Test', $values);
    if ($test === '') {
        return [];
    }
    return setObject($keys, $test);
}

/** set object values and return it
 *  used to prevent writing document to firebase
 * that has some fields missing
 * @param array $keys
 * @param array $values
 * @return array
 */
function setObject(array $keys, array $values)
{
    $object = [];
    foreach ($keys as $key) {
        $object[$key] = getObjectValue($key, $values);
    }
    return $object;
}

/** extract value from array and return it
 *  used to prevent writing document to firebase
 * that has some fields missing
 * @param $key
 * @param array $values
 * @return array
 */
function getObjectValue($key, array $values)
{
    $object = '';
    if (isset($values[$key])) {
        $object = $values[$key];
    }
    return $object;
}

function getTesValue(array $values)
{
    $obj = [];
    foreach ($values as $value) {
        if (is_array($value)) {
            if ($value[0] === "") {
                continue;
            }

            $obj[] = $value[0];
        }
    }
    return $obj;
}
