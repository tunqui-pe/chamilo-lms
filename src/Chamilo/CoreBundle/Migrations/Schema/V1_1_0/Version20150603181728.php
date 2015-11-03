<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Lp changes
 */
class Version20150603181728 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE c_lp ADD max_attempts INT NOT NULL, ADD subscribe_users INT NOT NULL DEFAULT 0'
        );
        $queries->addQuery(
            'ALTER TABLE c_item_property CHANGE c_id c_id INT DEFAULT NULL, CHANGE insert_user_id insert_user_id INT DEFAULT NULL, CHANGE start_visible start_visible DATETIME DEFAULT NULL, CHANGE end_visible end_visible DATETIME DEFAULT NULL, CHANGE session_id session_id INT DEFAULT NULL'
        );
        $queries->addQuery(
            'ALTER TABLE c_item_property ADD CONSTRAINT FK_1D84C18191D79BD3 FOREIGN KEY (c_id) REFERENCES course (id)'
        );
        $queries->addQuery(
            'ALTER TABLE c_item_property ADD CONSTRAINT FK_1D84C181330D47E9 FOREIGN KEY (to_group_id) REFERENCES c_group_info (iid)'
        );
        $queries->addQuery(
            'ALTER TABLE c_item_property ADD CONSTRAINT FK_1D84C18129F6EE60 FOREIGN KEY (to_user_id) REFERENCES user (id)'
        );
        $queries->addQuery(
            'ALTER TABLE c_item_property ADD CONSTRAINT FK_1D84C1819C859CC3 FOREIGN KEY (insert_user_id) REFERENCES user (id)'
        );
        $queries->addQuery(
            'ALTER TABLE c_item_property ADD CONSTRAINT FK_1D84C181613FECDF FOREIGN KEY (session_id) REFERENCES session (id)'
        );
        $queries->addQuery(
            'CREATE INDEX IDX_1D84C18191D79BD3 ON c_item_property (c_id)'
        );
        $queries->addQuery(
            'CREATE INDEX IDX_1D84C181330D47E9 ON c_item_property (to_group_id)'
        );
        $queries->addQuery(
            'CREATE INDEX IDX_1D84C18129F6EE60 ON c_item_property (to_user_id)'
        );
        $queries->addQuery(
            'CREATE INDEX IDX_1D84C1819C859CC3 ON c_item_property (insert_user_id)'
        );
        $queries->addQuery(
            'CREATE INDEX IDX_1D84C181613FECDF ON c_item_property (session_id)'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE c_lp DROP max_attempts, DROP subscribe_users'
        );
        $queries->addQuery(
            'ALTER TABLE c_item_property DROP FOREIGN KEY FK_1D84C18191D79BD3'
        );
        $queries->addQuery(
            'ALTER TABLE c_item_property DROP FOREIGN KEY FK_1D84C181330D47E9'
        );
        $queries->addQuery(
            'ALTER TABLE c_item_property DROP FOREIGN KEY FK_1D84C18129F6EE60'
        );
        $queries->addQuery(
            'ALTER TABLE c_item_property DROP FOREIGN KEY FK_1D84C1819C859CC3'
        );
        $queries->addQuery(
            'ALTER TABLE c_item_property DROP FOREIGN KEY FK_1D84C181613FECDF'
        );
        $queries->addQuery(
            'DROP INDEX IDX_1D84C18191D79BD3 ON c_item_property'
        );
        $queries->addQuery(
            'DROP INDEX IDX_1D84C181330D47E9 ON c_item_property'
        );
        $queries->addQuery(
            'DROP INDEX IDX_1D84C18129F6EE60 ON c_item_property'
        );
        $queries->addQuery(
            'DROP INDEX IDX_1D84C1819C859CC3 ON c_item_property'
        );
        $queries->addQuery(
            'DROP INDEX IDX_1D84C181613FECDF ON c_item_property'
        );
        $queries->addQuery(
            'ALTER TABLE c_item_property CHANGE c_id c_id INT NOT NULL, CHANGE insert_user_id insert_user_id INT NOT NULL, CHANGE session_id session_id INT NOT NULL, CHANGE start_visible start_visible DATETIME NOT NULL, CHANGE end_visible end_visible DATETIME NOT NULL'
        );
    }
}
