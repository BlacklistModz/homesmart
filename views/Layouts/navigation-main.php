<?php


#info
$info[] = array('key'=>'dashboard','text'=>$this->lang->translate('menu','Dashboard'),'link'=>$this->pageURL.'dashboard','icon'=>'home');
$info[] = array('key'=>'notifications','text'=>$this->lang->translate('menu','Notifications'),'link'=>$this->pageURL.'notifications','icon'=>'bell-o');
$info[] = array('key'=>'calendar','text'=>$this->lang->translate('menu','Calendar'),'link'=>$this->pageURL.'calendar','icon'=>'calendar');
foreach ($info as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($info[$key]);
}
if( !empty($info) ){
	echo $this->fn->manage_nav($info, $this->getPage('on'));
}


#Customer
// $cus[] = array('key'=>'customers','text'=>$this->lang->translate('menu','Customers'),'link'=>$this->pageURL.'customers','icon'=>'address-card-o');
// $cus[] = array('key'=>'companies','text'=>$this->lang->translate('menu','Companies'),'link'=>$this->pageURL.'employees','icon'=>'building-o');
// foreach ($cus as $key => $value) {
// 	if( empty($this->permit[$value['key']]['view']) ) unset($cus[$key]);
// }
// if( !empty($cus)){
// 	echo $this->fn->manage_nav($cus, $this->getPage('on'));
// }

$web[] = array('key'=>'website', 'text'=>$this->lang->translate('menu', 'View Website'), 'link'=>URL, 'icon'=>'eye', 'target'=>'_tpg' );
echo $this->fn->manage_nav($web, $this->getPage('on'));


#booking
/*$bok[] = array('key'=>'orders','text'=> $this->lang->translate('menu','Invoice'),'link'=>$this->pageURL.'order','icon'=>'file-text-o');
$bok[] = array('key'=>'booking','text'=> $this->lang->translate('menu','Booking'),'link'=>$this->pageURL.'booking','icon'=>'address-book-o');
foreach ($bok as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($bok[$key]);
}
if( !empty($bok)){
	echo $this->fn->manage_nav($bok, $this->getPage('on'));
}*/


#reports
// $reports[] = array('key'=>'projects','text'=>$this->lang->translate('menu','Projects'),'link'=>$this->pageURL.'projects','icon'=>'book');
// $reports[] = array('key'=>'tasks','text'=>$this->lang->translate('menu','Tasks'),'link'=>$this->pageURL.'tasks','icon'=>'check-square-o');
// $reports[] = array('key'=>'reports','text'=>$this->lang->translate('menu','Reports'),'link'=>$this->pageURL.'reports','icon'=>'line-chart');
// foreach ($reports as $key => $value) {
// 	if( empty($this->permit[$value['key']]['view']) ) unset($reports[$key]);
// }
// if( !empty($reports) ){
// 	echo $this->fn->manage_nav($reports, $this->getPage('on'));
// }


$cog[] = array('key'=>'settings','text'=>$this->lang->translate('menu','Settings'), 'link'=>$this->pageURL.'settings','icon'=>'cog');
echo $this->fn->manage_nav($cog, $this->getPage('on'));