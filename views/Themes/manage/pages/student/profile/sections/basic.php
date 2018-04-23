<?php

$form = new Form();
$form = $form->create()
	// set From
	// ->elem('div')
	->url( URL.'student/update/' . $this->section )
    ->method('post')
	->addClass('js-submit-form');

$form   ->field('user_login')
        ->name('user[login]')
        ->label('รหัสนักศึกษา')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['user_login']) ? $this->item['user_login'] : '' );

$form   ->field("user_name")
        ->name('user[name]')
        ->label('ชื่อภาษาไทย')
        ->text( $this->fn->q('form')->fullname( !empty($this->item)?$this->item:array(), array('field_first_name'=>'user_', 'prefix_name'=>$this->prefixName) ) );

$form 	->field("stu_name_en")
        ->name('stu[name_en]')
    	->label('ชื่อภาษาอังกฤษ')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name_en'])? $this->item['name_en']:'' );

$form   ->field("stu_faculty_id")
        ->name('stu[faculty_id]')
        ->label('คณะ')
        ->autocomplete('off')
        ->addClass('inputtext js-select-faculty')
        ->select( $this->faculty['lists'] )
        ->value( !empty($this->item['faculty_id']) ? $this->item['faculty_id'] : '' );

$form   ->field("stu_major_id")
        ->name('stu[major_id]')
        ->label('สาขา')
        ->autocomplete('off')
        ->addClass('inputtext js-select-majors')
        ->select( array() )
        ->value( '' );

$form   ->field("stu_teacher")
        ->name('stu[teacher]')
        ->label('อาจารย์นิเทศ')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->type('textarea')
        ->attr('data-plugins', 'autosize')
        ->value( !empty($this->item['teacher']) ? $this->item['teacher'] : '' );

/*
$form   ->button()
        ->addClass('btn btn-link')
        ->value('ยกเลิก');*/

$form   ->submit()
        ->addClass('btn btn-blue btn-submit')
        ->value('บันทึก');

$form ->hr( '<input type="hidden" autocomplete="off" class="hiddenInput" value="'.$this->item['id'].'" name="id">' );

echo $form->html();