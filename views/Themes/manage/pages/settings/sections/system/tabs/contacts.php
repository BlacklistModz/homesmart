<?php

$form = new Form();
$form = $form->create()
		->url($this->url."settings/system?run=1")
		->addClass('js-submit-form')
		->method('post');

$form  	->field("email")
		->label($this->lang->translate('Email'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['email']) ? $this->system['email']:'');
		
$form  	->field("phone")
		->label($this->lang->translate('Phone'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['phone']) ? $this->system['phone']:'');

$form  	->field("mobile_phone")
		->label($this->lang->translate('Mobile Phone'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['mobile_phone']) ? $this->system['mobile_phone']:'');

$form  	->field("fax")
		->label($this->lang->translate('Fax'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['fax']) ? $this->system['fax']:'');

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value($this->lang->translate('Save'));

echo $form->html();