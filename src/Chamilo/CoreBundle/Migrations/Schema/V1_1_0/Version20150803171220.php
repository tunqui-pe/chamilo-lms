<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Class Version20150803171220
 *
 * @package Application\Migrations\Schema\V1_1_0
 */
class Version20150803171220 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery('UPDATE user SET username_canonical = username');
        $queries->addQuery(
            'ALTER TABLE user ADD confirmation_token VARCHAR(255) NULL'
        );
        $queries->addQuery(
            'ALTER TABLE user ADD password_requested_at DATETIME DEFAULT NULL'
        );
        $queries->addQuery(
            'RENAME TABLE track_e_exercices TO track_e_exercises'
        );
        // This drops the old table
        // $schema->renameTable('track_e_exercices', 'track_e_exercises');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery('ALTER TABLE user DROP confirmation_token');
        $queries->addQuery('ALTER TABLE user DROP password_requested_at');
    }
}
