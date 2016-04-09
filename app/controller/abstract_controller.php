<?php

/**
 * The controller class.
 *
 * The base controller for all other controllers. Extend this for each
 * created controller and get access to it's wonderful functionality.
 */
abstract class abstract_controller {
	
	/**
	 * Constuctor of the class "controller".
	 */
	public function __construct() {
	}
	
	/**
	 * Default method that gets called if no method gets called over the URL.
	 */
	abstract public function index();
	
	/**
	 * The method checks if a specific user is allowed to use the controller.
	 * 
	 * @param int userType
	 * 
	 * @return true: user is allowed to use the controller
	 * 			false: user is not allowed to use the controller
	 */
	abstract public function authenticate($userType);
	
	/**
	 * Render a view
	 *
	 * @param string $screen
	 *        	name of the screen
	 */
	protected function loadView($screen) {
		require_once 'app/view/screens/' . $screen . '.php';
	}
}
