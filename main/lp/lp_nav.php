<?php
/* For licensing terms, see /license.txt */

use Chamilo\CoreBundle\Framework\Container;

/**
 * Script opened in an iframe and containing the
 * learning path's navigation and progress bar
 * @package chamilo.learnpath
 * @author Yannick Warnier <ywarnier@beeznest.org>
 */

// Flag to allow for anonymous user - needs to be set before global.inc.php.
$use_anonymous = true;
//require_once '../inc/global.inc.php';

$htmlHeadXtra[] = '<script>
    var chamilo_xajax_handler = window.parent.oxajax;
</script>';

$progress_bar = '';
$navigation_bar = '';
$display_mode = '';
$autostart = 'true';

$learnPath = learnpath::getCurrentLpFromSession();

if (isset($learnPath)) {
    $display_mode = $learnPath->mode;
    $scorm_css_header = true;
    $lp_theme_css = $learnPath->get_theme();

    $my_style = api_get_visual_theme();

    // Setting up the CSS theme if exists
    $mycourselptheme = null;
    if (api_get_setting('course.allow_course_theme') == 'true') {
        $mycourselptheme = api_get_course_setting('allow_learning_path_theme');
    }

    if (!empty($lp_theme_css) && !empty($mycourselptheme) && $mycourselptheme != -1 && $mycourselptheme == 1) {
        global $lp_theme_css;
    } else {
        $lp_theme_css = $my_style;
    }

    $progress_bar = $learnPath->getProgressBar();
    $navigation_bar = $learnPath->get_navigation_bar();
    $mediaplayer = $learnPath->get_mediaplayer($autostart);
}
Container::$legacyTemplate = 'layout_empty.html.twig';

session_write_close();
?>
<script type="text/javascript">
    $(document).ready(function() {
        jQuery('video:not(.skip), audio:not(.skip)').mediaelementplayer({
            success: function(player, node) {
            }
        });
    });
</script>
<span>
    <?php echo (!empty($mediaplayer)) ? $mediaplayer : '&nbsp;' ?>
</span>
