<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\InstallerBundle\Form\Type\Setup;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PortalType
 * @package Chamilo\InstallerBundle\Form\Type\Setup
 */
class PortalType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'institution',
                TextType::class,
                array(
                    'label' => 'form.setup.portal.institution',
                    'mapped' => false,
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array('max' => 15)),
                    ),
                )
            )
            ->add(
                'site_name',
                TextType::class,
                array(
                    'label' => 'form.setup.portal.site_name',
                    'mapped' => false,
                    'required' => false,
                )
            )
            ->add(
                'institution_url',
                UrlType::class,
                array(
                    'label' => 'form.setup.portal.institution_url',
                    'mapped' => false,
                    'required' => false,
                )
            )
            ->add(
                'allow_self_registration',
                ChoiceType::class,
                array(
                    'label' => 'form.setup.portal.allow_self_registration',
                    'mapped' => false,
                    'required' => true,
                    'choices' => array(
                        '1' => 'Yes',
                        '0' => 'No',
                    ),
                )
            )
            ->add(
                'allow_self_registration_as_trainer',
                ChoiceType::class,
                array(
                    'label' => 'form.setup.portal.allow_self_registration_as_trainer',
                    'mapped' => false,
                    'required' => true,
                    'choices' => array(
                        '1' => 'Yes',
                        '0' => 'No',
                    ),
                )
            )
            ->add(
                'timezone',
                TimezoneType::class,
                array(
                    'label' => 'form.setup.portal.timezone',
                    'mapped' => false,
                    'required' => false,
                    'preferred_choices' => array('Europe/Paris'),
                )
            );

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'allow_self_registration_as_trainer' => '0',
                'allow_self_registration' => '1',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chamilo_installer_setup_portal';
    }
}
