<?php

/**
 * The controller class.
 *
 * The base controller for all other controllers. Extend this for each
 * created controller and get access to it's wonderful functionality.
 */
abstract class abstract_controller {
	
	/**
	 * Consits of all GET and POST parameters.
	 * 
	 * @var array
	 */
	protected $request_params;
	
	/**
	 * Constuctor of the class "controller".
	 * 
	 * @param array $request_params
	 */
	public function __construct(array $request_params) {
		$this->request_params = $request_params;
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
