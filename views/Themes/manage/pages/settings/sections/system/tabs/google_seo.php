<?php

$form = new Form();
$form = $form->create()
		->url($this->url."settings/system?run=1")
		->addClass('js-submit-form')
		->method('post');

$form  	->field("seo_title")
		->label($this->lang->translate('Page title on search engines?'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['seo_title']) ? $this->system['seo_title']:'');
		
$form  	->field("seo_description")
		->type('textarea')
		->label($this->lang->translate('Page about? (Page Description)'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['seo_description']) ? $this->system['seo_description']:'');

$form  	->field("seo_keyword")
		->label($this->lang->translate('Page Keyword?'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['seo_keyword']) ? $this->system['seo_keyword']:'');

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value($this->lang->translate('Save'));

echo $form->html();