<?php

class Users extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$this->error();
	}

	#Users
	public function add(){

		if( empty($this->me) || $this->format!="json" ) $this->error();

		$this->view->setData("prefixName", $this->model->load("system")->prefixName());
		$this->view->setData("roles", $this->model->roles());
		$this->view->setPage("path", "Forms/users");
		$this->view->render("add");
	}
	public function edit($id=null){

		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
		if( empty($id) || empty($this->me) || $this->format!="json" ) $this->error();

		$item = $this->model->get($id);
		if( empty($item) ) $this->error();

		$this->view->setData("item", $item);
		$this->view->setData("prefixName", $this->model->load("system")->prefixName());
		$this->view->setData("roles", $this->model->roles());
		$this->view->setPage("path", "Forms/users");
		$this->view->render("add");
	}
	public function save(){
		if( empty($_POST) ) $this->error();

        $id = isset($_POST['id']) ? $_POST['id']: null;
        if( !empty($id) ){
            $item = $this->model->get($id);
            if( empty($item) ) $this->error();
        }

        try {
        	$form = new Form();
            $form   ->post('user_role_id')->val('is_empty')
                    ->post('user_login')->val('is_empty')
                    ->post('user_prefix_name')
                    ->post('user_first_name')->val('is_empty')
                    ->post('user_last_name')
                    ->post('user_nickname')
                    ->post('user_email');

            $form->submit();
            $postData = $form->fetch();

            if( empty($item) ){
                $postData['user_pass'] = $_POST['user_pass'];
                if( empty($postData['user_pass']) ){
                    $arr['error']['user_pass'] = 'กรุณากรอกรหัสผ่าน';
                }else if( strlen($postData['user_pass']) < 6 ){
                    $arr['error']['user_pass'] = 'รหัสผ่านของคุณมีจำนวนต่ำกว่า 6 ตัวอักษร';
                }
            }

            $has_user = true;
            $has_name = true;

            if( !empty($item) ){
            	if( $item["login"] == $postData["user_login"] ) {
            		$has_user = false;
            	}

            	if( $item['first_name'] == $postData["user_first_name"] && $item['last_name'] == $postData['user_last_name'] ){
            		$has_name = false;
            	}
            }

            if( $this->model->is_user($postData["user_login"]) && $has_user ){
            	$arr["error"]["user_login"] = "มี Username นี้อยู่ในระบบ";
            }

            if( $this->model->is_name($postData["user_first_name"], $postData["user_last_name"]) && $has_name ){
            	$arr["error"]["name"] = "มีชื่อและนามสกุลนี้อยู่ในระบบ";
            }

            if( empty($arr['error']) ){
            	if( !empty($item) ){
                    $this->model->update( $id, $postData );
                }
                else{

                	$postData["user_display"] = 'enabled';

                	$this->model->insert( $postData );
                    $id = $postData['id'];

                    if( !empty($id) ){
                        $stu['stu_user_id'] = $id;
                        $this->model->load('student')->insert( $stu );
                    }
                }

                $arr["message"] = "บันทึกเรียบร้อย";
                $arr["url"] = "refresh";
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());

            if( !empty($arr['error']['user_first_name']) ){
                $arr['error']['name'] = $arr['error']['user_first_name'];
            } else if( !empty($arr['error']['user_last_name']) ){
                $arr['error']['name'] = $arr['error']['user_last_name'];
            }
        }

        echo json_encode($arr);
	}
	public function del($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
		if( empty($this->me) || empty($id) || $this->format!="json" ) $this->error();

		$item = $this->model->get($id);
		if( empty($item) ) $this->error();

		if( !empty($_POST) ){
			if( !empty($item["permit"]["del"]) ){

                if( !empty($item['image_id']) ) $this->model->load('media')->del($item['image_id']);

                if( $item['role_id'] == 2 ) $this->model->load('student')->delByUser( $id );
                if( $item['role_id'] == 4 ) $this->model->load('corporation')->delByUser( $id );

				$this->model->delete( $id );
				$arr["message"] = "ลบข้อมูลเรียบร้อย";
				$arr["url"] = "refresh";
			}
			else{
				$arr["message"] = "ไม่สามารถลบข้อมูลได้";
			}

			echo json_encode($arr);
		}
		else{
			$this->view->setData("item", $item);
			$this->view->setPage("path", "Forms/users");
			$this->view->render("del");
		}
	}
	public function password($id='')  {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            try {
                $form = new Form();
                $form   ->post('password_new')->val('password')
                        ->post('password_confirm')->val('password');

                $form->submit();
                $dataPost = $form->fetch();

                if( $dataPost['password_new']!=$dataPost['password_confirm'] ){
                    $arr['error']['password_confirm'] = 'รหัสผ่านไม่ตรงกัน';
                }

                if( empty($arr['error']) ){

                    // update
                    $this->model->update($item['id'], array(
                        'user_pass' => Hash::create('sha256', $dataPost['password_new'], HASH_PASSWORD_KEY )
                    ));

                    $arr['message'] = "แก้ไขข้อมูลเรียบร้อย";
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item );
            
            $this->view->setPage('path','Forms/users');
            $this->view->render("change_password");
        }
    }
	public function display($id=null, $status=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
		$status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : $status;

		if( empty($id) || empty($this->me) ) $this->error();

		$item = $this->model->get($id);
		if( empty($item) ) $this->error();

		if( !empty($_POST) ){
			$this->model->update($id, array("user_display"=>$status));

			$arr["message"] = !empty($status) ? "เปิดการใช้งาน" : "ปิดการใช้งาน";
			$arr["url"] = "refresh";
			echo json_encode($arr);
		}
		else{
			$this->view->setData("item", $item);
			$this->view->setData("status", $status);
			$this->view->setPage("path", "Forms/users");
			$this->view->render("change_display");
		}
	}

	#Roles
	public function add_roles(){
		if( empty($this->me) || $this->format!="json" ) $this->error();

		$this->view->setPage("path", "Forms/roles");
		$this->view->render("add");
	}
	public function edit_roles( $id=null ){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
		if( empty($id) || empty($this->me) || $this->format!="json" ) $this->error();

		$item = $this->model->getRoles( $id );
		if( empty($item) ) $this->error();

		$this->view->setData("item", $item);
		$this->view->setPage("path", "Forms/roles");
		$this->view->render("add");
	}
	public function save_roles(){
		if( empty($_POST) ) $this->error();

		$id = isset($_POST['id']) ? $_POST['id']: null;
        if( !empty($id) ){
            $item = $this->model->get($id);
            if( empty($item) ) $this->error();
        }

        try{
        	$form = new Form();
            $form   ->post('role_name')->val('is_empty');

            $form->submit();
            $postData = $form->fetch();

            $has_name = true;
            if( !empty($item) ){
                if( $item["name"] == $postData["role_name"] ) $has_name = false;
            }

            if( $this->model->is_roleName($postData["role_name"]) && $has_name ) {
                $arr["error"]["role_name"] = "มีชื่อนี้อยู่ในระบบ";
            }

             if( $_POST["role_is"] == "admin" ){
                $postData["role_is_admin"] = 1;
            }

            if( $_POST["role_is"] == "manage" ){
                $postData["role_is_manage"] = 1;
            } 

            if( empty($arr["error"]) ){
            	if( !empty($item) ){
            		$this->model->updateRoles($id, $postData);
            	}
            	else{
            		$this->model->insertRoles($postData);
            	}

            	$arr["message"] = "บันทึกเรียบร้อย";
            	$arr["url"] = "refresh";
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
	}
	public function del_roles( $id=null ){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
		if( empty($this->me) || empty($id) || $this->format!="json" ) $this->error();

		$item = $this->model->getRoles( $id );
		if( empty($item) ) $this->error();

		if( !empty($_POST) ){
			if( !empty($item["permit"]["del"]) ){
				$this->model->delRoles( $id );
				$arr["message"] = "ลบข้อมูลเรียบร้อย";
				$arr["url"] = "refresh";
			}
			else{
				$arr["message"] = "ไม่สามารถลบข้อมูลได้";
			}

			echo json_encode($arr);
		}
		else{
			$this->view->setData("item", $item);
			$this->view->setPage("path", "Forms/roles");
			$this->view->render("del");
		}
	}

    #Data
    public function setdata($id=null, $field=null){
        if( empty($id) || empty($field) || empty($this->me) ) $this->error();

        $item = $this->model->get( $id );

        if( empty($item) ) $this->error();

        if( isset($_REQUEST['has_image_remove']) && !empty($item['image_id']) ){
            $this->model->load('media')->del( $item['image_id'] );
        }

        $data[$field] = isset($_REQUEST['value'])? $_REQUEST['value']:'';
        $this->model->update($id, $data);

        $arr['message'] = 'บันทึกเรียบร้อย';
        echo json_encode($arr);
    }
}