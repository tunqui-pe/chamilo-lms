<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\InstallerBundle\Form\Type;

use Chamilo\InstallerBundle\Form\Type\Setup\AdminType;
use Chamilo\InstallerBundle\Form\Type\Setup\PortalType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SetupType
 * @package Chamilo\InstallerBundle\Form\Type
 */
class SetupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'admin',
                AdminType::class,
                array(
                    'label' => 'form.setup.admin.header',
                )
            )
            ->add(
                'portal',
                PortalType::class,
                array(
                    'label' => 'form.setup.portal.header',
                )
            );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chamilo_installer_setup';
    }
}
