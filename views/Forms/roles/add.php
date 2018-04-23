<?php 
#    padding-left: 20px;

$title = $this->lang->translate('Roles');

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("role_name")
    	->label($this->lang->translate('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

 $ck_admin = !empty($this->item["is_admin"]) ? ' checked="1"' : "";
$ck_manage = !empty($this->item["is_manage"]) ? ' checked="1"' : "";

if( empty($this->item) ){
	$ck_manage = ' checked="1"';
}

$role = '
<label class="radio radio-inline"><input'.$ck_admin.' type="radio" name="role_is" value="admin"><span class="fwb">Admin</span></label>
<label class="radio radio-inline"><input'.$ck_manage.' type="radio" name="role_is" value="manage"><span class="fwb">Manage</span></label>';

$form   ->field("role_access")
        ->text( $role ); 

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'users/save_roles"></form>';
# body
$arr['body'] = $form->html();

# title
if( !empty($this->item) ){
    $arr['title']= $title;
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= $title;
}

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

$arr['width'] = 550;

echo json_encode($arr);