<?php

$title[] = array('key'=>'category', 'text'=>'ประเภทข่าวสาร', 'sort'=>'forum_id');
$title[] = array('key'=>'image', 'text'=>'');
$title[] = array('key'=>'name', 'text'=>'หัวข้อ', 'sort'=>'name');
$title[] = array('key'=>'email', 'text'=>'สถานะ');
$title[] = array('key'=>'actions', 'text'=>'จัดการ');

$this->tabletitle = $title;
$this->getURL =  URL.'cms/news/';