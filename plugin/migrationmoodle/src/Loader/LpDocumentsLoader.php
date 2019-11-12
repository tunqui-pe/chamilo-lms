<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\PluginBundle\MigrationMoodle\Loader;

use Chamilo\PluginBundle\MigrationMoodle\Interfaces\LoaderInterface;

/**
 * Class LpDocumentsLoader.
 *
 * @package Chamilo\PluginBundle\MigrationMoodle\Loader
 */
class LpDocumentsLoader implements LoaderInterface
{
    /**
     * Load the data and return the ID inserted.
     *
     * @param array $incomingData
     *
     * @return int
     */
    public function load(array $incomingData)
    {
        $courseInfo = api_get_course_info($incomingData['c_code']);

        $lp = new \learnpath(
            $incomingData['c_code'],
            $incomingData['lp_id'],
            api_get_user_id()
        );

        $lp->generate_lp_folder($courseInfo);

        $docId = $lp->create_document(
            $courseInfo,
            $incomingData['item_content'],
            $incomingData['item_title'],
            'html'
        );

        \Database::getManager()
            ->createQuery('UPDATE ChamiloCourseBundle:CLpItem i SET i.path = :path WHERE i.iid = :id')
            ->setParameters(['path' => $docId, 'id' => $incomingData['item_id']])
            ->execute();

        return $docId;
    }
}
