<?php

class System extends Controller {

    function __construct() {
        parent::__construct();


        $this->view->setPage('me', true);
    }

    function index() {

    	$this->error();
    }

    public function set()
    {	
    	if( empty($_POST) ) $this->error();

    	$post = $_POST;

    	foreach ($post as $key => $value) {
    		$this->model->set( $key, $value);
    	}

    	$arr['message'] = 'Success !';
    	$arr['url'] = 'refresh';

    	echo json_encode($arr);
    }

}