<?php
/* For licensing terms, see /license.txt */

/**
 * This is a learning path creation and player tool in Chamilo - previously learnpath_handler.php
 *
 * @author Patrick Cool
 * @author Denes Nagy
 * @author Roan Embrechts, refactoring and code cleaning
 * @author Yannick Warnier <ywarnier@beeznest.org> - cleaning and update for new SCORM tool
 * @author Julio Montoya  - Improving the list of templates
 * @package chamilo.learnpath
*/

$this_section = SECTION_COURSES;

api_protect_course_script();

/* Libraries */
include 'learnpath_functions.inc.php';

/* Header and action code */

$learnpath = learnpath::getCurrentLpFromSession();

$htmlHeadXtra[] = '
<script>'.$learnpath->get_js_dropdown_array().'

$(document).on("ready", function() {
    CKEDITOR.on("instanceReady", function (e) {
        showTemplates("content_lp");
    });
});
</script>';

/* Constants and variables */

$is_allowed_to_edit = api_is_allowed_to_edit(null, true);
$tbl_lp = Database::get_course_table(TABLE_LP_MAIN);

$isStudentView = isset($_REQUEST['isStudentView']) ? intval($_REQUEST['isStudentView']) : null;
$learnpath_id = (int) $_REQUEST['lp_id'];
$submit = isset($_POST['submit_button']) ? $_POST['submit_button'] : null;

/* MAIN CODE */
if ((!$is_allowed_to_edit) || ($isStudentView)) {
    error_log('New LP - User not authorized in lp_add_item.php');
    header('location:lp_controller.php?action=view&lp_id='.$learnpath_id);
}
// From here on, we are admin because of the previous condition, so don't check anymore.

$course_id = api_get_course_int_id();
$sql = "SELECT * FROM $tbl_lp
        WHERE c_id = $course_id AND id = $learnpath_id";
$result = Database::query($sql);
$therow = Database::fetch_array($result);

/*
    Course admin section
    - all the functions not available for students - always available in this case (page only shown to admin)
*/

/* SHOWING THE ADMIN TOOLS */
if (api_is_in_gradebook()) {
    $interbreadcrumb[]= array(
        'url' => api_get_path(WEB_CODE_PATH).'gradebook/index.php?'.api_get_cidreq(),
        'name' => get_lang('ToolGradebook')
    );
}

$interbreadcrumb[] = array(
    'url' => 'lp_controller.php?action=list&'.api_get_cidreq(),
    'name' => get_lang('LearningPaths'),
);
$interbreadcrumb[] = array(
    'url' => api_get_self()."?action=build&lp_id=$learnpath_id&".api_get_cidreq(),
    'name' => Security::remove_XSS("{$therow['name']}"),
);
$interbreadcrumb[] = array(
    'url' => api_get_self()."?action=add_item&type=step&lp_id=$learnpath_id&".api_get_cidreq(),
    'name' => get_lang('NewStep'),
);

// Theme calls.
$show_learn_path = true;
$lp_theme_css = $learnpath->get_theme();

Display::display_header(get_lang('Edit'),'Path');
$suredel = trim(get_lang('AreYouSureToDeleteJS'));

?>
<script>
/* <![CDATA[ */
function stripslashes(str) {
    str=str.replace(/\\'/g,'\'');
    str=str.replace(/\\"/g,'"');
    str=str.replace(/\\\\/g,'\\');
    str=str.replace(/\\0/g,'\0');
    return str;
}
function confirmation(name) {
    name=stripslashes(name);
    if (confirm("<?php echo $suredel; ?> " + name + " ?")) {
        return true;
    } else {
        return false;
    }
}

$(document).ready(function() {
    $('.lp-btn-associate-forum').on('click', function (e) {
        var associate = confirm('<?php echo get_lang('ConfirmAssociateForumToLPItem') ?>');

        if (!associate) {
            e.preventDefault();
        }
    });

    $('.lp-btn-dissociate-forum').on('click', function (e) {
        var dissociate = confirm('<?php echo get_lang('ConfirmDissociateForumToLPItem') ?>');

        if (!dissociate) {
            e.preventDefault();
        }
    });
});
</script>
<?php

/* DISPLAY SECTION */
echo $learnpath->build_action_menu();

echo '<div class="row">';
echo '<div class="col-md-3">';

$path_item = isset($_GET['path_item']) ? $_GET['path_item'] : 0;
$path_item = Database::escape_string($path_item);
$tbl_doc = Database :: get_course_table(TABLE_DOCUMENT);
$sql_doc = "SELECT path FROM " . $tbl_doc . "
            WHERE c_id = $course_id AND id = '". $path_item."' ";

$res_doc = Database::query($sql_doc);
$path_file = Database::result($res_doc, 0, 0);
$path_parts = pathinfo($path_file);

if (Database::num_rows($res_doc) > 0 && $path_parts['extension'] == 'html') {
    echo $learnpath->return_new_tree();

    // Show the template list
    echo '<div id="frmModel" class="lp-add-item"></div>';
} else {
    echo $learnpath->return_new_tree();
}

echo '</div>';
echo '<div class="col-md-9">';

if (isset($is_success) && $is_success === true) {
    $msg = '<div class="lp_message" style="margin-bottom:10px;">';
    $msg .= 'The item has been edited.';
    $msg .= '</div>';
    echo $learnpath->display_item($_GET['id'], $msg);
} else {
    echo $learnpath->display_edit_item($_GET['id']);
}

echo '</div>';
echo '</div>';

$learnpath->updateCurrentLpFromSession();
