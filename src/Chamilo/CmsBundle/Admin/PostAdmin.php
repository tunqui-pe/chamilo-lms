<?php

namespace Chamilo\CmsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;

class PostAdmin extends PageAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->with('form.group_general')
            ->add('date', 'date')
            ->end();
    }
}
