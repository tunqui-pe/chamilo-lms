<?php
/* For licensing terms, see /license.txt */

use Chamilo\CoreBundle\Framework\Container;

api_protect_course_script(true);
api_block_anonymous_users();

if (!api_is_allowed_to_edit()) {
    api_not_allowed(true);
}

$course_info = api_get_course_info();

$directory  = $course_info['directory'];
$title      = $course_info['title'];

// Preparing a confirmation message.
$link = api_get_path(WEB_COURSE_PATH).$directory.'/';

$tpl = Container::getTwig();

$tpl->addGlobal('course_url', $link);
$tpl->addGlobal('course_title', Display::url($title, $link));
$tpl->addGlobal('course_id', $course_info['code']);
$tpl->addGlobal('just_created', isset($_GET['first']) && $_GET['first'] ? 1 : 0);

echo $tpl->render('ChamiloCoreBundle:default/create_course:add_course.html.twig');
