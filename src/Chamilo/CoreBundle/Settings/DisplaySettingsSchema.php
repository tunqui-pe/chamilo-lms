<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DisplaySettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class DisplaySettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                array(
                    'enable_help_link' => 'true',
                    'show_administrator_data' => 'true',
                    'show_tutor_data' => 'true',
                    'show_teacher_data' => 'true',
                    'showonline' => 'world',
                    'allow_user_headings' => 'false',
                    'time_limit_whosonline' => '30',
                    'show_email_addresses' => 'false',
                    'show_number_of_courses' => 'false',
                    'show_empty_course_categories' => 'true',
                    'show_back_link_on_top_of_tree' => 'false',
                    'show_different_course_language' => 'true',
                    'display_categories_on_homepage' => 'false',
                    'show_closed_courses' => 'false',
                    'allow_students_to_browse_courses' => 'true',
                    'show_link_bug_notification' => 'true',
                    'accessibility_font_resize' => 'false',
                    'show_admin_toolbar' => 'show_to_admin',
                    'show_hot_courses' => 'true',
                    'user_name_order' => '', // ?
                    'user_name_sort_by' => '', // ?
                    'use_virtual_keyboard' => '', //?
                    'disable_copy_paste' => '',//?
                    'breadcrumb_navigation_display' => '',//?
                    'bug_report_link' => '', //?
                )
            )
            ->setAllowedTypes(
                array(
                    'time_limit_whosonline' => array('string'),
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('enable_help_link', 'yes_no')
            ->add('show_administrator_data', 'yes_no')
            ->add('show_tutor_data', 'yes_no')
            ->add('show_teacher_data', 'yes_no')
            ->add(
                'showonline',
                'choice',
                array(
                    'choices' => array(
                        'course' => 'Course',
                        'users' => 'Users',
                        'world' => 'World',
                    ),
                )
            )
            ->add('allow_user_headings', 'yes_no')
            ->add('time_limit_whosonline')
            ->add('show_email_addresses', 'yes_no')
            ->add('show_number_of_courses', 'yes_no')
            ->add('show_empty_course_categories', 'yes_no')
            ->add('show_back_link_on_top_of_tree', 'yes_no')
            ->add('show_empty_course_categories', 'yes_no')
            ->add('show_different_course_language', 'yes_no')
            ->add('display_categories_on_homepage', 'yes_no')
            ->add('show_closed_courses', 'yes_no')
            ->add('allow_students_to_browse_courses', 'yes_no')
            ->add('show_link_bug_notification', 'yes_no')
            ->add('accessibility_font_resize', 'yes_no')
            ->add(
                'show_admin_toolbar',
                'choice',
                [
                    'choices' => [
                        'do_not_show' => 'DoNotShow',
                        'show_to_admin' => 'ShowToAdminsOnly',
                        'show_to_admin_and_teachers' => 'ShowToAdminsAndTeachers',
                        'show_to_all' => 'ShowToAllUsers'
                    ]
                ])
            ->add('show_hot_courses', 'yes_no')
            ->add('use_virtual_keyboard', 'yes_no')
            ->add('disable_copy_paste', 'yes_no')
            ->add('breadcrumb_navigation_display', 'yes_no')
            ->add('bug_report_link', 'yes_no');
    }
}
