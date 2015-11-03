<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * GradebookCategory changes
 */
class Version20150706135000 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $gradebookCategory = $schema->getTable('gradebook_category');

        $isRequirement = $gradebookCategory->addColumn(
            'is_requirement',
            \Doctrine\DBAL\Types\Type::BOOLEAN
        );
        $isRequirement->setNotnull(true)->setDefault(false);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $gradebookCategory = $schema->getTable('gradebook_category');
        $gradebookCategory->dropColumn('is_requirement');
    }
}
