<?php

class Index extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        header("location:".URL."dashboard");
    }

    public function search($param=null) {

        
        // print_r($param); die;
    	/*if( !empty($param[0]) ){
    		if( in_array($param[0], array('property', 'search')) ){
               
    			$this->_callMethodProperty($param);
    			exit;
    		}
    	}*/

        $this->error();
    }
}
