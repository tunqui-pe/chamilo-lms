<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\UserBundle\Form;

use Chamilo\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\UserBundle\Model\UserInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class ProfileType
 * @package Chamilo\UserBundle\Form
 */
class ProfileType extends AbstractType
{
    /**
     * @inheritdoc
     **/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'gender',
                'sonata_user_gender',
                array(
                    'label' => 'form.label_gender',
                    'required' => true,
                    'translation_domain' => 'SonataUserBundle',
                    'choices' => array(
                        UserInterface::GENDER_FEMALE => 'gender_female',
                        UserInterface::GENDER_MALE => 'gender_male',
                    ),
                )
            )
            ->add(
                'firstname',
                null,
                array(
                    'label' => 'form.label_firstname',
                    'required' => false,
                )
            )
            ->add(
                'lastname',
                null,
                array(
                    'label' => 'form.label_lastname',
                    'required' => false,
                )
            )
            ->add(
                'dateOfBirth',
                'birthday',
                array(
                    'label' => 'form.label_date_of_birth',
                    'required' => false,
                    'widget' => 'single_text',
                )
            )
            ->add(
                'website',
                'url',
                array(
                    'label' => 'form.label_website',
                    'required' => false,
                )
            )
            ->add(
                'biography',
                'textarea',
                array(
                    'label' => 'form.label_biography',
                    'required' => false,
                )
            )
            /*->add('locale', 'locale', array(
                'label'    => 'form.label_locale',
                'required' => false,
            ))*/
            ->add(
                'timezone',
                'timezone',
                array(
                    'label' => 'form.label_timezone',
                    'required' => false,
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'form.label_phone',
                    'required' => false,
                )
            );

        $builder
            ->add('official_code', 'text')
            //->add('groups')
            ->add(
                'locale',
                'locale',
                array('preferred_choices' => array('en', 'fr', 'es'))
            )
            /*->add(
                'picture_uri',
                'sonata_media_type',
                array(
                    'provider' => 'sonata.media.provider.image',
                    'context' => 'user_image',
                    'required' => false,
                )
            )*/
            /*->add(
                'extraFields',
                'collection',
                array(
                    'required' => false,
                    'type' => 'chamilo_user.form.type.attribute_value_type',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                )
            )*/
            //->add('save', 'submit', array('label' => 'Update')            )
        ;

        // Update Author id
        /*$builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($currentUser) {
                // @var User $user
                $user = $event->getData();
                $extraFields = $user->getExtrafields();
                foreach ($extraFields as $extraField) {
                    $extraField->setAuthor($currentUser);
                }
            }
        );*/
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Remove it when bumping requirements to Symfony 2.7+
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Chamilo\UserBundle\Entity\User',
            )
        );
    }

    public function getName()
    {
        return 'chamilo_sonata_user_profile';
    }
}

