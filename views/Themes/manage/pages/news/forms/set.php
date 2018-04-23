<?php 
// print_r($this->item);die;

$title = "ข้อมูลข่าวสาร";
if( !empty($this->item) ){
	$title = "แก้ไข{$title}";
}
else{
	$title = "เพิ่ม{$title}";
}

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field('topic_forum_id')
		->label('ประเภทข่าวสาร')
		->autocomplete('off')
		->addClass('inputtext')
		->placeholder('')
		->select( $this->forums )
		->value( !empty($this->item['forum_id']) ? $this->item['forum_id'] : '' );

$form   ->field("topic_name")
        ->label('หัวข้อข่าวสาร')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form   ->field("image")
        ->label('รูปโปรไฟล์')
        ->text('<div class="profile-cover image-cover pas" data-plugins="imageCover" style="width:640px; height:360px; margin-left:40mm;" data-options="'.(
        !empty($this->item['image_arr']) 
            ? $this->fn->stringify( array_merge( 
                array( 
                    'scaledX'=> 640,
                    'scaledY'=> 360,
                    'action_url' => URL.'topics/del_image_cover/'.$this->item['id'],
                    // 'top_url' => IMAGES_PRODUCTS
                ), $this->item['image_arr'] ) )
            : $this->fn->stringify( array( 
                    'scaledX'=> 640,
                    'scaledY'=> 360
                ) )
            ).'">
        <div class="loader">
        <div class="progress-bar medium"><span class="bar blue" style="width:0"></span></div>
        </div>
        <div class="preview"></div>
        <div class="dropzone">
            <div class="dropzone-text">
                <div class="dropzone-icon"><i class="icon-picture-o img"></i></div>
                <div class="dropzone-title">เพิ่มรูปหน้าปก</div>
            </div>
            <div class="media-upload"><input type="file" accept="image/*" name="image_cover"></div>
        </div>
        
</div>');

 $form 	->field("topic_detail")
		->label("รายละเอียด")
		->addClass('inputtext')
		->type('textarea')
		->autocomplete('off')
		->attr('data-plugins', 'editor2')
		// ->attr('data-options', $this->fn->stringify(array(
  //           'getData' => array(
  //               'obj_type' => 'news',
  //               'obj_id' => ''
  //           )
  //       )))
        ->value( !empty($this->item['detail']) ?$this->fn->q('text')->strip_tags_editor(  $this->item['detail']): '' );  

$form   ->field('topic_status')
        ->label('สถานะ')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->select( $this->status )
        ->value( !empty($this->item['status']) ? $this->item['status'] : 1 );     
?>
<div id="mainContainer" class="report-main clearfix" data-plugins="main">
	<div role="content">
		<div role="main" class="pal">
			<div style="max-width: 1024px;">
				<div class="uiBoxWhite pas pam">
					<div class="clearfix">
						<div class="lfloat">
							<h3 class="fwb"><i class="icon-newspaper-o"></i> <?=$title?></h3>
						</div>
					</div>
				</div>
				<form class="js-submit-form" method="POST" action="<?=URL?>topics/save">
					<div class="uiBoxWhite pas pam mts">
						<?=$form->html()?>
					</div>
					<div class="uiBoxWhite pas pam">
						<div class="clearfix">
							<a href="<?=URL?>cms/news" class="btn btn-red lfloat">กลับ</a>
							<button class="btn btn-blue js-submit rfloat">บันทึก</button>
						</div>
					</div>
					<?php 
					if( !empty($this->item["id"]) ){
						echo '<input type="hidden" name="id" value="'.$this->item["id"].'">';
					}
					?>
				</form>
			</div>
		</div>
	</div>
</div>
