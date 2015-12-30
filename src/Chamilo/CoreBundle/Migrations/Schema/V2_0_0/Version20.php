<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V_2_0_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Class Version20
 * Migrate file to updated to Chamilo 2.0
 *
 */
class Version20 implements Migration, OrderedMigrationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     *
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE user ADD enabled TINYINT(1) NOT NULL, ADD locked TINYINT(1) NOT NULL, ADD expired TINYINT(1) NOT NULL, ADD expires_at DATETIME DEFAULT NULL, ADD credentials_expired TINYINT(1) NOT NULL, ADD credentials_expire_at DATETIME DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD date_of_birth DATETIME DEFAULT NULL, ADD website VARCHAR(64) DEFAULT NULL, ADD biography VARCHAR(255) DEFAULT NULL, ADD gender VARCHAR(1) DEFAULT NULL, ADD locale VARCHAR(8) DEFAULT NULL, ADD timezone VARCHAR(64) DEFAULT NULL, ADD facebook_uid VARCHAR(255) DEFAULT NULL, ADD facebook_name VARCHAR(255) DEFAULT NULL, ADD facebook_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD twitter_uid VARCHAR(255) DEFAULT NULL, ADD twitter_name VARCHAR(255) DEFAULT NULL, ADD twitter_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD gplus_uid VARCHAR(255) DEFAULT NULL, ADD gplus_name VARCHAR(255) DEFAULT NULL, ADD gplus_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD token VARCHAR(255) DEFAULT NULL, ADD two_step_code VARCHAR(255) DEFAULT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE username_canonical username_canonical VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL'
        );
        $queries->addQuery(
            'CREATE TABLE fos_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_4B019DDB5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE fos_user_user_group (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_B3C77447A76ED395 (user_id), INDEX IDX_B3C77447FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)'
        );
        $queries->addQuery(
            'ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447FE54D947 FOREIGN KEY (group_id) REFERENCES fos_group (id)'
        );
        $queries->addQuery('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $queries->addQuery(
            'ALTER TABLE user ADD roles LONGTEXT NOT NULL, ADD emailCanonical VARCHAR(255) NOT NULL, CHANGE lastname lastname VARCHAR(64) DEFAULT NULL, CHANGE firstname firstname VARCHAR(64) DEFAULT NULL, CHANGE phone phone VARCHAR(64) DEFAULT NULL'
        );

        $queries->addQuery(
            'ALTER TABLE fos_group CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\''
        );

        $queries->addQuery(
            'ALTER TABLE c_tool_intro DROP COLUMN id'
        );

        $queries->addQuery(
            'ALTER TABLE c_tool_intro ADD COLUMN tool VARCHAR(255)'
        );

        // Sonata changes:

        $queries->addQuery(
            'CREATE TABLE page__bloc (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, page_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(64) NOT NULL, settings LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', enabled TINYINT(1) DEFAULT NULL, position INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_FCDC1A97727ACA70 (parent_id), INDEX IDX_FCDC1A97C4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE page__snapshot (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, page_id INT DEFAULT NULL, route_name VARCHAR(255) NOT NULL, page_alias VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, decorate TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, url LONGTEXT DEFAULT NULL, parent_id INT DEFAULT NULL, target_id INT DEFAULT NULL, content LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', publication_date_start DATETIME DEFAULT NULL, publication_date_end DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_3963EF9AF6BD1646 (site_id), INDEX IDX_3963EF9AC4663E4 (page_id), INDEX idx_snapshot_dates_enabled (publication_date_start, publication_date_end, enabled), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE page__page (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, target_id INT DEFAULT NULL, route_name VARCHAR(255) NOT NULL, page_alias VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, decorate TINYINT(1) NOT NULL, edited TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, slug LONGTEXT DEFAULT NULL, url LONGTEXT DEFAULT NULL, custom_url LONGTEXT DEFAULT NULL, request_method VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, meta_keyword VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, javascript LONGTEXT DEFAULT NULL, stylesheet LONGTEXT DEFAULT NULL, raw_headers LONGTEXT DEFAULT NULL, template VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2FAE39EDF6BD1646 (site_id), INDEX IDX_2FAE39ED727ACA70 (parent_id), INDEX IDX_2FAE39ED158E0B66 (target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE page__site (id INT AUTO_INCREMENT NOT NULL, enabled TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, relative_path VARCHAR(255) DEFAULT NULL, host VARCHAR(255) NOT NULL, enabled_from DATETIME DEFAULT NULL, enabled_to DATETIME DEFAULT NULL, is_default TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, locale VARCHAR(6) DEFAULT NULL, title VARCHAR(64) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'ALTER TABLE page__bloc ADD CONSTRAINT FK_FCDC1A97727ACA70 FOREIGN KEY (parent_id) REFERENCES page__bloc (id) ON DELETE CASCADE'
        );
        $queries->addQuery(
            'ALTER TABLE page__bloc ADD CONSTRAINT FK_FCDC1A97C4663E4 FOREIGN KEY (page_id) REFERENCES page__page (id) ON DELETE CASCADE'
        );
        $queries->addQuery(
            'ALTER TABLE page__snapshot ADD CONSTRAINT FK_3963EF9AF6BD1646 FOREIGN KEY (site_id) REFERENCES page__site (id) ON DELETE CASCADE'
        );
        $queries->addQuery(
            'ALTER TABLE page__snapshot ADD CONSTRAINT FK_3963EF9AC4663E4 FOREIGN KEY (page_id) REFERENCES page__page (id) ON DELETE CASCADE'
        );
        $queries->addQuery(
            'ALTER TABLE page__page ADD CONSTRAINT FK_2FAE39EDF6BD1646 FOREIGN KEY (site_id) REFERENCES page__site (id) ON DELETE CASCADE'
        );
        $queries->addQuery(
            'ALTER TABLE page__page ADD CONSTRAINT FK_2FAE39ED727ACA70 FOREIGN KEY (parent_id) REFERENCES page__page (id) ON DELETE CASCADE'
        );
        $queries->addQuery(
            'ALTER TABLE page__page ADD CONSTRAINT FK_2FAE39ED158E0B66 FOREIGN KEY (target_id) REFERENCES page__page (id) ON DELETE CASCADE'
        );

        $queries->addQuery(
            'CREATE TABLE classification__tag (id INT AUTO_INCREMENT NOT NULL, context VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_CA57A1C7E25D857E (context), UNIQUE INDEX tag_context (slug, context), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE classification__collection (id INT AUTO_INCREMENT NOT NULL, context VARCHAR(255) DEFAULT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A406B56AE25D857E (context), INDEX IDX_A406B56AEA9FDD75 (media_id), UNIQUE INDEX tag_collection (slug, context), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE classification__category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, context VARCHAR(255) DEFAULT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_43629B36727ACA70 (parent_id), INDEX IDX_43629B36E25D857E (context), INDEX IDX_43629B36EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE classification__context (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'ALTER TABLE classification__tag ADD CONSTRAINT FK_CA57A1C7E25D857E FOREIGN KEY (context) REFERENCES classification__context (id)'
        );
        $queries->addQuery(
            'ALTER TABLE classification__collection ADD CONSTRAINT FK_A406B56AE25D857E FOREIGN KEY (context) REFERENCES classification__context (id)'
        );
        $queries->addQuery(
            'ALTER TABLE classification__collection ADD CONSTRAINT FK_A406B56AEA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE SET NULL'
        );
        $queries->addQuery(
            'ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36727ACA70 FOREIGN KEY (parent_id) REFERENCES classification__category (id) ON DELETE CASCADE'
        );
        $queries->addQuery(
            'ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36E25D857E FOREIGN KEY (context) REFERENCES classification__context (id)'
        );
        $queries->addQuery(
            'ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE SET NULL'
        );
        $queries->addQuery(
            'ALTER TABLE media__media ADD category_id INT DEFAULT NULL, ADD cdn_flush_identifier VARCHAR(64) DEFAULT NULL'
        );
        $queries->addQuery(
            'ALTER TABLE media__media ADD CONSTRAINT FK_5C6DD74E12469DE2 FOREIGN KEY (category_id) REFERENCES classification__category (id) ON DELETE SET NULL'
        );
        $queries->addQuery(
            'CREATE INDEX IDX_5C6DD74E12469DE2 ON media__media (category_id)'
        );


        $queries->addQuery("ALTER TABLE resource_link ADD start_visibility_at DATETIME DEFAULT NULL, ADD end_visibility_at DATETIME DEFAULT NULL;");

        $sql = "UPDATE user SET emailCanonical = email";
        $queries->addQuery($sql);

        $sql = "UPDATE user SET roles = 'a:0:{}'";
        $queries->addQuery($sql);

        $sql = "UPDATE user SET username_canonical = username;";
        $queries->addQuery($sql);

        // Update settings

        $settings = [
            'Institution' => 'institution',
            'SiteName' => 'site_name',
            'InstitutionUrl' => 'institution_url',
            'registration' => 'required_profile_fields',
            'profile' => 'changeable_options',
            'timezone_value' => 'timezone',
            'stylesheets' => 'theme',
            'platformLanguage' => 'platform_language',
            'languagePriority1' => 'language_priority_1',
            'languagePriority2' => 'language_priority_2',
            'languagePriority3' => 'language_priority_3',
            'languagePriority4' => 'language_priority_4',
            'gradebook_score_display_coloring' => 'my_display_coloring',
            'document_if_file_exists_option' => 'if_file_exists_option',
            'ProfilingFilterAddingUsers' => 'profiling_filter_adding_users',
            'course_create_active_tools' => 'active_tools_on_create',
            'EmailAdministrator' => 'administrator_email',
            'administratorSurname' => 'administrator_surname',
            'administratorName' => 'administrator_name',
            'administratorTelephone' => 'administrator_phone',
        ];

        foreach ($settings as $oldSetting => $newSetting) {
            $sql = "UPDATE settings_current SET variable = '$newSetting'
                    WHERE variable = $oldSetting";
            $queries->addQuery($sql);
        }

        $settings = [
            'cookie_warning' => 'platform',
            'donotlistcampus' => 'platform',
            'administrator_email' => 'admin',
            'administrator_surname' => 'admin',
            'administrator_name' => 'admin',
            'administrator_phone' => 'admin',
            'exercise_max_ckeditors_in_page' => 'exercise',
            'allow_hr_skills_management' => 'skill'
        ];

        foreach ($settings as $variable => $category) {
            $sql = "UPDATE settings_current SET category = '$newSetting'
                    WHERE variable = '$variable'";
            $queries->addQuery($sql);
        }

        $sql = "UPDATE course SET course_language = (SELECT isocode FROM language WHERE english_name = course_language);";
        $queries->addQuery($sql);

        $sql = "UPDATE sys_announcement SET lang = (SELECT isocode FROM language WHERE english_name = lang);";
        $queries->addQuery($sql);

        // Settings to delete
        $settings = [
            //'session_page_enabled',
            //'session_tutor_reports_visibility',
            'display_mini_month_calendar',
            'number_of_upcoming_events',
        ];

        foreach ($settings as $setting) {
            $sql = "DELETE FROM settings_current WHERE variable = $setting";
            $queries->addQuery($sql);
        }
    }

    /**
     *
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            'ALTER TABLE page__bloc DROP FOREIGN KEY FK_FCDC1A97727ACA70'
        );
        $queries->addQuery(
            'ALTER TABLE page__bloc DROP FOREIGN KEY FK_FCDC1A97C4663E4'
        );
        $queries->addQuery(
            'ALTER TABLE page__snapshot DROP FOREIGN KEY FK_3963EF9AC4663E4'
        );
        $queries->addQuery(
            'ALTER TABLE page__page DROP FOREIGN KEY FK_2FAE39ED727ACA70'
        );
        $queries->addQuery(
            'ALTER TABLE page__page DROP FOREIGN KEY FK_2FAE39ED158E0B66'
        );
        $queries->addQuery(
            'ALTER TABLE page__snapshot DROP FOREIGN KEY FK_3963EF9AF6BD1646'
        );
        $queries->addQuery(
            'ALTER TABLE page__page DROP FOREIGN KEY FK_2FAE39EDF6BD1646'
        );
        $queries->addQuery('DROP TABLE page__bloc');
        $queries->addQuery('DROP TABLE page__snapshot');
        $queries->addQuery('DROP TABLE page__page');
        $queries->addQuery('DROP TABLE page__site');


        $queries->addQuery(
            'ALTER TABLE media__media DROP FOREIGN KEY FK_5C6DD74E12469DE2'
        );
        $queries->addQuery(
            'ALTER TABLE classification__category DROP FOREIGN KEY FK_43629B36727ACA70'
        );
        $queries->addQuery(
            'ALTER TABLE classification__tag DROP FOREIGN KEY FK_CA57A1C7E25D857E'
        );
        $queries->addQuery(
            'ALTER TABLE classification__collection DROP FOREIGN KEY FK_A406B56AE25D857E'
        );
        $queries->addQuery(
            'ALTER TABLE classification__category DROP FOREIGN KEY FK_43629B36E25D857E'
        );
        $queries->addQuery('DROP TABLE classification__tag');
        $queries->addQuery('DROP TABLE classification__collection');
        $queries->addQuery('DROP TABLE classification__category');
        $queries->addQuery('DROP TABLE classification__context');
        $queries->addQuery('DROP INDEX IDX_5C6DD74E12469DE2 ON media__media');
        $queries->addQuery(
            'ALTER TABLE media__media DROP category_id, DROP cdn_flush_identifier'
        );
    }
}
