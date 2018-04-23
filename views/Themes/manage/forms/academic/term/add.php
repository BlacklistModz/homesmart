<?php

#SET DATE START & END
$startDate = '';
if( !empty($this->item['start']) ){
	$startDate = $this->item['start'];
}
elseif( isset($_REQUEST['date']) ){
	$startDate = $_REQUEST['date'];
}
$endDate = '';
if( !empty($this->item['end']) ){
	if( $this->item['end'] != '0000-00-00' ){
		$endDate = $this->item['end'];
	}
}

# title
$title = 'ภาคเรียน';
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

$form 	->field("term_year_id")
		->label('ปีการศึกษา*')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->year['lists'] )
		->value( !empty($this->item['year_id']) ? $this->item['year_id'] : '' );

$form 	->field("term_name")
    	->label('ภาคเรียน*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$options = $this->fn->stringify( array(
			'startDate' => $startDate,
			'endDate' => $endDate,

			'allday' => 'disabled',
			'endtime' => 'disabled',
			'time' => 'disabled',

			'str' => array(
				'เริ่ม',
				'สิ้นสุด',
				// $this->lang->translate('All day'),
				// $this->lang->translate('End Time'),
			),

			'lang' => $this->lang->getCode(),
			'name' => array('term_start', 'term_end'),
		) );
$form 	->field("term_start")
		->label('ระยะเวลา')
		->text( '<div data-plugins="setdate" data-options="'.$options.'"></div>' );


# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'year/save_term"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn btn-red" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);