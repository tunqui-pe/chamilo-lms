<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Chamilo\CourseBundle\Tool\BaseTool;
use Chamilo\CourseBundle\ToolChain;
use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Chamilo\SettingsBundle\Transformer\ArrayToIdentifierTransformer;

/**
 * Class CourseSettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class CourseSettingsSchema implements SchemaInterface
{
    /**
     * @var ToolChain
     */
    protected $toolChain;

    public function setToolChain(ToolChain $tools)
    {
        $this->toolChain = $tools;
    }

    /**
     * @return array
     */
    public function getProcessedToolChain()
    {
        $tools = [];
        /** @var BaseTool $tool */
        foreach ($this->toolChain->getTools() as $tool) {
            $name = $tool->getName();
            //$key = $tool->getName();
            $tools[$name] = $name;
        }

        return $tools;
    }

    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $tools = $this->getProcessedToolChain();

        $builder
            ->setDefaults(
                array(
                    'homepage_view' => 'activity_big',
                    'show_tool_shortcuts' => '', //?
                    'active_tools_on_create' => $tools,
                    'display_coursecode_in_courselist' => 'false',
                    'display_teacher_in_courselist' => 'true',
                    'student_view_enabled' => 'true',
                    'go_to_course_after_login' => 'false',
                    'show_navigation_menu' => 'false',
                    'enable_tool_introduction' => 'false',
                    'breadcrumbs_course_homepage' => 'course_title',
                    'example_material_course_creation' => 'true',
                    'allow_course_theme' => 'true',
                    'allow_users_to_create_courses' => 'true',
                    'show_courses_descriptions_in_catalog' => 'true',
                    'send_email_to_admin_when_create_course' => 'false',
                    'allow_user_course_subscription_by_course_admin' => 'true',
                    'course_validation' => 'false',
                    'course_validation_terms_and_conditions_url' => '',
                    'course_hide_tools' => [],
                    'scorm_cumulative_session_time' => 'true',
                    'courses_default_creation_visibility' => '2',
                    //COURSE_VISIBILITY_OPEN_PLATFORM
                    'allow_public_certificates' => 'false',
                    'allow_lp_return_link' => 'true',
                    'course_creation_use_template' => '',
                    'hide_scorm_export_link' => 'false',
                    'hide_scorm_copy_link' => 'false',
                    'hide_scorm_pdf_link' => 'true',
                    'course_catalog_published' => 'false',
                    'course_images_in_courses_list' => 'true'
                )
            )
            ->setAllowedTypes(
                array(
                    'homepage_view' => array('string'),
                    'show_tool_shortcuts' => array('string'),
                    'active_tools_on_create' => array('array'),
                    'course_hide_tools' => array('array'),
                    'display_coursecode_in_courselist' => array('string'),
                    'display_teacher_in_courselist' => array('string'),
                    'student_view_enabled' => array('string'),
                )
            )
            ->setTransformer(
                'active_tools_on_create',
                new ArrayToIdentifierTransformer()
            )
            ->setTransformer(
                'course_hide_tools',
                new ArrayToIdentifierTransformer()
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $tools = $this->getProcessedToolChain();
        $builder
            ->add(
                'homepage_view',
                'choice',
                array(
                    'choices' => array(
                        'activity' => 'activity',
                        'activity_big' => 'activity_big',
                    ),
                )
            )
            ->add('show_tool_shortcuts', 'yes_no')
            ->add(
                'active_tools_on_create',
                'choice',
                array(
                    'choices' => $tools,
                    'multiple' => true,
                    'expanded' => true,
                )
            )
            ->add('display_coursecode_in_courselist', 'yes_no')
            ->add('display_teacher_in_courselist', 'yes_no')
            ->add('student_view_enabled', 'yes_no')
            ->add('go_to_course_after_login', 'yes_no')
            ->add(
                'show_navigation_menu',
                'choice',
                array(
                    'choices' => array(
                        'false' => 'No',
                        'icons' => 'IconsOnly',
                        'text' => 'TextOnly',
                        'iconstext' => 'IconsText',
                    ),
                )
            )
            ->add('enable_tool_introduction', 'yes_no')
            ->add(
                'breadcrumbs_course_homepage',
                'choice',
                array(
                    'choices' => array(
                        'course_home' => 'CourseHomepage',
                        'course_code' => 'CourseCode',
                        'course_title' => 'CourseTitle',
                        'session_name_and_course_title' => 'SessionNameAndCourseTitle',
                    ),
                )
            )
            ->add('example_material_course_creation', 'yes_no')
            ->add('allow_course_theme', 'yes_no')
            ->add('allow_users_to_create_courses', 'yes_no')
            ->add('show_courses_descriptions_in_catalog', 'yes_no')
            ->add('send_email_to_admin_when_create_course', 'yes_no')
            ->add('allow_user_course_subscription_by_course_admin', 'yes_no')
            ->add('course_validation', 'yes_no')
            ->add('course_validation_terms_and_conditions_url', 'url')
            ->add(
                'course_hide_tools',
                'choice',
                array(
                    'choices' => $tools,
                    'multiple' => true,
                    'expanded' => true,
                )
            )
            ->add('scorm_cumulative_session_time', 'yes_no')
            ->add(
                'courses_default_creation_visibility',
                'choice',
                array(
                    'choices' => array(
                        '3' => 'Public',
                        '2' => 'Open',
                        '1' => 'Private',
                        '0' => 'Closed',
                    ),
                )
            )
            ->add('allow_public_certificates', 'yes_no')
            ->add('allow_lp_return_link', 'yes_no')
            ->add('course_creation_use_template')
            ->add('hide_scorm_export_link', 'yes_no')
            ->add('hide_scorm_copy_link', 'yes_no')
            ->add('hide_scorm_pdf_link', 'yes_no')
            ->add('course_catalog_published', 'yes_no')
            ->add('course_images_in_courses_list', 'yes_no')

        ;
    }
}
