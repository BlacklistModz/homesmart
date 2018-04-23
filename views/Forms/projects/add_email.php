<?php 
#    padding-left: 20px;

$title = $this->lang->translate('Email');

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("email_title")
    	->label($this->lang->translate('Title').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['title'])? $this->item['title']:'' );

$form 	->field("email_detail")
    	->label($this->lang->translate('Title').'*')
        ->type('textarea')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->attr('data-plugins', 'autosize')
        ->placeholder('')
        ->value( !empty($this->item["detail"]) ? $this->item["detail"] : '' );

$form 	->field("email_status")
		->label( $this->lang->translate('Status').'*' )
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->status )
		->value( !empty($this->item['status']) ? $this->item['status'] : '' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'projects/save_email"></form>';
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