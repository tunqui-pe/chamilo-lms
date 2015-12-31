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


        $this->addSql('CREATE TABLE media__gallery (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, context VARCHAR(64) NOT NULL, default_format VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media__media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, provider_name VARCHAR(255) NOT NULL, provider_status INT NOT NULL, provider_reference VARCHAR(255) NOT NULL, provider_metadata LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', width INT DEFAULT NULL, height INT DEFAULT NULL, length NUMERIC(10, 0) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, content_size INT DEFAULT NULL, copyright VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) DEFAULT NULL, context VARCHAR(64) DEFAULT NULL, cdn_is_flushable TINYINT(1) DEFAULT NULL, cdn_flush_at DATETIME DEFAULT NULL, cdn_status INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media__gallery_media (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, media_id INT DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_80D4C5414E7AF8F (gallery_id), INDEX IDX_80D4C541EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_namespaces (prefix VARCHAR(255) NOT NULL, uri VARCHAR(255) NOT NULL, PRIMARY KEY(prefix)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_workspaces (name VARCHAR(255) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_nodes (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, parent VARCHAR(255) NOT NULL, local_name VARCHAR(255) NOT NULL, namespace VARCHAR(255) NOT NULL, workspace_name VARCHAR(255) NOT NULL, identifier VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, props LONGTEXT NOT NULL, depth INT NOT NULL, sort_order INT DEFAULT NULL, UNIQUE INDEX UNIQ_A4624AD7B548B0F1AC10DC4 (path, workspace_name), UNIQUE INDEX UNIQ_A4624AD7772E836A1AC10DC4 (identifier, workspace_name), INDEX IDX_A4624AD73D8E604F (parent), INDEX IDX_A4624AD78CDE5729 (type), INDEX IDX_A4624AD7623C14D533E16B56 (local_name, namespace), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_internal_index_types (type VARCHAR(255) NOT NULL, node_id INT NOT NULL, PRIMARY KEY(type, node_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_binarydata (id INT AUTO_INCREMENT NOT NULL, node_id INT NOT NULL, property_name VARCHAR(255) NOT NULL, workspace_name VARCHAR(255) NOT NULL, idx INT DEFAULT 0 NOT NULL, data LONGBLOB NOT NULL, UNIQUE INDEX UNIQ_37E65615460D9FD7413BC13C1AC10DC4E7087E10 (node_id, property_name, workspace_name, idx), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_nodes_references (source_id INT NOT NULL, source_property_name VARCHAR(220) NOT NULL, target_id INT NOT NULL, INDEX IDX_F3BF7E1158E0B66 (target_id), PRIMARY KEY(source_id, source_property_name, target_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_nodes_weakreferences (source_id INT NOT NULL, source_property_name VARCHAR(220) NOT NULL, target_id INT NOT NULL, INDEX IDX_F0E4F6FA158E0B66 (target_id), PRIMARY KEY(source_id, source_property_name, target_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_type_nodes (node_type_id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, supertypes VARCHAR(255) NOT NULL, is_abstract TINYINT(1) NOT NULL, is_mixin TINYINT(1) NOT NULL, queryable TINYINT(1) NOT NULL, orderable_child_nodes TINYINT(1) NOT NULL, primary_item VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_34B0A8095E237E06 (name), PRIMARY KEY(node_type_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C5414E7AF8F FOREIGN KEY (gallery_id) REFERENCES media__gallery (id)');
        $this->addSql('ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C541EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id)');


        $this->addSql('CREATE TABLE notification__message (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', state INT NOT NULL, restart_count INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, started_at DATETIME DEFAULT NULL, completed_at DATETIME DEFAULT NULL, INDEX idx_state (state), INDEX idx_created_at (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');


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
        $this->addSql('DROP TABLE media__gallery');
        $this->addSql('DROP TABLE media__media');
        $this->addSql('DROP TABLE media__gallery_media');
        $this->addSql('DROP TABLE phpcr_namespaces');
        $this->addSql('DROP TABLE phpcr_workspaces');
        $this->addSql('DROP TABLE phpcr_nodes');
        $this->addSql('DROP TABLE phpcr_internal_index_types');
        $this->addSql('DROP TABLE phpcr_binarydata');
        $this->addSql('DROP TABLE phpcr_nodes_references');
        $this->addSql('DROP TABLE phpcr_nodes_weakreferences');
        $this->addSql('DROP TABLE phpcr_type_nodes');
        $this->addSql('DROP TABLE phpcr_type_props');
        $this->addSql('DROP TABLE phpcr_type_childs');
        $this->addSql('DROP TABLE notification__message');

    }
}
