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
class Version20150625155000 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            "INSERT INTO extra_field
            (extra_field_type, field_type, variable, display_text, visible, changeable)
            VALUES (1, 1, 'captcha_blocked_until_date', 'Account locked until', 0, 0)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            "DELETE FROM extra_field
            WHERE variable = 'captcha_blocked_until_date' AND
                extra_field_type = 1 AND
                field_type = 1");
    }

}
