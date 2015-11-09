<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PptToLpSettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class PptToLpSettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                array(
                    'active' => '',
                    'size' => '',
                    'host' => '',
                    'port' => '',
                    'user' => '',
                    'ftp_password' => '',
                    'path_to_lzx' => '',
                )
            )
            ->setAllowedTypes(
                array(
                    'active' => array('string'),
                    'size' => array('string'),
                    'host' => array('string'),
                    'port' => array('string'),
                    'user' => array('string'),
                    'ftp_password' => array('string'),
                    'path_to_lzx' => array('string'),
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('active', 'yes_no')
            ->add('size')
            ->add('host')
            ->add('port')
            ->add('user')
            ->add('ftp_password')
            ->add('path_to_lzx');
    }
}
