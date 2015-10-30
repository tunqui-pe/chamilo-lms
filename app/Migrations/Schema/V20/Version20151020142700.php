<?php
/* For licensing terms, see /license.txt */
namespace Application\Migrations\Schema\V20;

use Application\Migrations\AbstractMigrationChamilo;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migrations for skills feedback
 *
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 */
class Version20151020142700 extends AbstractMigrationChamilo
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $skillUser = $schema->getTable('skill_rel_user');
        $user = $schema->getTable('user');

        $skillUserComment = $schema->createTable('skill_rel_user_comment');
        $skillUserComment->addColumn('id', \Doctrine\DBAL\Types\Type::INTEGER, ['autoincrement' => true]);
        $skillUserComment->addColumn('skill_rel_user_id', \Doctrine\DBAL\Types\Type::INTEGER);
        $skillUserComment->addColumn('feedback_giver_id', \Doctrine\DBAL\Types\Type::INTEGER);
        $skillUserComment->addColumn('feedback_text', \Doctrine\DBAL\Types\Type::TEXT);
        $skillUserComment->addColumn('feedback_value', \Doctrine\DBAL\Types\Type::INTEGER, ['notnull' => false]);
        $skillUserComment->addColumn('feedback_datetime', \Doctrine\DBAL\Types\Type::DATETIME);
        $skillUserComment->setPrimaryKey(['id']);
        $skillUserComment->addForeignKeyConstraint($skillUser, ['skill_rel_user_id'], ['id']);
        $skillUserComment->addForeignKeyConstraint($user, ['feedback_giver_id'], ['id']);
        $skillUserComment->addIndex(['skill_rel_user_id', 'feedback_giver_id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('skill_rel_user_comment');
    }
}
