<?php

/* For licensing terms, see /license.txt */

/**
 * @author Julio Montoya <gugli100@gmail.com>
 */
$cidReset = true;
require_once __DIR__.'/../inc/global.inc.php';
$this_section = SECTION_PLATFORM_ADMIN;

api_protect_global_admin_script();

if (!api_get_multiple_access_url()) {
    header('Location: index.php');
    exit;
}
$dir = api_get_path(SYS_PATH).'custompages/url-images/';
$dirWeb = api_get_path(WEB_PATH).'custompages/url-images/';

$urlID = isset($_GET['url_id']) ? $_GET['url_id'] : 0;
$newLogoFileBig = 'logo_big_'.$urlID.'.png';
$newLogoFileSmall = 'logo_small_'.$urlID.'.png';
$newImageFileHome = 'img_home_'.$urlID.'.png';


// Create the form
$form = new FormValidator('add_url');

if ($form->validate()) {
    $check = Security::check_token('post');
    if ($check) {
        $url_array = $form->getSubmitValues();
        $url = Security::remove_XSS($url_array['url']);
        $description = Security::remove_XSS($url_array['description']);
        $active = isset($url_array['active']) ? (int)$url_array['active'] : 0;

        $deleteBig = isset($url_array['delete_big']) ? (int)$url_array['delete_big'] : 0;
        $deleteSmall = isset($url_array['delete_small']) ? (int)$url_array['delete_small'] : 0;
        $deleteImgHome = isset($url_array['delete_img_home']) ? (int)$url_array['delete_img_home'] : 0;

        $url_id = isset($url_array['id']) ? (int)$url_array['id'] : 0;

        $newLogoFileBig = 'logo_big_'.$url_id.'.png';
        $newLogoFileSmall = 'logo_small_'.$url_id.'.png';
        $newImageFileHome = 'img_home_'.$url_id.'.png';

        $url_to_go = 'access_urls.php';
        if (!empty($url_id)) {
            //we can't change the status of the url with id=1
            if (1 == $url_id) {
                $active = 1;
            }

            // Checking url
            if (substr($url, strlen($url) - 1, strlen($url)) == '/') {
                UrlManager::update($url_id, $url, $description, $active);
            } else {
                UrlManager::update($url_id, $url.'/', $description, $active);
            }
            // URL Images

            if (isset($_FILES['url_logo_big'])) {
                $imageLogoBig = getimagesize($_FILES['url_logo_big']['tmp_name']);
            }

            if (isset($_FILES['url_logo_small'])) {
                $imageLogoSmall = getimagesize($_FILES['url_logo_small']['tmp_name']);
            }

            if (isset($_FILES['url_img_home'])) {
                $imageImgHome = getimagesize($_FILES['url_img_home']['tmp_name']);
            }

            if ($deleteBig) {
                if (is_file($dir.$newLogoFileBig)) {

                    unlink($dir.$newLogoFileBig);
                }
            }
            if ($deleteSmall) {
                if (is_file($dir.$newLogoFileSmall)) {
                    unlink($dir.$newLogoFileSmall);
                }
            }
            if ($deleteImgHome) {
                if (is_file($dir.$newImageFileHome)) {
                    unlink($dir.$newImageFileHome);
                }
            }

            if ($imageLogoBig) {
                $widthBig = $imageLogoBig[0];
                $heightBig = $imageLogoBig[1];

                if ($widthBig <= 365 && $heightBig <= 125) {

                    $status = move_uploaded_file(
                        $_FILES['url_logo_big']['tmp_name'],
                        $dir.$newLogoFileBig
                    );
                } else {
                    $error = get_lang('InvalidImageDimensions');
                }
            }

            if ($imageLogoSmall) {
                $widthSmall = $imageLogoSmall[0];
                $heightSmall = $imageLogoSmall[1];

                if ($widthSmall <= 140 && $heightSmall <= 55) {

                    $status = move_uploaded_file(
                        $_FILES['url_logo_small']['tmp_name'],
                        $dir.$newLogoFileSmall
                    );
                } else {
                    $error = get_lang('InvalidImageDimensions');
                }
            }

            if ($imageImgHome) {
                $widthHome = $imageImgHome[0];
                $heightHome = $imageImgHome[1];

                if ($widthHome <= 425 && $heightHome <= 400) {

                    $status = move_uploaded_file(
                        $_FILES['url_img_home']['tmp_name'],
                        $dir.$newImageFileHome
                    );
                } else {
                    $error = get_lang('InvalidImageDimensions');
                }
            }


            $url_to_go = 'access_urls.php';
            $message = get_lang('URLEdited');

        } else {
            $num = UrlManager::url_exist($url);
            if ($num == 0) {
                // checking url
                if (substr($url, strlen($url) - 1, strlen($url)) == '/') {
                    UrlManager::add($url, $description, $active);
                } else {
                    //create
                    UrlManager::add($url.'/', $description, $active);
                }
                $message = get_lang('URLAdded');
                $url_to_go = 'access_urls.php';
            } else {
                $url_to_go = 'access_url_edit.php';
                $message = get_lang('URLAlreadyAdded');
            }
            // URL Images
            $url .= (substr($url, strlen($url) - 1, strlen($url)) == '/') ? '' : '/';
            $url_id = UrlManager::get_url_id($url);
            $url_images_dir = api_get_path(SYS_PATH).'custompages/url-images/';


        }
        Security::clear_token();
        $tok = Security::get_token();
        Display::addFlash(Display::return_message($message));
        Display::addFlash(Display::return_message($error, 'error'));
        header('Location: '.$url_to_go.'?sec_token='.$tok);
        exit();
    }
} else {
    if (isset($_POST['submit'])) {
        Security::clear_token();
    }
    $token = Security::get_token();
    $form->addElement('hidden', 'sec_token');
    $form->setConstants(['sec_token' => $token]);
}

$form->addElement('text', 'url', 'URL');
$form->addRule('url', get_lang('ThisFieldIsRequired'), 'required');
$form->addRule('url', '', 'maxlength', 254);
$form->addHtmlEditor('description', get_lang('Description'), false, false, ['ToolbarSet' => 'Minimal']);
//$form->addElement('textarea', 'description', get_lang('Description'));

//the first url with id = 1 will be always active
if (isset($_GET['url_id']) && $_GET['url_id'] != 1) {
    $form->addElement('checkbox', 'active', null, get_lang('Active'));
}

$defaults['url'] = 'http://';
$form->setDefaults($defaults);

$submit_name = get_lang('AddUrl');
if (isset($_GET['url_id'])) {
    $url_id = (int)$_GET['url_id'];
    $num_url_id = UrlManager::url_id_exist($url_id);
    if ($num_url_id != 1) {
        header('Location: access_urls.php');
        exit();
    }

    $allowedFileTypes = ['png', 'jpg'];
    //Logo Big

    if (file_exists($dir.$newLogoFileBig)) {
        $html = '<div class="form-group"><label class="col-md-2 control-label">'.get_lang('LogoUrlBig').'</label>';
        $html .= '<div class="col-md-10"><img width="200px" src="'.$dirWeb.$newLogoFileBig.'"></div></div>';
        $form->addHtml($html);
        $form->addElement('checkbox', 'delete_big', null, get_lang('DeleteAttachment'));
    } else {

        $form->addFile(
            'url_logo_big', [
                get_lang('LogoUrlBig'),
                get_lang('LogoUrlBigHelp'),
            ]
        );

        $form->addRule(
            'url_logo_big',
            get_lang('InvalidExtension').' ('.implode(',', $allowedFileTypes).')',
            'filetype',
            $allowedFileTypes
        );
    }

// Logo Small

    if (file_exists($dir.$newLogoFileSmall)) {
        $html = '<div class="form-group"><label class="col-md-2 control-label">'.get_lang('LogoUrlSmall').'</label>';
        $html .= '<div class="col-md-10"><img width="120px" src="'.$dirWeb.$newLogoFileSmall.'"></div></div>';
        $form->addHtml($html);
        $form->addElement('checkbox', 'delete_small', null, get_lang('DeleteAttachment'));
    } else {
        $form->addFile(
            'url_logo_small',
            [
                get_lang('LogoUrlSmall'),
                get_lang('LogoUrlSmallHelp'),
            ]
        );
        $form->addRule(
            'url_logo_small',
            get_lang('InvalidExtension').' ('.implode(',', $allowedFileTypes).')',
            'filetype',
            $allowedFileTypes
        );
    }

// Image Login Home

    if (file_exists($dir.$newImageFileHome)) {
        $html = '<div class="form-group"><label class="col-md-2 control-label">'.get_lang('LogoImageHome').'</label>';
        $html .= '<div class="col-md-10"><img width="200px" src="'.$dirWeb.$newImageFileHome.'"></div></div>';
        $form->addHtml($html);
        $form->addElement('checkbox', 'delete_img_home', null, get_lang('DeleteAttachment'));
    } else {
        $form->addFile(
            'url_img_home',
            [
                get_lang('LogoImageHome'),
                get_lang('LogoImageHomeHelp'),
            ]
        );

        $form->addRule(
            'url_img_home',
            get_lang('InvalidExtension').' ('.implode(',', $allowedFileTypes).')',
            'filetype',
            $allowedFileTypes
        );
    }

    $url_data = UrlManager::get_url_data_from_id($url_id);
    $form->addElement('hidden', 'id', $url_data['id']);
    $form->setDefaults($url_data);

    $submit_name = get_lang('Save');
}

if (!api_is_multiple_url_enabled()) {
    header('Location: index.php');
    exit;
}

$tool_name = get_lang('AddUrl');
$interbreadcrumb[] = ['url' => 'index.php', 'name' => get_lang('PlatformAdmin')];
$interbreadcrumb[] = ['url' => 'access_urls.php', 'name' => get_lang('MultipleAccessURLs')];

Display:: display_header($tool_name);

// URL Images


// Submit button
$form->addButtonCreate($submit_name);
$form->display();

Display::display_footer();
