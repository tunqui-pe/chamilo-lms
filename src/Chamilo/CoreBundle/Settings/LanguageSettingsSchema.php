<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LanguageSettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class LanguageSettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                array(
                    'platform_language' => '',
                    'allow_use_sub_language' => '',
                    'auto_detect_language_custom_pages' => '',
                    'show_different_course_language' => '',
                    'language_priority_1' => '',
                    'language_priority_2' => '',
                    'language_priority_3' => '',
                    'language_priority_4' => '',
                )
            )
            ->setAllowedTypes(
                array(
                    'platform_language' => array('string'),
                    'allow_use_sub_language' => array('string'),
                    'auto_detect_language_custom_pages' => array('string'),
                    'show_different_course_language' => array('string'),
                    'language_priority_1' => array('string'),
                    'language_priority_2' => array('string'),
                    'language_priority_3' => array('string'),
                    'language_priority_4' => array('string'),
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('platform_language')
            ->add('allow_use_sub_language', 'yes_no')
            ->add('auto_detect_language_custom_pages', 'yes_no')
            ->add('show_different_course_language', 'yes_no')
            ->add('language_priority_1')
            ->add('language_priority_2')
            ->add('language_priority_3')
            ->add('language_priority_4');
    }
}
