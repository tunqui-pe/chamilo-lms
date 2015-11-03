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
class Version20150609113500 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            "INSERT INTO extra_field
            (extra_field_type, field_type, variable, display_text, visible, changeable)
            VALUES (2, 10, 'tags', 'Tags', 1, 1)"
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            "DELETE FROM extra_field
            WHERE variable = 'tags' AND
                extra_field_type = 2 AND
                field_type = 10"
        );
    }

}
