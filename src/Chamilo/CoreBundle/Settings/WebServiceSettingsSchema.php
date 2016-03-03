<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class WebServiceSettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class WebServiceSettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                array(
                    'decode_utf8' => 'false',
                    'messaging_allow_send_push_notification' => 'false',
                    'messaging_gdc_project_number' => '',
                    'messaging_gdc_api_key' => ''
                )
            )
            ->setAllowedTypes(
                array(
                    // commenting this line allows setting to be null
                    //'header_extra_content' => array('string'),
                    //'footer_extra_content' => array('string'),
                    'messaging_gdc_project_number' => ['string'],
                    'messaging_gdc_api_key' => ['string']
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('decode_utf8', 'yes_no')
            ->add('messaging_allow_send_push_notification', 'yes_no')
            ->add('messaging_gdc_project_number')
            ->add('messaging_gdc_api_key')
        ;
    }
}
