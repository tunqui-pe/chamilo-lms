<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CToolIntroType
 * @package Chamilo\CourseBundle\Form\Type
 */
class CToolIntroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('introText', 'ckeditor')
            ->add('cId', 'hidden')
            ->add('sessionId', 'hidden')
            ->add('tool', 'hidden')
            ->add('save', 'submit');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Chamilo\CourseBundle\Entity\CToolIntro',
            )
        );
    }

    public function getName()
    {
        return 'chamilo_course_c_tool_intro';
    }
}
