<?php require 'init.php'; ?>
<div id="mainContainer" class="clearfix" data-plugins="main">

	<div class="profile-left" role="left" data-width="300">

		<div class="profile-left-header" role="leftHeader">
			
			<div class="profile-left-title">
				<h2>จัดการข้อมูลนักศึกษา</h2>
				<div class="fsm">แก้ไขข้อมูลล่าสุด: <?= $this->fn->q('time')->live( $this->item['updated'] )?></div>
			</div>

			<!-- <div id="overviewProfileCompleteness">
                <div class="title">
                    <span id="profileCompletenessLabel" class="label" aria-hidden="true">Profile completeness</span>
                    <span id="profileCompletenessValue" class="value" aria-hidden="true">70%</span>
                </div>
                <div class="progress-bar medium">
                    <span class="progresBarValue" rel="70%" style="width: 70%;"></span>
                </div>
            </div> -->
		</div>
		<!-- end: .profile-left-header -->

		<div class="profile-left-details form-insert-people" role="leftContent">

	    <!--  -->
	    <ul class="nav" style="box-shadow: rgba(255, 255, 255, .5) 0px 1px 0px 0px;border-bottom: 1px solid rgb(211, 211, 211);"><?php  

	    	$section_name = '';

	    	foreach ($list as $key => $value) { 

	    		$cls = '';
	    		if( $this->section == $value['section'] ){
	    			$cls .= !empty($cls)? ' ':'';
	    			$cls .= 'active';

	    			$section_name = $value['label'];
	    		}


	    	if( !empty($cls) ){
	    		$cls = ' class="'.$cls.'"';
	    	}

	    	echo '<li'.$cls.'><a href="'.URL.'cms/student/'.$this->item['id'].'/'.$value['section'].'">'.$value['label'].'</a></li>';

	    } ?>

	    </ul>
	    <div style="box-shadow: rgba(255, 255, 255, .5) 0px 1px 0px 0px;border-bottom: 1px solid rgb(211, 211, 211);"></div>

	    <!-- status -->
	    <?php 
	    $update_status = $this->fn->stringify(array('url' => URL. 'student/_update/'.$this->item['id'].'/status'));
	    ?>
	    <div class="mvm clearfix">
	    	<label style="font-weight: bold; font-size: 12px;margin-bottom: 3px;opacity: .5">สถานะ</label>
	    	<select name="status" data-plugins="_update" data-options="<?=$update_status?>" class="inputtext" style="background-color:#fff">
	    		<?php 
	    		foreach ($this->status as $key => $value) {
	    			$sel = '';
	    			if( $value['id'] == $this->item['user_display'] ){
	    				$sel = ' selected="1"';
	    			}
	    			echo '<option'.$sel.' value="'.$value['id'].'">'.$value['name'].'</option>';
	    		}
	    		?>
	    	</select>
	    </div>

	    <div class="mvm">
	    	<a style="width: 100%;text-align: left;" href="" target="_blank" class="btn btn-green"><i class="icon-print mrs"></i>Print PDF</a>
	    </div>

	    <div class="mvm">
	    	<a style="width: 100%;text-align: left;" class="btn btn-red" href="<?=URL?>student/del/<?=$this->item['id']?>" data-plugins="dialog"><i class="icon-trash-o mrs"></i><span>ลบ</span></a>
	    </div>

	    </div>
	    <!-- end: .profile-left-details -->
	</div>
	<div role="content">
		<div role="toolbar" class="cashier-toolbar">
			<div class="mhl phl ptl clearfix">
				
				<h1 style="display: inline-block;"><?=$this->item['user_login']?> - <?=$this->item['fullname']?></h1>
				<div>ตั้งค่า -> <?=$section_name?></div>
			</div>
		</div>
		<!-- End: toolbar -->

		<div class="" role="main">
			<?php require 'main.php'; ?>
		</div>
		<!-- end: main -->

	</div>
	<!-- end: content -->
</div>