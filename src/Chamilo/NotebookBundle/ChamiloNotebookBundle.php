<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\NotebookBundle;

use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

/**
 * Class ChamiloNotebookBundle
 * @package Chamilo\NotebookBundle
 */
class ChamiloNotebookBundle extends AbstractResourceBundle
{
    public function getSupportedDrivers()
    {
        return array(
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        );
    }

    /**
     *
     */
    public function getBundlePrefix()
    {
        parent::getBundlePrefix();
    }
}
