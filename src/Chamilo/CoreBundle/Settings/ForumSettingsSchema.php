<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ForumSettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class ForumSettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                array(
                    'default_forum_view' => '',
                    'display_groups_forum_in_general_tool' => '',
                )
            )
            ->setAllowedTypes(
                array(
                    'default_forum_view' => array('string'),
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
            ->add(
                'default_forum_view',
                'choice',
                array(
                    'choices' => array(
                        'flat' => 'Flat',
                        'threaded' => 'Threaded',
                        'nested' => 'Nested',
                    ),
                )
            )
            ->add('display_groups_forum_in_general_tool', 'yes_no');
    }
}
