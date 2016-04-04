<?php
/**
 * The index.php file handles the request for each call to the application
 * and calls the chosen controller and method after splitting the URL.
 *
 */

// make the session variables avilable
session_set_cookie_params(60*60*24*14,"/");
session_start();

///////////////////////Define Error Handling///////////////////////

function errorPage() {
	header("Location: error.html");
}
function errorMessage($errorcode, $errortext, $errorfile, $errorline) {
	echo "{$errortext}; {$errorfile} -> {$errorline} <br/>";
}

// either "error" or "errorPage" according to the function that should get called
set_error_handler("errorMessage");


///////////////////////load  all nessesary files///////////////////////

require_once 'app/controller/abstract_controller.php';
require_once 'app/model/abstract_data_object.php';
require_once 'app/model/sample_data_object.php';
require_once 'app/model/user_data_object.php';



//////////////////Split URL and call the right controller//////////////////

//default controller
define("DEFAULT_CONTROLLER", "sample_controller");

// set default values
/** Stores the controller from the split URL */
$controller = DEFAULT_CONTROLLER;
/** Stores the method from the split URL */
$function = 'index';
/** Stores the parameters from the split URL */
$params = [];

// parse url
if (isset ($_GET['url'])) {
	// Explode a trimmed and sanitized URL by /
	$url = explode ( '/', filter_var ( rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
}

// set controller
if (isset ($url[0])) {
	if (file_exists('app/controller/' . $url[0] . '.php')) {
		// if a valid controller is given use it as the controller
		$controller = $url[0];
		unset($url[0]);
	} else {
		// if $url[] is not a valid controller name for the selected controller,
		// or the index-function gets called, redirect the client so that the URL
		// gets shown accuratly
		header("Location: /php%20workspace/MVC%20PHP/". DEFAULT_CONTROLLER);
	}
}
// load the wanted controller class
require_once 'app/controller/' . $controller . '.php';
//creates on object of the controller
$controller = new $controller(array_merge($_GET, $_POST));

// set function
if (isset($url[1])) {
	if(method_exists($controller, $url[1]) && $url[1] != "index") {
		$function = $url[1];
		unset($url[1]);
	} else {
		// if $url[1] is not a valid function name for the selected controller,
		// or the index-function gets called, redirect the client so that the URL
		// gets shown accuratly
		header("Location: /php%20workspace/MVC%20PHP/". DEFAULT_CONTROLLER);
	}
}

// set parameters
if (isset($url)) {
	$params = array_values($url);
}

////////////////////////Authentication////////////////////////
// find out the usertype
$usertype = abstract_data_object::UNKOWN_USERTYPE;

if (isset($_SESSION["username"])) {
	$user = new user_data_object($_SESSION["username"]);
	$usertype = $user->getUserType();
}

// check, if the user is allowed to access the wanted controller
if ($controller->authenticate($usertype)) {
	// if user is allowed run the controller function
	call_user_func_array([$controller, $function], $params);
} else {
	// if user is not allowed to call the controller then link him to the default page
	header("Location: /php%20workspace/MVC%20PHP/". DEFAULT_CONTROLLER);
}
?>