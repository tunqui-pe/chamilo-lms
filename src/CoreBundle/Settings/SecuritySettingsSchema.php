<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Chamilo\CoreBundle\Form\Type\YesNoType;
use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecuritySettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class SecuritySettingsSchema extends AbstractSettingsSchema
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
                    'allow_captcha' => 'false'
                )
            );
        $allowedTypes = array(
            'allow_browser_sniffer' => array('string'),
            'allow_strength_pass_checker' => array('string'),
        );
        $this->setMultipleAllowedTypes($allowedTypes, $builder);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('filter_terms', 'textarea')
            ->add('allow_browser_sniffer', YesNoType::class)
            ->add('admins_can_set_users_pass', YesNoType::class)
            ->add('allow_strength_pass_checker', YesNoType::class)
            ->add('allow_captcha', YesNoType::class)

        ;
    }
}
