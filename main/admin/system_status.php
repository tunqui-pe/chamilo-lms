<?php
/* For licensing terms, see /license.txt */

use Chamilo\CoreBundle\Framework\Container;

/**
*	@package chamilo.admin
* 	@author Julio Montoya <gugli100@gmail.com>
*/
$cidReset = true;
$this_section = SECTION_PLATFORM_ADMIN;
// User permissions
api_protect_admin_script();
$interbreadcrumb[] = array('url' => Container::getRouter()->generate('administration'), 'name' => get_lang('PlatformAdmin'));
Display :: display_header(get_lang('SystemStatus'));
$diag = new Diagnoser();
$diag->show_html();
Display :: display_footer();
