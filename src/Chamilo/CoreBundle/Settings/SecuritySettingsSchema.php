<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecuritySettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class SecuritySettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                array(
                    'filter_terms' => '',
                    'allow_browser_sniffer' => 'false',
                    'admins_can_set_users_pass' => '', // ?
                    'allow_strength_pass_checker' => 'true',
                )
            )
            ->setAllowedTypes(
                array(
                    'allow_browser_sniffer' => array('string'),
                    'allow_strength_pass_checker' => array('string'),
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('filter_terms', 'textarea')
            ->add('allow_browser_sniffer', 'yes_no')
            ->add('admins_can_set_users_pass', 'yes_no')
            ->add('allow_strength_pass_checker', 'yes_no');
    }
}
