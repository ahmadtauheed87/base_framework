<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'Frontend/User';
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
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['home'] = 'frontend/user/login';
$route['login'] = 'frontend/user/login';
$route['logout'] = 'frontend/user/logout';
$route['register'] = 'frontend/user/register';

$route['proposal'] = 'frontend/proposal/proposal_received';
$route['proposal/(:num)'] = 'frontend/proposal/proposal_received/$1';

$route['proposal/proposal_received'] = 'frontend/proposal/proposal_received';
$route['proposal/proposal_received/(:num)'] = 'frontend/proposal/proposal_received/$1';

$route['proposal/proposal_sent'] = 'frontend/proposal/proposal_sent';
$route['proposal/proposal_sent/(:num)'] = 'frontend/proposal/proposal_sent/$1';

$route['proposal/proposal_accepted_received'] = 'frontend/proposal/proposal_accepted_received';
$route['proposal/proposal_accepted_received/(:num)'] = 'frontend/proposal/proposal_accepted_received/$1';
$route['proposal/proposal_accepted_sent'] = 'frontend/proposal/proposal_accepted_sent';
$route['proposal/proposal_accepted_sent/(:num)'] = 'frontend/proposal/proposal_accepted_sent/$1';



$route['proposal/proposal_needed_time_received'] = 'frontend/proposal/proposal_needed_time_received';
$route['proposal/proposal_needed_time_received/(:num)'] = 'frontend/proposal/proposal_needed_time_received/$1';
$route['proposal/proposal_needed_time_sent'] = 'frontend/proposal/proposal_needed_time_sent';
$route['proposal/proposal_needed_time_sent/(:num)'] = 'frontend/proposal/proposal_needed_time_sent/$1';



$route['proposal/proposal_declined_received'] = 'frontend/proposal/proposal_declined_received';
$route['proposal/proposal_declined_received/(:num)'] = 'frontend/proposal/proposal_declined_received/$1';
$route['proposal/proposal_declined_sent'] = 'frontend/proposal/proposal_declined_sent';
$route['proposal/proposal_declined_sent/(:num)'] = 'frontend/proposal/proposal_declined_sent/$1';



$route['about'] = 'frontend/about';
$route['contact'] = 'frontend/contact';
$route['search'] = 'frontend/user/search';
$route['forgot_password'] = 'frontend/user/forgot_password';

$route['bride/view_profile/(:num)'] = 'frontend/bride/view_profile/$1';
$route['bride/family_details/(:num)'] = 'frontend/bride/family_details/$1';
$route['bride/partner_preference/(:num)'] = 'frontend/bride/partner_preference/$1';
$route['bride/social_media/(:num)'] = 'frontend/bride/social_media/$1';

$route['groom/view_profile/(:num)'] = 'frontend/groom/view_profile/$1';
$route['groom/family_details/(:num)'] = 'frontend/groom/family_details/$1';
$route['groom/partner_preference/(:num)'] = 'frontend/groom/partner_preference/$1';
$route['groom/social_media/(:num)'] = 'frontend/groom/social_media/$1';

$route['bride/edit_profile/(:num)'] = 'frontend/bride/edit_profile/$1';
$route['groom/edit_profile/(:num)'] = 'frontend/groom/edit_profile/$1';

$route['groom/delete_profile_view/(:num)'] = 'frontend/groom/delete_profile_view/$1';
$route['bride/delete_profile_view/(:num)'] = 'frontend/bride/delete_profile_view/$1';

$route['user'] = 'frontend/user';
$route['privacy'] = 'frontend/privacy';
$route['terms'] = 'frontend/terms';

$route['new_profile'] = 'frontend/user/new_profile';
$route['new_profile/(:num)'] = 'frontend/user/new_profile/$1';
$route['new_profile/(:num)/(:num)'] = 'frontend/user/new_profile/$1/$1';

$route['active_profile'] = 'frontend/user/active_profile';
$route['active_profile/(:num)'] = 'frontend/user/active_profile/$1';
$route['active_profile/(:num)/(:num)'] = 'frontend/user/active_profile/$1/$1';

$route['preferred_matches'] = 'frontend/user/preferred_matches';

$route['marital_status/(:any)/(:any)'] = 'frontend/filter/marital_status/$1/$1';
$route['marital_status/(:any)'] = 'frontend/filter/marital_status/$1/1';

$route['age_wise/(:any)/(:any)'] = 'frontend/filter/age_wise/$1/$1';
$route['age_wise/(:any)'] = 'frontend/filter/age_wise/$1/1';

$route['height_wise/(:any)'] = 'frontend/filter/height_wise/$1/1';
$route['height_wise/(:any)/(:any)'] = 'frontend/filter/height_wise/$1/$1';

$route['qualification_wise'] = 'frontend/filter/qualification_wise';
$route['qualification_wise/(:any)'] = 'frontend/filter/qualification_wise/$1';
$route['qualification_wise/(:any)/(:any)'] = 'frontend/filter/qualification_wise/$1/$1';

