<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Chamilo\SettingsBundle\Transformer\ArrayToIdentifierTransformer;

/**
 * Class RegistrationSettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class RegistrationSettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                array(
                    'required_profile_fields' => [],
                    'allow_registration' => 'false',
                    'allow_registration_as_teacher' => 'false',
                    'allow_lostpassword' => 'true',
                    'page_after_login' => 'user_portal.php',
                    'extendedprofile_registration' => '', //@todo
                    'allow_terms_conditions' => 'false',
                    'student_page_after_login' => '',
                    'teacher_page_after_login' => '',
                    'drh_page_after_login' => '',
                    'sessionadmin_page_after_login' => '',
                    'student_autosubscribe' => '',
                    'teacher_autosubscribe' => '',
                    'drh_autosubscribe' => '',
                    'sessionadmin_autosubscribe' => '',
                    'platform_unsubscribe_allowed' => '',
                )
            )
            ->setAllowedTypes(
                array(
                    'required_profile_fields' => array('array'),
                    'allow_registration' => array('string'),
                    'allow_registration_as_teacher' => array('string'),
                    'allow_lostpassword' => array('string'),
                )
            )
            ->setTransformer(
                'required_profile_fields',
                new ArrayToIdentifierTransformer()
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add(
                'required_profile_fields',
                'choice',
                array(
                    'multiple' => true,
                    'choices' => array(
                        'officialcode' => 'officialcode',
                        'email' => 'email',
                        'language' => 'language',
                        'phone' => 'phone',
                    ),
                )
            )
            ->add(
                'allow_registration',
                'choice',
                array(
                    'choices' => array(
                        'true' => 'Yes',
                        'false' => 'No',
                        'approval' => 'Approval',
                    ),
                )
            )
            ->add('allow_registration_as_teacher', 'yes_no')
            ->add('allow_lostpassword', 'yes_no')
            ->add(
                'page_after_login',
                'choice',
                array(
                    'choices' => array(
                        'index.php' => 'CampusHomepage',
                        'user_portal.php' => 'MyCourses',
                        'main/auth/courses.php' => 'CourseCatalog',
                    ),
                )
            )
            //->add('extendedprofile_registration', '') // ?
            ->add('allow_terms_conditions', 'yes_no')
            ->add('student_page_after_login')
            ->add('teacher_page_after_login')
            ->add('drh_page_after_login')
            ->add('sessionadmin_page_after_login')
            ->add('student_autosubscribe')// ?
            ->add('teacher_autosubscribe')// ?
            ->add('drh_autosubscribe')//?
            ->add('sessionadmin_autosubscribe')// ?
            ->add('platform_unsubscribe_allowed', 'yes_no');
    }
}
