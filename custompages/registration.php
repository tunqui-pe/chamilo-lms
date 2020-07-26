<?php
/* For licensing terms, see /license.txt */
/**
 * This script allows for specific registration rules (see CustomPages feature of Chamilo)
 * Please contact CBlue regarding any licences issues.
 * Author: noel@cblue.be
 * Copyright: CBlue SPRL, 20XX (GNU/GPLv3).
 *
 * @package chamilo.custompages
 */
require_once api_get_path(SYS_PATH).'main/inc/global.inc.php';
require_once __DIR__.'/language.php';
/**
 * Removes some unwanted elementend of the form object.
 */
/*$content['form']->removeElement('extra_mail_notify_invitation');
$content['form']->removeElement('extra_mail_notify_message');
$content['form']->removeElement('extra_mail_notify_group_message');
$content['form']->removeElement('official_code');
$content['form']->removeElement('phone');
$content['form']->removeElement('submit');
if (isset($content['form']->_elementIndex['status'])) {
    $content['form']->removeElement('status');
    $content['form']->removeElement('status');
}*/

$content['form']->removeElement('official_code');
$content['form']->removeElement('phone');
$content['form']->addRule('extra_country', get_lang('ThisFieldIsRequired'), 'required');
$content['form']->addRule('extra_rol_unico_tributario', get_lang('ThisFieldIsRequired'), 'required');
$content['form']->removeElement('extra_rut_factura');

$theme = api_get_visual_theme();
$rootWeb = api_get_path('WEB_PATH');
$rootWebTheme = api_get_path('WEB_CSS_PATH').'themes/'.$theme;
$rootSYS = api_get_path('SYS_CSS_PATH').'themes/'.$theme;

?>
<!doctype html>
<html lang="es">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso al aula virtual</title>
    <link rel="icon" type="image/png" href="<?php echo $rootWebTheme; ?>/images/favicon.png" />
    <link rel="stylesheet" type="text/css" href="<?php echo $rootWeb; ?>web/assets/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $rootWeb; ?>web/assets/flag-icon-css/css/flag-icon.min.css" />
    <script type="text/javascript" src="<?php echo $rootWeb; ?>web/assets/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $rootWeb; ?>web/assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="<?php echo $rootWebTheme; ?>/custompage.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900,900i&display=swap');
    </style>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div class="container">
        <div class="text-center">
            <a href="<?php echo $rootWeb; ?>">
                <img src="<?php echo $rootWebTheme; ?>/images/logo.svg" class="logo" width="300px"/>
            </a>
        </div>
        <div class="panel panel-default form-register">
            <div class="panel-body">
                <div class="padding-login">
                    <div class="message">
                        <?php
                            if (isset($content['info']) && !empty($content['info'])) {
                                echo '<div class="alert alert-info" role="alert">'.$content['info'].'</div>';
                            }

                            if (isset($error_message)) {
                                echo '<div id="login-form-info" class="alert alert-danger" role="alert">'.$error_message.'</div>';
                            }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title-container">
                                <h2 class="section-title"><?php echo custompages_get_lang('newUserRegistration'); ?></h2>
                            </div>
                            <div class="alert alert-info text-register">
                                <?php echo custompages_get_lang('haveAccount'); ?> <a href="<?php echo $rootWeb; ?>"><?php echo custompages_get_lang('loginSession'); ?> </a> ó <a href="<?php echo $rootWeb; ?>main/auth/lostPassword.php?language=spanish2"><?php echo custompages_get_lang('forgetPassword'); ?></a>
                            </div>

                            <div id="msg-error-run" style="display: none;" class="alert alert-danger">
                               <?php echo custompages_get_lang('errorRUT'); ?>
                            </div>

                            <?php
                                $content['form']->display();
                            ?>
                            <div class="custom_required">
                                <?php echo custompages_get_lang('certificateApproval'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /container -->
<script type="text/javascript">

    $(function() {
        let Rut = $("#extra_rol_unico_tributario");
        let RutValue = null;
        let checkRut = true;

        $("input[value='Chile']").prop('checked', true);
        Rut.attr('placeholder','Ej: 11222333-K');
        //RUT.attr('pattern', '^[0-9]{8,9}[-|‐]{1}[0-9kK]{1}$');

        $("input[type=radio]").change(function () {
            checkRut = isCountryForRut($(this));
            //console.log(checkRut);
        });

        function isCountryForRut(input){
            let Country = input.val().toLowerCase();
            let isRut = true;
            if(Country==='otro-pais'){
                $("#form_extra_rol_unico_tributario_group").hide();
                Rut.val('');
                isRut = false;
            }else{
                $("#form_extra_rol_unico_tributario_group").show();
                isRut = true;
            }
            return isRut;
        }

        $("#registration").submit(function(e){
            //console.log(RUT.val());
            RutValue = Rut.val();
            //alert($("input[type=radio]:checked").val());
            console.log(checkRut);
            if(checkRut){
                if(!(RutValue.match('^[0-9]{8,9}[-|‐]{1}[0-9kK]{1}$'))){
                    $("#msg-error-run").show();
                    e.preventDefault();
                }
            }
        });
    });

</script>
</body>
</html>
