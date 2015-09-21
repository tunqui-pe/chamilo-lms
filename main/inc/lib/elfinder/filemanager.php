<?php
/* For licensing terms, see /license.txt */

require_once '../../global.inc.php';

Chat::set_disable_chat(true);
$template = new Template();
$template->display('default/javascript/editor/ckeditor/elfinder.tpl');
