<?php

$max_width = '420px';
require 'init.php';

echo '<div class="pal" style="max-width: 720px">';

// header
echo '<div class="setting-header cleafix">';
    // echo '<div class="setting-title">'.$this->lang->translate('Company').'</div>';

    echo '<nav class="setting-header-taps">';
    foreach ($taps as $key => $value) {
    	
    	$active = $this->_tap == $value['id'] ? ' active':'';
    	echo '<a class="tap'.$active.'" href="'.$this->url.'settings/system/'.$value['id'].'">'.$value['name'].'</a>';

    	if( $this->_tap == 'dealer' ){
    		$max_width = '720px';
    	}
    }
    echo '</nav>';

echo '</div>';
// end header

// section
echo '<section class="setting-section">';
    require "tabs/{$this->_tap}.php";
echo '</section>';

echo '</div>';