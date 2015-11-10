<?php
/* For licensing terms, see /license.txt */
/**
 * Code
 * @todo use globals or parameters or add this file in the template
 * @package chamilo.include
 */

/**
 * Determines the possible tabs (=sections) that are available.
 * This function is used when creating the tabs in the third header line and
 * all the sections that do not appear there (as determined by the
 * platform admin on the Dokeos configuration settings page)
 * will appear in the right hand menu that appears on several other pages
 * @return array containing all the possible tabs
 * @author Patrick Cool <patrick.cool@UGent.be>, Ghent University
 */
function get_tabs($courseId = null)
{
    $_course = api_get_course_info($courseId);

    $navigation = array();

    // Campus Homepage
    $navigation[SECTION_CAMPUS]['url'] = api_get_path(WEB_PATH).'index.php';
    $navigation[SECTION_CAMPUS]['title'] = get_lang('CampusHomepage');
    $navigation[SECTION_CAMPUS]['key'] = 'homepage';

    // My Courses

    if (api_is_allowed_to_create_course()) {
        // Link to my courses for teachers
        $navigation['mycourses']['url'] = api_get_path(WEB_PATH).'user_portal.php?nosession=true';
    } else {
        // Link to my courses for students
        $navigation['mycourses']['url'] = api_get_path(WEB_PATH).'user_portal.php';
    }
    $navigation['mycourses']['title'] = get_lang('MyCourses');
    $navigation['mycourses']['key'] = 'my-course';

    // My Profile
    $navigation['myprofile']['url'] = api_get_path(WEB_CODE_PATH).'auth/profile.php'.(!empty($_course['path']) ? '?coursePath='.$_course['path'].'&amp;courseCode='.$_course['official_code'] : '' );
    $navigation['myprofile']['title'] = get_lang('ModifyProfile');
    $navigation['myprofile']['key'] = 'profile';
	// Link to my agenda
    $navigation['myagenda']['url'] = api_get_path(WEB_CODE_PATH).'calendar/agenda_js.php?type=personal';
    $navigation['myagenda']['title'] = get_lang('MyAgenda');
    $navigation['myagenda']['key'] = 'agenda';

	// Gradebook
	if (api_get_setting('gradebook_enable') == 'true') {
        $navigation['mygradebook']['url'] = api_get_path(WEB_CODE_PATH).'gradebook/gradebook.php'.(!empty($_course['path']) ? '?coursePath='.$_course['path'].'&amp;courseCode='.$_course['official_code'] : '' );
        $navigation['mygradebook']['title'] = get_lang('MyGradebook');
        $navigation['mygradebook']['key'] = 'gradebook';
	}

	// Reporting
	if (api_is_allowed_to_create_course() || api_is_drh() || api_is_session_admin()) {
        // Link to my space
        $navigation['session_my_space']['url'] = api_get_path(WEB_CODE_PATH).'mySpace/'.(api_is_drh()?'session.php':'');
        $navigation['session_my_space']['title'] = get_lang('MySpace');
        $navigation['session_my_space']['key'] = 'my-space';
    } else if (api_is_student_boss()) {
        $navigation['session_my_space']['url'] = api_get_path(WEB_CODE_PATH) . 'mySpace/student.php';
        $navigation['session_my_space']['title'] = get_lang('MySpace');
        $navigation['session_my_space']['key'] = 'my-space';
    } else {
        $navigation['session_my_progress']['url'] = api_get_path(WEB_CODE_PATH);
            // Link to my progress
        switch (api_get_setting('platform.gamification_mode')) {
            case 1:
                $navigation['session_my_progress']['url'] .= 'gamification/my_progress.php';
                break;
            default:
                $navigation['session_my_progress']['url'] .= 'auth/my_progress.php';
        }

        $navigation['session_my_progress']['title'] = get_lang('MyProgress');
        $navigation['session_my_progress']['key'] = 'my-progress';
    }

	// Social
    if (api_get_setting('social.allow_social_tool') == 'true') {
        $navigation['social']['url'] = api_get_path(WEB_CODE_PATH).'social/home.php';
        $navigation['social']['title'] = get_lang('SocialNetwork');
        $navigation['social']['key'] = 'social-network';
	}

	// Dashboard
	if (api_is_platform_admin() || api_is_drh() || api_is_session_admin()) {
        $navigation['dashboard']['url'] = api_get_path(WEB_CODE_PATH).'dashboard/index.php';
        $navigation['dashboard']['title'] = get_lang('Dashboard');
        $navigation['dashboard']['key'] = 'dashboard';
	}

	// Reports
    /*
	if (api_is_platform_admin() || api_is_drh() || api_is_session_admin()) {
        $navigation['reports']['url'] = api_get_path(WEB_CODE_PATH).'reports/index.php';
        $navigation['reports']['title'] = get_lang('Reports');
	}*/

    // Custom Tabs See BT#7180
    $customTabs = getCustomTabs();
    if (!empty($customTabs)) {
        foreach ($customTabs as $tab) {
            if (api_get_setting($tab['variable'], $tab['subkey']) == 'true') {
                if (!empty($tab['comment']) && $tab['comment'] !== 'ShowTabsComment') {
                    $navigation[$tab['subkey']]['url'] = $tab['comment'];
                    // $tab['title'] value must be included in trad4all.inc.php
                    $navigation[$tab['subkey']]['title'] = get_lang($tab['title']);
                    $navigation[$tab['subkey']]['key'] = $tab['subkey'];
                }
            }
        }
    }
    // End Custom Tabs

	// Platform administration
	if (api_is_platform_admin(true)) {
        $navigation['platform_admin']['url'] = api_get_path(WEB_CODE_PATH).'admin/';
        $navigation['platform_admin']['title'] = get_lang('PlatformAdmin');
        $navigation['platform_admin']['key'] = 'admin';
	}

	return $navigation;
}

/**
 * This function returns the custom tabs
 *
 * @return array
 */
function getCustomTabs()
{
    $tableSettingsCurrent = Database::get_main_table(TABLE_MAIN_SETTINGS_CURRENT);
    $sql = "SELECT * FROM $tableSettingsCurrent
        WHERE variable = 'show_tabs' AND
        subkey like 'custom_tab_%'";
    $result = Database::query($sql);
    $customTabs = array();

    while ($row = Database::fetch_assoc($result)) {
        $customTabs[] = $row;
    }

    return $customTabs;
}

function return_logo($theme)
{
    $_course = api_get_course_info();
    $html = '';
    $logoBase = api_get_path(SYS_CSS_PATH).'themes/'.$theme.'/images/header-logo.';

    $site_name = api_get_setting('platform.site_name');
    $attributes = array(
        'title' => $site_name,
        'class' => 'img-responsive',
    );
    $testServer = api_get_setting('server_type');
    if ($testServer == 'test' && is_file($logoBase . 'svg')) {
        $logo = $logoBase . 'svg';
        $attributes['width'] = '245';
        $attributes['height'] = '68';
        $image_url = api_get_path(WEB_CSS_PATH).'themes/'.$theme.'/images/header-logo.svg';
    } else {
        $logo = $logoBase . 'png';
        $image_url = api_get_path(WEB_CSS_PATH).'themes/'.$theme.'/images/header-logo.png';
    }

    if (file_exists($logo)) {
        $site_name = api_get_setting('platform.institution').' - '.$site_name;
        $logo = Display::img(
            $image_url,
            $site_name,
            $attributes
        );
        $html .= Display::url($logo, api_get_path(WEB_PATH).'index.php');
    } else {
        $html .= '<a href="'.api_get_path(WEB_PATH).'index.php" target="_top">'.$site_name.'</a>';
        $iurl = api_get_setting('platform.institution_url');
        $iname = api_get_setting('platform.institution');

        if (!empty($iname)) {
            $html .= '-&nbsp;<a href="'.$iurl.'" target="_top">'.$iname.'</a>';
        }

        // External link section a.k.a Department - Department URL
        if (isset($_course['extLink']) && $_course['extLink']['name'] != '') {
            $html .= '<span class="extLinkSeparator"> - </span>';
            if ($_course['extLink']['url'] != '') {
                $html .= '<a class="extLink" href="'.$_course['extLink']['url'].'" target="_top">';
                $html .= $_course['extLink']['name'];
                $html .= '</a>';
            } else {
                $html .= $_course['extLink']['name'];
            }
        }
    }

    return $html;
}

function return_notification_menu()
{
    $_course = api_get_course_info();
    $course_id = 0;
    if (!empty($_course)) {
        $course_id  = $_course['code'];
    }

    $user_id = api_get_user_id();

    $html = '';

    if ((api_get_setting('showonline', 'world') == 'true' && !$user_id) ||
        (api_get_setting('showonline', 'users') == 'true' && $user_id) ||
        (api_get_setting('showonline', 'course') == 'true' && $user_id && $course_id)
    ) {
        $number = who_is_online_count(
            api_get_setting('display.time_limit_whosonline')
        );

        $number_online_in_course = 0;
        if (!empty($_course['id'])) {
            $number_online_in_course = who_is_online_in_this_course_count(
                $user_id,
                api_get_setting('display.time_limit_whosonline'),
                $_course['id']
            );
        }

        // Display the who's online of the platform
        if ($number) {
            if ((api_get_setting('showonline', 'world') == 'true' && !$user_id) ||
                (api_get_setting('showonline', 'users') == 'true' && $user_id)
            ) {
                $html .= '<li><a href="'.api_get_path(WEB_PATH).'whoisonline.php" target="_self" title="'.get_lang('UsersOnline').'" >'.
                            Display::return_icon('user.png', get_lang('UsersOnline'), array(), ICON_SIZE_TINY).' '.$number.'</a></li>';
            }
        }

        // Display the who's online for the course
        if ($number_online_in_course) {
            if (is_array($_course) &&
                api_get_setting('showonline', 'course') == 'true' &&
                isset($_course['sysCode'])
            ) {
                $html .= '<li><a href="'.api_get_path(WEB_PATH).'whoisonline.php?cidReq='.$_course['sysCode'].'" target="_self">'.
                        Display::return_icon('course.png', get_lang('UsersOnline').' '.get_lang('InThisCourse'), array(), ICON_SIZE_TINY).' '.$number_online_in_course.' </a></li>';
            }
        }

        //if (api_get_setting('showonline', 'session') == 'true') {

            // Display the who's online for the session
            if (isset($user_id) && api_get_session_id() != 0) {
                $html .= '<li><a href="'.api_get_path(WEB_PATH).'whoisonlinesession.php?id_coach='.$user_id.'&amp;referer='.urlencode($_SERVER['REQUEST_URI']).'" target="_self">'.
                        Display::return_icon('session.png', get_lang('UsersConnectedToMySessions'), array(), ICON_SIZE_TINY).' </a></li>';
            }
        //}
    }

    return $html;
}

function return_navigation_array()
{
    $navigation = array();
    $menu_navigation = array();
    $possible_tabs = get_tabs();

    // Campus Homepage
    if (api_get_setting('show_tabs', 'campus_homepage') == 'true') {
        $navigation[SECTION_CAMPUS] = $possible_tabs[SECTION_CAMPUS];
    } else {
        $menu_navigation[SECTION_CAMPUS] = $possible_tabs[SECTION_CAMPUS];
    }

    if (api_get_user_id() && !api_is_anonymous()) {
        // My Courses
        if (api_get_setting('show_tabs', 'my_courses') == 'true') {
            $navigation['mycourses'] = $possible_tabs['mycourses'];
        } else {
            $menu_navigation['mycourses'] = $possible_tabs['mycourses'];
        }

        // My Profile
        if (api_get_setting('show_tabs', 'my_profile') == 'true' &&
            api_get_setting('social.allow_social_tool') != 'true'
        ) {
            $navigation['myprofile'] = $possible_tabs['myprofile'];
        } else {
            $menu_navigation['myprofile'] = $possible_tabs['myprofile'];
        }

        // My Agenda
        if (api_get_setting('show_tabs', 'my_agenda') == 'true') {
            $navigation['myagenda'] = $possible_tabs['myagenda'];
        } else {
            $menu_navigation['myagenda'] = $possible_tabs['myagenda'];
        }

        // Gradebook
        if (api_get_setting('gradebook_enable') == 'true') {
            if (api_get_setting('show_tabs', 'my_gradebook') == 'true') {
                $navigation['mygradebook'] = $possible_tabs['mygradebook'];
            } else{
                $menu_navigation['mygradebook'] = $possible_tabs['mygradebook'];
            }
        }

        // Reporting
        if (api_get_setting('show_tabs', 'reporting') == 'true') {
            if (api_is_allowed_to_create_course() || api_is_drh() || api_is_session_admin() || api_is_student_boss()) {
                $navigation['session_my_space'] = $possible_tabs['session_my_space'];
            } else {
                $navigation['session_my_space'] = $possible_tabs['session_my_progress'];
            }
        } else {
            if (api_is_allowed_to_create_course() || api_is_drh() || api_is_session_admin() || api_is_student_boss()) {
                $menu_navigation['session_my_space'] = $possible_tabs['session_my_space'];
            } else {
                $menu_navigation['session_my_space'] = $possible_tabs['session_my_progress'];
            }
        }

        // Social Networking
        if (api_get_setting('show_tabs', 'social') == 'true') {
            if (api_get_setting('social.allow_social_tool') == 'true') {
                $navigation['social'] = isset($possible_tabs['social']) ? $possible_tabs['social'] : null;
            }
        } else{
            $menu_navigation['social'] = isset($possible_tabs['social']) ? $possible_tabs['social'] : null;
        }

        // Dashboard
        if (api_get_setting('show_tabs', 'dashboard') == 'true') {
            if (api_is_platform_admin() || api_is_drh() || api_is_session_admin()) {
                $navigation['dashboard'] = isset($possible_tabs['dashboard']) ? $possible_tabs['dashboard'] : null;
            }
        } else{
            $menu_navigation['dashboard'] = isset($possible_tabs['dashboard']) ? $possible_tabs['dashboard'] : null;
        }

        // Administration
        if (api_is_platform_admin(true)) {
            if (api_get_setting('show_tabs', 'platform_administration') == 'true') {
                $navigation['platform_admin'] = $possible_tabs['platform_admin'];
            } else {
                $menu_navigation['platform_admin'] = $possible_tabs['platform_admin'];
            }
        }

		// Reports
        if (!empty($possible_tabs['reports'])) {
            if (api_get_setting('show_tabs', 'reports') == 'true') {
                if ((api_is_platform_admin() || api_is_drh() || api_is_session_admin()) && Rights::hasRight('show_tabs:reports')) {
                    $navigation['reports'] = $possible_tabs['reports'];
                }
            } else {
                $menu_navigation['reports'] = $possible_tabs['reports'];
            }
        }

        // Custom tabs
        $customTabs = getCustomTabs();
        if (!empty($customTabs)) {
            foreach ($customTabs as $tab) {
                if (api_get_setting($tab['variable'], $tab['subkey']) == 'true' &&
                    isset($possible_tabs[$tab['subkey']])
                ) {
                    $possible_tabs[$tab['subkey']]['url'] = api_get_path(WEB_PATH).$possible_tabs[$tab['subkey']]['url'];
                    $navigation[$tab['subkey']] = $possible_tabs[$tab['subkey']];
                } else {
                    if (isset($possible_tabs[$tab['subkey']])) {
                        $possible_tabs[$tab['subkey']]['url'] = api_get_path(WEB_PATH).$possible_tabs[$tab['subkey']]['url'];
                        $menu_navigation[$tab['subkey']] = $possible_tabs[$tab['subkey']];
                    }
                }
            }
        }
    }

    return array(
        'menu_navigation' => $menu_navigation,
        'navigation' => $navigation,
        'possible_tabs' => $possible_tabs,
    );
}

function return_menu()
{
    $navigation = return_navigation_array();
    $navigation = $navigation['navigation'];

    // Displaying the tabs

    $lang = api_get_setting('language.platform_language');

    if (!empty($_SESSION['user_language_choice'])) {
        $lang = $_SESSION['user_language_choice'];
    } elseif (!empty($_SESSION['_user']['language'])) {
        $lang = $_SESSION['_user']['language'];
    }

    // Preparing home folder for multiple urls

    if (api_get_multiple_access_url()) {
        $access_url_id = api_get_current_access_url_id();
        if ($access_url_id != -1) {
            $url_info = api_get_access_url($access_url_id);
            $url = api_remove_trailing_slash(preg_replace('/https?:\/\//i', '', $url_info['url']));
            $clean_url = api_replace_dangerous_char($url);
            $clean_url = str_replace('/', '-', $clean_url);
            $clean_url .= '/';
            $homep     = api_get_path(SYS_APP_PATH).'home/'.$clean_url; //homep for Home Path
            //we create the new dir for the new sites
            if (!is_dir($homep)) {
                mkdir($homep, api_get_permissions_for_new_directories());
            }
        }
    } else {
        $homep = api_get_path(SYS_APP_PATH).'home/';
    }

    $ext = '.html';
    $menutabs = 'home_tabs';
    $mtloggedin = 'home_tabs_logged_in';
    $home_top = '';

    if (is_file($homep.$menutabs.'_'.$lang.$ext) && is_readable($homep.$menutabs.'_'.$lang.$ext)) {
        $home_top = @(string)file_get_contents($homep.$menutabs.'_'.$lang.$ext);
    } elseif (is_file($homep.$menutabs.$lang.$ext) && is_readable($homep.$menutabs.$lang.$ext)) {
        $home_top = @(string)file_get_contents($homep.$menutabs.$lang.$ext);
    } else {
        //$errorMsg = get_lang('HomePageFilesNotReadable');
    }

    $home_top = api_to_system_encoding($home_top, api_detect_encoding(strip_tags($home_top)));

    $open = str_replace('{rel_path}',api_get_path(REL_PATH), $home_top);
    $open = api_to_system_encoding($open, api_detect_encoding(strip_tags($open)));

    $open_mtloggedin = '';
    if (api_get_user_id() && !api_is_anonymous()) {
        if (is_file($homep.$mtloggedin.'_'.$lang.$ext) && is_readable($homep.$mtloggedin.'_'.$lang.$ext)) {
            $home_top = @(string)file_get_contents($homep.$mtloggedin.'_'.$lang.$ext);
            $home_top = str_replace('::private', '', $home_top);
        } elseif (is_file($homep.$mtloggedin.$lang.$ext) && is_readable($homep.$mtloggedin.$lang.$ext)) {
            $home_top = @(string)file_get_contents($homep.$mtloggedin.$lang.$ext);
            $home_top = str_replace('::private', '', $home_top);
        } else {
            //$errorMsg = get_lang('HomePageFilesNotReadable');
        }

        $home_top = api_to_system_encoding($home_top, api_detect_encoding(strip_tags($home_top)));
        $open_mtloggedin = str_replace('{rel_path}',api_get_path(REL_PATH), $home_top);
        $open_mtloggedin = api_to_system_encoding($open_mtloggedin, api_detect_encoding(strip_tags($open_mtloggedin)));
    }

    $lis = '';

    if (!empty($open) OR !empty($open_mtloggedin)) {
        if (strpos($open.$open_mtloggedin, 'show_menu') === false) {
            if (api_is_anonymous()) {
                $navigation[SECTION_CAMPUS]  = null;
            }
        } else {
            if (api_get_user_id() && !api_is_anonymous()) {
                $lis .= $open_mtloggedin;
            } else {
                $lis .= $open;
            }
        }
    }

    if (count($navigation) > 0 || !empty($lis)) {
        $pre_lis = '';
        foreach ($navigation as $section => $navigation_info) {
            $key = (!empty($navigation_info['key'])?'tab-'.$navigation_info['key']:'');
            if (isset($GLOBALS['this_section'])) {
                $current = $section == $GLOBALS['this_section'] ? ' id="current" class="active '.$key.'" ' : ' class="'.$key.'"';
            } else {
                $current = '';
            }
            if (!empty($navigation_info['title'])) {
                $pre_lis .= '<li'.$current.'><a href="'.$navigation_info['url'].'" target="_self">'.$navigation_info['title'].'</a></li>';
            }
        }
        $lis = $pre_lis.$lis;
    }

    $menu = null;
    if (!empty($lis)) {
         $menu .= $lis;
    }
    return $menu;
}

