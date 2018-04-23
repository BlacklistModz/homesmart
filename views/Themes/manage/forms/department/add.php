<?php


# title
$title = 'แผนก';
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

// ประเภท
$form 	->field("dep_name")
    	->label('Name*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form   ->field("dep_notes")
        ->label('Note')
        ->type('textarea')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugins', 'autosize')
        ->placeholder('')
        ->value( !empty($this->item['notes'])? $this->fn->q('text')->textarea($this->item['notes']):'' );

$form   ->field("dep_premit")
        ->text(
            '<label class="checkbox mrl"><input type="checkbox" name="dep_is_admin"'.( !empty($this->item['is_admin'])? ' checked': '' ).' value="1"><span class="fwb">Admin</span></label>'.
            '<label class="checkbox mrl"><input type="checkbox" name="dep_is_sale"'.( !empty($this->item['is_sale'])? ' checked': '' ).'  value="1"><span class="fwb">Sale</span></label>'.
            '<label class="checkbox mrl"><input type="checkbox" name="dep_is_service"'.( !empty($this->item['is_service'])? ' checked': '' ).'  value="1"><span class="fwb">Service</span></label>'.
            '<label class="checkbox mrl"><input type="checkbox" name="dep_is_tec"'.( !empty($this->item['is_tec'])? ' checked': '' ).'  value="1"><span class="fwb">ช่างซ่อม</span></label>'
        );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'employees/save_department"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);