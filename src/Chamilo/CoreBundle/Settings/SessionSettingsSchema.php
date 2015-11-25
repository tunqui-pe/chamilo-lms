<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SessionSettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class SessionSettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                array(
                    'add_users_by_coach' => 'false',
                    'extend_rights_for_coach' => '',
                    'show_session_coach' => '',
                    'show_session_data' => '',
                    'allow_coach_to_edit_course_session' => '',
                    'show_groups_to_users' => '',
                    'hide_courses_in_sessions' => '',
                    'allow_session_admins_to_manage_all_sessions' => '',
                    'session_tutor_reports_visibility' => '',
                    'session_page_enabled' => 'true',
                    'allow_teachers_to_create_sessions' => '',
                    'prevent_session_admins_to_manage_all_users' => '',
                    'session_course_ordering' => '',
                    'limit_session_admin_role' => '',
                    'allow_tutors_to_assign_students_to_session' => '',
                    'drh_can_access_all_session_content' => '',
                    'catalog_allow_session_auto_subscription' => ''
                )
            )
            ->setAllowedTypes(
                array(
                    'add_users_by_coach' => array('string'),
                )
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('add_users_by_coach', 'yes_no')
            ->add('extend_rights_for_coach', 'yes_no')
            ->add('show_session_coach', 'yes_no')
            ->add('show_session_data', 'yes_no')
            ->add('allow_coach_to_edit_course_session', 'yes_no')
            ->add('show_groups_to_users', 'yes_no')
            ->add('hide_courses_in_sessions', 'yes_no')
            ->add('allow_session_admins_to_manage_all_sessions', 'yes_no')
            ->add('session_tutor_reports_visibility', 'yes_no')
            ->add('session_page_enabled', 'yes_no')
            ->add('allow_teachers_to_create_sessions', 'yes_no')
            ->add('prevent_session_admins_to_manage_all_users', 'yes_no')
            ->add('session_course_ordering')
            ->add('limit_session_admin_role', 'yes_no')
            ->add('allow_tutors_to_assign_students_to_session', 'yes_no')
            ->add('drh_can_access_all_session_content', 'yes_no')
            ->add('catalog_allow_session_auto_subscription', 'yes_no')
        ;
    }
}
