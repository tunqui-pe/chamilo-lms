<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Bundle\SettingsBundle\Model\SettingsInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;


/**
 * SettingsCurrent
 *
 * @ORM\Table(name="settings_current", uniqueConstraints={@ORM\UniqueConstraint(name="unique_setting", columns={"variable", "subkey", "access_url"})}, indexes={@ORM\Index(name="access_url", columns={"access_url"})})
 * @ORM\Entity(repositoryClass="Chamilo\CoreBundle\Entity\Repository\SettingsCurrentRepository")
 */
class SettingsCurrent implements SettingsInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="variable", type="string", length=255, nullable=true)
     */
    private $variable;

    /**
     * @var string
     *
     * @ORM\Column(name="subkey", type="string", length=255, nullable=true)
     */
    private $subkey;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="selected_value", type="string", length=255, nullable=true)
     */
    private $selectedValue;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="scope", type="string", length=50, nullable=true)
     */
    private $scope;

    /**
     * @var string
     *
     * @ORM\Column(name="subkeytext", type="string", length=255, nullable=true)
     */
    private $subkeytext;

    /**
     * @var integer
     *
     * @ORM\Column(name="access_url", type="integer", nullable=false, options={"default": 1 } )
     */
    private $accessUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="access_url_changeable", type="integer", nullable=false)
     */
    private $accessUrlChangeable;

    /**
     * @var integer
     *
     * @ORM\Column(name="access_url_locked", type="integer", nullable=false, options={"default": 0 } )
     */
    private $accessUrlLocked;

    /**
     * @ORM\ManyToOne(targetEntity="AccessUrl", inversedBy="settings", cascade={"persist"})
     * @ORM\JoinColumn(name="access_url", referencedColumnName="id")
     */
    protected $url;


    private $parameters;
    /**
     * @var string
     **/
    private $schemaAlias;

    /**
     * Constructor
     */
    public function __constructor()
    {
        $this->accessUrlLocked = 0;
    }

    /**
     * Set variable
     *
     * @param string $variable
     *
     * @return SettingsCurrent
     */
    public function setVariable($variable)
    {
        $this->variable = $variable;

        return $this;
    }

    /**
     * Get variable
     *
     * @return string
     */
    public function getVariable()
    {
        return $this->variable;
    }

    /**
     * Set subkey
     *
     * @param string $subkey
     *
     * @return SettingsCurrent
     */
    public function setSubkey($subkey)
    {
        $this->subkey = $subkey;

        return $this;
    }

    /**
     * Get subkey
     *
     * @return string
     */
    public function getSubkey()
    {
        return $this->subkey;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return SettingsCurrent
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return SettingsCurrent
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set selectedValue
     *
     * @param string $selectedValue
     *
     * @return SettingsCurrent
     */
    public function setSelectedValue($selectedValue)
    {
        $this->selectedValue = $selectedValue;

        return $this;
    }

    /**
     * Get selectedValue
     *
     * @return string
     */
    public function getSelectedValue()
    {
        return $this->selectedValue;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return SettingsCurrent
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return SettingsCurrent
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
     * Set scope
     *
     * @param string $scope
     *
     * @return SettingsCurrent
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set subkeytext
     *
     * @param string $subkeytext
     *
     * @return SettingsCurrent
     */
    public function setSubkeytext($subkeytext)
    {
        $this->subkeytext = $subkeytext;

        return $this;
    }

    /**
     * Get subkeytext
     *
     * @return string
     */
    public function getSubkeytext()
    {
        return $this->subkeytext;
    }

    /**
     * Set accessUrl
     *
     * @param integer $accessUrl
     *
     * @return SettingsCurrent
     */
    public function setAccessUrl($accessUrl)
    {
        $this->accessUrl = $accessUrl;

        return $this;
    }

    /**
     * Get accessUrl
     *
     * @return integer
     */
    public function getAccessUrl()
    {
        return $this->accessUrl;
    }

    /**
     * Set accessUrlChangeable
     *
     * @param integer $accessUrlChangeable
     *
     * @return SettingsCurrent
     */
    public function setAccessUrlChangeable($accessUrlChangeable)
    {
        $this->accessUrlChangeable = $accessUrlChangeable;

        return $this;
    }

    /**
     * Get accessUrlChangeable
     *
     * @return integer
     */
    public function getAccessUrlChangeable()
    {
        return $this->accessUrlChangeable;
    }

    /**
     * Set accessUrlLocked
     *
     * @param integer $accessUrlLocked
     *
     * @return SettingsCurrent
     */
    public function setAccessUrlLocked($accessUrlLocked)
    {
        $this->accessUrlLocked = intval($accessUrlLocked);

        return $this;
    }

    /**
     * Get accessUrlLocked
     *
     * @return integer
     */
    public function getAccessUrlLocked()
    {
        return $this->accessUrlLocked;
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
     * Set accessUrl
     *
     * @param AccessUrl $accessUrl
     * @return SettingsCurrent
     */
    public function setUrl($accessUrl)
    {
        $this->url = $accessUrl;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return $this->getCategory();
    }

    /**
     * {@inheritdoc}
     */
    public function setNamespace($namespace)
    {
        $this->setCategory($namespace);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getVariable();
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->setTitle($name);
        $this->setVariable($name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->getSelectedValue();
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $this->setSelectedValue($value);

        return $this;
    }

    public function getParameters()
    {
        return [];
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }


    /**
     * {@inheritdoc}
     */
    public function getSchemaAlias()
    {
        return $this->schemaAlias;
    }

    /**
     * @return string
     */
    public function getServiceFromCategory()
    {
        return 'chamilo_core.settings.'.$this->category;
    }

    /**
     * {@inheritdoc}
     */
    public function setSchemaAlias($schemaAlias)
    {
        if (null !== $this->schemaAlias) {
            throw new \LogicException('The schema alias of the settings model is immutable, instantiate a new object in order to use another schema.');
        }

        $this->schemaAlias = $schemaAlias;
    }


    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new ParameterNotFoundException($name);
        }

        return $this->parameters[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return array_key_exists($name, $this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        if (!$this->has($name)) {
            throw new ParameterNotFoundException($name);
        }

        unset($this->parameters[$name]);
    }


}
