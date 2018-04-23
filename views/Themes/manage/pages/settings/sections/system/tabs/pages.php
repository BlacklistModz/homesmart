<?php

$form = new Form();
$form = $form->create()
		->url($this->url."settings/system?run=1")
		->addClass('js-submit-form')
		->method('post');

$form  	->field("page_present")
		->label($this->lang->translate('Home Present'))
		->addClass('inputtext')
		->required(true)
		->autocomplete("off")
		->value( !empty($this->system['page_present']) ? $this->system['page_present']:'' );

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value($this->lang->translate('Save'));

echo $form->html();
