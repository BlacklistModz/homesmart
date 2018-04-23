<?php 
$arr['title'] = 'ยืนยันการลบข้อมูล';

$arr['form'] = '<form class="js-submit-form" action="'.URL.'projects/del_email'.$next.'"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
$arr['body'] = "{$this->lang->translate('You want to delete')} <span class=\"fwb\">\"{$this->item['title']}\"</span> {$this->lang->translate('or not')}?";

$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">ลบ</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">'.$this->lang->translate('Cancel').'</span></a>';
?>