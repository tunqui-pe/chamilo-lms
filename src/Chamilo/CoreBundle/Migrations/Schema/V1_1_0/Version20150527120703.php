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
class Version20150527120703 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE c_lp CHANGE COLUMN autolunch autolaunch INT NOT NULL DEFAULT 0'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE c_lp CHANGE COLUMN autolaunch autolunch INT NOT NULL'
        );
    }
}
