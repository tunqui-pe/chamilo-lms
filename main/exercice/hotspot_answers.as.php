<?php
/* For licensing terms, see /license.txt */

/**
 * This file generates the ActionScript variables code used by the
 * HotSpot .swf
 * @package chamilo.exercise
 * @author Toon Keppens, Julio Montoya adding hotspot "medical" support
 */

include('../inc/global.inc.php');

// Set vars
$questionId    = intval($_GET['modifyAnswers']);
$exe_id        = intval($_GET['exe_id']);
$objQuestion   = Question :: read($questionId);
$TBL_ANSWERS   = Database::get_course_table(TABLE_QUIZ_ANSWER);
$documentPath  = api_get_path(SYS_COURSE_PATH).$_course['path'].'/document';

$picturePath   = $documentPath.'/images';
$pictureName   = $objQuestion->selectPicture();
$pictureSize   = getimagesize($picturePath.'/'.$objQuestion->selectPicture());
$pictureWidth  = $pictureSize[0];
$pictureHeight = $pictureSize[1];

$answer_type   = $objQuestion->selectType();

$course_id     = api_get_course_int_id();

if ($answer_type == HOT_SPOT_DELINEATION) {
	// Query db for answers
	$sql = "SELECT id, answer, hotspot_coordinates, hotspot_type FROM $TBL_ANSWERS
	        WHERE c_id = $course_id AND question_id = ".intval($questionId)." AND hotspot_type <> 'noerror' ORDER BY id";
} else {
	$sql = "SELECT id, answer, hotspot_coordinates, hotspot_type FROM $TBL_ANSWERS
	        WHERE c_id = $course_id AND question_id = ".intval($questionId)." ORDER BY id";
}
$result = Database::query($sql);
// Init
$data = [];
$data['type'] = 'solution';
$data['lang'] = [
    'Square' => get_lang('Square'),
    'Ellipse' => get_lang('Ellipse'),
    'Polygon' => get_lang('Polygon'),
    'HotspotStatus1' => get_lang('HotspotStatus1'),
    'HotspotStatus2Polygon' => get_lang('HotspotStatus2Polygon'),
    'HotspotStatus2Other' => get_lang('HotspotStatus2Other'),
    'HotspotStatus3' => get_lang('HotspotStatus3'),
    'HotspotShowUserPoints' => get_lang('HotspotShowUserPoints'),
    'ShowHotspots' => get_lang('ShowHotspots'),
    'Triesleft' => get_lang('Triesleft'),
    'HotspotExerciseFinished' => get_lang('HotspotExerciseFinished'),
    'NextAnswer' => get_lang('NextAnswer'),
    'Delineation' => get_lang('Delineation'),
    'CloseDelineation' => get_lang('CloseDelineation'),
    'Oar' => get_lang('Oar'),
    'ClosePolygon' => get_lang('ClosePolygon'),
    'DelineationStatus1' => get_lang('DelineationStatus1')
];
$data['image'] = $objQuestion->selectPicturePath();
$data['image_width'] = $pictureWidth;
$data['image_height'] = $pictureHeight;
$data['courseCode'] = $_course['path'];
$data['hotspots'] = [];

while ($hotspot = Database::fetch_array($result)) {
    $hotSpot = [];
    $hotSpot['id'] = $hotspot['id'];
    $hotSpot['answer'] = $hotspot['answer'];

	// Square or rectancle
	if ($hotspot['hotspot_type'] == 'square' ) {
        $hotSpot['type'] = 'square';
	}

	// Circle or ovale
	if ($hotspot['hotspot_type'] == 'circle') {
        $hotSpot['type'] = 'circle';
	}

	// Polygon
	if ($hotspot['hotspot_type'] == 'poly') {
        $hotSpot['type'] = 'poly';
	}

	// Delineation
	if ($hotspot['hotspot_type'] == 'delineation') {
        $hotSpot['type'] = 'delineation';
	}
	// oar
	if ($hotspot['hotspot_type'] == 'oar') {
        $hotSpot['type'] = 'delineation';
	}

    $hotSpot['coord'] = $hotspot['hotspot_coordinates'];

    $data['hotspots'][] = $hotSpot;
}

$data['answers'] = [];

$em = Database::getManager();

$rs = $em
    ->getRepository('ChamiloCoreBundle:TrackEHotspot')
    ->findBy([
        'hotspotQuestionId' => $questionId,
        'course' => $course_id,
        'hotspotExeId' => $exe_id
    ]);

foreach ($rs as $hotspotAnswer) {
    $data['answers'][] = $hotspotAnswer->hotspotCoordinate();
}

$data['done'] = 'done';

header('Content-Type: application/json');

echo json_encode($data);
