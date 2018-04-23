<?php

# title
$title = $this->lang->translate('Majors');
if( !empty($this->item) ){
    $arr['title']= "แก้ไข{$title}";
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= "เพิ่ม{$title}";
}

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("major_faculty_id")
		->label($this->lang->translate('Faculty').'*')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->faculty['lists'] )
		->value( !empty($this->item['faculty_id']) ? $this->item['faculty_id'] : '' );

// ประเภท
$form 	->field("major_name")
    	->label($this->lang->translate('Name').$this->lang->translate('Majors').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'student/save_majors"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn btn-red" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);