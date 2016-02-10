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
                )
            )
            ->setAllowedTypes(
                array(
                    // commenting this line allows setting to be null
                    //'header_extra_content' => array('string'),
                    //'footer_extra_content' => array('string'),
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
        ;
    }
}
