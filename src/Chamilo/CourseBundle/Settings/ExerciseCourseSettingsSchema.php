<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CourseBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ExerciseCourseSettingsSchema
 * @package Chamilo\CourseBundle\Settings
 */
class ExerciseCourseSettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(array(
                'enabled' => '',
                'email_alert_manager_on_new_quiz' => '',
            ))
            ->setAllowedTypes(array(
                'enabled' => array('string'),
                'email_alert_manager_on_new_quiz' => array('string'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('enabled', 'yes_no')
            ->add('email_alert_manager_on_new_quiz', 'yes_no')
        ;
    }
}
