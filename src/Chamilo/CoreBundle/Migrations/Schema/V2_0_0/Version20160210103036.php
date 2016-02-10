<?php

namespace Chamilo\CoreBundle\Migrations\Schema\V_2_0_0;

use Chamilo\CoreBundle\Migrations\AbstractMigrationChamilo;
use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class Version20160210103036 extends AbstractMigrationChamilo
{
    /**
     * @param Schema $schema
     * @param QueryBag $queries
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery('CREATE TABLE page__site (id INT AUTO_INCREMENT NOT NULL, enabled TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, relative_path VARCHAR(255) DEFAULT NULL, host VARCHAR(255) NOT NULL, enabled_from DATETIME DEFAULT NULL, enabled_to DATETIME DEFAULT NULL, is_default TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, locale VARCHAR(6) DEFAULT NULL, title VARCHAR(64) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE page__page (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, target_id INT DEFAULT NULL, route_name VARCHAR(255) NOT NULL, page_alias VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, decorate TINYINT(1) NOT NULL, edited TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, slug LONGTEXT DEFAULT NULL, url LONGTEXT DEFAULT NULL, custom_url LONGTEXT DEFAULT NULL, request_method VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, meta_keyword VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, javascript LONGTEXT DEFAULT NULL, stylesheet LONGTEXT DEFAULT NULL, raw_headers LONGTEXT DEFAULT NULL, template VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2FAE39EDF6BD1646 (site_id), INDEX IDX_2FAE39ED727ACA70 (parent_id), INDEX IDX_2FAE39ED158E0B66 (target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE page__snapshot (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, page_id INT DEFAULT NULL, route_name VARCHAR(255) NOT NULL, page_alias VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, decorate TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, url LONGTEXT DEFAULT NULL, parent_id INT DEFAULT NULL, target_id INT DEFAULT NULL, content LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', publication_date_start DATETIME DEFAULT NULL, publication_date_end DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_3963EF9AF6BD1646 (site_id), INDEX IDX_3963EF9AC4663E4 (page_id), INDEX idx_snapshot_dates_enabled (publication_date_start, publication_date_end, enabled), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE page__bloc (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, page_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(64) NOT NULL, settings LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', enabled TINYINT(1) DEFAULT NULL, position INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_FCDC1A97727ACA70 (parent_id), INDEX IDX_FCDC1A97C4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE notification__message (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', state INT NOT NULL, restart_count INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, started_at DATETIME DEFAULT NULL, completed_at DATETIME DEFAULT NULL, INDEX notification_message_state_idx (state), INDEX notification_message_created_at_idx (created_at), INDEX idx_state (state), INDEX idx_created_at (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE timeline__action_component (id INT AUTO_INCREMENT NOT NULL, action_id INT DEFAULT NULL, component_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, text VARCHAR(255) DEFAULT NULL, INDEX IDX_6ACD1B169D32F035 (action_id), INDEX IDX_6ACD1B16E2ABAFFF (component_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE timeline__action (id INT AUTO_INCREMENT NOT NULL, verb VARCHAR(255) NOT NULL, status_current VARCHAR(255) NOT NULL, status_wanted VARCHAR(255) NOT NULL, duplicate_key VARCHAR(255) DEFAULT NULL, duplicate_priority INT DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE timeline__component (id INT AUTO_INCREMENT NOT NULL, model VARCHAR(255) NOT NULL, identifier LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', hash VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1B2F01CDD1B862B8 (hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE timeline__timeline (id INT AUTO_INCREMENT NOT NULL, action_id INT DEFAULT NULL, subject_id INT DEFAULT NULL, context VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_FFBC6AD59D32F035 (action_id), INDEX IDX_FFBC6AD523EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE classification__category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, context VARCHAR(255) DEFAULT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_43629B36727ACA70 (parent_id), INDEX IDX_43629B36E25D857E (context), INDEX IDX_43629B36EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE classification__context (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE classification__tag (id INT AUTO_INCREMENT NOT NULL, context VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_CA57A1C7E25D857E (context), UNIQUE INDEX tag_context (slug, context), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE classification__collection (id INT AUTO_INCREMENT NOT NULL, context VARCHAR(255) DEFAULT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A406B56AE25D857E (context), INDEX IDX_A406B56AEA9FDD75 (media_id), UNIQUE INDEX tag_collection (slug, context), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE media__gallery (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, context VARCHAR(64) NOT NULL, default_format VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE media__media (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, provider_name VARCHAR(255) NOT NULL, provider_status INT NOT NULL, provider_reference VARCHAR(255) NOT NULL, provider_metadata LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', width INT DEFAULT NULL, height INT DEFAULT NULL, length NUMERIC(10, 0) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, content_size INT DEFAULT NULL, copyright VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) DEFAULT NULL, context VARCHAR(64) DEFAULT NULL, cdn_is_flushable TINYINT(1) DEFAULT NULL, cdn_flush_identifier VARCHAR(64) DEFAULT NULL, cdn_flush_at DATETIME DEFAULT NULL, cdn_status INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_5C6DD74E12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE media__gallery_media (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, media_id INT DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_80D4C5414E7AF8F (gallery_id), INDEX IDX_80D4C541EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE fos_user_user_group (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_B3C77447A76ED395 (user_id), INDEX IDX_B3C77447FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE user_audit (id INT NOT NULL, rev INT NOT NULL, picture INT DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, username_canonical VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) DEFAULT NULL, expired TINYINT(1) DEFAULT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) DEFAULT NULL, credentials_expire_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, date_of_birth DATETIME DEFAULT NULL, firstname VARCHAR(64) DEFAULT NULL, lastname VARCHAR(64) DEFAULT NULL, website VARCHAR(64) DEFAULT NULL, biography VARCHAR(1000) DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, locale VARCHAR(8) DEFAULT NULL, timezone VARCHAR(64) DEFAULT NULL, phone VARCHAR(64) DEFAULT NULL, facebook_uid VARCHAR(255) DEFAULT NULL, facebook_name VARCHAR(255) DEFAULT NULL, facebook_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', twitter_uid VARCHAR(255) DEFAULT NULL, twitter_name VARCHAR(255) DEFAULT NULL, twitter_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', gplus_uid VARCHAR(255) DEFAULT NULL, gplus_name VARCHAR(255) DEFAULT NULL, gplus_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', token VARCHAR(255) DEFAULT NULL, two_step_code VARCHAR(255) DEFAULT NULL, user_id INT DEFAULT NULL, auth_source VARCHAR(50) DEFAULT NULL, status INT DEFAULT NULL, official_code VARCHAR(40) DEFAULT NULL, picture_uri VARCHAR(250) DEFAULT NULL, creator_id INT DEFAULT NULL, competences LONGTEXT DEFAULT NULL, diplomas LONGTEXT DEFAULT NULL, openarea LONGTEXT DEFAULT NULL, teach LONGTEXT DEFAULT NULL, productions VARCHAR(250) DEFAULT NULL, chatcall_user_id INT DEFAULT NULL, chatcall_date DATETIME DEFAULT NULL, chatcall_text VARCHAR(50) DEFAULT NULL, language VARCHAR(40) DEFAULT NULL, registration_date DATETIME DEFAULT NULL, expiration_date DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT NULL, openid VARCHAR(255) DEFAULT NULL, theme VARCHAR(255) DEFAULT NULL, hr_dept_id SMALLINT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, emailCanonical VARCHAR(255) DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE fos_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_4B019DDB5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE tool (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE tool_resource_rights (id INT AUTO_INCREMENT NOT NULL, tool_id INT DEFAULT NULL, role VARCHAR(255) NOT NULL, mask INT NOT NULL, INDEX IDX_95CE3398F7B22CC (tool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE session_audit (id INT NOT NULL, rev INT NOT NULL, id_coach INT DEFAULT NULL, session_category_id INT DEFAULT NULL, name VARCHAR(150) DEFAULT NULL, description LONGTEXT DEFAULT NULL, show_description TINYINT(1) DEFAULT NULL, duration INT DEFAULT NULL, nbr_courses SMALLINT DEFAULT NULL, nbr_users INT DEFAULT NULL, nbr_classes INT DEFAULT NULL, session_admin_id INT DEFAULT NULL, visibility INT DEFAULT NULL, promotion_id INT DEFAULT NULL, display_start_date DATETIME DEFAULT NULL, display_end_date DATETIME DEFAULT NULL, access_start_date DATETIME DEFAULT NULL, access_end_date DATETIME DEFAULT NULL, coach_access_start_date DATETIME DEFAULT NULL, coach_access_end_date DATETIME DEFAULT NULL, send_subscription_notification TINYINT(1) DEFAULT \'0\', revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE course_audit (id INT NOT NULL, rev INT NOT NULL, room_id INT DEFAULT NULL, title VARCHAR(250) DEFAULT NULL, code VARCHAR(40) DEFAULT NULL, directory VARCHAR(40) DEFAULT NULL, course_language VARCHAR(20) DEFAULT NULL, description LONGTEXT DEFAULT NULL, category_code VARCHAR(40) DEFAULT NULL, visibility INT DEFAULT NULL, show_score INT DEFAULT NULL, tutor_name VARCHAR(200) DEFAULT NULL, visual_code VARCHAR(40) DEFAULT NULL, department_name VARCHAR(30) DEFAULT NULL, department_url VARCHAR(180) DEFAULT NULL, disk_quota BIGINT DEFAULT NULL, last_visit DATETIME DEFAULT NULL, last_edit DATETIME DEFAULT NULL, creation_date DATETIME DEFAULT NULL, expiration_date DATETIME DEFAULT NULL, subscribe TINYINT(1) DEFAULT NULL, unsubscribe TINYINT(1) DEFAULT NULL, registration_code VARCHAR(255) DEFAULT NULL, legal LONGTEXT DEFAULT NULL, activate_legal INT DEFAULT NULL, add_teachers_to_sessions_courses TINYINT(1) DEFAULT NULL, course_type_id INT DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE skill_rel_user_comment (id INT AUTO_INCREMENT NOT NULL, skill_rel_user_id INT DEFAULT NULL, feedback_giver_id INT DEFAULT NULL, feedback_text LONGTEXT NOT NULL, feedback_value INT DEFAULT 1, feedback_datetime DATETIME NOT NULL, INDEX IDX_7AE9F6B6484A9317 (skill_rel_user_id), INDEX IDX_7AE9F6B63AF3B65B (feedback_giver_id), INDEX idx_select_su_giver (skill_rel_user_id, feedback_giver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE resource_link (id INT AUTO_INCREMENT NOT NULL, resource_node_id INT DEFAULT NULL, session_id INT DEFAULT NULL, user_id INT DEFAULT NULL, c_id INT DEFAULT NULL, group_id INT DEFAULT NULL, usergroup_id INT DEFAULT NULL, private TINYINT(1) DEFAULT NULL, public TINYINT(1) DEFAULT NULL, start_visibility_at DATETIME DEFAULT NULL, end_visibility_at DATETIME DEFAULT NULL, INDEX IDX_398C394B1BAD783F (resource_node_id), INDEX IDX_398C394B613FECDF (session_id), INDEX IDX_398C394BA76ED395 (user_id), INDEX IDX_398C394B91D79BD3 (c_id), INDEX IDX_398C394BFE54D947 (group_id), INDEX IDX_398C394BD2112630 (usergroup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE resource_node (id INT AUTO_INCREMENT NOT NULL, tool_id INT DEFAULT NULL, creator_id INT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, level INT DEFAULT NULL, path VARCHAR(3000) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_8A5F48FF8F7B22CC (tool_id), INDEX IDX_8A5F48FF61220EA6 (creator_id), INDEX IDX_8A5F48FF727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE resource_rights (id INT AUTO_INCREMENT NOT NULL, resource_link_id INT DEFAULT NULL, role VARCHAR(255) NOT NULL, mask INT NOT NULL, UNIQUE INDEX UNIQ_C99C3BF9F004E599 (resource_link_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('CREATE TABLE revisions (id INT AUTO_INCREMENT NOT NULL, timestamp DATETIME NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $queries->addQuery('ALTER TABLE page__page ADD CONSTRAINT FK_2FAE39EDF6BD1646 FOREIGN KEY (site_id) REFERENCES page__site (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE page__page ADD CONSTRAINT FK_2FAE39ED727ACA70 FOREIGN KEY (parent_id) REFERENCES page__page (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE page__page ADD CONSTRAINT FK_2FAE39ED158E0B66 FOREIGN KEY (target_id) REFERENCES page__page (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE page__snapshot ADD CONSTRAINT FK_3963EF9AF6BD1646 FOREIGN KEY (site_id) REFERENCES page__site (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE page__snapshot ADD CONSTRAINT FK_3963EF9AC4663E4 FOREIGN KEY (page_id) REFERENCES page__page (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE page__bloc ADD CONSTRAINT FK_FCDC1A97727ACA70 FOREIGN KEY (parent_id) REFERENCES page__bloc (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE page__bloc ADD CONSTRAINT FK_FCDC1A97C4663E4 FOREIGN KEY (page_id) REFERENCES page__page (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE timeline__action_component ADD CONSTRAINT FK_6ACD1B169D32F035 FOREIGN KEY (action_id) REFERENCES timeline__action (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE timeline__action_component ADD CONSTRAINT FK_6ACD1B16E2ABAFFF FOREIGN KEY (component_id) REFERENCES timeline__component (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE timeline__timeline ADD CONSTRAINT FK_FFBC6AD59D32F035 FOREIGN KEY (action_id) REFERENCES timeline__action (id)');
        $queries->addQuery('ALTER TABLE timeline__timeline ADD CONSTRAINT FK_FFBC6AD523EDC87 FOREIGN KEY (subject_id) REFERENCES timeline__component (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36727ACA70 FOREIGN KEY (parent_id) REFERENCES classification__category (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36E25D857E FOREIGN KEY (context) REFERENCES classification__context (id)');
        $queries->addQuery('ALTER TABLE classification__category ADD CONSTRAINT FK_43629B36EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE SET NULL');
        $queries->addQuery('ALTER TABLE classification__tag ADD CONSTRAINT FK_CA57A1C7E25D857E FOREIGN KEY (context) REFERENCES classification__context (id)');
        $queries->addQuery('ALTER TABLE classification__collection ADD CONSTRAINT FK_A406B56AE25D857E FOREIGN KEY (context) REFERENCES classification__context (id)');
        $queries->addQuery('ALTER TABLE classification__collection ADD CONSTRAINT FK_A406B56AEA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE SET NULL');
        $queries->addQuery('ALTER TABLE media__media ADD CONSTRAINT FK_5C6DD74E12469DE2 FOREIGN KEY (category_id) REFERENCES classification__category (id) ON DELETE SET NULL');
        $queries->addQuery('ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C5414E7AF8F FOREIGN KEY (gallery_id) REFERENCES media__gallery (id)');
        $queries->addQuery('ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C541EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id)');
        $queries->addQuery('ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $queries->addQuery('ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447FE54D947 FOREIGN KEY (group_id) REFERENCES fos_group (id)');
        $queries->addQuery('ALTER TABLE tool_resource_rights ADD CONSTRAINT FK_95CE3398F7B22CC FOREIGN KEY (tool_id) REFERENCES tool (id)');
        $queries->addQuery('ALTER TABLE skill_rel_user_comment ADD CONSTRAINT FK_7AE9F6B6484A9317 FOREIGN KEY (skill_rel_user_id) REFERENCES skill_rel_user (id)');
        $queries->addQuery('ALTER TABLE skill_rel_user_comment ADD CONSTRAINT FK_7AE9F6B63AF3B65B FOREIGN KEY (feedback_giver_id) REFERENCES user (id)');
        $queries->addQuery('ALTER TABLE resource_link ADD CONSTRAINT FK_398C394B1BAD783F FOREIGN KEY (resource_node_id) REFERENCES resource_node (id)');
        $queries->addQuery('ALTER TABLE resource_link ADD CONSTRAINT FK_398C394B613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $queries->addQuery('ALTER TABLE resource_link ADD CONSTRAINT FK_398C394BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $queries->addQuery('ALTER TABLE resource_link ADD CONSTRAINT FK_398C394B91D79BD3 FOREIGN KEY (c_id) REFERENCES course (id)');
        $queries->addQuery('ALTER TABLE resource_link ADD CONSTRAINT FK_398C394BFE54D947 FOREIGN KEY (group_id) REFERENCES c_group_info (iid)');
        $queries->addQuery('ALTER TABLE resource_link ADD CONSTRAINT FK_398C394BD2112630 FOREIGN KEY (usergroup_id) REFERENCES usergroup (id)');
        $queries->addQuery('ALTER TABLE resource_node ADD CONSTRAINT FK_8A5F48FF8F7B22CC FOREIGN KEY (tool_id) REFERENCES tool (id)');
        $queries->addQuery('ALTER TABLE resource_node ADD CONSTRAINT FK_8A5F48FF61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE resource_node ADD CONSTRAINT FK_8A5F48FF727ACA70 FOREIGN KEY (parent_id) REFERENCES resource_node (id) ON DELETE CASCADE');
        $queries->addQuery('ALTER TABLE resource_rights ADD CONSTRAINT FK_C99C3BF9F004E599 FOREIGN KEY (resource_link_id) REFERENCES resource_link (id)');
        $queries->addQuery('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $queries->addQuery('ALTER TABLE user ADD picture INT DEFAULT NULL, ADD enabled TINYINT(1) NOT NULL, ADD locked TINYINT(1) NOT NULL, ADD expired TINYINT(1) NOT NULL, ADD expires_at DATETIME DEFAULT NULL, ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD credentials_expired TINYINT(1) NOT NULL, ADD credentials_expire_at DATETIME DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD date_of_birth DATETIME DEFAULT NULL, ADD website VARCHAR(64) DEFAULT NULL, ADD biography VARCHAR(1000) DEFAULT NULL, ADD gender VARCHAR(1) DEFAULT NULL, ADD locale VARCHAR(8) DEFAULT NULL, ADD timezone VARCHAR(64) DEFAULT NULL, ADD facebook_uid VARCHAR(255) DEFAULT NULL, ADD facebook_name VARCHAR(255) DEFAULT NULL, ADD facebook_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD twitter_uid VARCHAR(255) DEFAULT NULL, ADD twitter_name VARCHAR(255) DEFAULT NULL, ADD twitter_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD gplus_uid VARCHAR(255) DEFAULT NULL, ADD gplus_name VARCHAR(255) DEFAULT NULL, ADD gplus_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD token VARCHAR(255) DEFAULT NULL, ADD two_step_code VARCHAR(255) DEFAULT NULL, ADD emailCanonical VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE username_canonical username_canonical VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE lastname lastname VARCHAR(64) DEFAULT NULL, CHANGE firstname firstname VARCHAR(64) DEFAULT NULL, CHANGE phone phone VARCHAR(64) DEFAULT NULL');
        $queries->addQuery('ALTER TABLE user ADD CONSTRAINT FK_8D93D64916DB4F89 FOREIGN KEY (picture) REFERENCES media__media (id)');
        $queries->addQuery('CREATE INDEX IDX_8D93D64916DB4F89 ON user (picture)');

        $queries->addQuery('ALTER TABLE access_url_rel_user DROP PRIMARY KEY');
        $queries->addQuery('ALTER TABLE access_url_rel_user CHANGE access_url_id access_url_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $queries->addQuery('ALTER TABLE access_url_rel_user ADD id INT NOT NULL PRIMARY KEY AUTO_INCREMENT');

        $queries->addQuery('ALTER TABLE course_category CHANGE parent_id parent_id INT DEFAULT NULL');
        $queries->addQuery('ALTER TABLE course_category ADD CONSTRAINT FK_AFF87497727ACA70 FOREIGN KEY (parent_id) REFERENCES course_category (id)');
        $queries->addQuery('ALTER TABLE skill_rel_user ADD argumentation LONGTEXT NOT NULL, CHANGE course_id course_id INT DEFAULT NULL, CHANGE session_id session_id INT DEFAULT NULL');
        $queries->addQuery('ALTER TABLE skill_rel_user ADD CONSTRAINT FK_79D3D95AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $queries->addQuery('ALTER TABLE skill_rel_user ADD CONSTRAINT FK_79D3D95A5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $queries->addQuery('ALTER TABLE skill_rel_user ADD CONSTRAINT FK_79D3D95A591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $queries->addQuery('ALTER TABLE skill_rel_user ADD CONSTRAINT FK_79D3D95A613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $queries->addQuery('CREATE INDEX IDX_79D3D95AA76ED395 ON skill_rel_user (user_id)');
        $queries->addQuery('CREATE INDEX IDX_79D3D95A5585C142 ON skill_rel_user (skill_id)');
        $queries->addQuery('CREATE INDEX IDX_79D3D95A591CC992 ON skill_rel_user (course_id)');
        $queries->addQuery('CREATE INDEX IDX_79D3D95A613FECDF ON skill_rel_user (session_id)');
        $queries->addQuery('CREATE INDEX idx_select_s_c_u ON skill_rel_user (session_id, course_id, user_id)');
        $queries->addQuery('CREATE INDEX idx_select_sk_u ON skill_rel_user (skill_id, user_id)');
        $queries->addQuery('DROP INDEX user_sco_course_sv_stack ON track_stored_values_stack');
        $queries->addQuery('ALTER TABLE extra_field ADD configuration LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD storage_type LONGTEXT DEFAULT NULL, ADD updated_at DATETIME NOT NULL, CHANGE field_type field_type VARCHAR(255) NOT NULL');
        $queries->addQuery('ALTER TABLE course_request CHANGE user_id user_id INT DEFAULT NULL');
        $queries->addQuery('ALTER TABLE course_request ADD CONSTRAINT FK_33548A73A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $queries->addQuery('CREATE INDEX IDX_33548A73A76ED395 ON course_request (user_id)');
        $queries->addQuery('ALTER TABLE extra_field_values ADD CONSTRAINT FK_171DF924126F525E FOREIGN KEY (item_id) REFERENCES user (id)');
        $queries->addQuery('CREATE INDEX IDX_171DF924126F525E ON extra_field_values (item_id)');
        $queries->addQuery('ALTER TABLE settings_current ADD CONSTRAINT FK_62F79C3B9436187B FOREIGN KEY (access_url) REFERENCES access_url (id)');
        $queries->addQuery('DROP INDEX user_sco_course_sv ON track_stored_values');
        $queries->addQuery('ALTER TABLE c_group_info ADD CONSTRAINT FK_CE06532491D79BD3 FOREIGN KEY (c_id) REFERENCES course (id)');
        $queries->addQuery('ALTER TABLE c_tool ADD CONSTRAINT FK_8456658091D79BD3 FOREIGN KEY (c_id) REFERENCES course (id)');
        $queries->addQuery('ALTER TABLE c_tool_intro CHANGE id tool VARCHAR(255) NOT NULL');

        /*$queries->addQuery('ALTER TABLE c_notebook MODIFY iid INT NOT NULL');
        $queries->addQuery('DROP INDEX course ON c_notebook');
        $queries->addQuery('ALTER TABLE c_notebook DROP PRIMARY KEY');
        $queries->addQuery('ALTER TABLE c_notebook DROP c_id, DROP notebook_id, DROP user_id, DROP course, DROP session_id, DROP creation_date, DROP update_date, CHANGE iid id INT AUTO_INCREMENT NOT NULL, CHANGE status resource_node_id INT DEFAULT NULL, CHANGE title name VARCHAR(255) NOT NULL');
        $queries->addQuery('ALTER TABLE c_notebook ADD CONSTRAINT FK_E7EE1CE01BAD783F FOREIGN KEY (resource_node_id) REFERENCES resource_node (id)');
        $queries->addQuery('CREATE UNIQUE INDEX UNIQ_E7EE1CE01BAD783F ON c_notebook (resource_node_id)');
        $queries->addQuery('ALTER TABLE c_notebook ADD PRIMARY KEY (id)');*/
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {

        $queries->addQuery('ALTER TABLE page__page DROP FOREIGN KEY FK_2FAE39EDF6BD1646');
        $queries->addQuery('ALTER TABLE page__snapshot DROP FOREIGN KEY FK_3963EF9AF6BD1646');
        $queries->addQuery('ALTER TABLE page__page DROP FOREIGN KEY FK_2FAE39ED727ACA70');
        $queries->addQuery('ALTER TABLE page__page DROP FOREIGN KEY FK_2FAE39ED158E0B66');
        $queries->addQuery('ALTER TABLE page__snapshot DROP FOREIGN KEY FK_3963EF9AC4663E4');
        $queries->addQuery('ALTER TABLE page__bloc DROP FOREIGN KEY FK_FCDC1A97C4663E4');
        $queries->addQuery('ALTER TABLE page__bloc DROP FOREIGN KEY FK_FCDC1A97727ACA70');
        $queries->addQuery('ALTER TABLE timeline__action_component DROP FOREIGN KEY FK_6ACD1B169D32F035');
        $queries->addQuery('ALTER TABLE timeline__timeline DROP FOREIGN KEY FK_FFBC6AD59D32F035');
        $queries->addQuery('ALTER TABLE timeline__action_component DROP FOREIGN KEY FK_6ACD1B16E2ABAFFF');
        $queries->addQuery('ALTER TABLE timeline__timeline DROP FOREIGN KEY FK_FFBC6AD523EDC87');
        $queries->addQuery('ALTER TABLE classification__category DROP FOREIGN KEY FK_43629B36727ACA70');
        $queries->addQuery('ALTER TABLE media__media DROP FOREIGN KEY FK_5C6DD74E12469DE2');
        $queries->addQuery('ALTER TABLE classification__category DROP FOREIGN KEY FK_43629B36E25D857E');
        $queries->addQuery('ALTER TABLE classification__tag DROP FOREIGN KEY FK_CA57A1C7E25D857E');
        $queries->addQuery('ALTER TABLE classification__collection DROP FOREIGN KEY FK_A406B56AE25D857E');
        $queries->addQuery('ALTER TABLE media__gallery_media DROP FOREIGN KEY FK_80D4C5414E7AF8F');
        $queries->addQuery('ALTER TABLE classification__category DROP FOREIGN KEY FK_43629B36EA9FDD75');
        $queries->addQuery('ALTER TABLE classification__collection DROP FOREIGN KEY FK_A406B56AEA9FDD75');
        $queries->addQuery('ALTER TABLE media__gallery_media DROP FOREIGN KEY FK_80D4C541EA9FDD75');
        $queries->addQuery('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64916DB4F89');
        $queries->addQuery('ALTER TABLE fos_user_user_group DROP FOREIGN KEY FK_B3C77447FE54D947');
        $queries->addQuery('ALTER TABLE tool_resource_rights DROP FOREIGN KEY FK_95CE3398F7B22CC');
        $queries->addQuery('ALTER TABLE resource_node DROP FOREIGN KEY FK_8A5F48FF8F7B22CC');
        $queries->addQuery('ALTER TABLE resource_rights DROP FOREIGN KEY FK_C99C3BF9F004E599');
        $queries->addQuery('ALTER TABLE resource_link DROP FOREIGN KEY FK_398C394B1BAD783F');
        $queries->addQuery('ALTER TABLE resource_node DROP FOREIGN KEY FK_8A5F48FF727ACA70');
        $queries->addQuery('ALTER TABLE c_notebook DROP FOREIGN KEY FK_E7EE1CE01BAD783F');
        $queries->addQuery('DROP TABLE page__site');
        $queries->addQuery('DROP TABLE page__page');
        $queries->addQuery('DROP TABLE page__snapshot');
        $queries->addQuery('DROP TABLE page__bloc');
        $queries->addQuery('DROP TABLE notification__message');
        $queries->addQuery('DROP TABLE timeline__action_component');
        $queries->addQuery('DROP TABLE timeline__action');
        $queries->addQuery('DROP TABLE timeline__component');
        $queries->addQuery('DROP TABLE timeline__timeline');
        $queries->addQuery('DROP TABLE classification__category');
        $queries->addQuery('DROP TABLE classification__context');
        $queries->addQuery('DROP TABLE classification__tag');
        $queries->addQuery('DROP TABLE classification__collection');
        $queries->addQuery('DROP TABLE media__gallery');
        $queries->addQuery('DROP TABLE media__media');
        $queries->addQuery('DROP TABLE media__gallery_media');
        $queries->addQuery('DROP TABLE fos_user_user_group');
        $queries->addQuery('DROP TABLE user_audit');
        $queries->addQuery('DROP TABLE fos_group');
        $queries->addQuery('DROP TABLE migrations');
        $queries->addQuery('DROP TABLE migrations_data');
        $queries->addQuery('DROP TABLE tool');
        $queries->addQuery('DROP TABLE tool_resource_rights');
        $queries->addQuery('DROP TABLE session_audit');
        $queries->addQuery('DROP TABLE course_audit');
        $queries->addQuery('DROP TABLE skill_rel_user_comment');
        $queries->addQuery('DROP TABLE resource_link');
        $queries->addQuery('DROP TABLE resource_node');
        $queries->addQuery('DROP TABLE resource_rights');
        $queries->addQuery('DROP TABLE revisions');
        $queries->addQuery('ALTER TABLE access_url_rel_user MODIFY id INT NOT NULL');
        $queries->addQuery('ALTER TABLE access_url_rel_user DROP PRIMARY KEY');
        $queries->addQuery('ALTER TABLE access_url_rel_user DROP id, CHANGE user_id user_id INT NOT NULL, CHANGE access_url_id access_url_id INT NOT NULL');
        $queries->addQuery('ALTER TABLE access_url_rel_user ADD PRIMARY KEY (access_url_id, user_id)');
        $queries->addQuery('ALTER TABLE c_group_info DROP FOREIGN KEY FK_CE06532491D79BD3');
        $queries->addQuery('ALTER TABLE c_notebook MODIFY id INT NOT NULL');
        $queries->addQuery('DROP INDEX UNIQ_E7EE1CE01BAD783F ON c_notebook');
        $queries->addQuery('ALTER TABLE c_notebook DROP PRIMARY KEY');
        $queries->addQuery('ALTER TABLE c_notebook ADD c_id INT NOT NULL, ADD notebook_id INT NOT NULL, ADD user_id INT NOT NULL, ADD course VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci, ADD session_id INT NOT NULL, ADD creation_date DATETIME NOT NULL, ADD update_date DATETIME NOT NULL, CHANGE id iid INT AUTO_INCREMENT NOT NULL, CHANGE name title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE resource_node_id status INT DEFAULT NULL');
        $queries->addQuery('CREATE INDEX course ON c_notebook (c_id)');
        $queries->addQuery('ALTER TABLE c_notebook ADD PRIMARY KEY (iid)');
        $queries->addQuery('ALTER TABLE c_tool DROP FOREIGN KEY FK_8456658091D79BD3');
        $queries->addQuery('ALTER TABLE c_tool_intro CHANGE tool id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $queries->addQuery('ALTER TABLE course_category DROP FOREIGN KEY FK_AFF87497727ACA70');
        $queries->addQuery('ALTER TABLE course_category CHANGE parent_id parent_id VARCHAR(40) DEFAULT NULL COLLATE utf8_unicode_ci');
        $queries->addQuery('ALTER TABLE course_request DROP FOREIGN KEY FK_33548A73A76ED395');
        $queries->addQuery('DROP INDEX IDX_33548A73A76ED395 ON course_request');
        $queries->addQuery('ALTER TABLE course_request CHANGE user_id user_id INT NOT NULL');
        $queries->addQuery('ALTER TABLE extra_field DROP configuration, DROP storage_type, DROP updated_at, CHANGE field_type field_type INT NOT NULL');
        $queries->addQuery('ALTER TABLE extra_field_values DROP FOREIGN KEY FK_171DF924126F525E');
        $queries->addQuery('DROP INDEX IDX_171DF924126F525E ON extra_field_values');
        $queries->addQuery('ALTER TABLE settings_current DROP FOREIGN KEY FK_62F79C3B9436187B');
        $queries->addQuery('ALTER TABLE skill_rel_user DROP FOREIGN KEY FK_79D3D95AA76ED395');
        $queries->addQuery('ALTER TABLE skill_rel_user DROP FOREIGN KEY FK_79D3D95A5585C142');
        $queries->addQuery('ALTER TABLE skill_rel_user DROP FOREIGN KEY FK_79D3D95A591CC992');
        $queries->addQuery('ALTER TABLE skill_rel_user DROP FOREIGN KEY FK_79D3D95A613FECDF');
        $queries->addQuery('DROP INDEX IDX_79D3D95AA76ED395 ON skill_rel_user');
        $queries->addQuery('DROP INDEX IDX_79D3D95A5585C142 ON skill_rel_user');
        $queries->addQuery('DROP INDEX IDX_79D3D95A591CC992 ON skill_rel_user');
        $queries->addQuery('DROP INDEX IDX_79D3D95A613FECDF ON skill_rel_user');
        $queries->addQuery('DROP INDEX idx_select_s_c_u ON skill_rel_user');
        $queries->addQuery('DROP INDEX idx_select_sk_u ON skill_rel_user');
        $queries->addQuery('ALTER TABLE skill_rel_user DROP argumentation, CHANGE course_id course_id INT NOT NULL, CHANGE session_id session_id INT NOT NULL');
        $queries->addQuery('CREATE INDEX user_sco_course_sv ON track_stored_values (user_id, sco_id, course_id, sv_key)');
        $queries->addQuery('CREATE INDEX user_sco_course_sv_stack ON track_stored_values_stack (user_id, sco_id, course_id, sv_key, stack_order)');
        $queries->addQuery('DROP INDEX IDX_8D93D64916DB4F89 ON user');
        $queries->addQuery('ALTER TABLE user DROP picture, DROP enabled, DROP locked, DROP expired, DROP expires_at, DROP roles, DROP credentials_expired, DROP credentials_expire_at, DROP created_at, DROP updated_at, DROP date_of_birth, DROP website, DROP biography, DROP gender, DROP locale, DROP timezone, DROP facebook_uid, DROP facebook_name, DROP facebook_data, DROP twitter_uid, DROP twitter_name, DROP twitter_data, DROP gplus_uid, DROP gplus_name, DROP gplus_data, DROP token, DROP two_step_code, DROP emailCanonical, CHANGE username username VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, CHANGE username_canonical username_canonical VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, CHANGE firstname firstname VARCHAR(60) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE lastname lastname VARCHAR(60) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE phone phone VARCHAR(30) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE email email VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci');
        $queries->addQuery('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }
}
