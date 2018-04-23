<?php 
$options = array(
	'url'=> URL.'calendar/events',
	'add_url' => URL.'events/add',
	'weekDayStart' => 1
);
?>
<div id="mainContainer" class="clearfix" data-plugins="main">
	<div role="content">
		<div role="main" class="calendarGridRootContainer">
			<div class="pal">
				<div data-plugins="calendar" data-options="<?=$this->fn->stringify( $options )?>"></div>
			</div>
		</div>
	</div>
</div>