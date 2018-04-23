<?php

$this->count_nav = 0;

/* System */
$sub = array();
$sub[] = array('text' => 'System','key' => 'system','url' => $this->pageURL.'settings/system');
$sub[] = array('text' => 'My Profile','key' => 'my','url' => $this->pageURL.'settings/my');

foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
}
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text' => $this->lang->translate('Preferences'), 'url' => $this->pageURL.'settings/system', 'sub' => $sub);
}


/**/
/* Accounts */
/**/
$sub = array();
$sub[] = array('text'=> $this->lang->translate('Users'),'key'=>'users','url'=>$this->pageURL.'settings/users');
// $sub[] = array('text'=> $this->lang->translate('User Roles'),'key'=>'roles','url'=>$this->pageURL.'settings/users/roles');

/* foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
} */
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=> $this->lang->translate('Accounts'),'sub' => $sub, 'url' => $this->pageURL.'settings/users/');
}
