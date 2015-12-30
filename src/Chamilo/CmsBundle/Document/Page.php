<?php

namespace Chamilo\CmsBundle\Document;

use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Knp\Menu\NodeInterface;

/**
 * @PHPCR\Document(
 *     translator="attribute",
 *     referenceable=true,
 *     versionable="full"
 * )
 */
class Page implements RouteReferrersReadInterface, NodeInterface, TranslatableInterface
{
    use ContentTrait;

    /**
     * @PHPCR\Children()
     */
    protected $children;

    public function getName()
    {
        return $this->title;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getOptions()
    {
        return array(
            'label' => $this->title,
            'content' => $this,
            'attributes' => array(),
            'childrenAttributes' => array(),
            'displayChildren' => true,
            'linkAttributes' => array(),
            'labelAttributes' => array(),
        );
    }
}
