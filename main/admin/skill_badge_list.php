<?php
/* For licensing terms, see /license.txt */

use ChamiloSession as Session;
use Chamilo\CoreBundle\Framework\Container;

/**
 * Show information about Mozilla OpenBadges
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 * @package chamilo.admin.openbadges
 */

$cidReset = true;

//require_once '../inc/global.inc.php';

if (!api_is_platform_admin() ||
    api_get_setting('skill.allow_skills_tool') !== 'true'
) {
    api_not_allowed(true);
}

$this_section = SECTION_PLATFORM_ADMIN;

$objSkill = new Skill();
$skills = $objSkill->get_all();

$interbreadcrumb = array(
    array(
        'url' => api_get_path(WEB_CODE_PATH) . 'admin/index.php',
        'name' => get_lang('Administration')
    ),
    array(
        'url' => api_get_path(WEB_CODE_PATH) . 'admin/skill_badge.php',
        'name' => get_lang('Badges')
    )
);

$toolbar = Display::toolbarButton(
    get_lang('ManageSkills'),
    api_get_path(WEB_CODE_PATH) . 'admin/skill_list.php',
    'list',
    'primary',
    ['title' => get_lang('ManageSkills')]
);

//$tpl = new Template(get_lang('Skills'));


echo $toolbar;
echo Container::getTemplating()->render(
    '@template_style/skill/badge_list.html.twig',
    [
        'skills' => $skills
    ]
);

