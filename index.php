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
define("DEFAULT_DOMAIN", "/MVC%20PHP/");

// set default values
/** Stores the controller from the split URL */
$controller = DEFAULT_CONTROLLER;
/** Stores the method from the split URL */
$function = 'index';

// parse url
if (isset ($_GET['url'])) {
	// Explode a trimmed and sanitized URL by /
	$url = explode ( '/', filter_var ( rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
}

// set controller
if (isset ($url[0])) {
	// test if the wanted controller is given
	if (file_exists('app/controller/' . $url[0] . '.php')) {
		// if a valid controller is given use it as the controller
		$controller = $url[0];
	} else {
		// if $url[] is not a valid controller name for the selected controller
		// redirect the client to the home page
		header("Location: " . DEFAULT_DOMAIN. DEFAULT_CONTROLLER);
	}
}
// load the wanted controller class
require_once 'app/controller/' . $controller . '.php';

// check if the controller needs a parameter and if in the url a parameter is given
$contructorOfController = new ReflectionMethod($controller, '__construct');
$constructorHasParameter = sizeof($contructorOfController->getParameters()) == 1;
if ($constructorHasParameter && !isset($url[1])) {
	// if there are not enough parameter given redirect the client to the default page 
	header("Location: " . DEFAULT_DOMAIN. DEFAULT_CONTROLLER);
}

if ($constructorHasParameter) {
	//creates on object of the controller
	$controller = new $controller($url[1]);
} else {
	//creates on object of the controller
	$controller = new $controller();
}

// set function
if ($constructorHasParameter) {
	if (isset($url[2])) {
		if(method_exists($controller, $url[2])) {
			$function = $url[2];
		} else {
			// if $url[1] is not a valid function name for the selected controller,
			// redirect the client so that the URL gets shown accuratly
			header("Location: " . DEFAULT_DOMAIN. DEFAULT_CONTROLLER);
		}
	}
} else {
	if (isset($url[1])) {
		if(method_exists($controller, $url[1])) {
			$function = $url[1];
		} else {
			// if $url[1] is not a valid function name for the selected controller,
			// redirect the client so that the URL gets shown accuratly
			header("Location: " . DEFAULT_DOMAIN. DEFAULT_CONTROLLER);
		}
	}
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
	call_user_func([$controller, $function]);
} else {
	// if user is not allowed to call the controller then link him to the default page
	header("Location: " . DEFAULT_DOMAIN. DEFAULT_CONTROLLER);
}
?>