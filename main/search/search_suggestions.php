<?php
/* For licensing terms, see /license.txt */

/**
 * Suggest words to search
 * @package chamilo.search
 */

require_once dirname(__FILE__) . '/../inc/global.inc.php';

function get_suggestions_from_search_engine($q)
{
//    if (strlen($q)<2) { return null;}
    global $charset;

    $em = Database::getManager();
    $queryParams = [];

    $json = [];
    $q = Database::escape_string($q);
    $course = $em->find('ChamiloCoreBundle:Course', api_get_course_int_id());

    $sql_add = '';
    if ($course) {
        $sql_add = "AND sfv.course = :course ";
        $queryParams['course'] = $course;
    }

    $dql = "
        SELECT sfv FROM ChamiloCoreBundle:SpecificFieldValues sfv
        WHERE sfv.value LIKE :q $sql_add
        ORDER BY sfv.course, sfv.toolId, sfv.refId, sfv.fieldId
    ";
    $queryParams['q'] = "%$q%";

    $sql_result = $em
        ->createQuery($dql)
        ->setParameters($queryParams)
        ->getResult();

    $data = array();
    $i = 0;
    foreach ($sql_result as $row) {
        $value = api_convert_encoding($row->getValue(), 'UTF-8', $charset);
        $json[] = [
            'id' => $value,
            'value' => $value,
            'label' => $value
        ];

        if ($i < 20) {
            $data[$row->getCourse()->getCode()][$row->getToolId()][$row->getRefId()] = 1;
        }
        $i++;
    }
    // now that we have all the values corresponding to this search, we want to
    // make sure we get all the associated values that could match this one
    // initial value...
    $more_sugg = array();
    foreach ($data as $cc => $course_id) {
        foreach ($course_id as $ti => $item_tool_id) {
            foreach ($item_tool_id as $ri => $item_ref_id) {
                //natsort($item_ref_id);
                $output = array();
                $field_val = array();
                $res2 = $em
                    ->createQuery('
                        SELECT sfv FROM ChamiloCoreBundle:SpecificFieldValues sfv
                        WHERE sfv.courseCode = :course AND sfv.toolId = :tool AND sfv.refId = :ref
                        ORDER BY sfv.fieldId
                    ')
                    ->execute([
                        'course' => $cc,
                        'tool' => $ti,
                        'ref' => $ri
                    ]);
                // TODO this code doesn't manage multiple terms in one same field just yet (should duplicate results in this case)
                $field_id = 0;
                foreach ($res2 as $row2) {
                    //TODO : this code is not perfect yet. It overrides the
                    // first match set, so having 1:Yannick,Julio;2:Rectum;3:LASER
                    // will actually never return: Yannick - Rectum - LASER
                    // because it is overwriteen by Julio - Rectum - LASER
                    // We should have recursivity here to avoid this problem!
                    //Store the new set of results (only one per combination
                    // of all fields)
                    $field_val[$row2->getFieldId()] = $row2->getValue();
                    $current_field_val = '';
                    foreach ($field_val as $id => $val) {
                        $current_field_val .= $val.' - ';
                    }
                    //Check whether we have a field repetition or not. Results
                    // have been ordered by field_id, so we should catch them
                    // all here
                    if ($field_id == $row2->getFieldId()) {
                        //We found the same field id twice, split the output
                        // array to allow for two sets of results (copy all
                        // existing array elements into copies and update the
                        // copies) eg. Yannick - Car - Driving in $output[1]
                        // will create a copy as Yannick - Car - Speed
                        // in $output[3]
                        $c = count($output);
                        for ($i=0;$i<$c; $i++) {
                            $output[($c+$i)] = $current_field_val;
                        }
                    } else {
                        //no identical field id, continue as usual
                        $c = count($output);
                        if ($c == 0) {
                            $output[] = $row2->getValue() . ' - ';
                        } else {
                            foreach ($output as $i=>$out) {
                                //use the latest combination of fields
                                $output[$i] .= $row2->getValue().' - ';
                            }
                        }
                        $field_id = $row2->getFieldId();
                    }
                }
                foreach ($output as $i=>$out) {
                    if (api_stristr($out,$q) === false) {continue;}
                    $s = api_convert_encoding(substr($out, 0, -3), 'UTF-8', $charset);
                    if (!in_array($s,$more_sugg)) {
                        $more_sugg[] = $s;
                        $json[] = [
                            'id' => $s,
                            'value' => $s,
                            'label' => $s
                        ];
                    }
                }
            }
        }
    }

    echo json_encode($json);
}

$q = strtolower($_GET["term"]);
if (!$q) return;
//echo $q . "| value\n";
get_suggestions_from_search_engine($q);
