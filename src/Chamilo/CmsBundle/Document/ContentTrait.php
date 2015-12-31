<?php

namespace Chamilo\CmsBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Component\Validator\Constraints as Assert;


trait ContentTrait
{
    /**
     * @PHPCR\Id()
     */
    protected $id;

    /**
     * @PHPCR\ParentDocument()
     */
    protected $parent;

    /**
     * @Assert\NotBlank
     * @PHPCR\Nodename()
     */
    protected $title;

    /**
     * @PHPCR\String(nullable=true, translated=true)
     */
    protected $content;

    /**
     * @var \DateTime
     */
    protected $publishStartDate;

    /**
     * @var \DateTime
     */
    protected $publishEndDate;

    /**
     * The language this document currently is in
     * @PHPCR\Locale
     */
    private $locale;

    /**
     * @PHPCR\Referrers(
     *     referringDocument="Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route",
     *     referencedBy="content"
     * )
     */
    protected $routes;

    /** @PHPCR\VersionName */
    private $versionName;

    /** @PHPCR\VersionCreated */
    private $versionCreated;

    /**
     * @var @PHPCR\Boolean
     */
    protected $publishable = true;

    public function getId()
    {
        return $this->id;
    }

    public function getParentDocument()
    {
        return $this->parent;
    }

    public function setParentDocument($parent)
    {
        $this->parent = $parent;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     * @return ContentTrait
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function isPublishable()
    {
        return $this->publishable;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublishable($publishable)
    {
        $this->publishable = $publishable;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishStartDate()
    {
        return $this->publishStartDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublishStartDate(\DateTime $publishStartDate = null)
    {
        $this->publishStartDate = $publishStartDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishEndDate()
    {
        return $this->publishEndDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublishEndDate(\DateTime $publishEndDate = null)
    {
        $this->publishEndDate = $publishEndDate;
    }

    /**
     * Get the "date" of this page, which is the publishStartDate.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->getPublishStartDate();
    }

    public function setDate(\DateTime $date)
    {
        $this->setPublishStartDate($date);
    }
}
