<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Username changes
 */
class Version20150511133949 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery('ALTER TABLE user ADD salt VARCHAR(255) NOT NULL');
        $queries->addQuery(
            'ALTER TABLE user ADD username_canonical VARCHAR(100) NOT NULL'
        );
        //$queries->addQuery('CREATE UNIQUE INDEX UNIQ_8D93D64992FC23A8 ON user (username_canonical)');
        $queries->addQuery(
            'ALTER TABLE user CHANGE password password VARCHAR(255) NOT NULL'
        );

        $queries->addQuery(
            "INSERT INTO settings_current (variable, subkey, type, category, selected_value, title, comment, scope, subkeytext, access_url_changeable) VALUES ('allow_teachers_to_create_sessions', NULL,'radio','Session','false','AllowTeachersToCreateSessionsTitle','AllowTeachersToCreateSessionsComment', NULL, NULL, 0)"
        );
        $queries->addQuery(
            "INSERT INTO settings_options (variable, value, display_text) VALUES ('allow_teachers_to_create_sessions', 'true', 'Yes')"
        );
        $queries->addQuery(
            "INSERT INTO settings_options (variable, value, display_text) VALUES ('allow_teachers_to_create_sessions', 'false', 'No')"
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery('ALTER TABLE user DROP salt');
        $queries->addQuery('DROP INDEX UNIQ_8D93D64992FC23A8 ON user');
        $queries->addQuery('ALTER TABLE user DROP username_canonical');
        $queries->addQuery(
            'ALTER TABLE user CHANGE password password VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci'
        );

        $queries->addQuery(
            'DELETE FROM settings_current WHERE variable = "allow_teachers_to_create_sessions" '
        );
        $queries->addQuery(
            'DELETE FROM settings_options WHERE variable = "allow_teachers_to_create_sessions" '
        );
    }
}
