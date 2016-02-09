<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeSubjectInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;

/**
 * Class ExtraFieldValues
 * @todo change entity name to ExtraFieldValue
 * @ORM\Table(name="extra_field_values")
 * @ORM\Entity(repositoryClass="Chamilo\CoreBundle\Entity\Repository\ExtraFieldValuesRepository")
 */
class ExtraFieldValues implements AttributeValueInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="value", type="string", nullable=true, unique=false)
     */
    protected $value;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CoreBundle\Entity\ExtraField")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id")
     **/
    protected $field;

    /**
     * @var ExtraField
     *
     * @ORM\ManyToOne(targetEntity="Chamilo\CoreBundle\Entity\ExtraField")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id")
     **/
    protected $attribute;

    /**
     * @var string
     * @ORM\Column(name="item_id", type="integer", nullable=false, unique=false)
     */
    protected $itemId;

    /**
     * @var string
     * @ORM\Column(name="comment", type="text", nullable=true, unique=false)
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\UserBundle\Entity\User", cascade={"persist"}, inversedBy="extraFields")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CoreBundle\Entity\Course", cascade={"persist"}, inversedBy="extraFields")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    //protected $course;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->getExtraField()->getDisplayText();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getExtraField()->getTypeToString();
    }

    /**
     * @return ExtraField
     */
    public function getExtraField()
    {
        return $this->getField();
    }

    /**
     * @param mixed $field
     *
     * @return ExtraFieldValues
     */
    public function setExtraField(ExtraField $field)
    {
        $this->setField($field);

        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     *
     * @return ExtraFieldValues
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return string
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param string $itemId
     *
     * @return ExtraFieldValues
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

     /**
     * Set comment
     *
     * @param string $comment
      *
     * @return ExtraFieldValues
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }


    /**
     * @return AttributeSubjectInterface
     */
    public function getSubject()
    {
        return $this->user;
    }

    /**
     * @param AttributeSubjectInterface|null $subject
     */
    public function setSubject(AttributeSubjectInterface $subject = null)
    {
        //$this->user = $subject;
    }


    /**
     * @param AttributeSubjectInterface|null $subject
     */
    public function setSubjectUser(AttributeSubjectInterface $subject = null)
    {
        $this->user = $subject;
    }

    /**
     * @param AttributeSubjectInterface|null $subject
     */
    public function setSubjectCourse(AttributeSubjectInterface $subject = null)
    {
        //$this->cours = $subject;
    }

    /**
     * @param AttributeSubjectInterface|null $subject
     */
    public function setSubjectSession(AttributeSubjectInterface $subject = null)
    {
        //$this->user = $subject;
    }

    /**
     * @return AttributeInterface
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param AttributeInterface $attribute
     *
     * @return $this
     */
    public function setAttribute(AttributeInterface $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Proxy method to access the code from real attribute.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->attribute->getVariable();
    }


}
