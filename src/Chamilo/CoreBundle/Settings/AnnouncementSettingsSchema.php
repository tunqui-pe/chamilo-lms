<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AnnouncementSettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class AnnouncementSettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                array(//'allow_user_edit_announcement' => '',
                )
            )
            ->setAllowedTypes(
                array(//'allow_user_edit_announcement' => array('string'),
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        /*$builder
            ->add('allow_user_edit_announcement', 'yes_no')
        ;
        */
    }
}
