<?php

$arr['title'] = 'ยืนยันการลบข้อมูล';
if ( !empty($this->item['permit']['del']) ) {
	
	$arr['form'] = '<form class="js-submit-form" action="'.URL. 'year/del_term"></form>';
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
	$arr['body'] = "คุณต้องการลบ <span class=\"fwb\">\"ภาคเรียน {$this->item['name']} ปีการศึกษา {$this->item['year_name']}\"</span> หรือไม่?";
	
	$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">ลบ</span></button>';
	$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';
}
else{

	$arr['body'] = "คุณไม่สามารถลบ <span class=\"fwb\">\"ภาคเรียน {$this->item['name']} ปีการศึกษา {$this->item['year_name']}\"</span> ได้?";	
	$arr['button'] = '<a href="#" class="btn btn-cancel" role="dialog-close"><span class="btn-text">ปิด</span></a>';
}


echo json_encode($arr);