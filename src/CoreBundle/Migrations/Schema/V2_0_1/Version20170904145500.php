<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V2_0_1;

use Chamilo\CoreBundle\Migrations\AbstractMigrationChamilo;
use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Class Version20170904145500
 * @package Application\Migrations\Schema\V200
 */
class Version20170904145500 extends AbstractMigrationChamilo implements OrderedMigrationInterface
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
     * @param QueryBag $queries
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery('DELETE FROM c_group_rel_user WHERE user_id NOT IN (SELECT id FROM user)');
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
