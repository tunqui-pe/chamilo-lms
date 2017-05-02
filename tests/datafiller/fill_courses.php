<?php
/* For licensing terms, see /license.txt */

/**
 * This script contains a data filling procedure for users
 * @author Yannick Warnier <yannick.warnier@beeznest.com>
 *
 */

/**
 * Loads the data and injects it into the Chamilo database, using the Chamilo
 * internal functions.
 * @return  array  List of user IDs for the users that have just been inserted
 */
function fill_courses()
{
    $courses = array(); // declare only to avoid parsing notice
    require_once 'data_courses.php'; // fill the $courses array
    $output = array();
    $output[] = array('title'=>'Courses Filling Report: ');
    $i = 1;
    foreach ($courses as $i => $course) {
        // First check that the first item doesn't exist already
    	$output[$i]['line-init'] = $course['title'];
        // The wanted code is necessary to avoid interpretation
        $course['wanted_code'] = $course['code'];
        $course['course_language'] = !empty($course['course_language']) ? $course['course_language'] : 'en';
        $res = CourseManager::create_course($course);
    	$output[$i]['line-info'] = $res ? get_lang('Added') : get_lang('NotInserted');
    	$i++;
    }

    return $output;
}
