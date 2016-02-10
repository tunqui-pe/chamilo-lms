<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V2_0_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class NotebookBundle
 * @package Chamilo\CoreBundle\Migrations\Schema\v2
 */
class NotebookBundle implements Migration
{
    /**
     * @inheritdoc
     **/
    public function up(Schema $schema, QueryBag $queries)
    {
        /*$queries->addQuery(
            "CREATE TABLE c_notebook (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, resource_node_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB"
        );
        $queries->addQuery(
            "CREATE TABLE c_notebook_audit (id INT NOT NULL, rev INT NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, resource_node_id INT DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB"
        );*/
        //$queries->addQuery('CREATE TABLE c_item_visibility (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, visibility TINYINT(1) NOT NULL, start_visible DATETIME DEFAULT NULL, end_visible DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        //$queries->addQuery('CREATE TABLE c_item (id INT AUTO_INCREMENT NOT NULL, to_user_id INT DEFAULT NULL, tool VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, ref INT NOT NULL, user_id INT NOT NULL, INDEX IDX_BBBE7E4F29F6EE60 (to_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        //$queries->addQuery('ALTER TABLE c_item ADD CONSTRAINT FK_BBBE7E4F29F6EE60 FOREIGN KEY (to_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema, QueryBag $queries)
    {
        //$queries->addQuery("DROP TABLE c_notebook");
        //$queries->addQuery("DROP TABLE c_notebook_audit");
    }
}
