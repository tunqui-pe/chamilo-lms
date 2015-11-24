<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\UserBundle\Form\Type;

use Chamilo\CoreBundle\Entity\ExtraField;
use Chamilo\UserBundle\Form\EventListener\BuildAttributeValueFormListener;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
//use Sylius\Component\Product\Model\AttributeInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Attribute value form type.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class AttributeValueType extends AbstractResourceType
{
    /**
     * Attributes subject name.
     *
     * @var string
     */
    protected $subjectName;

    /**
     * Constructor.
     *
     * @param string $dataClass
     * @param array $validationGroups
     * @param string $subjectName
     */
    public function __construct(
        $dataClass,
        array $validationGroups,
        $subjectName
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->subjectName = $subjectName;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'extraField',
                sprintf('chamilo_%s_extra_field_choice', $this->subjectName)
            )
            ->addEventSubscriber(
                new BuildAttributeValueFormListener($builder->getFormFactory())
            );

        $prototypes = array();
        $attributes = $this->getAttributes($builder);

        if ($attributes) {
            /** @var \Chamilo\CoreBundle\Entity\ExtraField $attribute */
            foreach ($attributes as $attribute) {
                $configuration = $attribute->getConfiguration();
                $type = $attribute->getTypeToString();

                if (!is_array($configuration)) {
                    $configuration = array();
                }

                if (empty($type)) {
                    continue;
                }

                $prototypes[] = $builder->create(
                    'value',
                    $type,
                    $configuration
                )->getForm();
            }
        }

        $builder->setAttribute('prototypes', $prototypes);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ) {
        $view->vars['prototypes'] = array();

        foreach ($form->getConfig()->getAttribute('prototypes', array()) as $name => $prototype) {
            $view->vars['prototypes'][$name] = $prototype->createView($view);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return sprintf('chamilo_%s_extra_field_value', $this->subjectName);
    }

    /**
     * Get attributes
     *
     * @param FormBuilderInterface $builder
     *
     * @return AttributeInterface[]
     */
    private function getAttributes(FormBuilderInterface $builder)
    {
        /** @var \Symfony\Component\Form\FormBuilder $extraField */
        $extraField = $builder->get('extraField');

        if ($extraField->hasOption('choice_list')) {
            return $extraField->getOption('choice_list')->getChoices();
        }

        return null;
    }
}
