<?php
/* For licensing terms, see /license.txt */

use Chamilo\CoreBundle\Framework\Container;

/**
 *  @package chamilo.admin
 */
$cidReset = true;

//require_once '../inc/global.inc.php';

$this_section = SECTION_SOCIAL;

if (api_get_setting('skill.allow_skills_tool') != 'true') {
    api_not_allowed();
}

api_block_anonymous_users();

//Adds the JS needed to use the jqgrid
$htmlHeadXtra[] = api_get_js('js/d3/d3.v3.5.4.min.js');
$htmlHeadXtra[] = api_get_js('js/d3/colorbrewer.js');
$htmlHeadXtra[] = api_get_js('js/d3/jquery.xcolor.js');

$userId = api_get_user_id();
$userInfo = api_get_user_info();

$skill = new Skill();
$ranking = $skill->get_user_skill_ranking($userId);
$skills = $skill->get_user_skills($userId, true);

$dialogForm = new FormValidator('form', 'post', null, null, ['id' => 'add_item']);
$dialogForm->addLabel(
    get_lang('Name'),
    Display::tag('p', null, ['id' => 'name', 'class' => 'form-control-static'])
);
$dialogForm->addLabel(
    get_lang('ShortCode'),
    Display::tag('p', null, ['id' => 'short_code', 'class' => 'form-control-static'])
);
$dialogForm->addLabel(
    get_lang('Parent'),
    Display::tag('p', null, ['id' => 'parent', 'class' => 'form-control-static'])
);
$dialogForm->addLabel(
    [get_lang('Gradebook'), get_lang('WithCertificate')],
    Display::tag('ul', null, ['id' => 'gradebook', 'class' => 'form-control-static list-unstyled'])
);
$dialogForm->addLabel(
    get_lang('Description'),
    Display::tag('p', null, ['id' => 'description', 'class' => 'form-control-static'])
);

$wheelUrl = api_get_path(WEB_AJAX_PATH)."skill.ajax.php?a=get_skills_tree_json&load_user=$userId";
$url  = api_get_path(WEB_AJAX_PATH).'skill.ajax.php?1=1';

echo Container::getTemplating()->render(
    '@template_style/skill/skill_wheel_student.html.twig',
    [
        'skill_id_to_load' => 0,
        'url' => $url,
        'wheel_url' => $wheelUrl,
        'dialogForm' => $dialogForm->returnForm(),
        'user_info' => $userInfo,
        'ranking' => $ranking,
        'skills' => $skills
    ]
);
