<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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


//Weather
$route['get-weather']                                                   = "secure_server_trigger_controller/get_weather";
$route['get-forecast']                                                  = "secure_server_trigger_controller/get_forecast";

//Cerberus Default Routing
$route['(:any)']                                                        = "default_controller";
$route['default_controller']                                            = "default_controller";
$route['404_override']                                                  = "default_controller";

/* End of file routes.php */
/* Location: ./application/config/routes.php */


/*
	Create new market/event procedure
*/

// standard market table entry
// Create areas/locations /test/function/clone-market-locations
// Create market layout - mapping page
// Create market days - should use old day creater
// Create email templates /test/function/clone-email-templates

// cloud firewall
// port blocking
// ufw - mirror port blocking