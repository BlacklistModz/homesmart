<?php

$form = new Form();
$form = $form->create()
		->url($this->url."settings/system?run=1")
		->addClass('js-submit-form form-insert')
		->method('post');

$form  	->field("google_analytic")
		->label($this->lang->translate('Google Analytic Code'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['google_analytic']) ? $this->system['google_analytic']:'');

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value($this->lang->translate('Save'));

echo $form->html();
?>
