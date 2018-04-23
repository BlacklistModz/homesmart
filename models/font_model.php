<?php

class Font_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private $_objName = "pages_fonts";
    private $_table = "pages_fonts";
    private $_field = "*";
    private $_cutNamefield = "font_";

    public function lists( $options=array() ) {

    	$options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'unlimit' => isset($_REQUEST['unlimit'])? $_REQUEST['unlimit']:true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'name',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            // 'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,
            'more' => true
        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        if( isset($options['not']) ){
        	$where_str = "{$this->_cutNamefield}id!=:not";
            $where_arr[':not'] = $options['not'];
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);
        
        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $limit = empty($options['unlimit'])? $this->limited( $options['limit'], $options['pager'] ): '';

        
        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ) );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function popular($options=array()) {

    	return $this->lists($options);
    }

    public function buildFrag($results) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert( $value );
        }
        return $data;
    }
    public function get($id){
        
        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_cutNamefield}id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        return $sth->rowCount()==1
            ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) )
            : array();
    }
    public function convert($data){

        
        return $this->cut($this->_cutNamefield, $data);
    }

    
    /* active */
    public function insert(&$data) {

        $data["{$this->_cutNamefield}created"] = date('c');
        $this->db->insert( $this->_objName, $data );
        $data["{$this->_cutNamefield}id"] = $this->db->lastInsertId();
        $data = $this->convert( $data );
    }
    public function del($id) {
        $this->db->delete($this->_objName, "`{$this->_cutNamefield}id`={$id}" );
    }
    public function update($id, $data) {
        $this->db->update($this->_objName, $data, "`{$this->_cutNamefield}id`={$id}" );
    }
    
}