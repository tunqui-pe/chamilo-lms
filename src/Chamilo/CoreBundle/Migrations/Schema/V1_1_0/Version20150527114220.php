<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Class Version20150527114220
 * Lp category
 * @package Application\Migrations\Schema\V1_1_010
 */
class Version20150527114220 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'CREATE TABLE c_lp_category (iid INT AUTO_INCREMENT NOT NULL, c_id INT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(iid)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'ALTER TABLE c_lp ADD category_id INT NOT NULL DEFAULT 0'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery('DROP TABLE c_lp_category');
        $queries->addQuery('ALTER TABLE c_lp DROP category_id');
    }
}
