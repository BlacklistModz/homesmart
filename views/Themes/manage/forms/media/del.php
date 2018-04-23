<?php

$arr['title'] = 'ยืนยันการลบรูป';
$arr['body'] = "ลบรูปโปรไฟล์หรือไม่?";
$arr['form'] = '<form action="'.URL.'media/del"></form>';
$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
$arr['button'] = '<a href="#" class="btn btn-link btn-cancel" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';
$arr['button'] .= '<button type="submit" role="submit" class="btn btn-submit btn-red"><span class="btn-text">ลบ</span></button>';

echo json_encode($arr);