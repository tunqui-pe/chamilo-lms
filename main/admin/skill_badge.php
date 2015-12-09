<?php
/* For licensing terms, see /license.txt */

use Chamilo\CoreBundle\Framework\Container;

/**
 * Show information about Mozilla OpenBadges
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 * @package chamilo.admin.openbadges
 */
$cidReset = true;

//require_once '../inc/global.inc.php';

$this_section = SECTION_PLATFORM_ADMIN;

if (!api_is_platform_admin() || api_get_setting(
        'skill.allow_skills_tool'
    ) !== 'true'
) {
    api_not_allowed(true);
}
$backpack = 'https://backpack.openbadges.org/';

$configBackpack = api_get_setting('gradebook.openbadges_backpack');
if (strcmp($backpack, $configBackpack) !== 0) {
    $backpack = $configBackpack;
}

$interbreadcrumb = array(
    array(
        'url' => api_get_path(WEB_CODE_PATH) . 'admin/index.php',
        'name' => get_lang('Administration')
    )
);

$interbreadcrumb[] =
    array(
        'url' => '#',
        'name' => get_lang('Badges')
    )
;

$toolbar = Display::toolbarButton(
    get_lang('ManageSkills'),
    api_get_path(WEB_CODE_PATH) . 'admin/skill_list.php',
    'list',
    'primary',
    ['title' => get_lang('ManageSkills')]
);

//$tpl = new Template(get_lang('Badges'));

echo $toolbar;
echo Container::getTemplating()->render(
    '@template_style/skill/badge.html.twig',
    [
        'backpack' => $backpack
    ]
);

