<?php
/* For licensing terms, see /license.txt */
namespace Application\Migrations\Schema\V20;

use Application\Migrations\AbstractMigrationChamilo;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migrations for assigning skills
 *
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 */
class Version20151019170300 extends AbstractMigrationChamilo
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $skillRelUser = $schema->getTable('skill_rel_user');
        $skillRelUser->addColumn('argumentation', \Doctrine\DBAL\Types\Type::TEXT);
        $skillRelUser->getColumn('course_id')->setNotnull(false);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $skillRelUser = $schema->getTable('skill_rel_user');
        $skillRelUser->dropColumn('argumentation');
        $skillRelUser->getColumn('course_id')->setNotnull(true);
    }
}
