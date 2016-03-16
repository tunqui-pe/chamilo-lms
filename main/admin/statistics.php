<?php
/* For licensing terms, see /license.txt */

use Chamilo\CoreBundle\Framework\Container;

/**
*   @package chamilo.admin
*/

$cidReset = true;
$this_section = SECTION_PLATFORM_ADMIN;

api_protect_admin_script();

$interbreadcrumb[] = array('url' => Container::getRouter()->generate('administration'), "name" => get_lang('PlatformAdmin'));
$tool_name = get_lang('Statistics');
Display::display_header($tool_name);
Display::display_footer();
