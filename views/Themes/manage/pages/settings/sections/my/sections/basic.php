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

if( !empty($this->me['id']) ){
    $options['setdata_url'] = URL.'users/setdata/'.$this->me['id'].'/user_image_id/?has_image_remove';
}

$image_url = '';
$hasfile = false;
if( !empty($this->me['image_url']) ){
    $hasfile = true;
    $image_url = '<img class="img" src="'.$this->me['image_url'].'?rand='.rand(100, 1).'">';

    $options['remove_url'] = URL.'media/del/'.$this->me['image_id'];
    
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
		->url(URL."me/updated/basic?run=1")
		->addClass('js-submit-form form-insert')
		->method('post');

$form   ->field("image")
		->label(Translate::Val('Avatar'))
        ->text( $picture_box );

$form   ->field("user_first_name")
        ->label(Translate::Val('Name'))
        ->text( $this->fn->q('form')->fullname( $this->me, array('field_first_name'=>'user_') ) );

$form  	->field("user_email")
		->label(Translate::Val('Email'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->me['email']) ? $this->me['email']:''  );

/*$form  	->field("user_phone")
		->label(Translate::Val('Phone'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->me['phone_number']) ? $this->me['phone_number']:''   );

$form   ->field("user_line_id")
        ->label('Line ID')
        ->addClass('inputtext')
        ->autocomplete("off")
        ->value( !empty($this->me['line_id']) ? $this->me['line_id']:'' );*/

$a = array();
$a[] = array('id'=>'light', 'name'=>'Light');
$a[] = array('id'=>'dark', 'name'=>'Dark');
$a[] = array('id'=>'blue', 'name'=>'Blue');
$a[] = array('id'=>'green', 'name'=>'Green');

$mode = '';
if( empty($this->me['mode']) ) $this->me['mode'] = 'light';
foreach ($a as $key => $value) {
    
    $check = $this->me['mode']==$value['id'] ? ' checked="1"':'';
    $mode .= '<li><label class="radio"><input type="radio" name="user_mode" value="'.$value['id'].'"'.$check.' />'.$value['name'].'</label></li>';
}

$form   ->field("user_mode")
        ->label( Translate::Val('Themes') )
        ->text( '<ul>'.$mode.'</ul>' );

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value(Translate::Val('Save'));

echo $form->html();