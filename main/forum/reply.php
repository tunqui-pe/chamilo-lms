<?php
/* For licensing terms, see /license.txt */

use ChamiloSession as Session;

/**
 * These files are a complete rework of the forum. The database structure is
 * based on phpBB but all the code is rewritten. A lot of new functionalities
 * are added:
 * - forum categories and forums can be sorted up or down, locked or made invisible
 * - consistent and integrated forum administration
 * - forum options:     are students allowed to edit their post?
 *                      moderation of posts (approval)
 *                      reply only forums (students cannot create new threads)
 *                      multiple forums per group
 * - sticky messages
 * - new view option: nested view
 * - quoting a message
 *
 * @package chamilo.forum
 */

//require_once '../inc/global.inc.php';

// The section (tabs).
$this_section = SECTION_COURSES;

// Notification for unauthorized people.
api_protect_course_script(true);

$nameTools = get_lang('ForumCategories');

$origin = '';
if (isset($_GET['origin'])) {
    $origin =  Security::remove_XSS($_GET['origin']);
    $origin_string = '&origin='.$origin;
}

/* Including necessary files */
require_once 'forumconfig.inc.php';
require_once 'forumfunction.inc.php';

/* MAIN DISPLAY SECTION */

/* Retrieving forum and forum categorie information */
// We are getting all the information about the current forum and forum category.
// Note pcool: I tried to use only one sql statement (and function) for this,
// but the problem is that the visibility of the forum AND forum cateogory are stored in the item_property table.
$current_thread	= get_thread_information($_GET['thread']); // Note: This has to be validated that it is an existing thread.
$current_forum	= get_forum_information($current_thread['forum_id']); // Note: This has to be validated that it is an existing forum.
$current_forum_category = get_forumcategory_information(Security::remove_XSS($current_forum['forum_category']));

/* Is the user allowed here? */
// The user is not allowed here if
// 1. the forumcategory, forum or thread is invisible (visibility==0
// 2. the forumcategory, forum or thread is locked (locked <>0)
// 3. if anonymous posts are not allowed
// The only exception is the course manager
// I have split this is several pieces for clarity.
//if (!api_is_allowed_to_edit() AND (($current_forum_category['visibility'] == 0 OR $current_forum['visibility'] == 0) OR ($current_forum_category['locked'] <> 0 OR $current_forum['locked'] <> 0 OR $current_thread['locked'] <> 0))) {
if (!api_is_allowed_to_edit(false, true) AND (($current_forum_category && $current_forum_category['visibility'] == 0) OR $current_forum['visibility'] == 0)) {
    api_not_allowed();
}
if (!api_is_allowed_to_edit(false, true) AND (($current_forum_category && $current_forum_category['locked'] <> 0) OR $current_forum['locked'] <> 0 OR $current_thread['locked'] <> 0)) {
    api_not_allowed();
}
if (!$_user['user_id'] AND $current_forum['allow_anonymous'] == 0) {
    api_not_allowed();
}

if ($current_forum['forum_of_group'] != 0) {
    $show_forum = GroupManager::user_has_access(
        api_get_user_id(),
        $current_forum['forum_of_group'],
        GroupManager::GROUP_TOOL_FORUM
    );
    if (!$show_forum) {
        api_not_allowed();
    }
}

/* Breadcrumbs */
if (api_is_in_gradebook()) {
    $interbreadcrumb[]= array(
        'url' => api_get_path(WEB_CODE_PATH).'gradebook/index.php?'.api_get_cidreq(),
        'name' => get_lang('ToolGradebook')
    );
}

$groupId = api_get_group_id();

if (!empty($groupId)) {
    $group_properties  = GroupManager :: get_group_properties($groupId);
    $interbreadcrumb[] = array(
        'url' => '../group/group.php?'.api_get_cidreq(),
        'name' => get_lang('Groups'),
    );
    $interbreadcrumb[] = array(
        'url' => '../group/group_space.php?'.api_get_cidreq(),
        'name' => get_lang('GroupSpace').' '.$group_properties['name'],
    );
    $interbreadcrumb[] = array(
        'url' => 'viewforum.php?origin='.$origin.'&forum='.intval($_GET['forum']).'&'.api_get_cidreq(),
        'name' => $current_forum['forum_title'],
    );
    $interbreadcrumb[] = array(
        'url' => 'viewthread.php?origin='.$origin.'&forum='.intval($_GET['forum']).'&thread='.intval($_GET['thread']).'&'.api_get_cidreq(),
        'name' => $current_thread['thread_title'],
    );
    $interbreadcrumb[] = array(
        'url' => 'javascript: void(0);',
        'name' => get_lang('Reply'),
    );
} else {
    $interbreadcrumb[] = array(
        'url' => 'index.php?'.api_get_cidreq(),
        'name' => $nameTools,
    );
    $interbreadcrumb[] = array(
        'url' => 'viewforumcategory.php?forumcategory='.$current_forum_category['cat_id'].'&'.api_get_cidreq(),
        'name' => $current_forum_category['cat_title'],
    );
    $interbreadcrumb[] = array(
        'url' => 'viewforum.php?origin='.$origin.'&forum='.intval($_GET['forum']).'&'.api_get_cidreq(),
        'name' => $current_forum['forum_title'],
    );
    $interbreadcrumb[] = array(
        'url' => 'viewthread.php?origin='.$origin.'&forum='.intval($_GET['forum']).'&thread='.intval($_GET['thread']).'&'.api_get_cidreq(),
        'name' => $current_thread['thread_title'],
    );
    $interbreadcrumb[] = array('url' => '#', 'name' => get_lang('Reply'));
}

/* Header */

$htmlHeadXtra[] = <<<JS
    <script>
    $(document).on('ready', function() {
        $('#reply-add-attachment').on('click', function(e) {
            e.preventDefault();

            var newInputFile = $('<input>', {
                type: 'file',
                name: 'user_upload[]'
            });

            $('[name="user_upload[]"]').parent().append(newInputFile);
        });
    });
    </script>
JS;

if ($origin == 'learnpath') {
    Display :: display_reduced_header('');
} else {
    // The last element of the breadcrumb navigation is already set in interbreadcrumb, so give an empty string.
    Display :: display_header('');
}
/* Action links */

if ($origin != 'learnpath') {
    echo '<div class="actions">';
    echo '<span style="float:right;">'.search_link().'</span>';
    echo '<a href="viewthread.php?'.api_get_cidreq().'&forum='.intval($_GET['forum']).'&thread='.intval($_GET['thread']).'&origin='.$origin.'">'.
        Display::return_icon('back.png', get_lang('BackToThread'), '', ICON_SIZE_MEDIUM).'</a>';
    echo '</div>';
} else {
    echo '<div style="height:15px">&nbsp;</div>';
}
/*New display forum div*/
echo '<div class="forum_title">';
echo '<h1><a href="viewforum.php?&origin='.$origin.'&forum='.$current_forum['forum_id'].'" '.
    class_visible_invisible($current_forum['visibility']).'>'.
    prepare4display($current_forum['forum_title']).'</a></h1>';
echo '<p class="forum_description">'.prepare4display($current_forum['forum_comment']).'</p>';
echo '</div>';
/* End new display forum */
// The form for the reply
$my_action   = isset($_GET['action']) ? Security::remove_XSS($_GET['action']) : '';
$my_post     = isset($_GET['post']) ?   Security::remove_XSS($_GET['post']) : '';
$my_elements = isset($_SESSION['formelements']) ? $_SESSION['formelements'] : '';
$values = show_add_post_form(
    $current_forum,
    $forum_setting,
    $my_action,
    $my_post,
    $my_elements
);
if (!empty($values) AND isset($_POST['SubmitPost'])) {
    $result = store_reply($current_forum, $values);
    //@todo split the show_add_post_form function

    $url = 'viewthread.php?'.api_get_cidreq().'&forum='.$current_thread['forum_id'].'&thread='.intval($_GET['thread']).'&origin='.(isset($origin)?$origin:'').'&msg='.$result['msg'].'&type='.$result['type'];
    echo '
    <script>
    window.location = "'.$url.'";
    </script>';
}

if (isset($origin) && $origin != 'learnpath') {
    Display :: display_footer();
}
