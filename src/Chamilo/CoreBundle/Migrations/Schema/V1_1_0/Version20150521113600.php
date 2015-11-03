<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Username changes
 */
class Version20150521113600 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE c_forum_thread MODIFY thread_replies int UNSIGNED NOT NULL DEFAULT 0'
        );
        $queries->addQuery(
            'ALTER TABLE c_forum_thread MODIFY thread_views int UNSIGNED NOT NULL DEFAULT 0'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE c_forum_thread MODIFY thread_replies int NULL'
        );
        $queries->addQuery(
            'ALTER TABLE c_forum_thread MODIFY thread_views int NULL'
        );
    }
}
