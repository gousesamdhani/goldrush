<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


define('PROFILE_PIC_PATH','/assets/uploadedfiles/profile_pictures/');


/*FITBIT settings START*/
define('CLIENT_KEY','6da51f8aebb6490a8b88d98da651b17f');
define('CLIENT_SECRET','90d5d5d5c74f80297e71d9c315b5d799');
define('FITBIT_REDIRECT_URI','http://'.$_SERVER['HTTP_HOST'].'/fitbit/fitbitaccess');
define('FROM_EMAIL','admin@sureify.com');
define('SERVICE_EMAIL', 'satyaraj@vendus.com');
//define('CLIENT_KEY','9e87b2b0c3fc0fe465cdfe06871cdd98');
//define('CLIENT_SECRET','9091fbb9164f11a2bbf62edecc34c26b');
define('BAD_REQUEST', 400);
define('SUCCESS_OK', 200);
define('PAGE_NOT_FOUND', 404);
define('INTERNAL_SERVER_ERROR', 500);
define('CONTENT_NOT_FOUND', 204);



//DB TABLES START
define('USER_STEPS','user_steps');
define('USER_CALORIES','user_calories');
define('USER_WEIGHTS','user_weights');
//DB TABLES END



//stripe constants START
define('STRIPE_SECRET_KEY','sk_test_W0ccvrOo9HBx7euXeTFy7asf');
define('STRIPE_PUBLISHABLE_KEY','pk_test_cSyZslQRUmwOXDl6f8Q1qkTu');
//stripe constants END

/* End of file constants.php */
/* Location: ./application/config/constants.php */