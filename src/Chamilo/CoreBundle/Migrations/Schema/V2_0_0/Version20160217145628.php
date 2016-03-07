<?php

namespace Chamilo\CoreBundle\Migrations\Schema\V_2_0_0;

use Chamilo\CoreBundle\Migrations\AbstractMigrationChamilo;
use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160217145628 extends AbstractMigrationChamilo
{
    /**
     * @param Schema $schema
     * @param QueryBag $queries
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery('ALTER TABLE access_url ADD limit_courses INT DEFAULT NULL, ADD limit_active_courses INT DEFAULT NULL, ADD limit_sessions INT DEFAULT NULL, ADD limit_users INT DEFAULT NULL, ADD limit_teachers INT DEFAULT NULL, ADD limit_disk_space INT DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL');
        $queries->addQuery('ALTER TABLE c_student_publication ADD filesize INT DEFAULT NULL');

        $queries->addQuery('ALTER TABLE access_url_rel_session ADD id INT AUTO_INCREMENT NOT NULL, CHANGE access_url_id access_url_id INT DEFAULT NULL, CHANGE session_id session_id INT DEFAULT NULL, ADD PRIMARY KEY (id)');
        $queries->addQuery('ALTER TABLE access_url_rel_session ADD CONSTRAINT FK_6CBA5F5D613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $queries->addQuery('ALTER TABLE access_url_rel_session ADD CONSTRAINT FK_6CBA5F5D73444FD5 FOREIGN KEY (access_url_id) REFERENCES access_url (id)');
        $queries->addQuery('CREATE INDEX IDX_6CBA5F5D613FECDF ON access_url_rel_session (session_id)');
        $queries->addQuery('CREATE INDEX IDX_6CBA5F5D73444FD5 ON access_url_rel_session (access_url_id)');

    }

    /**
     * @param Schema $schema
     * @param QueryBag $queries
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery('ALTER TABLE access_url DROP limit_courses, DROP limit_active_courses, DROP limit_sessions, DROP limit_users, DROP limit_teachers, DROP limit_disk_space, DROP email');
        $queries->addQuery('ALTER TABLE c_student_publication DROP filesize');

        $queries->addQuery('ALTER TABLE access_url_rel_session MODIFY id INT NOT NULL');
        $queries->addQuery('ALTER TABLE access_url_rel_session DROP FOREIGN KEY FK_6CBA5F5D613FECDF');
        $queries->addQuery('ALTER TABLE access_url_rel_session DROP FOREIGN KEY FK_6CBA5F5D73444FD5');
        $queries->addQuery('DROP INDEX IDX_6CBA5F5D613FECDF ON access_url_rel_session');
        $queries->addQuery('DROP INDEX IDX_6CBA5F5D73444FD5 ON access_url_rel_session');
        $queries->addQuery('ALTER TABLE access_url_rel_session DROP PRIMARY KEY');
        $queries->addQuery('ALTER TABLE access_url_rel_session DROP id, CHANGE session_id session_id INT NOT NULL, CHANGE access_url_id access_url_id INT NOT NULL');
    }
}
