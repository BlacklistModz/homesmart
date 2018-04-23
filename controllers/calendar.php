<?php

class Calendar extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$this->view->render("calendar/display");
	}

	public function events(){
		
	}
}