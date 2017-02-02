<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\InstallerBundle\Form\Type;

use Chamilo\InstallerBundle\Form\Type\Configuration\DatabaseType;
use Chamilo\InstallerBundle\Form\Type\Configuration\MailerType;
use Chamilo\InstallerBundle\Form\Type\Configuration\SystemType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Chamilo\InstallerBundle\Validator\Constraints as Assert;

/**
 * Class ConfigurationType
 * @package Chamilo\InstallerBundle\Form\Type
 */
class ConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // See class DatabaseConnectionValidator to see the validator.
        $builder
            ->add(
                'database',
                DatabaseType::class,
                array(
                    'label' => 'form.configuration.database.header',
                    'constraints' => array(
                        new Assert\DatabaseConnection(),
                    ),
                )
            )
            ->add(
                'mailer',
                MailerType::class,
                array(
                    'label' => 'form.configuration.mailer.header',
                )
            )
            ->add(
                'system',
                SystemType::class,
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
