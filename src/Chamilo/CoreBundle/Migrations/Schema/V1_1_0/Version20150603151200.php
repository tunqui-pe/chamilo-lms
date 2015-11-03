<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Session date changes
 */
class Version20150603151200 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE c_forum_forum ADD lp_id INTEGER UNSIGNED NOT NULL'
        );
        $queries->addQuery(
            'ALTER TABLE c_forum_thread ADD lp_item_id INTEGER UNSIGNED NOT NULL'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery('ALTER TABLE c_forum_forum DROP lp_id');
        $queries->addQuery('ALTER TABLE c_forum_thread DROP lp_item_id');
    }

}
