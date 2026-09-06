<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

// $route['default_controller'] = 'auth'; // atau 'dashboard', tergantung tujuan
// $route['default_controller'] = 'welcome';
// $route['login'] = 'auth';
// $route['logout'] = 'auth/logout'; // optional nanti
// $route['404_override'] = '';
// $route['translate_uri_dashes'] = FALSE;


$route['default_controller'] = 'Auth';
$route['404_override'] = '';
// $route['owner/'] = 'owner/home';
// $route['member/(:any)'] = 'member/home';

$route['translate_uri_dashes'] = FALSE;

$route['health'] = 'api/presensi/health';
$route['api/v1/connection'] = 'api/presensi/connection';
$route['api/v1/presensi/upload'] = 'api/presensi/upload';
$route['api/v1/presensi'] = 'api/presensi/index';
$route['api/v1/presensi/(:num)'] = 'api/presensi/show/$1';
$route['api/v1/employees'] = 'api/employees/index';
$route['api/v1/employees/mapping'] = 'api/employees/mapping';
$route['admin/employees'] = 'admin/employees/index';
$route['admin/employees/save'] = 'admin/employees/save';
$route['admin/employees/detail/(:num)'] = 'admin/employees/detail/$1';
$route['admin/employees/salary-details/(:num)'] = 'admin/employees/salary_details/$1';
$route['admin/employees/salary-details/(:num)/save'] = 'admin/employees/save_salary_details/$1';
$route['admin/payroll-components'] = 'admin/payroll_components/index';
$route['admin/payroll-components/save'] = 'admin/payroll_components/save';
$route['admin/payroll-components/toggle-status/(:num)'] = 'admin/payroll_components/toggle_status/$1';
$route['admin/payroll_components'] = 'admin/payroll_components/index';
$route['admin/payroll-details'] = 'admin/payroll_details/index';
$route['admin/payroll-details/(:num)'] = 'admin/payroll_details/edit/$1';
$route['admin/payroll-details/(:num)/save'] = 'admin/payroll_details/save/$1';
$route['admin/tokens'] = 'admin/tokens/index';
$route['admin/tokens/generate'] = 'admin/tokens/generate';
$route['admin/tokens/revoke/(:num)'] = 'admin/tokens/revoke/$1';
$route['admin/presensi'] = 'admin/presensi_log/index';
$route['admin/attendance'] = 'admin/attendance/index';
$route['admin/attendance/process'] = 'admin/attendance/process';
