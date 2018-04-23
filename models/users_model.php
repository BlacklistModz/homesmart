<?php

class Users_Model extends Model{

    public function __construct() {
        parent::__construct();
    }

    private $_objType = "users";
    private $_table = "users u LEFT JOIN users_roles r ON u.user_role_id=r.role_id";
    private $_field = "
        u.*
        , role_id
        , role_name";
    private $_cutNamefield = "user_";

    public function is_user($text){
        $c = $this->db->count('users', "(user_login=:txt AND user_login!='') OR (user_email=:txt AND user_email!='')", array(':txt'=>$text));
        
        return $c;
    }
    public function is_name($text) {
        return $this->db->count($this->_objType, "name='{$text}'");
    }

    public function insert(&$data) {
        
        $data["{$this->_cutNamefield}created"] = date('c');
        $data["{$this->_cutNamefield}updated"] = date('c');

        if( isset($data["{$this->_cutNamefield}pass"]) ){
            $data["{$this->_cutNamefield}pass"] = Hash::create('sha256', $data["{$this->_cutNamefield}pass"], HASH_PASSWORD_KEY);
        }

        $this->db->insert($this->_objType, $data);
        $data['id'] = $this->db->lastInsertId();

        $data = $this->cut($this->_cutNamefield, $data);
    }
    public function update($id, $data) {
        $data["{$this->_cutNamefield}updated"] = date('c');
        $this->db->update($this->_objType, $data, "{$this->_cutNamefield}id={$id}");
    }
    public function delete($id) {
        $this->db->delete($this->_objType, "{$this->_cutNamefield}id={$id}");
    }

    public function lists( $options=array() ) {

        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'created',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',
            
            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,

        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        if( !empty($options['q']) ){
            $q = explode(' ', $options['q']);
            $wq = '';
            foreach ($q as $key => $value) {
                $wq .= !empty( $wq ) ? " OR ":'';
                $wq .= "user_first_name LIKE :q{$key} 
                        OR user_last_name=:q{$key} 
                        OR user_login LIKE :q{$key} 
                        OR user_phone_number LIKE :q{$key}";
                $where_arr[":q{$key}"] = "%{$value}%";
                $where_arr[":f{$key}"] = $value;
            }
            if( !empty( $wq) ){
                $where_str .= !empty( $where_str ) ? " AND ":'';
                $where_str .= "($wq)";
            }        
        }

        if( isset($_REQUEST["display"]) ){
            $options["display"] = $_REQUEST["display"];
        }

        if( isset($options["display"]) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= "user_display=:display";
            $where_arr[':display'] = $options['display'];
        }

        if( isset($_REQUEST["roles"]) ){
            $options["roles"] = $_REQUEST["roles"];
        }

        if( !empty($options["roles"]) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= "user_role_id=:roles";
            $where_arr[':roles'] = $options['roles'];
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $limit = $this->limited( $options['limit'], $options['pager'] );
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ) );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function get($id, $options=array() ){

        $condition = "`user_id`=:id";
        $params[':id'] = $id;

        if( isset($options['display']) ){
            $condition .= " AND `user_display`=:display";
            $params[':display'] = $options['display'];
        }
        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$condition} LIMIT 1");
        $sth->execute( $params );

        return $sth->rowCount()==1
            ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) )
            : array();
    }

    public function buildFrag($results) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert( $value );
        }

        return $data;
    }
    public function convert($data){

        $data = $this->cut($this->_cutNamefield, $data);
        
        $data['fullname'] = $data['first_name'];
        $data['fullname'] .=  !empty($data['last_name']) ? " {$data['last_name']}":'';
        // print_r($data); die;
        // $data['role'] = 'admin';
        // $data['image_url'] = IMAGES.'avatar/error/admin.png';

        if( !empty($data['image_id']) ){
            $image = $this->load('media')->get($data['image_id']);
            if( !empty($image) ){
                $data['image_arr'] = $image;
                $data['image_url'] = $image['quad_url'];
            }
        }

        $data['initials'] = $this->fn->q('text')->initials( $data['fullname'] );
        $data['permit']['del'] = !empty($data['is_owner']) ? false: true;

        $data['access'] = $this->getAccess($data);

        return $data;
    }

    public function login($user, $pess){

        $sth = $this->db->prepare("SELECT user_id as id FROM {$this->_objType} WHERE (user_login=:login AND user_pass=:pass AND user_display='enabled') OR (user_email=:login AND user_pass=:pass AND user_display='enabled')");

        $sth->execute( array(
            ':login' => $user,
            ':pass' => Hash::create('sha256', $pess, HASH_PASSWORD_KEY)
        ) );

        $fdata = $sth->fetch( PDO::FETCH_ASSOC );
        return $sth->rowCount()==1 ? $fdata['id']: false;
    }

    /**/
    /* roles */
    /**/
    #, role_is_admin AS is_admin, role_is_manage AS is_manage
    private $r_select = "role_id as id
                        , role_name as name";
    private $r_table = "users_roles";
    public function roles($type='') {
        return $this->db->select("SELECT {$this->r_select} FROM {$this->r_table}");
    }
    public function getRoles($id){

        $sth = $this->db->prepare("SELECT {$this->r_select} FROM {$this->r_table} WHERE role_id=:id");
        $sth->execute( array(":id"=>$id) );

        $fdata = $sth->fetch( PDO::FETCH_ASSOC );
        $fdata["permit"]["del"] = true;
        if( $this->db->count("users", "user_role_id={$fdata["id"]}") ){
            $fdata["permit"]["del"] = false;
        }

        return $sth->rowCount()==1 ? $fdata : array();
    }
    public function insertRoles( &$data ){
        $this->db->insert($this->r_table, $data);
        $data["role_id"] = $this->db->lastInsertId();
    }
    public function updateRoles( $id, $data ){
        $this->db->update($this->r_table, $data, "role_id={$id}");
    }
    public function delRoles( $id ){
        $this->db->delete($this->r_table, "role_id={$id}");
    }
    public function is_roleName($text){
        return $this->db->count($this->r_table, "role_name=:text", array(":text"=>$text));
    }

    #Access
    public function getAccess($options) {
        
        $arr = array();
        if( !empty( $options['is_owner'] ) ){
            array_push($arr, 1);
        }

        return $arr;
    }

    #Status
    public function status(){
        $a[] = array('id'=>'enabled', 'name'=>'เปิดใช้งาน');
        $a[] = array('id'=>'disabled', 'name'=>'ปิดใช้งาน');

        return $a;
    }
    public function getStatus($id){
        $data = array();
        foreach ($this->status() as $key => $value) {
            if( $value['id'] == $id ){
                $data = $value;
                break;
            }
        }
        return $data;
    }
}
