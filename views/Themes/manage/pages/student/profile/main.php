<div class="pll mhl mtl">
<ul class="uiList settingsList">
    <?php foreach ($list as $key => $value) {

        $class = '';
        
        if( $this->section == $value['section'] ){
            $class .= !empty($class) ? ' ':'';
            $class .= 'openPanel';
        }
        
     ?>
    <li class="<?=$class?>">
        <div class="clearfix settingsListLink hidden_elem">

            <div class="rfloat">
                <a class="js-edit" href="<?=URL?>cms/student/<?=$this->item['id']?>/<?=$value['section']?>"><i class="icon-pencil mrs"></i><span>แก้ไข</span></a>
            </div>

            <div class="label"><?=$value['label']?></div>
        </div>
        <?php 
        $options = $this->fn->stringify(
            array(
                'faculty' => !empty($this->item['faculty_id']) ? $this->item['faculty_id'] : '',
                'major' => !empty($this->item['major_id']) ? $this->item['major_id'] : ''
            ));
        ?>
        <div class="content" data-plugins="form_student" data-options="<?=$options?>">
            <?php 
                if( $this->section == $value['section'] ){
                    require "sections/{$value['section']}.php";
                } 
            ?>
        </div>
    </li>
    <?php } ?>
</ul>
</div>