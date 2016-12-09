<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\InstallerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Chamilo\InstallerBundle\Validator\Constraints as Assert;

/**
 * Class ConfigurationType
 * @package Chamilo\InstallerBundle\Form\Type
 */
class ConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // See class DatabaseConnectionValidator to see the validator.
        $builder
            ->add(
                'database',
                'chamilo_installer_configuration_database',
                array(
                    'label' => 'form.configuration.database.header',
                    'constraints' => array(
                        new Assert\DatabaseConnection(),
                    ),
                )
            )
            ->add(
                'mailer',
                'chamilo_installer_configuration_mailer',
                array(
                    'label' => 'form.configuration.mailer.header',
                )
            )
            ->add(
                'system',
                'chamilo_installer_configuration_system',
                array(
                    'label' => 'form.configuration.system.header',
                )
            )
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chamilo_installer_configuration';
    }
}
