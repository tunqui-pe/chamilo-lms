<?php
/* For licensing terms, see /license.txt */

use ChamiloSession as Session;

/**
 * Script opened in an iframe and containing the learning path's table of contents
 * @package chamilo.learnpath
 * @author Yannick Warnier <ywarnier@beeznest.org>
 * @license	GNU/GPL
 */

// Flag to allow for anonymous user - needs to be set before global.inc.php.
$use_anonymous = true;

$lpFromSession = Session::read('lpobject');

if (isset($lpFromSession)) {
    /** @var learnpath $oLP */
    $oLP = unserialize($lpFromSession);
    if (is_object($oLP)) {
        Session::write('oLP', $oLP);
    } else {
        die('Could not instanciate lp object.');
    }
}

$lp_theme_css = $oLP->get_theme();
$scorm_css_header = true;
Display::display_reduced_header();

echo '<body dir="'.api_get_text_direction().'">';
echo '<div id="audiorecorder">	';
$audio_recorder_studentview = 'true';
$audio_recorder_item_id = $oLP->current;
if (api_get_setting('service_visio', 'active') == 'true') {
    require_once 'audiorecorder.inc.php';
}
echo '</div>';
// end of audiorecorder include
echo '</body></html>';
