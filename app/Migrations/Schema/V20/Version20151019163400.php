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
        $this->addSql("ALTER TABLE skill_rel_user CHANGE session_id session_id INT DEFAULT NULL");
        $this->addSql("UPDATE skill_rel_user SET course_id = NULL WHERE course_id = 0");
        $this->addSql("UPDATE skill_rel_user SET session_id = NULL WHERE session_id = 0");
        $this->addSql("ALTER TABLE skill_rel_user ADD CONSTRAINT FK_su_user FOREIGN KEY (user_id) REFERENCES user (id)");
        $this->addSql("ALTER TABLE skill_rel_user ADD CONSTRAINT FK_su_skill FOREIGN KEY (skill_id) REFERENCES skill (id)");
        $this->addSql("ALTER TABLE skill_rel_user ADD CONSTRAINT FK_su_course FOREIGN KEY (course_id) REFERENCES course (id)");
        $this->addSql("ALTER TABLE skill_rel_user ADD CONSTRAINT FK_su_session FOREIGN KEY (session_id) REFERENCES session (id)");
        $this->addSql("CREATE INDEX idx_select_s_c_u ON skill_rel_user (session_id, course_id, user_id)");
        $this->addSql("CREATE INDEX idx_select_sk_u ON skill_rel_user (skill_id, user_id)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE skill_rel_user DROP FOREIGN KEY FK_su_session");
        $this->addSql("ALTER TABLE skill_rel_user DROP FOREIGN KEY FK_su_course");
        $this->addSql("ALTER TABLE skill_rel_user DROP FOREIGN KEY FK_su_skill");
        $this->addSql("ALTER TABLE skill_rel_user DROP FOREIGN KEY FK_su_user");
        $this->addSql("DROP INDEX idx_select_s_c_u ON skill_rel_user");
        $this->addSql("DROP INDEX idx_select_sk_u ON skill_rel_user");
        $this->addSql("ALTER TABLE skill_rel_user CHANGE session_id session_id INT NOT NULL");
        $this->addSql("UPDATE skill_rel_user SET course_id = 0 WHERE course_id IS NULL");
        $this->addSql("UPDATE skill_rel_user SET session_id = 0 WHERE session_id IS NULL");
    }
}
