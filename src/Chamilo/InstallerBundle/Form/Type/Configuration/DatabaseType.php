<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\InstallerBundle\Form\Type\Configuration;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DatabaseType
 * @package Chamilo\InstallerBundle\Form\Type\Configuration
 */
class DatabaseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add(
                'chamilo_installer_database_driver',
                ChoiceType::class,
                array(
                    // 'label' => 'form.configuration.database.driver',
                    'choices' => array(
                        'MySQL' => 'pdo_mysql',
                        //'pdo_pgsql' => 'PostgreSQL',
                    ),
                    'choices_as_values' => true,
                    'constraints' => array(
                        //new Assert\NotBlank(),
                        //new ExtensionLoaded(),
                    )
                )
            )*/
            ->add(
                'chamilo_installer_database_host',
                TextType::class,
                array(
                    'label' => 'form.configuration.database.host',
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )
            ->add(
                'chamilo_installer_database_port',
                IntegerType::class,
                array(
                    'label' => 'form.configuration.database.port',
                    'required' => false,
                    'constraints' => array(
                        new Assert\Type(array('type' => 'integer')),
                    ),
                )
            )
            ->add(
                'chamilo_installer_database_name',
                TextType::class,
                array(
                    'label' => 'form.configuration.database.name',
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )
            ->add(
                'chamilo_installer_database_user',
                TextType::class,
                array(
                    'label' => 'form.configuration.database.user',
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )
            ->add(
                'chamilo_installer_database_password',
                PasswordType::class,
                array(
                    'label' => 'form.configuration.database.password',
                    'required' => false,
                )
            )
            ->add(
                'chamilo_installer_database_drop_full',
                CheckboxType::class,
                array(
                    'label' => 'form.configuration.database.drop_full',
                    'required' => false,
                    'data' => false,
                )
            );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chamilo_installer_configuration_database';
    }
}
