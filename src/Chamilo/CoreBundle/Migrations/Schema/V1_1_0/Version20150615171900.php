<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Version20150615171900 class
 * Change in CCourseDescription entity
 *
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 */
class Version20150615171900 implements Migration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE c_course_description CHANGE description_type description_type INTEGER NOT NULL DEFAULT 1'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE c_course_description CHANGE description_type description_type BOOLEAN NOT NULL'
        );
    }

}
