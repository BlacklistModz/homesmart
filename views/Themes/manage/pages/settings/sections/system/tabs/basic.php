<?php

$form = new Form();
$form = $form->create()
		->url($this->url."settings/system?run=1")
		->addClass('js-submit-form')
		->method('post');

$form  	->field("name")
		->label($this->lang->translate('System Name'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['name']) ? $this->system['name']:'' );

$form  	->field("title")
		->label($this->lang->translate('System Name (English)'))
		->addClass('inputtext')
		->required(true)
		->autocomplete("off")
		->value( !empty($this->system['title']) ? $this->system['title']:'' );

// $form  	->field("license")
// 		->label($this->lang->translate('License No.'))
// 		->addClass('inputtext')
// 		->autocomplete("off")
// 		->value( !empty($this->system['license']) ? $this->system['license']:'');

$form  	->field("description")
		->label($this->lang->translate('Description'))
		->type('textarea')
		->addClass('inputtext')
		->autocomplete("off")
		->attr('data-plugins', 'autosize')
		->value( !empty($this->system['description']) ? $this->system['description']:'');

$form  	->field("workingtime")
		->label($this->lang->translate('เวลาทำการ'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['workingtime']) ? $this->system['workingtime']:'');

$form  	->field("email")
		->label($this->lang->translate('Email'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['email']) ? $this->system['email']:'');

$form  	->field("short_about")
		->label($this->lang->translate('Short About'))
		->addClass('inputtext')
		->autocomplete("off")
		->type('textarea')
		->value( !empty($this->system['short_about']) ? $this->system['short_about']:'');

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value($this->lang->translate('Save'));

echo $form->html();