<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V2_0_1;

use Chamilo\CoreBundle\Migrations\AbstractMigrationChamilo;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Class Version20170904173000
 * @package Application\Migrations\Schema\V200
 */
class Version20170904173000 extends AbstractMigrationChamilo implements OrderedMigrationInterface
{
    /**
    * {@inheritdoc}
    */
    public function getOrder()
    {
        return 3;
    }

    /**
     * @param Schema $schema
     * @param QueryBag $queries
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $tblQuestion = $schema->getTable('c_survey_question');

        if (!$tblQuestion->hasColumn('is_required')) {
            $tblQuestion
                ->addColumn('is_required', Type::BOOLEAN)
                ->setDefault(false);
        }
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
