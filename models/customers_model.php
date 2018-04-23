<?php

class Customers_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private $_objName = "customers";
    private $_table = "customers c LEFT JOIN city ci ON c.cus_city_id=ci.city_id";
    private $_field = "c.*, ci.city_name";
    private $_cutNameField = "cus_";

    public function insert(&$data){
    	$data["{$this->_cutNameField}created"] = date("c");
    	$data["{$this->_cutNameField}updated"] = date("c");
    	$this->db->insert($this->_objName, $data);
    }
    public function update($id, $data){
    	$data["{$this->_cutNameField}updated"] = date("c");
    	$this->db->update($this->_objName, $data, "{$this->_cutNameField}id={$id}");
    }
    public function delete($id){
    	$this->db->delete($this->_objName, "{$this->_cutNameField}id={$id}");
    }

    public function lists($options=array()){
    	$options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,


            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'created',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:'',

            'more' => true
        ), $options);

        $where_str = "";
        $where_arr = array();

        if( isset($options['not']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str = "{$this->_cutNamefield}id!=:not";
            $where_arr[':not'] = $options['not'];
        }

        if( !empty($options['q']) ){

            $arrQ = explode(' ', $options['q']);
            $wq = '';
            foreach ($arrQ as $key => $value) {
                $wq .= !empty( $wq ) ? " OR ":'';
                $wq .= "cus_first_name LIKE :q{$key} OR cus_first_name=:f{$key} OR cus_last_name LIKE :q{$key} OR cus_last_name=:f{$key} OR cus_phone LIKE :s{$key} OR cus_phone=:f{$key} OR cus_email LIKE :s{$key} OR cus_email=:f{$key} OR cus_card_id=:f{$key}";
                $where_arr[":q{$key}"] = "%{$value}%";
                $where_arr[":s{$key}"] = "{$value}%";
                $where_arr[":f{$key}"] = $value;
            }

            if( !empty($wq) ){
                $where_str .= !empty( $where_str ) ? " AND ":'';
                $where_str .= "($wq)";
            }
        }

        if( isset($_REQUEST["period_start"]) && isset($_REQUEST["period_end"]) ){
        	$options["period_start"] = $_REQUEST["period_start"];
        	$options["period_end"] = $_REQUEST["period_end"]
        }
        if( !empty($options['period_start']) && !empty($options['period_end']) ){

            $period_start = !empty($options['period_start']) ? $options['period_start'] : $_REQUEST['period_start'];
            $period_end = !empty($options['period_end']) ? $options['period_end'] : $_REQUEST['period_end'];

            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str .= "cus_created BETWEEN :startDate AND :endDate";
            $where_arr[':startDate'] = $period_start;
            $where_arr[':endDate'] = $period_end;
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $limit = $this->limited( $options['limit'], $options['pager'] );
        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ), $options );


        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
}