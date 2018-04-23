<?php

class Customers extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($id=null){
    	$this->view->setPage('on', 'customers');

    	if( !empty($id) ){
    		$render = "customers/profile/display";
    	}
    	else{
    		if( $this->format=='json' ){
    			$render = "customers/lists/json";
    		}
    		else{
    			$this->view->setData('status', $this->model->load("users")->status());
    			$render = "customers/lists/display";
    		}
    	}

    	$this->view->render( $render );
    }
}