<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CourseBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DropboxCourseSettingsSchema
 * @package Chamilo\CourseBundle\Settings
 */
class DropboxCourseSettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(array(
                'enabled' => '',
                'email_alert_on_new_doc_dropbox' => ''
            ))
            ->setAllowedTypes(array(
                'enabled' => array('string'),
                'email_alert_on_new_doc_dropbox' => array('string'),
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
            ->add('email_alert_on_new_doc_dropbox', 'yes_no')
        ;
    }
}
