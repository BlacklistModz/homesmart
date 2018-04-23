<?php

$title[] = array('key'=>'number', 'text'=>'รหัสนักศึกษา', 'sort'=>'username');
$title[] = array('key'=>'name', 'text'=>'ชื่อ-นามสกุล', 'sort'=>'first_name');
$title[] = array('key'=>'status', 'text'=>'ปีการศึกษา', 'sort'=>'year_id');
$title[] = array('key'=>'actions', 'text'=>'จัดการ');

$this->tabletitle = $title;
$this->getURL =  URL.'cms/student/';