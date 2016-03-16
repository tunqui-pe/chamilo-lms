<?php
/* For licensing terms, see /license.txt */

use Chamilo\CoreBundle\Framework\Container;

/**
 * @package chamilo.social
 * @author Julio Montoya <gugli100@gmail.com>
 * @autor Alex Aragon <alex.aragon@beeznest.com> CSS Design and Template
 */

$cidReset = true;

$user_id = api_get_user_id();
$show_full_profile = true;
// social tab
$this_section = SECTION_SOCIAL;
unset($_SESSION['this_section']);

api_block_anonymous_users();
if (api_get_setting('social.allow_social_tool') != 'true') {
    $url = Container::getRouter()->generate('fos_user_profile_edit');
    header('Location: ' . $url);
    exit;
}

$userGroup = new UserGroup();

//fast upload image
if (api_get_setting_in_list(
        'profile.changeable_options',
        'picture'
    ) == 'true'
) {
    $form = new FormValidator('profile', 'post', 'home.php', null, array());

    //	PICTURE
    $form->addElement('file', 'picture', get_lang('AddImage'));
    $form->add_progress_bar();
    if (!empty($user_data['picture_uri'])) {
        $form->addElement(
            'checkbox',
            'remove_picture',
            null,
            get_lang('DelImage')
        );
    }
    $allowed_picture_types = api_get_supported_image_extensions();
    $form->addRule(
        'picture',
        get_lang('OnlyImagesAllowed') . ' (' . implode(
            ',',
            $allowed_picture_types
        ) . ')',
        'filetype',
        $allowed_picture_types
    );
    $form->addButtonSave(get_lang('SaveSettings'), 'apply_change');

    if ($form->validate()) {
        $user_data = $form->getSubmitValues();
        // upload picture if a new one is provided
        if ($_FILES['picture']['size']) {
            if ($new_picture = UserManager::update_user_picture(
                api_get_user_id(),
                $_FILES['picture']['name'],
                $_FILES['picture']['tmp_name']
            )
            ) {
                $table_user = Database :: get_main_table(TABLE_MAIN_USER);

                $sql = "UPDATE $table_user
                    SET picture_uri = '$new_picture' WHERE user_id =  " . api_get_user_id();

                $result = Database::query($sql);
            }
        }
    }
}

//Block Menu
$social_menu_block = SocialManager::show_social_menu('home');
$social_search_block = Display::panel(
    UserManager::get_search_form(''),
    get_lang("SearchUsers")
);

$results = $userGroup->get_groups_by_age(1, false);

$groups_newest = array();

if (!empty($results)) {
    foreach ($results as $result) {
        $id = $result['id'];
        $result['description'] = Security::remove_XSS($result['description'], STUDENT, true);
        $result['name'] = Security::remove_XSS($result['name'], STUDENT, true);

        if ($result['count'] == 1) {
            $result['count'] = '1 ' . get_lang('Member');
        } else {
            $result['count'] = $result['count'] . ' ' . get_lang('Members');
        }

        $group_url = "group_view.php?id=$id";

        $result['name'] = '<div class="group-name">'.Display::url(
                          api_ucwords(cut($result['name'], 40, true)), $group_url)
                          .'</div><div class="count-username">'.
                            Display::returnFontAwesomeIcon('user').$result['count'].'</div>';

        $picture = $userGroup->get_picture_group(
            $id,
            $result['picture'],
            80
        );

        $result['picture'] = '<img class="group-image" src="' . $picture['file'] . '" />';
        $group_actions = '<div class="group-more"><a href="groups.php?#tab_browse-2">' . get_lang('SeeMore') . '</a></div>';
        $group_info= '<div class="description"><p>' . cut($result['description'], 120, true) . "</p></div>";
        $groups_newest[] = array(
            Display::url(
                $result['picture'],
                $group_url
            ),
            $result['name'],
            $group_info.$group_actions,
        );
    }
}

// Top popular
$results = $userGroup->get_groups_by_popularity(1, false);

$groups_pop = array();
foreach ($results as $result) {
    $result['description'] = Security::remove_XSS(
        $result['description'],
        STUDENT,
        true
    );
    $result['name'] = Security::remove_XSS($result['name'], STUDENT, true);
    $id = $result['id'];
    $group_url = "group_view.php?id=$id";

    if ($result['count'] == 1) {
        $result['count'] = '1 ' . get_lang('Member');
    } else {
        $result['count'] = $result['count'] . ' ' . get_lang('Members');
    }
    $result['name'] = '<div class="group-name">'.Display::url(
            api_ucwords(cut($result['name'], 40, true)),$group_url)
        .'</div><div class="count-username">'.Display::returnFontAwesomeIcon('user').$result['count'].'</div>';

    $picture = $userGroup->get_picture_group(
        $id,
        $result['picture'],
        80
    );
    $result['picture_uri'] = '<img class="group-image" src="' . $picture['file'] . '" />';
    $group_actions = '<div class="group-more" ><a href="groups.php?#tab_browse-3">' . get_lang('SeeMore') . '</a></div>';
    $group_info= '<div class="description"><p>' . cut($result['description'], 120, true) . "</p></div>";
    $groups_pop[] = array(
        Display::url($result['picture_uri'], $group_url),
        $result['name'],$group_info. $group_actions
    );
}

$list=count($groups_newest);
$social_group_block = null;
if ($list > 0) {
    $social_group_block .= '<div class="list-group-newest">';
    $social_group_block .= '<div class="group-title">' . get_lang('Newest') . '</div>';
    for($i = 0;$i < $list; $i++){
        $social_group_block.='<div class="row">';
        $social_group_block.='<div class="col-md-2">' . $groups_newest[$i][0] . '</div>';
        $social_group_block.='<div class="col-md-10">' . $groups_newest[$i][1];
        $social_group_block.= $groups_newest[$i][2] . '</div>';
        $social_group_block.="</div>";
    }
    $social_group_block.= "</div>";
}
$list=count($groups_pop);
if ($list > 0) {
    $social_group_block .= '<div class="list-group-newest">';
    $social_group_block .= '<div class="group-title">' . get_lang('Popular') . '</div>';

    for($i = 0;$i < $list; $i++){
        $social_group_block.='<div class="row">';
        $social_group_block.='<div class="col-md-2">' . $groups_pop[$i][0] . '</div>';
        $social_group_block.='<div class="col-md-10">' . $groups_pop[$i][1];
        $social_group_block.= $groups_pop[$i][2] . '</div>';
        $social_group_block.="</div>";
    }
    $social_group_block.= "</div>";
}
// My friends
$friend_html = SocialManager::listMyFriendsBlock(
    $user_id,
    '',
    $show_full_profile
);
//Block Social Sessions
$social_session_block = null;
$user_info = api_get_user_info($user_id);
$sessionList = SessionManager::getSessionsFollowedByUser($user_id, $user_info['status']);

if (count($sessionList) > 0) {
    $social_session_block = $sessionList;
}

$social_group_block = Display::panelCollapse(get_lang('Group'), $social_group_block, 'sm-groups', null, 'grups-acordion', 'groups-collapse');

//$tpl =  new Template(get_lang('SocialNetwork'));
$tpl = \Chamilo\CoreBundle\Framework\Container::getTwig();
SocialManager::setSocialUserBlock($tpl, api_get_user_id(), 'home');

$tpl->addGlobal('social_menu_block', $social_menu_block);
$tpl->addGlobal('social_friend_block', $friend_html);
$tpl->addGlobal('sessionList', $social_session_block);
$tpl->addGlobal('social_search_block', $social_search_block);
$tpl->addGlobal('social_skill_block', SocialManager::getSkillBlock($user_id));
$tpl->addGlobal('social_group_block', $social_group_block);
$tpl->addGlobal('social_right_content', '');
$tpl->addGlobal('social_auto_extend_link', '');

echo $tpl->render('@template_style/social/home.html.twig');

