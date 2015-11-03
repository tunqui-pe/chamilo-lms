<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Session date changes
 */
class Version20150624164100 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            "INSERT INTO extra_field
            (extra_field_type, field_type, variable, display_text, visible, changeable)
            VALUES (3, 16, 'image', 'Image', 1, 1)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            "DELETE FROM extra_field
            WHERE variable = 'image' AND
                extra_field_type = 3 AND
                field_type = 16");
    }

}
