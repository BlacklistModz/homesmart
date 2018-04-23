<?php

class Controller {

    public $format = "html";
    public $pathName = "";
    function __construct() {
        $this->fn = new _function();
        $this->format = $this->get_format_json() ? "json":"html";
        $this->lang = new Langs();

        // View
        $this->view = new View();
        $this->view->format = $this->format;

        $this->view->setPage('locale', $this->lang->getCode() );
    }
 
    private function get_format_json() {
        $_q = false;
        if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) ){
            if( strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ){
                $_q = true;
            }
        }

        return $_q;
    }
    
    public function verifyWWW() {

        if (strpos($_SERVER['SERVER_NAME'],'www') !== false) {
            header("Location:". URL. ltrim($_SERVER['REQUEST_URI'], '/'));
        }
    }

    /**
     * 
     * @param string $name Name of the model
     * @param string $path Location of the models
     */
    public function loadModel($name, $modelPath = 'models/') {

        $path = $modelPath . $name.'_model.php';
        $this->pathName = $name;
        
        if (file_exists($path)) {
            require $modelPath .$name.'_model.php';
            
            $modelName = $name . '_Model';
            $this->model = new $modelName();
        }
        else{
            $this->model = new Model();
        }
        
        $this->system = $this->model->load('system')->get();
        if( !empty($this->system) ){
            $this->setSystem();
            $this->view->setData('system', $this->system);
        }

        $this->handleLogin();
        $this->_modify();
    }
    public function setPagePermit($value='') {

        $permit = $this->model->load('system')->permit( !empty($this->me['access']) ? $this->me['access']:array() );

        if( !empty($this->me['permission']) ){

            foreach ($permit as $key => $value) {
                
                if( !empty($this->me['permission'][ $key ]) ){
                    $permit[$key] = array_merge($value, $this->me['permission'][ $key ]);
                }
            }

        }

        // print_r($permit); die;
        
        $this->permit = $permit;
        $this->view->setData('permit', $this->permit);
        // print_r($this->permit); die;
    }

    public function setSystem(){

        $url = isset($_GET['url']) ?$_GET['url']:'';

        $title = '';
        if( !empty($url) ){
            $url = trim($url, '/');
            $url = str_replace('-', ' ', $url);
            $title = str_replace('/', ' - ', $url);
        }

        if( empty($title) && !empty($this->system['title']) ){
            $title = $this->system['title'];
        }

        $this->view->setPage('title', $title);

        $on = str_replace(' ', '-', $url);
        if( empty($on) ) $on = 'index';
        $this->view->setPage('on', $on );
        

        $this->system['url'] = URL.$on;
        $keys = array('site_name', 'type', 'url', 'image', 'keywords', 'color', 'facebook_app_id');
        foreach ($keys as $key) {
            if( !empty($this->system[$key]) ){

                if( $key=='site_name' ){
                    $this->view->setPage('site', $this->system[$key] );
                }

                $this->view->setPage($key, $this->system[$key] );
            }
        }

        if( !empty($this->system['blurb']) || !empty($this->system['description']) ){

            $description  ='';
            if( !empty($this->system['blurb']) ){
                $description = $this->system['blurb'];
            }
            else{
                $description = $this->fn->q('text')->more($this->system['description']);
            }

            $this->view->setPage('description',  $description );
        }  
	
		if( empty($this->system['image']) ){
            $this->view->setPage( 'image', IMAGES.'logo/48x48.png' );
			$this->view->setPage( 'image-128', IMAGES.'logo/128x128.png' );
		}

        if( !empty($this->system['theme']) ){
            $this->view->setPage('theme',  $this->system['theme'] );
        }

    }

    protected function _getError($err) {
        $err = explode(',', rtrim($err, ','));

        $error = array();
        foreach ($err as $k) {
            $str = explode('=>', $k);
            $error[$str[0]] = $str[1];
        }

        return $error;        
    }

    public $me = null;
    public function handleLogin(){

        if ( Cookie::get( COOKIE_KEY_ADMIN ) ) {
            $me = $this->model->load('users')->get( Cookie::get( COOKIE_KEY_ADMIN ), array('display' =>'enabled') );
        }

        if( !empty($me) ){
            $this->me =  $me;

            if( !empty($this->me['lang']) ){

                Session::init();
                Session::set('lang', $this->me['lang']);
                
                $this->lang->set( $this->me['lang'] );
            }

            $this->model->me = $this->me;
            $this->view->me = $this->me;
            $this->view->setData('me', $this->me);

            Cookie::set( COOKIE_KEY_ADMIN, $this->me['id'], time() + (3600*24));

            // 
            $this->setPagePermit();
        }else{
            $this->login();
        }
    }
    public function login() {

        Session::init();
        $attempt = Session::get('login_attempt');
        if( isset($attempt) && $attempt>=2 ){
            $this->view->setData('captcha', true);
            $this->view->js('https://www.google.com/recaptcha/api.js', true);
        }
        elseif( empty($attempt) ){
            $attempt = 0;
            Session::set('login_attempt', $attempt);
        }

        $login_mode = isset($_REQUEST['login_mode']) ? $_REQUEST['login_mode']: 'default';

        if( empty($_REQUEST['g-recaptcha-response']) && $attempt>2 && $login_mode=='default' ){
            $error['captcha'] = 'คุณป้อนรหัสไม่ถูกต้อง?';
        }

        if( !empty($_POST) && empty($error) ){
            
            if( $login_mode=='pin' && $this->format=='json' ){

                $ip = $this->fn->q('util')->get_client_ip();
                $pin = isset($_POST['pin']) ? $_POST['pin']:'';

                $id = $this->model->load('users')->loginPIN($ip, $pin);
                if( !empty($id) ){

                    Cookie::set( COOKIE_KEY_ADMIN, $id, time() + (3600*24));
                    sleep(2);

                    if( isset($attempt) ){
                       Session::clear('login_attempt');
                    }

                    $arr['url'] = !empty($_REQUEST['next'])
                        ? $_REQUEST['next']
                        : $_SERVER['REQUEST_URI'];
                }
                else{
                    $arr['error'] = 1;
                }

                /*$arr['ip'] = $ip;
                $arr['post'] = $_POST;*/
                echo json_encode($arr);
                exit;
            }
            else{
                try {
                    $form = new Form();

                    $form   ->post('email')->val('is_empty')
                            ->post('pass')->val('is_empty');

                    $form->submit();
                    $post = $form->fetch();

                    $id = $this->model->load('users')->login($post['email'], $post['pass']);

                    if( !empty($id) ){

                        Cookie::set( COOKIE_KEY_ADMIN, $id, time() + (3600*24));
                        Session::set('isPushedLeft', 1);

                        if( isset($attempt) ){
                           Session::clear('login_attempt');
                        }

                        $url = !empty($_REQUEST['next'])
                            ? $_REQUEST['next']
                            : $_SERVER['REQUEST_URI'];

                        header('Location: '.$url);
                    }
                    else{

                        if(!$this->model->load('users')->is_user($post['email'])){
                            $error['email'] = 'ชื่อผู้ใช้ไม่ถูกต้อง'; 
                        }
                        else{
                            $error['pass'] = 'รหัสผ่านไม่ถูกต้อง';
                        }
                    }

                    $post['pass'] = "";
                    $this->view->setData('post', $post);
                } catch (Exception $e) {
                    $error = $this->_getError( $e->getMessage() );
                }

            }
        }

        if(!empty($error)){

            if( isset($attempt) ){
                $attempt++;
                Session::set('login_attempt', $attempt);
            }

            $this->view->setData('error', $error);
        }

        $redirect = URL;
        $next = isset($_REQUEST['next']) ? $_REQUEST['next']: '';

        if( empty($next) && !empty($_SERVER['REQUEST_URI']) ){
            $next = $_SERVER['REQUEST_URI'];
        }

        if( !in_array($this->view->getPage('theme'), array('manage')) ){
            $redirect = URL;
        }
        
        if( !empty( $next) ){
            $this->view->setData('next', $next);
        }

        $this->view->setPage('title',  $this->system['title'] );
        $this->view->setData('redirect', $redirect);
        $this->view->setPage('name', $this->lang->getCode()=='th'?'เข้าสู่ระบบ': 'Login');
        $this->view->setPage('theme', 'login');
        $this->view->setPage('theme_options', array(
            'has_topbar' => false,
            'has_footer' => false,
        ));

        if( isset($_REQUEST['login_mode']) ){
            Session::set('login_mode', $_REQUEST['login_mode']);
        }

        $mode = Session::get('login_mode');
        if( empty($mode) ) $mode = 'default';
        $this->view->render( $mode );
        exit;
    }
    public function error(){

        if( empty($this->model) ){
            $this->loadModel('error');
        }

        $this->view->setPage('title', $this->lang->getCode()=='th'?'ไม่พบเพจ': 'Page not found');
        $this->view->elem('body')->addClass('page-errors');
        $this->view->render( 'error' );
        exit;
    }

    public function _modify() {
        
        $options = array();

        if( empty($this->system['theme']) ){
            $options['has_topbar'] = false;
            $options['has_footer'] = false;
            $options['has_menu'] = true;
            $this->system['theme'] = 'manage';
        }

        $this->view->setPage('theme', $this->system['theme']);
        $this->view->setPage('theme_options', $options);  
    }

    public function _getUrl() {
        $url = isset( $_GET['url'] ) ? $_GET['url']:null;
        if( empty($url) ) $this->error();
        $url = rtrim($url, '/');
        $this->_url = explode('/', $url);
    }
}
