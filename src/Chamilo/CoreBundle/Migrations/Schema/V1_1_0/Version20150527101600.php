<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Class Version20150527120703
 * LP autolunch -> autolaunch
 * @package Application\Migrations\Schema\V1_1_010
 */
class Version20150527101600 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->addSettingCurrent(
            'gamification_mode',
            '',
            'radio',
            'Platform',
            0,
            'GamificationModeTitle',
            'GamificationModeComment',
            null,
            '',
            1,
            true,
            false,
            [
                [
                    'value' => 1,
                    'text' => 'Yes'
                ],
                [
                    'value' => 0,
                    'text' => 'No'
                ]
            ]
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            "DELETE FROM settings_options WHERE variable = 'gamification_mode'"
        );
        $queries->addQuery(
            "DELETE FROM settings_current WHERE variable = 'gamification_mode'"
        );
    }
}
