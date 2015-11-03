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
class Version20150713132630 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        if ($schema->hasTable('c_student_publication')) {
            $queries->addQuery(
                'ALTER TABLE c_student_publication ADD url_correction VARCHAR(255) DEFAULT NULL'
            );
            $queries->addQuery(
                'ALTER TABLE c_student_publication ADD title_correction VARCHAR(255) DEFAULT NULL'
            );
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE c_student_publication DROP url_correction'
        );
        $queries->addQuery(
            'ALTER TABLE c_student_publication DROP title_correction'
        );
    }
}
