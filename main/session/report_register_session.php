<?php

use Chamilo\CoreBundle\Entity\Course;
use Chamilo\CoreBundle\Entity\Session;
use Chamilo\CoreBundle\Entity\SessionCategory;
use Chamilo\CoreBundle\Entity\SessionRelCourseRelUser;

/**
 * @author  Bart Mollet, Julio Montoya lot of fixes
 *
 * @package chamilo.admin
 */
$cidReset = true;
require_once __DIR__.'/../inc/global.inc.php';

// setting the section (for the tabs)
$this_section = SECTION_PLATFORM_ADMIN;

$codePath = api_get_path(WEB_CODE_PATH);
$tool_name = get_lang('SessionOverview');

$tbl_session = Database::get_main_table(TABLE_MAIN_SESSION);
$tbl_sessioncategory = Database::get_main_table(TABLE_MAIN_SESSION_CATEGORY);
$em = Database::getManager();

$sessionCategoryRepository = $em->getRepository('ChamiloCoreBundle:SessionCategory');
$sessionCategory = $sessionCategoryRepository->findAll();

var_dump($sessionCategory);

$sessionRepository = $em->getRepository('ChamiloCoreBundle:Session');
$sessions = $sessionRepository->findAll();

foreach ($sessions as $session){

}

$tpl = new Template($tool_name);
$layout = $tpl->get_template('session/report_register_session.tpl');
$tpl->display($layout);
