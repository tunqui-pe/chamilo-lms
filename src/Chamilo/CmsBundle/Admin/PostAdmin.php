<?php

namespace Chamilo\CmsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class PostAdmin
 * @package Chamilo\CmsBundle\Admin
 */
class PostAdmin extends PageAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->with('form.group_general')
            ->end();
    }
}
