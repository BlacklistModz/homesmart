<?php 

$title = $this->lang->translate('Users');

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

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert form-emp');

$form   ->field("image")
        ->text( $picture_box );

$form   ->field("user_login")
        ->label($this->lang->translate('Username').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->value( !empty($this->item['login'])? $this->item['login']:'' );
        
if( empty($this->item) ){

$form   ->field("user_pass")
        ->label($this->lang->translate('Password').'*')
        ->type('password')
        ->maxlength(30)
        ->autocomplete('off')
        ->addClass('inputtext');
}

$form   ->field("name")
        ->label($this->lang->translate('Name'))
        ->text( $this->fn->q('form')->fullname( !empty($this->item)?$this->item:array(), array('field_first_name'=>'user_', 'prefix_name'=>$this->prefixName) ) );

$form 	->field("user_email")
		->label($this->lang->translate("Email"))
		->autocomplete("off")
		->addClass("inputtext")
		->value( !empty($this->item["email"]) ? $this->item["email"] : "" );

$form 	->field("user_role_id")
		->label($this->lang->translate("Roles"))
		->addClass("inputtext")
		->autocomplete("off")
		->select( $this->roles )
		->value( !empty($this->item["role_id"]) ? $this->item["role_id"] : "" );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'users/save"></form>';
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