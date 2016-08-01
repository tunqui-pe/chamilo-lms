<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\SettingsBundle\Templating\Helper;

use Sylius\Bundle\SettingsBundle\Templating\Helper\SettingsHelperInterface;
use Symfony\Component\Templating\Helper\Helper;

/**
 * Class SettingsHelper
 * @package Chamilo\SettingsBundle\Templating\Helper
 */
//class SettingsHelper extends Helper implements SettingsHelperInterface
class SettingsHelper extends Helper
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'chamilo_settings';
    }

    public function getSettings($schemaAlias)
    {
        return $this->settingsManager->load($schemaAlias);
    }

    public function getSettingsParameter($schemaAlias)
    {
        //return $this->settingsManager->load($schemaAlias);
    }


}
