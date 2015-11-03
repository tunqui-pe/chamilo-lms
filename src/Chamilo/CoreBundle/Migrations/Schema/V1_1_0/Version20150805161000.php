<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Class Version20150713132630
 *
 * @package Application\Migrations\Schema\V1_1_010
 */
class Version20150805161000 implements Migration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $sessionTable = $schema->getTable('session');

        $sessionTable->addColumn(
            'send_subscription_notification',
            \Doctrine\DBAL\Types\Type::BOOLEAN,
            ['default' => false]
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $sessionTable = $schema->getTable('session');
        $sessionTable->dropColumn('send_subscription_notification');
    }

}
