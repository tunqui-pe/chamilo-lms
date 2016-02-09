<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Attribute\Model\Attribute as BaseAttribute;
use Sylius\Component\Translation\Model\AbstractTranslatable;
use Sylius\Component\Translation\Model\AbstractTranslation;
use Sylius\Component\Translation\Model\TranslationInterface;

/**
 * Class ExtraFieldTranslation
 */
class ExtraFieldTranslation extends AbstractTranslation
{
    public function setName($name)
    {

    }
    public function getName()
    {
        return 'name';
    }
}