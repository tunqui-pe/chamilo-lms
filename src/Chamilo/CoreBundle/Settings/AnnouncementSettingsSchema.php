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
            ->setDefaults(array(
                    'hide_global_announcements_when_not_connected' => 'false',
                )
            )
            ->setAllowedTypes(
                array(
                    'hide_global_announcements_when_not_connected' => array('string'),
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('hide_global_announcements_when_not_connected', 'yes_no')
        ;
    }
}
