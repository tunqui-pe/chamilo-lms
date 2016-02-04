<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V_2_0_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Migrations for skill assignment
 *
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 */
class Version20151019163400 implements Migration, OrderedMigrationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            "ALTER TABLE skill_rel_user CHANGE session_id session_id INT DEFAULT NULL"
        );
        $queries->addQuery(
            "UPDATE skill_rel_user SET course_id = NULL WHERE course_id = 0"
        );
        $queries->addQuery(
            "UPDATE skill_rel_user SET session_id = NULL WHERE session_id = 0"
        );
        $queries->addQuery(
            "ALTER TABLE skill_rel_user ADD CONSTRAINT FK_su_user FOREIGN KEY (user_id) REFERENCES user (id)"
        );
        $queries->addQuery(
            "ALTER TABLE skill_rel_user ADD CONSTRAINT FK_su_skill FOREIGN KEY (skill_id) REFERENCES skill (id)"
        );
        $queries->addQuery(
            "ALTER TABLE skill_rel_user ADD CONSTRAINT FK_su_course FOREIGN KEY (course_id) REFERENCES course (id)"
        );
        $queries->addQuery(
            "ALTER TABLE skill_rel_user ADD CONSTRAINT FK_su_session FOREIGN KEY (session_id) REFERENCES session (id)"
        );
        $queries->addQuery(
            "CREATE INDEX idx_select_s_c_u ON skill_rel_user (session_id, course_id, user_id)"
        );
        $queries->addQuery(
            "CREATE INDEX idx_select_sk_u ON skill_rel_user (skill_id, user_id)"
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            "ALTER TABLE skill_rel_user DROP FOREIGN KEY FK_su_session"
        );
        $queries->addQuery(
            "ALTER TABLE skill_rel_user DROP FOREIGN KEY FK_su_course"
        );
        $queries->addQuery(
            "ALTER TABLE skill_rel_user DROP FOREIGN KEY FK_su_skill"
        );
        $queries->addQuery(
            "ALTER TABLE skill_rel_user DROP FOREIGN KEY FK_su_user"
        );
        $queries->addQuery("DROP INDEX idx_select_s_c_u ON skill_rel_user");
        $queries->addQuery("DROP INDEX idx_select_sk_u ON skill_rel_user");
        $queries->addQuery(
            "ALTER TABLE skill_rel_user CHANGE session_id session_id INT NOT NULL"
        );
        $queries->addQuery(
            "UPDATE skill_rel_user SET course_id = 0 WHERE course_id IS NULL"
        );
        $queries->addQuery(
            "UPDATE skill_rel_user SET session_id = 0 WHERE session_id IS NULL"
        );
    }
}
