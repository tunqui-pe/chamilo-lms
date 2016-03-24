<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Attribute\AttributeType\CheckboxAttributeType;
use Sylius\Component\Attribute\AttributeType\DateAttributeType;
use Sylius\Component\Attribute\AttributeType\DatetimeAttributeType;
use Sylius\Component\Attribute\AttributeType\IntegerAttributeType;
use Sylius\Component\Attribute\AttributeType\TextareaAttributeType;
use Sylius\Component\Attribute\AttributeType\TextAttributeType;
use Sylius\Component\Translation\Model\AbstractTranslatable;

/**
 * Class ExtraField
 *
 * @ORM\Entity
 * @ORM\Table(name="extra_field")
 */
//implements AttributeInterface
class ExtraField
{
    const USER_FIELD_TYPE = 1;
    const COURSE_FIELD_TYPE = 2;
    const SESSION_FIELD_TYPE = 3;
    const QUESTION_FIELD_TYPE = 4;
    const CALENDAR_FIELD_TYPE = 5;
    const LP_FIELD_TYPE = 6;
    const LP_ITEM_FIELD_TYPE = 7;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="extra_field_type", type="integer", nullable=false, unique=false)
     */
    protected $extraFieldType;

    /**
     * user/session/course etc
     * @var string
     *
     * @ORM\Column(name="field_type", type="string", nullable=false, unique=false)
     */
    protected $fieldType;

    /**
     * @var string
     * @ORM\Column(name="variable", type="string", length=64, nullable=false, unique=false)
     */
    protected $variable;

    /**
     * @var string
     *
     * @ORM\Column(name="display_text", type="string", length=255, nullable=true, unique=false)
     */
    protected $displayText;

    /**
     * @var string
     *
     * @ORM\Column(name="default_value", type="text", nullable=true, unique=false)
     */
    protected $defaultValue;

    /**
     * @var integer
     * @Gedmo\SortablePosition
     * @ORM\Column(name="field_order", type="integer", nullable=true, unique=false)
     */
    protected $fieldOrder;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean", nullable=true, unique=false)
     */
    protected $visible;

    /**
     * @var boolean
     *
     * @ORM\Column(name="changeable", type="boolean", nullable=true, unique=false)
     */
    protected $changeable;

    /**
     * @var boolean
     *
     * @ORM\Column(name="filter", type="boolean", nullable=true, unique=false)
     */
    protected $filter;

    /**
     * @ORM\OneToMany(targetEntity="Chamilo\CoreBundle\Entity\ExtraFieldOptions", mappedBy="field")
     **/
    protected $options;

    /**
     * @var array
     *
     * @ORM\Column(name="configuration", type="array", nullable=true, unique=false)
     */
    protected $configuration = [];

    /**
     * @var string
     *
     * @ORM\Column(name="storage_type", type="text", nullable=true, unique=false)
     */
    protected $storageType;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getDisplayText();
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

    /**
     * @return int
     */
    public function getExtraFieldType()
    {
        return $this->extraFieldType;
    }

    /**
     * @param int $extraFieldType
     *
     * @return $this
     */
    public function setExtraFieldType($extraFieldType)
    {
        $this->extraFieldType = $extraFieldType;

        return $this;
    }

    /**
     * @return string
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * @param string $fieldType
     *
     * @return $this
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    /**
     * @return string
     */
    public function getVariable()
    {
        return $this->variable;
    }

    /**
     * @param string $variable
     *
     * @return $this
     */
    public function setVariable($variable)
    {
        $this->variable = $variable;

        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayText()
    {
        return $this->displayText;
    }

    /**
     * @param string $displayText
     *
     * @return $this
     */
    public function setDisplayText($displayText)
    {
        $this->displayText = $displayText;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param string $defaultValue
     *
     * @return $this
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @return int
     */
    public function getFieldOrder()
    {
        return $this->fieldOrder;
    }

    /**
     * @param int $fieldOrder
     *
     * @return $this
     */
    public function setFieldOrder($fieldOrder)
    {
        $this->fieldOrder = $fieldOrder;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @param boolean $visible
     *
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isChangeable()
    {
        return $this->changeable;
    }

    /**
     * @param boolean $changeable
     *
     * @return $this
     */
    public function setChangeable($changeable)
    {
        $this->changeable = $changeable;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isFilter()
    {
        return $this->filter;
    }

    /**
     * @param boolean $filter
     *
     * @return $this
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function getTypeToInt($value)
    {
        switch ($value) {
            case TextAttributeType::TYPE:
                return \ExtraField::FIELD_TYPE_TEXT;
                break;
            case TextareaAttributeType::TYPE:
                return \ExtraField::FIELD_TYPE_TEXTAREA;
                break;
            case DateAttributeType::TYPE:
                return \ExtraField::FIELD_TYPE_DATE;
                break;
            case DatetimeAttributeType::TYPE:
                return \ExtraField::FIELD_TYPE_DATETIME;
                break;
            case CheckboxAttributeType::TYPE:
                return \ExtraField::FIELD_TYPE_CHECKBOX;
                break;
            case IntegerAttributeType::TYPE:
                return \ExtraField::FIELD_TYPE_INTEGER;
                break;
            case 'choice':
            //case \ExtraField::FIELD_TYPE_SELECT:
                return \ExtraField::FIELD_TYPE_RADIO;
            default:
                return \ExtraField::FIELD_TYPE_TEXT;
        }
    }

    /**
     * @return string
     */
    public function getTypeToString()
    {
        /*
    const FIELD_TYPE_RADIO = 3;
    const FIELD_TYPE_SELECT = 4;
    const FIELD_TYPE_SELECT_MULTIPLE = 5;
    const FIELD_TYPE_DOUBLE_SELECT = 8;
    const FIELD_TYPE_DIVIDER = 9;
    const FIELD_TYPE_TAG = 10;
    const FIELD_TYPE_TIMEZONE = 11;
    const FIELD_TYPE_SOCIAL_PROFILE = 12;
    const FIELD_TYPE_MOBILE_PHONE_NUMBER = 14;
    const FIELD_TYPE_FILE_IMAGE = 16;
    const FIELD_TYPE_FLOAT = 17;
    const FIELD_TYPE_FILE = 18;
    const FIELD_TYPE_VIDEO_URL = 19;
    const FIELD_TYPE_LETTERS_ONLY = 20;
    const FIELD_TYPE_ALPHANUMERIC = 21;
    const FIELD_TYPE_LETTERS_SPACE = 22;
    const FIELD_TYPE_ALPHANUMERIC_SPACE = 23;
         */
        switch ($this->fieldType) {
            case \ExtraField::FIELD_TYPE_TEXT:
                return TextAttributeType::TYPE;
                break;
            case \ExtraField::FIELD_TYPE_TEXTAREA:
                return TextareaAttributeType::TYPE;
                break;
            case \ExtraField::FIELD_TYPE_DATE:
                return DateAttributeType::TYPE;
                break;
            case \ExtraField::FIELD_TYPE_DATETIME:
                return DatetimeAttributeType::TYPE;
                break;
            case \ExtraField::FIELD_TYPE_CHECKBOX:
                return CheckboxAttributeType::TYPE;
                break;
            case \ExtraField::FIELD_TYPE_INTEGER:
                return IntegerAttributeType::TYPE;
                break;
            case \ExtraField::FIELD_TYPE_RADIO:
            case \ExtraField::FIELD_TYPE_SELECT:
                return 'choice';
            default:
                return 'text';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return $this->variable;
    }

    /**
     * {@inheritdoc}
     */
    public function setCode($code)
    {
        $this->setVariable($code);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->displayText;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->setDisplayText($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->getTypeToString();
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->setExtraFieldType($this->getTypeToInt($type));
        $this->setFieldType($type);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {

    }

    /**
     * @param string $storageType
     */
    public function setStorageType($storageType)
    {
        $this->storageType = $storageType;
    }

    /**
     * @return string
     */
    public function getStorageType()
    {
        return $this->storageType;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }



}
