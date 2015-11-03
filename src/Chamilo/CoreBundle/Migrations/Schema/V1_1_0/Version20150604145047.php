<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * track_e_default changes
 */
class Version20150604145047 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE track_e_default CHANGE default_event_type default_event_type VARCHAR(255) NOT NULL, CHANGE default_value_type default_value_type VARCHAR(255) NOT NULL'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE track_e_default CHANGE default_event_type default_event_type VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci, CHANGE default_value_type default_value_type VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci'
        );
    }
}
