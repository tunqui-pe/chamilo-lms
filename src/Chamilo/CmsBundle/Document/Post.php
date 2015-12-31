<?php

namespace Chamilo\CmsBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishableInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishTimePeriodInterface;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;

/**
 * @PHPCR\Document(
 *     translator="attribute",
 *     referenceable=true,
 *     versionable="full"
 * )
 */
class Post implements
    RouteReferrersReadInterface,
    TranslatableInterface,
    PublishableInterface,
    PublishTimePeriodInterface
{
    use ContentTrait;

    /**
     * @PHPCR\PrePersist()
     */
    public function updateDate()
    {
        if (!$this->publishStartDate) {
            $this->publishStartDate = new \DateTime();
        }
    }
}
