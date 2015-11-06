<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Chamilo\SettingsBundle\Transformer\ArrayToIdentifierTransformer;
use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProfileSettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class ProfileSettingsSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                array(
                    'changeable_options' => [],
                    'extended_profile' => '',
                    'account_valid_duration' => '3660',
                    'split_users_upload_directory' => '',
                    'user_selected_theme' => '',
                    'use_users_timezone' => '',
                    'allow_users_to_change_email_with_no_password' => '',
                    'login_is_email' => '',
                )
            )
            ->setAllowedTypes(
                array(
                    'changeable_options' => array('array'),
                    'account_valid_duration' => array('string'),
                )
            )
            ->setTransformer(
                'changeable_options',
                new ArrayToIdentifierTransformer()
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add(
                'changeable_options',
                'choice',
                array(
                    'multiple' => true,
                    'choices' => array(
                        'name' => 'name',
                        'officialcode' => 'officialcode',
                        'email' => 'email',
                        'picture' => 'picture',
                        'login' => 'login',
                        'password' => 'password',
                        'language' => 'language',
                        'phone' => 'phone',
                        'openid' => 'openid',
                        'theme' => 'theme',
                        'apikeys' => 'apikeys',
                    )
                )
            )
            ->add('extended_profile', 'yes_no')
            ->add('account_valid_duration')
            ->add('split_users_upload_directory', 'yes_no')
            ->add('user_selected_theme', 'yes_no')
            ->add('use_users_timezone', 'yes_no')
            ->add('allow_users_to_change_email_with_no_password', 'yes_no')
            ->add('login_is_email', 'yes_no', ['label' => 'LoginIsEmailTitle'])
        ;
    }
}
