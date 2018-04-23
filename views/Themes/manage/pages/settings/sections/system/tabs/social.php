<?php

$form = new Form();
$form = $form->create()
		->url($this->url."settings/system?run=1")
		->addClass('js-submit-form')
		->method('post');

$form  	->field("google_id")
		->label($this->lang->translate('Google ID'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['google_id']) ? $this->system['google_id']:'');

$form  	->field("facebook_id")
		->label($this->lang->translate('Facebook ID'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['facebook_id']) ? $this->system['facebook_id']:'');

$form  	->field("twitter_id")
		->label($this->lang->translate('Twitter ID'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['twitter_id']) ? $this->system['twitter_id']:'');

$form  	->field("instagram_id")
		->label($this->lang->translate('Instagram ID'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['instagram_id']) ? $this->system['instagram_id']:'');

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value($this->lang->translate('Save'));


echo $form->html();