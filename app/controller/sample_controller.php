<?php
class sample_controller extends abstract_controller {
	
	public function __construct(array $request_params) {
		// call super constructor
		parent::__construct ($request_params);
	}
	
	
	public function index() {
		echo "sample controller:   ";
	}
	
	public function authenticate($userType) {
		if ($userType == abstract_data_object::ADMIN_USERTYPE) {
			return ture;
		} else if ($userType == abstract_data_object::USER_USERTYPE) {
			return ture;
		} else if ($userType == abstract_data_object::UNKOWN_USERTYPE) {
			return true;
		}
	}
	
	public function sampleFunction() {
		echo "sample controller:   ";
		
		$this->loadView("sample_view");
	}
}