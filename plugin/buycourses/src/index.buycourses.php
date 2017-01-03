<?php
/* For license terms, see /license.txt */

/**
 * Index of the Buy Courses plugin courses list
 * @package chamilo.plugin.buycourses
 */

$plugin = BuyCoursesPlugin::create();
$guess_enable = $plugin->get('unregistered_users_enable');
$userInfo = api_get_user_info();

if ($guess_enable == 'true' || isset($userInfo)) {
    // If the user is NOT an administrator, redirect it to course/session buy list
    if (!api_is_platform_admin()) {
        header('Location: src/course_panel.php');
        exit;
    }

    $htmlHeadXtra[] = api_get_css('plugins/buycourses/css/style.css');
    $tpl = new Template();
    $content = $tpl->fetch('@plugin/buycourses/view/index.html.twig');
    $tpl->assign('content', $content);
    $tpl->display_one_col_template();
}
