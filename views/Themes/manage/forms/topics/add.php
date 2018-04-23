<?php

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert pal');

$form 	->field('topic_forum_id')
		->label('ประเภท')
		->autocomplete('off')
		->addClass('inputtext')
		->placeholder('')
		->select( $this->forums )
		->value( !empty($this->item['forum_id']) ? $this->item['forum_id'] : '' );

$form   ->field("topic_name")
        ->label('หัวข้อ')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form   ->field("image")
        ->label('รูปหน้าปก')
        ->text('<div class="profile-cover image-cover pas" data-plugins="imageCover" data-options="'.(
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

$form ->field("topic_detail")
        ->label("รายละเอียด")
        ->type('textarea')
        ->addClass('inputtext')
        ->attr('data-plugins', 'editor')
        ->attr('data-options', $this->fn->stringify(array(
            'image_upload_url' => URL .'media/set',
            'album_obj_type'=>'topics_detail',
            'album_obj_id'=>'5'
        )))
        ->autocomplete("off")
        ->value( !empty($this->item['detail']) ? $this->item['detail']:'' );

$form   ->field('topic_status')
        ->label('สถานะ')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->select( $this->status )
        ->value( !empty($this->item['status']) ? $this->item['status'] : 1 );

# body
$arr['body'] = $form->html();

$arr['title']= "เพิ่มข่าวสาร";

if( !empty($this->item) ){
    $arr['title'] = "แก้ไขข่าวสาร";
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL.'topics/save"></form>';

$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

$arr['height'] = 'full';
$arr['width'] = 650;

echo json_encode($arr);