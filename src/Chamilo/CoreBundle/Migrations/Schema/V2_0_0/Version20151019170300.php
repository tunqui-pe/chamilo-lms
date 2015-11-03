<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V_2_0_0;
use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Migrations for assigning skills
 *
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 */
class Version20151019170300 implements Migration, OrderedMigrationInterface
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
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $skillRelUser = $schema->getTable('skill_rel_user');
        $skillRelUser->addColumn('argumentation', \Doctrine\DBAL\Types\Type::TEXT);
        $skillRelUser->getColumn('course_id')->setNotnull(false);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $skillRelUser = $schema->getTable('skill_rel_user');
        $skillRelUser->dropColumn('argumentation');
        $skillRelUser->getColumn('course_id')->setNotnull(true);
    }
}
