<?php
/* For licensing terms, see /license.txt */

use Chamilo\CoreBundle\Framework\Container;

$tpl = \Chamilo\CoreBundle\Framework\Container::getTwig();

$templateName = 'glossary/glossary_auto.js.twig';
if (api_get_setting('document.show_glossary_in_documents') == 'ismanual') {
    $templateName = 'glossary/glossary_manual.js.twig';
}

$addReady = isset($_GET['add_ready']) ? true : false;
$tpl->addGlobal('add_ready', $addReady);
echo $tpl->render('@template_style/'.$templateName);

// Hide headers
Container::$legacyTemplate = 'layout_empty.html.twig';
