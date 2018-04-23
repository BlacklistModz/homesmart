<?php

class Settings extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->my();
    }

    public function my($tap=null){
    	$this->view->setPage("title", "Setting ".ucfirst($tap));

        $this->view->setPage('on', 'settings' );
        $this->view->setData('section', 'my');
        $this->view->setData('tap', 'display');
        $this->view->setData('_tap', $tap);

        if( $tap=='basic' ){
            $this->view
            ->js(  VIEW .'Themes/'.$this->view->getPage('theme').'/assets/js/bootstrap-colorpicker.min.js', true)
            ->css( VIEW .'Themes/'.$this->view->getPage('theme').'/assets/css/bootstrap-colorpicker.min.css', true);

            $this->view->setData('prefixName', $this->model->load('system')->prefixName());
        }

        $this->view->render("settings/display");
    }
}