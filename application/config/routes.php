<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = '';

/** Admin Routing **/
$route['admin'] = "admin/authorization/login";
$route['admin/index'] = "admin/authorization/login";
$route['admin/login'] = "admin/authorization/login";
$route['admin/logout'] = "admin/authorization/logout";
$route['admin/users'] = "admin/dashboard/users";
$route['admin/devices'] = "admin/dashboard/devices";

/** Carrier Routing **/
$route['carrier'] = "carrier/authorization/login";
$route['carrier/index'] = "carrier/authorization/login";
$route['carrier/login'] = "carrier/authorization/login";
$route['carrier/logout'] = "carrier/authorization/logout";

/** Rest API's routing **/
$route['users'] = "api/v1/users";
$route['users/config'] = "api/v1/users/config";
$route['users/challenges'] = "api/v1/users/challenges";
$route['users/edit'] = "api/v1/users/edit";
$route['users/steps'] = "api/v1/users/steps";
$route['users/weights'] = "api/v1/users/weights";
$route['users/livedata'] = "api/v1/users/livedata";
$route['users/payments'] = "api/v1/users/payments";
$route['users/policies'] = "api/v1/users/policies";
$route['users/discounts'] = "api/v1/users/discounts";
$route['users/logout'] = "api/v1/users/logout";
$route['users/photo'] = "api/v1/users/photo";
$route['auth'] = "api/v1/auth";
$route['recovery'] = "api/v1/recovery";

$route['users/sync'] = "api/SyncDevices/sync";
//$route['users/sync/healthapp'] = "api/SyncDevices/sync/healthapp";
$route['users/lastsync'] = "api/SyncDevices/lastsync";


//for GOLD app
$route['auth'] = "api/v1/auth";
$route['registration'] = "api/v1/newAuth";
$route['transact'] = "api/v1/transact";
$route['usersessions'] = "api/v1/usersessions";
$route['sales'] = "api/v1/sales";
$route['stock'] = "api/v1/stock";

//for GOLD WEB
$route['home'] = "web/home";
$route['home/logout'] = "web/home/logout";
$route['user_login'] = "web/login";
$route['user_signup'] = "web/signup";
$route['profile'] = "web/profile";


$route['users/states'] = "api/v1/getStates";
$route['users/states/(:num)/districts'] = "api/v1/getDistricts/$1";
$route['users/states/(:num)/districts/(:num)/areas'] = "api/v1/getAreas/$1/$2";
$route['users/materials'] = "api/v1/getMaterials";
$route['users/orders'] = "api/v1/orders/";
//$route['users/orders/:any'] = "api/v1/orderDetails/$1";
$route['users/orders/cancel/(:any)'] = "api/v1/cancelOrder/$1";



$route['api/users/syncfitbit'] = "api/v1/users/syncfitbit";
$route['api/users/usercards'] = "api/v1/users/usercards";
$route['api/users/cards'] = "api/v1/users/cards";
$route['api/users/payment'] = "api/v1/users/payment";

$route['api/savecreditcard'] = "api/v1/savecreditcard";


//$route['auth/'] = "api/v1/auth_post/$1/$2";
$route['users/(:any)/(:num)/(:any)'] = "api/v1/users/$2/$3/$4";

/*$app->get('/user', '/v1/api/myuser'); // Using Get HTTP Method and process getUsers function 
$app->get('/weigh', '/v1/api/weights/id');

$app->run();*/


/* End of file routes.php */
/* Location: ./application/config/routes.php */
