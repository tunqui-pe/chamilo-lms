<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Class Version20150819095300
 *
 * @package Application\Migrations\Schema\V1_1_010
 */
class Version20150819095300 implements Migration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $skillTable = $schema->getTable('skill');

        $skillTable->addColumn(
            'status',
            \Doctrine\DBAL\Types\Type::INTEGER,
            ['default' => 1]
        );
        $skillTable->addColumn(
            'updated_at',
            \Doctrine\DBAL\Types\Type::DATETIME
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $skillTable = $schema->getTable('skill');
        $skillTable->dropColumn('status');
        $skillTable->dropColumn('updated_at');
    }

}
