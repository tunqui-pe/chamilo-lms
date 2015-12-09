<?php
/* For licensing terms, see /license.txt */

/**
 * Download script for course info
 * @package chamilo.course_info
 */

////require_once '../inc/global.inc.php';
$this_section = SECTION_COURSES;
$_cid = false;
if (isset($_GET['session']) && $_GET['session']) {
	$archive_path = api_get_path(SYS_ARCHIVE_PATH).'temp/';
	$_cid = true;
	$is_courseAdmin = true;
} else {
	$archive_path = api_get_path(SYS_ARCHIVE_PATH);
}

$archive_file = isset($_GET['archive']) ? $_GET['archive'] : null;
$archive_file = str_replace(array('..', '/', '\\'), '', $archive_file);

list($extension) = getextension($archive_file);

if (empty($extension) || !file_exists($archive_path.$archive_file)) {
    api_not_allowed(true);
}

if (Security::check_abs_path($archive_path.$archive_file, $archive_path)) {
    DocumentManager::file_send_for_download($archive_path.$archive_file, true, $archive_file);
    exit;
} else {
    api_not_allowed(true);
}
