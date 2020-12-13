<?php
/** DEFINES URLS, NAMES, LINKS AND OTHER "CAN-CHANGE-ANY-TIME" VARIABLES
 *MUST BE INCLUDED AT THE BEGINNING OF EVERY FILE
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/* set root directory*/
define('DOC_ROOT', dirname(__FILE__, 2));

/* set css path */
define('CSS', DOC_ROOT . '/shared/stylesheets.php');

/* set js path */
define('JS', DOC_ROOT . '/shared/javascript.php');

/* set Navbar path */
define('NAV_BAR', DOC_ROOT . '/shared/navbar.php');

/* Definitions for navbar links*/
/** HOME needs to be changed to whatever yours is, if it is an online domain change it to "/"  */
define('HOME', 'http://localhost:8888/FREELANCER/hero-dentist/');
// define('HOME', 'https://hero.bescharityfoundation.org/');
define('BASE', HOME);
define('SIGN_IN', HOME . 'views/users/signin.php');
define('SIGN_UP', HOME . 'views/users/signup.php');

/*side bar */
define('SIDEBAR', DOC_ROOT . '/shared/sidebar.php');

/*search input */
define('SEARCH_INPUT', DOC_ROOT . '/shared/searchInput.php');

/* popup when you click appointment */
define('APPOINTMENT_POPUP', DOC_ROOT . '/shared/appointmentPopUp.php');

/* firebase id input */
define('FIREBASE_ID_INPUT', DOC_ROOT . '/shared/firebaseIdInput.php');

/* you need to check if user is logged in and the log the out on initialization */
define('LOGOUT_OUT', SIGN_IN);
define('LOGO_URL', BASE . 'assets/images/logo.png');

/* Definitions for sidebar links*/
define('CALENDAR', HOME . 'views/appointments/calendar.php');
define('CALENDAR_IMAGE_URL', BASE . 'assets/icons/calendar.svg');
define('DENTIST_IMAGE_URL', BASE . 'assets/icons/doctor.svg');
define('ADD_DENTIST', HOME . 'views/dentists/add-dentist.php');

define('LIST_DENTIST', HOME . 'views/dentists/dentist-list.php');
define('PATIENT_IMAGE_URL', BASE . 'assets/icons/patient.svg');
define('ADD_PATIENT', HOME . 'views/patients/add-patient.php');
define('LIST_PATIENT', HOME . 'views/patients/patient-list.php');
define('LIST_PROGRESS_NOTE', HOME . 'views/patients/progress-note-list.php');
define('ADD_PROGRESS_NOTE', HOME . 'views/patients/add-progress-note.php');

define('APPOINTMENT_IMAGE_URL', BASE . 'assets/icons/appointment.svg');
define('ADD_APPOINTMENT', HOME . 'views/appointments/add-appointment.php');
define('LIST_APPOINTMENT', HOME . 'views/appointments/appointment-list.php');

/* Definitions for index page links*/
define('ECHO_DOT_IMAGE_URL', BASE . 'assets/images/echo_dot.png');

/* set controller and model paths */
define('USERS_CONTROLLER', HOME . 'controllers/usersController.php');
define('USERS_MODEL', DOC_ROOT . '/models/usersModel.php');

define('DENTISTS_CONTROLLER', HOME . 'controllers/dentistsController.php');
define('DENTISTS_MODEL', DOC_ROOT . '/models/dentistsModel.php');

define('PATIENTS_CONTROLLER', HOME . 'controllers/patientsController.php');
define('PATIENTS_MODEL', DOC_ROOT . '/models/patientsModel.php');

define('APPOINTMENTS_CONTROLLER', HOME . 'controllers/appointmentsController.php');
define('APPOINTMENTS_MODEL', DOC_ROOT . '/models/appointmentsModel.php');

define('CALENDAR_CONTROLLER', HOME . 'controllers/calendarController.php');
define('CALENDAR_MODEL', DOC_ROOT . '/models/appointmentsModel.php');

define('MONTHS', array('JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'));
