<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CourseBundle\Form\Type;

use Chamilo\CoreBundle\Component\Editor\CkEditor\Toolbar\Introduction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CToolIntroType
 * @package Chamilo\CourseBundle\Form\Type
 */
class CToolIntroType extends AbstractType
{
    /** @var \Chamilo\CoreBundle\Component\Editor\CkEditor\CkEditor */
    protected $toolBar;

    /**
     * @param $toolBar
     */
    public function setToolBar($toolBar)
    {
        $this->toolBar = $toolBar;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $toolBar = $this->toolBar->createToolBar('Introduction');
        $config = $toolBar->getNamedToolBarConfig();

        $builder
            ->add('introText', 'ckeditor', ['config' => $config])
            ->add('cId', 'hidden')
            ->add('sessionId', 'hidden')
            ->add('tool', 'hidden')
            ->add('SaveIntroText', 'submit');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Chamilo\CourseBundle\Entity\CToolIntro',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chamilo_course_c_tool_intro';
    }
}
