<?php
/* For licensing terms, see /license.txt */

use Chamilo\CoreBundle\Framework\Container;

/**
 * Show information about OpenBadge criteria
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 * @package chamilo.badge
 */
//require_once '../inc/global.inc.php';

$entityManager = Database::getManager();
$skill = $entityManager->find('ChamiloCoreBundle:Skill', $_GET['id']);

if (!$skill) {
    Display::addFlash(
        Display::return_message(get_lang('SkillNotFound'), 'error')
    );

    header('Location: ' . api_get_path(WEB_PATH));
    exit;
}

$skillInfo = [
    'name' => $skill->getName(),
    'short_code' => $skill->getShortCode(),
    'description' => $skill->getDescription(),
    'criteria' => $skill->getCriteria(),
    'badge_image' => $skill->getWebIconPath()
];

echo Container::getTemplating()->render(
    '@template_style/skill/criteria.html.twig',
    [
        'skill_info' => $skillInfo
    ]
);
