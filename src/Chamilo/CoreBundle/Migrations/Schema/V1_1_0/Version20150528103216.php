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
class Version20150528103216 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE session ADD COLUMN access_start_date datetime'
        );
        $queries->addQuery(
            'ALTER TABLE session ADD COLUMN access_end_date datetime'
        );
        $queries->addQuery(
            'ALTER TABLE session ADD COLUMN coach_access_start_date datetime'
        );
        $queries->addQuery(
            'ALTER TABLE session ADD COLUMN coach_access_end_date datetime'
        );
        $queries->addQuery(
            'ALTER TABLE session ADD COLUMN display_start_date datetime'
        );
        $queries->addQuery(
            'ALTER TABLE session ADD COLUMN display_end_date datetime'
        );


        $queries->addQuery('UPDATE session SET access_start_date = date_start');
        $queries->addQuery(
            "UPDATE session SET access_end_date = CONVERT(CONCAT(date_end, ' 23:59:59'), DATETIME)"
        );

        $queries->addQuery(
            'UPDATE session SET coach_access_start_date = CONVERT(DATE_SUB(date_start, INTERVAL nb_days_access_before_beginning DAY), DATETIME) '
        );
        $queries->addQuery(
            'UPDATE session SET coach_access_start_date = NULL WHERE nb_days_access_before_beginning = 0'
        );

        $queries->addQuery(
            'UPDATE session SET coach_access_end_date = CONVERT(DATE_ADD(date_end, INTERVAL nb_days_access_after_end DAY), DATETIME) '
        );
        $queries->addQuery(
            'UPDATE session SET coach_access_end_date = NULL WHERE nb_days_access_after_end = 0'
        );

        $queries->addQuery(
            'UPDATE session SET display_start_date = access_start_date'
        );
        $queries->addQuery(
            'UPDATE session SET display_end_date = access_end_date'
        );

        // Set dates to NULL

        $queries->addQuery(
            'UPDATE session SET access_start_date = NULL WHERE access_start_date = "0000-00-00 00:00:00"'
        );
        $queries->addQuery(
            'UPDATE session SET access_end_date = NULL WHERE access_end_date = "0000-00-00 00:00:00"'
        );

        $queries->addQuery(
            'UPDATE session SET coach_access_start_date = NULL WHERE coach_access_start_date = "0000-00-00 00:00:00"'
        );
        $queries->addQuery(
            'UPDATE session SET coach_access_end_date = NULL WHERE coach_access_end_date = "0000-00-00 00:00:00"'
        );

        $queries->addQuery(
            'UPDATE session SET display_start_date = NULL WHERE display_start_date = "0000-00-00 00:00:00"'
        );
        $queries->addQuery(
            'UPDATE session SET display_end_date = NULL WHERE display_end_date = "0000-00-00 00:00:00"'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE session CREATE date_start date NOT NULL'
        );
        $queries->addQuery('ALTER TABLE session CREATE date_end date NOT NULL');
        $queries->addQuery(
            'ALTER TABLE session CREATE nb_days_access_before_beginning TINYINT'
        );
        $queries->addQuery(
            'ALTER TABLE session CREATE nb_days_access_after_end TINYINT'
        );

        $queries->addQuery('UPDATE session SET date_start = access_start_date');
        $queries->addQuery('UPDATE session SET date_end = access_end_date');

        $queries->addQuery(
            'UPDATE session SET nb_days_access_before_beginning = DATEDIFF(access_start_date, coach_access_start_date) WHERE access_start_date != coach_access_start_date AND coach_access_start_date IS NOT NULL'
        );
        $queries->addQuery(
            'UPDATE session SET nb_days_access_after_end = DATEDIFF(coach_access_end_date, coach_access_end_date) WHERE access_end_date != coach_access_end_date AND coach_access_end_date IS NOT NULL'
        );
        $queries->addQuery(
            'UPDATE session SET nb_days_access_before_beginning = 0 WHERE access_start_date = coach_access_start_date OR coach_access_start_date IS NULL'
        );
        $queries->addQuery(
            'UPDATE session SET nb_days_access_after_end = 0 WHERE access_end_date = coach_access_end_date OR coach_access_end_date IS NULL'
        );

        $queries->addQuery('ALTER TABLE session DROP access_start_date');
        $queries->addQuery('ALTER TABLE session DROP access_end_date');
        $queries->addQuery('ALTER TABLE session DROP coach_access_start_date');
        $queries->addQuery('ALTER TABLE session DROP coach_access_end_date');
        $queries->addQuery('ALTER TABLE session DROP display_start_date');
        $queries->addQuery('ALTER TABLE session DROP display_end_date');
    }
}
