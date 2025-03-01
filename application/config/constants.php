<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//database table
define('USERS', 'users');
define('USERSLIMIT', '25');
define('LIMIT', '10');

define('DATETIME', gmdate("Y-m-d H:i:s"));
define('DATE', gmdate("Y-m-d"));
define('DATE_FORMAT', "Y-m-d");
define('NOREPLY_EMAIL', "noreply@marketingtiki.com");
define('SUPPORT_EMAIL', "ballastcc@gmail.com");
define('CC_EMAIL', "ballastcc@gmail.com");
define('FROM_NAME', "BALLAST COACHING CLASSES");
define('CONTACT_US_EMAIL_SUBMISSION', "ballastcc@gmail.com");

define('ANALYTICS_INTERVAL', '15552000');
define('PENDING_INVITE_AUTO_DELETE', '172800');
define('RECENT_VIEW_AUTO_DELETE', '604800');
define('EXPIRED_VERIFICATION_CODE', '86400');

define("UPLOADPATH", realpath(APPPATH . '../uploads'));
define("ASSETS", realpath(APPPATH . '../assets'));

// CLAIM ORGANISTAION REMINDER FREQUENCY DAYS
define('REMINDER_FREQUENCY', 10);

// API access key from Google FCM App Console
// define('FCM_SERVER_KEY', 'AAAA1vhMliE:APA91bEL4b0Sok9ZvQ1QlgpqZc9U2fJsLbSB7GrZ7VM00MXC3Lk7ADeZjboP21KwSegTjyhvfJ7MmerG_YiJc4cnjBhgO8b1i3NDjEtgL96sGjmjZx-o1lBLfh3VL5To7UIVdf4iaJvf');
define('FCM_SERVER_KEY', 'AAAAi1Z5zng:APA91bHvTIFl2P2gKVJMEw5JfNx-SuqPvCs8A_CxCpc9V7J8Tl27bGxz6E834aHi9-xK83yfnfcEZF_4QA8emLcnkl8liVqP6ae4f1LPrI5OQpPLJ3SxDRjS3a8XqJJ0_6nCw8bWtPZs');
define('FCM_SENDER_ID', '');
define('IMAGE_SERVER', 'REMOTE');
//localhost_bcci
switch (ENVIRONMENT) {
case 'production':
	define('SITE_ROOT', 'https://api.ballastcc.in');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'balla699_bcci');
	define('DB_USERNAME', 'balla699_bcci');
	define('DB_PASSWORD', 'A+ZTZUQ@P8W;');
	define('SITE_ADDR', 'https://ballastcc.in/');
	// Stripe API configuration
	define('STRIPE_PUBLISHABLE_KEY', 'pk_test_DSSTxP5NaHXwInGOlRX2rYM300aoJSRuBB');
	define('STRIPE_SKEY', 'sk_test_yzdkYc6t9GzoDMEfTeNBcDuG00rVcpv242');
	define('STRIPE_PRODUCT_ID', 'prod_I2T0PUIwVRuHto');
	define('SMTP_HOST',  'smtp.gmail.com');
	define('SMTP_USER',  'ballastcc@gmail.com');
	define('SMTP_PASSWORD',  '8370010921');
	define('CALL_RAIL_TOKEN',  '57b29b5700be014e6a0975fb539a085f');
	define('CALL_RAIL_ACCOUNT_ID',  'ACCfec31210be444298ba7d66ecae991317');
	break;
case 'development':
	define('SITE_ROOT', 'api.ballastcc.com');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'ballast');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('SITE_ADDR', 'http://localhost:3000/');
	// Stripe API configuration
	define('STRIPE_PUBLISHABLE_KEY', 'pk_test_DSSTxP5NaHXwInGOlRX2rYM300aoJSRuBB');
	define('STRIPE_SKEY', 'sk_test_yzdkYc6t9GzoDMEfTeNBcDuG00rVcpv242');
	define('STRIPE_PRODUCT_ID', 'prod_I2T0PUIwVRuHto');
	define('SMTP_HOST',  'smtp.gmail.com');
	define('SMTP_USER',  'ballastcc@gmail.com');
	define('SMTP_PASSWORD',  '8370010921');
	define('CALL_RAIL_TOKEN',  '57b29b5700be014e6a0975fb539a085f');
	define('CALL_RAIL_ACCOUNT_ID',  'ACCfec31210be444298ba7d66ecae991317');
default:	
}
