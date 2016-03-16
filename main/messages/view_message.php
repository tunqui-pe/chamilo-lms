<?php
/* For licensing terms, see /license.txt */

use Chamilo\CoreBundle\Framework\Container;

/**
*	@package chamilo.messages
*/
$cidReset= true;
api_block_anonymous_users();
if (api_get_setting('message.allow_message_tool') != 'true') {
	api_not_allowed();
}

if (isset($_REQUEST['f']) && $_REQUEST['f'] == 'social') {
	$this_section = SECTION_SOCIAL;
	$interbreadcrumb[]= array ('url' => api_get_path(WEB_PATH).'main/social/home.php','name' => get_lang('Social'));
	$interbreadcrumb[]= array ('url' => 'inbox.php?f=social','name' => get_lang('Inbox'));
} else {
	$this_section = SECTION_MYPROFILE;
	$interbreadcrumb[]= array ('url' => Container::getRouter()->generate('fos_user_profile_edit'),'name' => get_lang('Profile'));
}

$social_right_content = null;

if (isset($_GET['f']) && $_GET['f']=='social') {
	$social_parameter = '?f=social';
} else {
	if (api_get_setting('profile.extended_profile') == 'true') {
		$social_right_content .= '<div class="actions">';

        if (api_get_setting(
                'social.allow_social_tool'
            ) == 'true' && api_get_setting(
                'message.allow_message_tool'
            ) == 'true'
        ) {
			$social_right_content .= '<a href="'.api_get_path(WEB_PATH).'main/social/profile.php">'.
                Display::return_icon('shared_profile.png', get_lang('ViewSharedProfile')).'</a>';
		}
        if (api_get_setting('message.allow_message_tool') == 'true') {
		    $social_right_content .= '<a href="'.api_get_path(WEB_PATH).'main/messages/new_message.php">'.
                Display::return_icon('message_new.png',get_lang('ComposeMessage')).'</a>';
            $social_right_content .= '<a href="'.api_get_path(WEB_PATH).'main/messages/inbox.php">'.
                Display::return_icon('inbox.png',get_lang('Inbox')).'</a>';
            $social_right_content .= '<a href="'.api_get_path(WEB_PATH).'main/messages/outbox.php">'.
                Display::return_icon('outbox.png',get_lang('Outbox')).'</a>';
		}
		$social_right_content .= '</div>';
	}
}

if (empty($_GET['id'])) {
    $id_message = $_GET['id_send'];
    $source = 'outbox';
    $show_menu = 'messages_outbox';
} else {
    $id_message = $_GET['id'];
    $source = 'inbox';
    $show_menu = 'messages_inbox';
}

$message  = '';

// LEFT COLUMN
if (api_get_setting('social.allow_social_tool') == 'true') {
    //Block Social Menu
    $social_menu_block = SocialManager::show_social_menu($show_menu);
}
//MAIN CONTENT
$message .= MessageManager::show_message_box($id_message, $source);

if (!empty($message)) {
    $social_right_content .= $message;
} else {
    api_not_allowed();
}
//$tpl = new Template(get_lang('View'));

$tpl = \Chamilo\CoreBundle\Framework\Container::getTwig();
// Block Social Avatar
SocialManager::setSocialUserBlock($tpl, api_get_user_id(), $show_menu);
if (api_get_setting('social.allow_social_tool') == 'true') {
    $tpl->addGlobal('social_menu_block', $social_menu_block);
    $tpl->addGlobal('social_right_content', $social_right_content);
    echo $tpl->render('@template_style/social/inbox.html.twig');
} else {
    $content = $social_right_content;
    echo $actions;
    echo $content;
}


