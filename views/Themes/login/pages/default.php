<?php

$f = new Form();
$form = $f->create(); 

    // attr, options
$form   ->addClass('login-form-container form-insert form-large')
        ->method('post')
        ->url( $this->redirect );

    // set field
$form   ->field("email")
        ->label('<i class="icon-user"></i>')
        ->placeholder("Username")
        ->addClass('inputtext')
        ->required(true)
        ->autocomplete("off");

if( (!empty($this->post['email']) && !empty($this->error['email'])) || empty($this->post['email']) ){
$form   ->attr('autofocus', '1');
}

$form   ->value( !empty($this->post['email'])? $this->post['email'] : '' )
        ->notify( !empty($this->error['email']) ? $this->error['email'] : '' );

$form   ->field("pass")
        ->label('<i class="icon-key"></i>')
        ->type('password')
        ->required(true);

if( (!empty($this->post['email']) && empty($this->error['email'])) ){
$form   ->attr('autofocus', '1');
}

$form   ->addClass('inputtext')
        ->placeholder("Password")
        ->notify( !empty($this->error['pass']) ? $this->error['pass'] : '' );


if( !empty($this->captcha) ){

    $form->field("captcha")
    ->text('<div class="g-recaptcha" data-sitekey="'.RECAPTCHA_SITE_KEY.'"></div>')
    ->notify( !empty($this->error['captcha']) ? $this->error['captcha'] : '' );

}

$form->hr( !empty($this->next) ? '<input type="hidden" autocomplete="off" value="'.$this->next .'" name="next">': '' )

// ->hr('<input type="hidden" autocomplete="off" value="1" name="path_admin">' )

->submit()
->addClass('btn btn-blue btn-large')
->value('Log In');


$title = !empty($this->system['title']) ? $this->system['title']:'';
$name = !empty($this->system['name']) ? $this->system['name']:'';
$image = $this->getPage('image-128');


?>

<div class="section">
    <div class="content-wrapper<?=!empty($this->captcha)? ' has-captcha':''?>">

        <div class="login-header-bar login-logo">
            <div class="text">
                <?php if( !empty($image) ){ ?><div class="pic mbm"><img src="<?=$image?>"></div><?php } ?>

                <?php

                if( !empty($name)  ){
                    echo '<h2>'. (!empty( $name ) ? $name :'') .'</h2>';

                    if( !empty($title) ){
                        echo '<h3>'. (!empty( $title ) ? $title :'') .'</h3>';
                    }
                }
                else if( !empty($title) ){
                    echo '<h2>'. (!empty( $title ) ? $title :'') .'</h2>';
                }

                ?>
                
            </div>

            <div class="subtext mvm"></div>

        </div>
        <!-- end: login-header -->

        <div class="login-container-wrapper auth-box">
            <div class="login-container">
                <!-- <div class="login-title"><span class="fwb">Login</span></div> -->
                <?=$form->html()?>
            </div>

        </div>
        <!-- end: login-container-wrapper -->

        <div class="login-footer-text">
            <a href="<?=URL?>"><i class="icon-home mrs"></i><span>Back To Home</span></a>
            <!-- <span class="mhm">·</span> -->
            <!-- <a href="<?=URL?>forgot_password" class="forgot_password"><span>Forgot password?</span></a> -->
        </div>
        <!-- end: login-footer -->
        
    </div>
    <!-- end: content-wrapper -->

</div>
<!-- /section -->


<!-- <div class="fixed top right">
    <a  href="<?=URL?>?login_mode=pin" class="btn btn-blue btn-large">PIN Login</a>
</div> -->