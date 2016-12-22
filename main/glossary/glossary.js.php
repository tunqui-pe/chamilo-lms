<?php
/* For licensing terms, see /license.txt */

require_once __DIR__.'/../inc/global.inc.php';

$tpl = new Template('', false);

$templateName = 'glossary/glossary_auto.html.twig';
if (api_get_setting('show_glossary_in_documents') == 'ismanual') {
    $templateName = 'glossary/glossary_manual.html.twig';
}

$addReady = isset($_GET['add_ready']) ? true : false;
$tpl->assign('add_ready', $addReady);
$contentTemplate = $tpl->get_template($templateName);
header('Content-type: application/x-javascript');
$tpl->display($contentTemplate);
