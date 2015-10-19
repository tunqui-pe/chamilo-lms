<?php
/* For licensing terms, see /license.txt */
namespace Application\Migrations\Schema\V20;

use Application\Migrations\AbstractMigrationChamilo;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migrations for skill assignment
 *
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 */
class Version20151019163400 extends AbstractMigrationChamilo
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $user = $schema->getTable('user');
        $skill = $schema->getTable('skill');
        $course = $schema->getTable('course');
        $session = $schema->getTable('session');

        $skillRelUser = $schema->getTable('skill_rel_user');
        $skillRelUser->getColumn('session_id')->setNotnull(false);
        $skillRelUser->addForeignKeyConstraint($user, ['user_id'], ['id'], [], 'FK_su_user');
        $skillRelUser->addForeignKeyConstraint($skill, ['skill_id'], ['id'], [], 'FK_su_skill');
        $skillRelUser->addForeignKeyConstraint($course, ['course_id'], ['id'], [], 'FK_su_course');
        $skillRelUser->addForeignKeyConstraint($session, ['session_id'], ['id'], [], 'FK_su_session');
        $skillRelUser->addIndex(['session_id', 'course_id', 'user_id'], 'idx_select_s_c_u');
        $skillRelUser->addIndex(['skill_id', 'user_id'], 'idx_select_sk_u');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $skillRelUser = $schema->getTable('skill_rel_user');
        $skillRelUser->removeForeignKey('FK_su_user');
        $skillRelUser->removeForeignKey('FK_su_skill');
        $skillRelUser->removeForeignKey('FK_su_course');
        $skillRelUser->removeForeignKey('FK_su_session');
        $skillRelUser->dropIndex('idx_select_s_c_u');
        $skillRelUser->dropIndex('idx_select_sk_u');
    }
}
