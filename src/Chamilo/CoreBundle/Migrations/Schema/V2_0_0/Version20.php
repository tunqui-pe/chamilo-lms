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
            'CREATE TABLE media__gallery (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, context VARCHAR(64) NOT NULL, default_format VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE media__media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, provider_name VARCHAR(255) NOT NULL, provider_status INT NOT NULL, provider_reference VARCHAR(255) NOT NULL, provider_metadata LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', width INT DEFAULT NULL, height INT DEFAULT NULL, length NUMERIC(10, 0) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, content_size INT DEFAULT NULL, copyright VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) DEFAULT NULL, context VARCHAR(64) DEFAULT NULL, cdn_is_flushable TINYINT(1) DEFAULT NULL, cdn_flush_at DATETIME DEFAULT NULL, cdn_status INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE media__gallery_media (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, media_id INT DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_80D4C5414E7AF8F (gallery_id), INDEX IDX_80D4C541EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE phpcr_namespaces (prefix VARCHAR(255) NOT NULL, uri VARCHAR(255) NOT NULL, PRIMARY KEY(prefix)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE phpcr_workspaces (name VARCHAR(255) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE phpcr_nodes (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, parent VARCHAR(255) NOT NULL, local_name VARCHAR(255) NOT NULL, namespace VARCHAR(255) NOT NULL, workspace_name VARCHAR(255) NOT NULL, identifier VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, props LONGTEXT NOT NULL, depth INT NOT NULL, sort_order INT DEFAULT NULL, UNIQUE INDEX UNIQ_A4624AD7B548B0F1AC10DC4 (path, workspace_name), UNIQUE INDEX UNIQ_A4624AD7772E836A1AC10DC4 (identifier, workspace_name), INDEX IDX_A4624AD73D8E604F (parent), INDEX IDX_A4624AD78CDE5729 (type), INDEX IDX_A4624AD7623C14D533E16B56 (local_name, namespace), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE phpcr_internal_index_types (type VARCHAR(255) NOT NULL, node_id INT NOT NULL, PRIMARY KEY(type, node_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE phpcr_binarydata (id INT AUTO_INCREMENT NOT NULL, node_id INT NOT NULL, property_name VARCHAR(255) NOT NULL, workspace_name VARCHAR(255) NOT NULL, idx INT DEFAULT 0 NOT NULL, data LONGBLOB NOT NULL, UNIQUE INDEX UNIQ_37E65615460D9FD7413BC13C1AC10DC4E7087E10 (node_id, property_name, workspace_name, idx), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE phpcr_nodes_references (source_id INT NOT NULL, source_property_name VARCHAR(220) NOT NULL, target_id INT NOT NULL, INDEX IDX_F3BF7E1158E0B66 (target_id), PRIMARY KEY(source_id, source_property_name, target_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE phpcr_nodes_weakreferences (source_id INT NOT NULL, source_property_name VARCHAR(220) NOT NULL, target_id INT NOT NULL, INDEX IDX_F0E4F6FA158E0B66 (target_id), PRIMARY KEY(source_id, source_property_name, target_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'CREATE TABLE phpcr_type_nodes (node_type_id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, supertypes VARCHAR(255) NOT NULL, is_abstract TINYINT(1) NOT NULL, is_mixin TINYINT(1) NOT NULL, queryable TINYINT(1) NOT NULL, orderable_child_nodes TINYINT(1) NOT NULL, primary_item VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_34B0A8095E237E06 (name), PRIMARY KEY(node_type_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $queries->addQuery(
            'ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C5414E7AF8F FOREIGN KEY (gallery_id) REFERENCES media__gallery (id)'
        );
        $queries->addQuery(
            'ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C541EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id)'
        );
        $queries->addQuery(
            'CREATE TABLE notification__message (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', state INT NOT NULL, restart_count INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, started_at DATETIME DEFAULT NULL, completed_at DATETIME DEFAULT NULL, INDEX idx_state (state), INDEX idx_created_at (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );

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
            'CREATE INDEX state ON notification__message (state)'
        );
        $queries->addQuery(
            'CREATE INDEX createdAt ON notification__message (created_at)'
        );
        $queries->addQuery(
            'ALTER TABLE settings_current DROP FOREIGN KEY FK_62F79C3B9436187B'
        );
        $queries->addQuery(
            'DROP INDEX idx_62f79c3b9436187b ON settings_current'
        );
        $queries->addQuery(
            'CREATE INDEX access_url ON settings_current (access_url)'
        );
        $queries->addQuery(
            'ALTER TABLE settings_current ADD CONSTRAINT FK_62F79C3B9436187B FOREIGN KEY (access_url) REFERENCES access_url (id)'
        );
        $queries->addQuery(
            'ALTER TABLE access_url_rel_user DROP FOREIGN KEY FK_8557426373444FD5'
        );
        $queries->addQuery(
            'ALTER TABLE access_url_rel_user DROP FOREIGN KEY FK_85574263A76ED395'
        );
        $queries->addQuery(
            'DROP INDEX idx_85574263a76ed395 ON access_url_rel_user'
        );
        $queries->addQuery(
            'CREATE INDEX idx_access_url_rel_user_user ON access_url_rel_user (user_id)'
        );
        $queries->addQuery(
            'DROP INDEX idx_8557426373444fd5 ON access_url_rel_user'
        );
        $queries->addQuery(
            'CREATE INDEX idx_access_url_rel_user_access_url ON access_url_rel_user (access_url_id)'
        );
        $queries->addQuery(
            'ALTER TABLE access_url_rel_user ADD CONSTRAINT FK_8557426373444FD5 FOREIGN KEY (access_url_id) REFERENCES access_url (id)'
        );
        $queries->addQuery(
            'ALTER TABLE access_url_rel_user ADD CONSTRAINT FK_85574263A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)'
        );
        $queries->addQuery(
            'ALTER TABLE session_rel_course DROP FOREIGN KEY FK_12D110D391D79BD3'
        );
        $queries->addQuery(
            'DROP INDEX idx_12d110d391d79bd3 ON session_rel_course'
        );
        $queries->addQuery(
            'CREATE INDEX idx_session_rel_course_course_id ON session_rel_course (c_id)'
        );
        $queries->addQuery(
            'ALTER TABLE session_rel_course ADD CONSTRAINT FK_12D110D391D79BD3 FOREIGN KEY (c_id) REFERENCES course (id)'
        );
        $queries->addQuery(
            'ALTER TABLE session_rel_course_rel_user DROP FOREIGN KEY FK_720167E91D79BD3'
        );
        $queries->addQuery(
            'ALTER TABLE session_rel_course_rel_user DROP FOREIGN KEY FK_720167EA76ED395'
        );
        $queries->addQuery(
            'DROP INDEX idx_720167ea76ed395 ON session_rel_course_rel_user'
        );
        $queries->addQuery(
            'CREATE INDEX idx_session_rel_course_rel_user_id_user ON session_rel_course_rel_user (user_id)'
        );
        $queries->addQuery(
            'DROP INDEX idx_720167e91d79bd3 ON session_rel_course_rel_user'
        );
        $queries->addQuery(
            'CREATE INDEX idx_session_rel_course_rel_user_course_id ON session_rel_course_rel_user (c_id)'
        );
        $queries->addQuery(
            'ALTER TABLE session_rel_course_rel_user ADD CONSTRAINT FK_720167E91D79BD3 FOREIGN KEY (c_id) REFERENCES course (id)'
        );
        $queries->addQuery(
            'ALTER TABLE session_rel_course_rel_user ADD CONSTRAINT FK_720167EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)'
        );
        $queries->addQuery(
            'ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4D1DC2CFC'
        );
        $queries->addQuery('DROP INDEX idx_d044d5d4d1dc2cfc ON session');
        $queries->addQuery('CREATE INDEX idx_id_coach ON session (id_coach)');
        $queries->addQuery(
            'ALTER TABLE session ADD CONSTRAINT FK_D044D5D4D1DC2CFC FOREIGN KEY (id_coach) REFERENCES user (id)'
        );
        $queries->addQuery(
            'ALTER TABLE c_group_info DROP FOREIGN KEY FK_CE06532491D79BD3'
        );
        $queries->addQuery('DROP INDEX idx_ce06532491d79bd3 ON c_group_info');
        $queries->addQuery('CREATE INDEX course ON c_group_info (c_id)');
        $queries->addQuery(
            'ALTER TABLE c_group_info ADD CONSTRAINT FK_CE06532491D79BD3 FOREIGN KEY (c_id) REFERENCES course (id)'
        );
        $queries->addQuery(
            'ALTER TABLE c_tool DROP FOREIGN KEY FK_8456658091D79BD3'
        );
        $queries->addQuery('DROP INDEX idx_8456658091d79bd3 ON c_tool');
        $queries->addQuery('CREATE INDEX course ON c_tool (c_id)');
        $queries->addQuery(
            'ALTER TABLE c_tool ADD CONSTRAINT FK_8456658091D79BD3 FOREIGN KEY (c_id) REFERENCES course (id)'
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
        $queries->addQuery('DROP INDEX state ON notification__message');
        $queries->addQuery(
            'CREATE INDEX notification_message_state_idx ON notification__message (state)'
        );
        $queries->addQuery('DROP INDEX createdat ON notification__message');
        $queries->addQuery(
            'CREATE INDEX notification_message_created_at_idx ON notification__message (created_at)'
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
        $queries->addQuery(
            'ALTER TABLE user CHANGE biography biography VARCHAR(1000) DEFAULT NULL'
        );



        $queries->addQuery("ALTER TABLE resource_link ADD start_visibility_at DATETIME DEFAULT NULL, ADD end_visibility_at DATETIME DEFAULT NULL;");

        $sql = "UPDATE user SET emailCanonical = email";
        $queries->addQuery($sql);

        $sql = "UPDATE user SET roles = 'a:0:{}'";
        $queries->addQuery($sql);

        $sql = "UPDATE user SET username_canonical = username;";
        $queries->addQuery($sql);

        // Update iso
        $sql = "UPDATE course SET course_language = (SELECT isocode FROM language WHERE english_name = course_language);";
        $queries->addQuery($sql);

        $sql = "UPDATE sys_announcement SET lang = (SELECT isocode FROM language WHERE english_name = lang);";
        $queries->addQuery($sql);

        // Update settings variable

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

        // Update settings category
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

        // Update settings value
        $settings = [
            'upload_extensions_whitelist' => 'htm;html;jpg;jpeg;gif;png;swf;avi;mpg;mpeg;mov;flv;doc;docx;xls;xlsx;ppt;pptx;odt;odp;ods;pdf;webm;oga;ogg;ogv;h264',
        ];

        foreach ($settings as $variable => $value) {
            $sql = "UPDATE settings_current SET selected_value = '$value'
                    WHERE variable = '$variable'";
            $queries->addQuery($sql);
        }

        // Delete settings
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
        $queries->addQuery('DROP TABLE media__gallery');
        $queries->addQuery('DROP TABLE media__media');
        $queries->addQuery('DROP TABLE media__gallery_media');
        $queries->addQuery('DROP TABLE phpcr_namespaces');
        $queries->addQuery('DROP TABLE phpcr_workspaces');
        $queries->addQuery('DROP TABLE phpcr_nodes');
        $queries->addQuery('DROP TABLE phpcr_internal_index_types');
        $queries->addQuery('DROP TABLE phpcr_binarydata');
        $queries->addQuery('DROP TABLE phpcr_nodes_references');
        $queries->addQuery('DROP TABLE phpcr_nodes_weakreferences');
        $queries->addQuery('DROP TABLE phpcr_type_nodes');
        $queries->addQuery('DROP TABLE phpcr_type_props');
        $queries->addQuery('DROP TABLE phpcr_type_childs');
        $queries->addQuery('DROP TABLE notification__message');

    }
}
