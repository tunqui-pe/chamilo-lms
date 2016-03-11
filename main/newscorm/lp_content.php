<?php
/* For licensing terms, see /license.txt */

/**
 * Script that displays an error message when no content could be loaded
 * @package chamilo.learnpath
 * @author Yannick Warnier <ywarnier@beeznest.org>
 */

//require_once '../inc/global.inc.php';
$learnPath = learnpath::getCurrentLpFromSession();
$debug = 0;
if ($debug > 0) {
    error_log('New lp - In lp_content.php', 0);
}
if (empty($lp_controller_touched)) {
    if ($debug > 0) {
        error_log('New lp - In lp_content.php - Redirecting to lp_controller', 0);
    }
    header('location: lp_controller.php?action=content&lp_id='.Security::remove_XSS($_REQUEST['lp_id']).'&item_id='.Security::remove_XSS($_REQUEST['item_id']).'&'.api_get_cidreq());
    exit;
}
$learnPath->error = '';
$lp_type = $learnPath->get_type();
$lp_item_id = $learnPath->get_current_item_id();

/**
 * Get a link to the corresponding document
 */
$src = '';
if ($debug > 0) {
    error_log('New lp - In lp_content.php - Looking for file url', 0);
}

$list = $learnPath->get_toc();

$dokeos_chapter = false;

foreach ($list as $toc) {
    if ($toc['id'] == $lp_item_id && ($toc['type'] == 'dokeos_chapter' || $toc['type'] == 'dokeos_module' || $toc['type'] == 'dir')) {
        $dokeos_chapter = true;
    }
}

if ($dokeos_chapter) {
    $src = 'blank.php';
} else {
    switch ($lp_type) {
        case 1:
            $learnPath->stop_previous_item();
            $prereq_check = $learnPath->prerequisites_match($lp_item_id);

            if ($prereq_check === true) {
                $src = $learnPath->get_link('http', $lp_item_id);
                $learnPath->start_current_item(); // starts time counter manually if asset
                $src = $learnPath->fixBlockedLinks($src);

                break;
            }

            $src = 'blank.php?error=prerequisites';
            break;
        case 2:
            $learnPath->stop_previous_item();
            $prereq_check = $learnPath->prerequisites_match($lp_item_id);
            if ($prereq_check === true) {
                $src = $learnPath->get_link('http', $lp_item_id);
                $learnPath->start_current_item(
                ); // starts time counter manually if asset
            } else {
                $src = 'blank.php?error=prerequisites';
            }
            break;
        case 3:
            // save old if asset
            $learnPath->stop_previous_item(); // save status manually if asset
            $prereq_check = $learnPath->prerequisites_match($lp_item_id);
            if ($prereq_check === true) {
                $src = $learnPath->get_link('http', $lp_item_id);
                $learnPath->start_current_item(
                ); // starts time counter manually if asset
            } else {
                $src = 'blank.php';
            }
            break;
        case 4:
            break;
    }
}

if ($debug > 0) {
    error_log('New lp - In lp_content.php - File url is '.$src, 0);
}
$learnPath->set_previous_item($lp_item_id);

if (api_is_in_gradebook()) {
    $interbreadcrumb[]= array(
        'url' => api_get_path(WEB_CODE_PATH).'gradebook/index.php?'.api_get_cidreq(),
        'name' => get_lang('ToolGradebook')
    );
}

// Define the 'doc.inc.php' as language file.
$nameTools = $learnPath->get_name();
$interbreadcrumb[] = array(
    'url' => './lp_list.php?'.api_get_cidreq(),
    'name' => get_lang('Doc'),
);
// Update global setting to avoid displaying right menu.
$save_setting = api_get_setting('course.show_navigation_menu');
global $_setting;
$_setting['show_navigation_menu'] = false;
if ($debug > 0) {
    error_log('New LP - In lp_content.php - Loading '.$src, 0);
}
header("Location: ".urldecode($src));
exit;
