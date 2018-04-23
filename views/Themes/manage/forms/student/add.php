<?php

$options = array(
    'url' => URL.'media/set',
    'data' => array(
        'album_name'=>'profile',
        'obj_type'=>'profile', 
        'obj_id'=>2,
        'minimize'=> array(128,128),
        'has_quad'=> true,
     ),
    'autosize' => true,
    'show'=>'quad_url',
    'remove' => true
);

if( !empty($this->item['id']) ){
    $options['setdata_url'] = URL.'users/setdata/'.$this->item['id'].'/user_image_id/?has_image_remove';
}

$image_url = '';
$hasfile = false;
if( !empty($this->item['image_url']) ){
    $hasfile = true;
    $image_url = '<img class="img" src="'.$this->item['image_url'].'?rand='.rand(100, 1).'">';

    $options['remove_url'] = URL.'media/del/'.$this->item['image_id'];
    
}

$picture_box = '<div class="anchor"><div class="clearfix">'.

        '<div class="ProfileImageComponent lfloat size80 radius mrm is-upload'.($hasfile ? ' has-file':' has-empty').'" data-plugins="uploadProfile" data-options="'.$this->fn->stringify( $options ).'">'.
            '<div class="ProfileImageComponent_image">'.$image_url.'</div>'.
            '<div class="ProfileImageComponent_overlay"><i class="icon-camera"></i></div>'.
            '<div class="ProfileImageComponent_empty"><i class="icon-camera"></i></div>'.
            '<div class="ProfileImageComponent_uploader"><div class="loader-spin-wrap"><div class="loader-spin"></div></div></div>'.
            '<button type="button" class="ProfileImageComponent_remove"><i class="icon-remove"></i></button>'.
        '</div>'.
    '</div>'.

'</div>';

# title
$title = $this->lang->translate('Student');
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

$form   ->field("image")
        ->text( $picture_box );
// ประเภท
$form   ->field('user_login')
        ->name("user[login]")
    	->label($this->lang->translate('Username').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('');

$form   ->field('user_pass')
        ->name("user[pass]")
        ->label('รหัสผ่าน (วัน/เดือน/ปี เกิด = ตย. 151136)*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('');

$form   ->field("name")
        ->label($this->lang->translate('Name').'*')
        ->text( $this->fn->q('form')->fullname( !empty($this->item)?$this->item:array(), array('field_first_name'=>'user[', 'prefix_name'=>$this->prefixName, 'field_last_name'=>']') ) );

$form   ->field('stu_faculty_id')
        ->name('stu[faculty_id]')
        ->label($this->lang->translate('Faculty').'*')
        ->autocomplete('off')
        ->addClass('inputtext js-select-faculty')
        ->placeholder('off')
        ->select( $this->faculty['lists'] );

$form   ->field('stu_major_id')
        ->name('stu[major_id]')
        ->label($this->lang->translate('Majors').'*')
        ->autocomplete('off')
        ->addClass('inputtext js-select-majors')
        ->placeholder('off')
        ->select();

$form   ->field('stu_class')
        ->name('stu[class]')
        ->label($this->lang->translate('Class').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('off')
        ->select( $this->class );

$form   ->field('stu_year_id')
        ->name('stu[year_id]')
        ->label('ปีการศึกษา*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('off')
        ->maxlength(4)
        ->select( $this->year['lists'] );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'student/save" data-plugins="form_student"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn btn-red" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);