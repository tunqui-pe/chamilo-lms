<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V_2_0_0;

use Chamilo\CoreBundle\Migrations\AbstractMigrationChamilo;
use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Class Version20
 * Migrate file to updated to Chamilo 2.0
 *
 */
class Version20 extends AbstractMigrationChamilo implements OrderedMigrationInterface
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

        // Update settings variable name
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
            'registration.soap.php.decode_utf8' => 'decode_utf8',
        ];

        foreach ($settings as $oldSetting => $newSetting) {
            $sql = "UPDATE settings_current SET variable = '$newSetting'
                    WHERE variable = '$oldSetting'";
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
            'allow_hr_skills_management' => 'skill',
            'accessibility_font_resize' => 'display',
            'account_valid_duration' => 'profile',
            'activate_email_template' => 'mail',
            'allow_global_chat' => 'chat',
            'allow_lostpassword' => 'registration',
            'allow_registration' => 'registration',
            'allow_registration_as_teacher' => 'registration',
            'allow_skills_tool' => 'skill',
            'allow_students_to_browse_courses' => 'display',
            'allow_terms_conditions' => 'registration',
            'allow_users_to_create_courses' => 'course',
            'auto_detect_language_custom_pages' => 'language',
            'course_validation' => 'course',
            'course_validation_terms_and_conditions_url' => 'course',
            'display_categories_on_homepage' => 'display',
            'display_coursecode_in_courselist' => 'course',
            'display_teacher_in_courselist' => 'course',
            'drh_autosubscribe' => 'registration',
            'drh_page_after_login' => 'registration',
            'enable_help_link' => 'display',
            'example_material_course_creation' => 'course',
            'login_is_email' => 'profile',
            'noreply_email_address' => 'mail',
            'page_after_login' => 'registration',
            'pdf_export_watermark_by_course' => 'document',
            'pdf_export_watermark_enable' => 'document',
            'pdf_export_watermark_text' => 'document',
            'platform_unsubscribe_allowed' => 'registration',
            'send_email_to_admin_when_create_course' => 'course',
            'show_admin_toolbar' => 'display',
            'show_administrator_data' => 'display',
            'show_back_link_on_top_of_tree' => 'display',
            'show_closed_courses' => 'display',
            'show_different_course_language' => 'display',
            'show_email_addresses' => 'display',
            'show_empty_course_categories' => 'display',
            'show_full_skill_name_on_skill_wheel' => 'skill',
            'show_hot_courses' => 'display',
            'show_link_bug_notification' => 'display',
            'show_number_of_courses' => 'display',
            'show_teacher_data' => 'display',
            'showonline' => 'display',
            'student_autosubscribe' => 'registration',
            'student_page_after_login' => 'registration',
            'student_view_enabled' => 'course',
            'teacher_autosubscribe' => 'registration',
            'teacher_page_after_login' => 'registration',
            'time_limit_whosonline' => 'display',
            'user_selected_theme' => 'profile',
            'hide_global_announcements_when_not_connected' => 'announcement',
            'hide_home_top_when_connected' => 'display',
            'hide_logout_button' => 'display',
            'institution_address' => 'platform',
            'redirect_admin_to_courses_list' => 'admin',
            'decode_utf8' => 'webservice',
            'use_custom_pages' => 'platform',
            'allow_group_categories' => 'group',
            'allow_user_headings' => 'display',
            'default_document_quotum' => 'document',
            'default_forum_view' => 'forum',
            'default_group_quotum' => 'document',
            'enable_quiz_scenario' => 'exercise',
            'exercise_max_score' => 'exercise',
            'exercise_min_score' => 'exercise',
            'pdf_logo_header' => 'platform',
            'show_glossary_in_documents' => 'document',
            'show_glossary_in_extra_tools' => 'glossary',
            //'show_toolshortcuts' => '',
            'survey_email_sender_noreply'=> 'survey',
            'allow_coach_feedback_exercises' => 'exercise',
            'sessionadmin_autosubscribe' => 'registration',
            'sessionadmin_page_after_login' => 'registration',
            'show_tutor_data' => 'display'
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
            'server_type',
            'use_session_mode',
            'show_toolshortcuts',
            'show_tabs',
            //'session_page_enabled',
            //'session_tutor_reports_visibility',
            'display_mini_month_calendar',
            'number_of_upcoming_events',
            'chamilo_database_version'
        ];

        foreach ($settings as $setting) {
            $sql = "DELETE FROM settings_current WHERE variable = '$setting'";
            $queries->addQuery($sql);
        }

        $queries->addQuery('UPDATE settings_current SET category = LOWER(category)');
    }

    /**
     *
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
    }
}
