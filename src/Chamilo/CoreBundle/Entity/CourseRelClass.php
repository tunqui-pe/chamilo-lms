<?php

namespace Chamilo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CourseRelClass
 *
 * @ORM\Table(name="course_rel_class")
 * @ORM\Entity
 */
class CourseRelClass
{
    /**
     * @var integer
     *
     * @ORM\Column(name="c_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $courseId;

    /**
     * @var integer
     *
     * @ORM\Column(name="class_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $classId;



    /**
     * Set courseId
     *
     * @param integer $courseCode
     * @return \Chamilo\CoreBundle\Entity\CourseRelClass
     */
    public function setCourseId($courseCode)
    {
        $this->courseId = $courseCode;

        return $this;
    }

    /**
     * Get courseId
     *
     * @return integer
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * Set classId
     *
     * @param integer $classId
     * @return \Chamilo\CoreBundle\Entity\CourseRelClass
     */
    public function setClassId($classId)
    {
        $this->classId = $classId;

        return $this;
    }

    /**
     * Get classId
     *
     * @return integer
     */
    public function getClassId()
    {
        return $this->classId;
    }
}
