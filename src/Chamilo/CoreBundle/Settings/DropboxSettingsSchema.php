<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DropboxSettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class DropboxSettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                array(
                    'dropbox_allow_overwrite' => 'true',
                    'dropbox_max_filesize' => '100000000',
                    'dropbox_allow_just_upload' => 'true',
                    'dropbox_allow_student_to_student' => 'true',
                    'dropbox_allow_group' => 'true',
                    'dropbox_allow_mailing' => 'false'
                )
            )
            ->setAllowedTypes(
                array(
                    'dropbox_allow_overwrite' => array('string'),
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('dropbox_allow_overwrite', 'yes_no')
            ->add('dropbox_max_filesize')
            ->add('dropbox_allow_just_upload', 'yes_no')
            ->add('dropbox_allow_student_to_student', 'yes_no')
            ->add('dropbox_allow_group', 'yes_no')
            ->add('dropbox_allow_mailing', 'yes_no')
        ;
    }
}
