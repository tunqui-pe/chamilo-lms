<?php
/* For licensing terms, see /license.txt */

use ChamiloSession as Session;
use Chamilo\CoreBundle\Framework\Container;

/**
 * Skill list for management
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 * @package chamilo.admin
 */

$cidReset = true;

//require_once '../inc/global.inc.php';

$this_section = SECTION_PLATFORM_ADMIN;

api_protect_admin_script();

if (api_get_setting('skill.allow_skills_tool') != 'true') {
    api_not_allowed();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$skillId = isset($_GET['id']) ? intval($_GET['id']): 0;
$view = isset($_GET['view']) ? $_GET['view'] : 'list';

$entityManager = Database::getManager();

switch ($action) {
    case 'enable':
        $skill = $entityManager->find('ChamiloCoreBundle:Skill', $skillId);

        if (is_null($skill)) {
            Display::addFlash(
                Display::return_message(
                    get_lang('SkillNotFound'),
                    'error'
                )
            );
        } else {
            $updatedAt = new DateTime(
                api_get_utc_datetime(),
                new DateTimeZone(_api_get_timezone())
            );

            $skill->setStatus(1);
            $skill->setUpdatedAt($updatedAt);

            $entityManager->persist($skill);
            $entityManager->flush();

            Display::addFlash(
                Display::return_message(
                    sprintf(get_lang('SkillXEnabled'), $skill->getName()),
                    'success'
                )
            );
        }

        header('Location: ' . api_get_self() . '?' .  http_build_query(['view' => $view]));
        exit;
        break;
    case 'disable':
        $skill = $entityManager->find('ChamiloCoreBundle:Skill', $skillId);

        if (is_null($skill)) {
            Display::addFlash(
                Display::return_message(
                    get_lang('SkillNotFound'),
                    'error'
                )
            );
        } else {
            $updatedAt = new DateTime(
                api_get_utc_datetime(),
                new DateTimeZone(_api_get_timezone())
            );

            $skill->setStatus(0);
            $skill->setUpdatedAt($updatedAt);

            $entityManager->persist($skill);

            $skillObj = new Skill();
            $childrens = $skillObj->get_children($skill->getId());

            foreach ($childrens as $children) {
                $skill = $entityManager->find(
                    'ChamiloCoreBundle:Skill',
                    $children['id']
                );

                if (empty($skill)) {
                    continue;
                }

                $skill->setStatus(0);
                $skill->setUpdatedAt($updatedAt);

                $entityManager->persist($skill);
            }

            $entityManager->flush();

            Display::addFlash(
                Display::return_message(
                    sprintf(get_lang('SkillXDisabled'), $skill->getName()),
                    'success'
                )
            );
        }

        header('Location: ' . api_get_self() . '?' .  http_build_query(['view' => $view]));
        exit;
        break;
}

switch ($view) {
    case 'nested':
        $interbreadcrumb[] = array("url" => 'index.php', "name" => get_lang('PlatformAdmin'));

        $toolbar = Display::toolbarButton(
            get_lang('CreateSkill'),
            api_get_path(WEB_CODE_PATH) . 'admin/skill_create.php',
            'plus',
            'success',
            ['title' => get_lang('CreateSkill')]
        );
        $toolbar .= Display::toolbarButton(
            get_lang('SkillsWheel'),
            api_get_path(WEB_CODE_PATH) . 'admin/skills_wheel.php',
            'bullseye',
            'primary',
            ['title' => get_lang('CreateSkill')]
        );
        $toolbar .= Display::toolbarButton(
            get_lang('BadgesManagement'),
            api_get_path(WEB_CODE_PATH) . 'admin/skill_badge_list.php',
            'shield',
            'warning',
            ['title' => get_lang('BadgesManagement')]
        );
        $toolbar .= Display::toolbarButton(
            get_lang('FlatView'),
            api_get_path(WEB_CODE_PATH) . 'admin/skill_list.php?view=list',
            'eye',
            'info pull-right',
            ['title' => get_lang('FlatView')]
        );

        /* Nested View */
        //extra JS lib for the collapsible table
        $htmlHeadXtra[] = '<script src="'. api_get_path(WEB_PATH) .'web/assets/aCollapTable/jquery.aCollapTable.js"></script>';
        $htmlHeadXtra[] = '<script>
                            $(document).ready(function(){
                              $(".collaptable").aCollapTable({
                                startCollapsed: true,
                                addColumn: false,
                                plusButton: "<em class=\"fa fa-plus-circle \"></em>  ",
                                minusButton: "<em class=\"fa fa-minus-circle\"></em>  "
                              });
                            });
                           </script>';
        $skill = new Skill();
        //obtain all skills
        $allSkills = $skill->get_all();
        //order the skill list by a nested view array
        $skillList = $skill->get_nested_skill_view($allSkills);

        //$tpl = new Template(get_lang('ManageSkills'));
        echo $toolbar;
        echo Container::getTemplating()->render(
            '@template_style/skill/nested.html.twig',
            [
                'skills' => $skillList
            ]
        );
        break;
    case 'list':
        //no break
    default:
        $interbreadcrumb[] = array ("url" => 'index.php', "name" => get_lang('PlatformAdmin'));

        $toolbar = Display::toolbarButton(
            get_lang('CreateSkill'),
            api_get_path(WEB_CODE_PATH) . 'admin/skill_create.php',
            'plus',
            'success',
            ['title' => get_lang('CreateSkill')]
        );
        $toolbar .= Display::toolbarButton(
            get_lang('SkillsWheel'),
            api_get_path(WEB_CODE_PATH) . 'admin/skills_wheel.php',
            'bullseye',
            'primary',
            ['title' => get_lang('CreateSkill')]
        );
        $toolbar .= Display::toolbarButton(
            get_lang('BadgesManagement'),
            api_get_path(WEB_CODE_PATH) . 'admin/skill_badge_list.php',
            'shield',
            'warning',
            ['title' => get_lang('BadgesManagement')]
        );

        $toolbar .= Display::toolbarButton(
            get_lang('NestedView'),
            api_get_path(WEB_CODE_PATH) . 'admin/skill_list.php?view=nested',
            'eye',
            'info pull-right',
            ['title' => get_lang('NestedView')]
        );

        /* List View */
        $skill = new Skill();
        $skillList = $skill->get_all();

        //$tpl = new Template(get_lang('ManageSkills'));

        echo $toolbar;
        echo Container::getTemplating()->render(
            '@template_style/skill/list.html.twig',
            [
                'skills' => $skillList
            ]
        );
        break;
}
