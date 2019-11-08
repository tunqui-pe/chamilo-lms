<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\PluginBundle\MigrationMoodle\Transformer\Property;

use Chamilo\PluginBundle\MigrationMoodle\Task\LearningPathsTask;

/**
 * Class LoadedLpLookup.
 *
 * @package Chamilo\PluginBundle\MigrationMoodle\Transformer\Property
 */
class LoadedLpLookup extends LoadedKeyLookup
{
    /**
     * LoadedLpLookup constructor.
     */
    public function __construct()
    {
        $this->calledClass = LearningPathsTask::class;
    }
}
