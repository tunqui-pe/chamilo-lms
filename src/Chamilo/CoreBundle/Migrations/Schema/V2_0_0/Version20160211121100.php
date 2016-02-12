<?php

namespace Chamilo\CoreBundle\Migrations\Schema\V_2_0_0;

use Chamilo\CoreBundle\Migrations\AbstractMigrationChamilo;
use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class Version20160211121100 extends AbstractMigrationChamilo
{
    /**
     * @param Schema $schema
     * @param QueryBag $queries
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery("ALTER TABLE course_rel_class ADD c_id int NOT NULL");
        $queries->addQuery("
            UPDATE course_rel_class cc
            SET cc.c_id = (SELECT id FROM course WHERE code = cc.course_code)
        ");
        $queries->addQuery("ALTER TABLE course_rel_class DROP INDEX PRIMARY");
        $queries->addQuery("ALTER TABLE course_rel_class DROP course_code");
        $queries->addQuery("ALTER TABLE course_rel_class ADD PRIMARY KEY (class_id, c_id)");
        $queries->addQuery("
            ALTER TABLE course_rel_class ADD FOREIGN KEY (c_id) REFERENCES course (id) ON DELETE RESTRICT
        ");

        $tables = [
            'gradebook_category',
            'gradebook_evaluation',
            'gradebook_link',
            'search_engine_ref',
            'shared_survey',
            'specific_field_values',
            'templates',
            'track_e_attempt'
        ];

        foreach ($tables as $table) {
            $queries->addQuery("ALTER TABLE $table ADD c_id int NOT NULL");
            $queries->addQuery("
                UPDATE $table t
                SET t.c_id = (SELECT id FROM course WHERE code = t.course_code)
            ");
            $queries->addQuery("ALTER TABLE $table DROP course_code");
            $queries->addQuery("
                ALTER TABLE $table ADD FOREIGN KEY (c_id) REFERENCES course (id) ON DELETE RESTRICT
            ");
        }

        $queries->addQuery("ALTER TABLE personal_agenda DROP course");

        $queries->addQuery("
            ALTER TABLE specific_field_values
            ADD c_id int(11) NOT NULL,
            ADD FOREIGN KEY (c_id) REFERENCES course (id) ON DELETE RESTRICT;
        ");

        $queries->addQuery("
            ALTER TABLE track_e_hotspot
            CHANGE c_id c_id int(11) NOT NULL AFTER hotspot_course_code,
            ADD FOREIGN KEY (c_id) REFERENCES course (id) ON DELETE RESTRICT;
        ");
        $queries->addQuery("
            UPDATE track_e_hotspot teh
            SET teh.c_id = (SELECT id FROM course WHERE code = teh.hotspot_course_code)
            WHERE teh.hotspot_course_code != NULL OR hotspot_course_code != ''
        ");
        $queries->addQuery("ALTER TABLE personal_agenda DROP hotspot_course_code");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        
    }
}
