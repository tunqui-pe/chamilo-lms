<?php
/* For licensing terms, see /license.txt */
use Doctrine\Common\Collections\Criteria;

/**
 * Class AbstractLink
 * Defines a gradebook AbstractLink object.
 * To implement specific links,
 * extend this class and define a type in LinkFactory.
 * Use the methods in LinkFactory to create link objects.
 * @author Bert Steppé
 * @author Julio Montoya <gugli100@gmail.com> security improvements
 * @package chamilo.gradebook
 */
abstract class AbstractLink implements GradebookItem
{
    protected $id;
    protected $type;
    protected $ref_id;
    protected $user_id;
    protected $course_code;
    /** @var Category */
    protected $category;
    protected $created_at;
    protected $weight;
    protected $visible;
    protected $session_id;
    public $course_id;
    public $studentList;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->course_id = api_get_course_int_id();
    }

    /**
     * @return int
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function get_ref_id()
    {
        return $this->ref_id;
    }

    /**
     * @return int
     */
    public function get_session_id()
    {
        return $this->session_id;
    }

    /**
     * @return int
     */
    public function get_user_id()
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function get_course_code()
    {
        return $this->course_code;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return int
     */
    public function get_category_id()
    {
        return $this->category->get_id();
    }

    /**
     * @param int $category_id
     */
    public function set_category_id($category_id)
    {
        $categories = Category::load($category_id);
        if (isset($categories[0])) {
            $this->setCategory($categories[0]);
        }
    }

    public function get_date()
    {
        return $this->created_at;
    }

    public function get_weight()
    {
        return $this->weight;
    }

    public function is_locked()
    {
        return isset($this->locked) && $this->locked == 1 ? true : false;
    }

    public function is_visible()
    {
        return $this->visible;
    }

    public function set_id ($id)
    {
        $this->id = $id;
    }

    public function set_type ($type)
    {
        $this->type = $type;
    }

    public function set_ref_id ($ref_id)
    {
        $this->ref_id = $ref_id;
    }

    public function set_user_id ($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @param string $course_code
     */
    public function set_course_code($course_code)
    {
        $this->course_code = $course_code;
        $this->course_id = api_get_course_int_id($course_code);
    }

    public function getStudentList()
    {
        return $this->studentList;
    }

    public function setStudentList($list)
    {
        $this->studentList = $list;
    }

    public function set_date($date)
    {
        $this->created_at = $date;
    }

    public function set_weight($weight)
    {
        $this->weight = $weight;
    }

    public function set_visible($visible)
    {
        $this->visible = $visible;
    }

    public function set_session_id($id)
    {
        $this->session_id = $id;
    }

    /**
     * @param $locked
     */
    public function set_locked($locked)
    {
        $this->locked = $locked;
    }

    /**
     * @return int
     */
    public function getCourseId()
    {
        return $this->course_id;
    }

    /**
     * Retrieve links and return them as an array of extensions of AbstractLink.
     * To keep consistency, do not call this method but LinkFactory::load instead.
     */
    public static function load(
        $id = null,
        $type = null,
        $ref_id = null,
        $user_id = null,
        $course_code = null,
        $category_id = null,
        $visible = null
    ) {
        $em = Database::getManager();
        $criteria = \Doctrine\Common\Collections\Criteria::create();
        $tbl_grade_links = Database :: get_main_table(TABLE_MAIN_GRADEBOOK_LINK);
        $sql = 'SELECT * FROM '.$tbl_grade_links;
        $paramcount = 0;
        if (isset ($id)) {
            $id = intval($id);
            $criteria->andWhere(
                Criteria::expr()->eq('id', $id)
            );
        }
        if (isset ($type)) {
            $type = intval($type);
            $criteria->andWhere(
                Criteria::expr()->eq('type', $type)
            );
        }
        if (isset ($ref_id)) {
            $ref_id = intval($ref_id);
            $criteria->andWhere(
                Criteria::expr()->eq('refId', $ref_id)
            );
        }
        if (isset ($user_id)) {
            $user_id = intval($user_id);
            $criteria->andWhere(
                Criteria::expr()->eq('userId', $user_id)
            );
        }
        if (isset ($course_code)) {
            $course = $em->getRepository('ChamiloCoreBundle:Course')->findOneBy(['code' => $course_code]);
            $criteria->andWhere(
                Criteria::expr()->eq('course', $course->getId())
            );
        }
        if (isset ($category_id)) {
            $category_id = intval($category_id);
            $criteria->andWhere(
                Criteria::expr()->eq('categoryId', $category_id)
            );
        }
        if (isset ($visible)) {
            $visible = intval($visible);
            $criteria->andWhere(
                Criteria::expr()->eq('visible', $visible)
            );
        }
        $result = $em->getRepository('ChamiloCoreBundle:GradebookLink')->matching($criteria);
        $links = AbstractLink::createObjectsFromEntities($result);

        return $links;
    }

    /**
     * Create an AbsctrackLink array from GradebookLink \Doctrine\Common\Collections\ArrayCollection or array
     * @param \Doctrine\Common\Collections\ArrayCollection|array $entities
     * @return array
     */
    private static function createObjectsFromEntities($entities)
    {
        $links = [];

        foreach ($entities as $gradebookLink) {
            $link = LinkFactory::create($gradebookLink->getType());
            $link->set_id($gradebookLink->getId());
            $link->set_type($gradebookLink->getType());
            $link->set_ref_id($gradebookLink->getRefId());
            $link->set_user_id($gradebookLink->getUserId());
            $link->set_course_code($gradebookLink->getCourse()->getCode());
            $link->set_category_id($gradebookLink->getCategoryId());
            $link->set_date($gradebookLink->getCreatedAt()->format('Y-m-d h:m:i'));
            $link->set_weight($gradebookLink->getWeight());
            $link->set_visible($gradebookLink->getVisible());
            $link->set_locked($gradebookLink->getLocked());

            //session id should depend of the category --> $data['category_id']
            $session_id = api_get_session_id();

            $link->set_session_id($session_id);
            $links[] = $link;
        }

        return $links;
    }

    /**
     * Insert this link into the database
     */
    public function add()
    {
        $this->add_linked_data();
        if (isset($this->type) &&
            isset($this->ref_id) &&
            isset($this->user_id) &&
            isset($this->course_code) &&
            isset($this->category) &&
            isset($this->weight) &&
            isset($this->visible)
        ) {
            $em = Database::getManager();

            $row_testing = $em
                ->createQuery('
                    SELECT COUNT(gl) FROM ChamiloCoreBundle:GradebookLink gl
                    WHERE gl.refId = :reference AND gl.categoryId = :category AND
                        gl.course = :course AND gl.type = :type
                ')
                ->setParameters([
                    'reference' => $this->get_ref_id(),
                    'category' => $this->category->get_id(),
                    'course' => $this->course_id,
                    'type' => $this->type
                ])
                ->getSingleScalarResult();

            if ($row_testing == 0) {
                $createdAt = new DateTime(api_get_utc_datetime(), new DateTimeZone('UTC'));
                $course = $em->find('ChamiloCoreBundle:Course', $this->course_id);
                $gradebookLink = new \Chamilo\CoreBundle\Entity\GradebookLink();
                $gradebookLink
                    ->setType($this->get_type())
                    ->setRefId($this->get_ref_id())
                    ->setUserId($this->get_user_id())
                    ->setCourse($course)
                    ->setCategoryId($this->get_category_id())
                    ->setWeight($this->get_weight())
                    ->setVisible($this->is_visible())
                    ->setCreatedAt($createdAt)
                    ->setLocked(0);

                $em->persist($gradebookLink);
                $em->flush();

                $inserted_id = $gradebookLink->getId();
                $this->set_id($inserted_id);

                return $inserted_id;
            }
        }

        return false;
    }

    /**
     * Update the properties of this link in the database
     */
    public function save()
    {
        $this->save_linked_data();
        
        $em = Database::getManager();
        $gradebookLink = $em->find('ChamiloCoreBundle:GradebookLink', $this->id);

        if ($gradebookLink) {
            $course = $em->find('ChamiloCoreBundle:Course', $this->course_id);
            $gradebookLink
                ->setType($this->get_type())
                ->setRefId($this->get_ref_id())
                ->setUserId($this->get_user_id())
                ->setCourse($course)
                ->setCategoryId($this->get_category_id())
                ->setWeight($this->get_weight())
                ->setVisible($this->is_visible());

            $em->persist($gradebookLink);
            $em->flush();
        }

        AbstractLink::add_link_log($this->id);

    }

    /**
     * @param int $idevaluation
     */
    public static function add_link_log($idevaluation, $nameLog = null)
    {
        $table = Database:: get_main_table(TABLE_MAIN_GRADEBOOK_LINKEVAL_LOG);
        $dateobject = AbstractLink::load($idevaluation, null, null, null, null);
        $current_date_server = api_get_utc_datetime();
        $arreval = get_object_vars($dateobject[0]);
        $description_log = isset($arreval['description']) ? $arreval['description']:'';
        if (empty($nameLog)) {
            if (isset($_POST['name_link'])) {
                $name_log = isset($_POST['name_link']) ? $_POST['name_link'] : $arreval['course_code'];
            } elseif (isset($_POST['link_' . $idevaluation]) && $_POST['link_' . $idevaluation]) {
                $name_log = $_POST['link_' . $idevaluation];
            } else {
                $name_log = $arreval['course_code'];
            }
        } else {
            $name_log = $nameLog;
        }

        $params = [
            'id_linkeval_log' => $arreval['id'],
            'name' => $name_log,
            'description' => $description_log,
            'created_at' => $current_date_server,
            'weight' => $arreval['weight'],
            'visible' => $arreval['visible'],
            'type' => 'Link',
            'user_id_log' => api_get_user_id(),
        ];
        Database::insert($table, $params);
    }

    /**
     * Delete this link from the database
     */
    public function delete()
    {
        $this->delete_linked_data();
        
        $em = Database::getManager();
        $gradebookLink = $em->find('ChamiloCoreBundle:GradebookLink', $this->id);

        if ($gradebookLink) {
            $em->remove($gradebookLink);
            $em->flush();
        }
    }

    /**
     * Generate an array of possible categories where this link can be moved to.
     * Notice: its own parent will be included in the list: it's up to the frontend
     * to disable this element.
     * @return array 2-dimensional array - every element contains 3 subelements (id, name, level)
     */
    public function get_target_categories()
    {
        // links can only be moved to categories inside this course
        $targets = array();
        $level = 0;

        $crscats = Category::load(null,null,$this->get_course_code(),0);
        foreach ($crscats as $cat) {
            $targets[] = array($cat->get_id(), $cat->get_name(), $level+1);
            $targets = $this->add_target_subcategories($targets, $level+1, $cat->get_id());
        }

        return $targets;
    }

    /**
     * Internal function used by get_target_categories()
     */
    private function add_target_subcategories($targets, $level, $catid)
    {
        $subcats = Category::load(null,null,null,$catid);
        foreach ($subcats as $cat) {
            $targets[] = array ($cat->get_id(), $cat->get_name(), $level+1);
            $targets = $this->add_target_subcategories($targets, $level+1, $cat->get_id());
        }
        return $targets;
    }

    /**
     * Move this link to the given category.
     * If this link moves to outside a course, delete it.
     */
    public function move_to_cat($cat)
    {
        if ($this->get_course_code() != $cat->get_course_code()) {
            $this->delete();
        } else {
            $this->set_category_id($cat->get_id());
            $this->save();
        }
    }

    /**
     * Find links by name
     * To keep consistency, do not call this method but LinkFactory::find_links instead.
     * @todo can be written more efficiently using a new (but very complex) sql query
     */
    public function find_links ($name_mask,$selectcat)
    {
        $rootcat = Category::load($selectcat);
        $links = $rootcat[0]->get_links((api_is_allowed_to_edit() ? null : api_get_user_id()), true);
        $foundlinks = array();
        foreach ($links as $link) {
            if (!(api_strpos(api_strtolower($link->get_name()), api_strtolower($name_mask)) === false)) {
                $foundlinks[] = $link;
            }
        }

        return $foundlinks;
    }

    /**
     * @return string
     */
    public function get_item_type()
    {
        return 'L';
    }

    /**
     * @return string
     */
    public function get_icon_name()
    {
        return 'link';
    }

    abstract function has_results();
    abstract function get_link();
    abstract function is_valid_link();
    abstract function get_type_name();
    abstract function needs_name_and_description();
    abstract function needs_max();
    abstract function needs_results();
    abstract function is_allowed_to_change_name();

    /* Seems to be not used anywhere */
    public function get_not_created_links()
    {
        return null;
    }

    public function get_all_links()
    {
        return null;
    }

    public function add_linked_data()
    {
    }

    public function save_linked_data()
    {
    }

    /**
     *
     */
    public function delete_linked_data()
    {
    }

    /**
     * @param $name
     */
    public function set_name($name)
    {
    }

    /**
     * @param $description
     */
    public function set_description($description)
    {
    }

    /**
     * @param $max
     */
    public function set_max($max)
    {
    }

    public function get_view_url($stud_id)
    {
        return null;
    }

    /**
     * Locks a link
     * @param int $locked 1 or unlocked 0
     *
     * */
    public function lock($locked)
    {
        $locked = intval($locked);
        $em = Database::getManager();
        $gradebookLink = $em->find('ChamiloCoreBundle:GradebookLink', $this->id);

        if ($gradebookLink) {
            $gradebookLink->setLocked($locked);

            $em->persist($gradebookLink);
            $em->flush();
        }
    }

    /**
     * Get current user ranking
     *
     * @param int $userId
     * @param array $studentList Array with user id and scores
     * Example: [1 => 5.00, 2 => 8.00]
     */
    public static function getCurrentUserRanking($userId, $studentList)
    {
        $ranking = null;
        $currentUserId = $userId;
        if (!empty($studentList) && !empty($currentUserId)) {
            $studentList = array_map('floatval', $studentList);
            asort($studentList);
            $ranking = $count = count($studentList);

            foreach ($studentList as $userId => $position) {
                if ($currentUserId == $userId) {
                    break;
                }
                $ranking--;
            }

            // If no ranking was detected.
            if ($ranking == 0) {
                return [];
            }

            return array($ranking, $count);
        }

        return array();
    }
}
