<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrackEHotspot
 *
 * @ORM\Table(name="track_e_hotspot", indexes={
 *  @ORM\Index(name="hotspot_user_id", columns={"hotspot_user_id"}),
 *  @ORM\Index(name="hotspot_exe_id", columns={"hotspot_exe_id"}),
 *  @ORM\Index(name="hotspot_question_id", columns={"hotspot_question_id"})
 * })
 * @ORM\Entity
 */
class TrackEHotspot
{
    /**
     * @var integer
     *
     * @ORM\Column(name="hotspot_user_id", type="integer", nullable=false)
     */
    private $hotspotUserId;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CoreBundle\Entity\Course", inversedBy="trackEHotspots")
     * @ORM\JoinColumn(name="c_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotspot_exe_id", type="integer", nullable=false)
     */
    private $hotspotExeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotspot_question_id", type="integer", nullable=false)
     */
    private $hotspotQuestionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotspot_answer_id", type="integer", nullable=false)
     */
    private $hotspotAnswerId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hotspot_correct", type="boolean", nullable=false)
     */
    private $hotspotCorrect;

    /**
     * @var string
     *
     * @ORM\Column(name="hotspot_coordinate", type="text", nullable=false)
     */
    private $hotspotCoordinate;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotspot_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $hotspotId;



    /**
     * Set hotspotUserId
     *
     * @param integer $hotspotUserId
     * @return TrackEHotspot
     */
    public function setHotspotUserId($hotspotUserId)
    {
        $this->hotspotUserId = $hotspotUserId;

        return $this;
    }

    /**
     * Get hotspotUserId
     *
     * @return integer
     */
    public function getHotspotUserId()
    {
        return $this->hotspotUserId;
    }

    /**
     * Set course
     * @param \Chamilo\CoreBundle\Entity\Course $course
     * @return \Chamilo\CoreBundle\Entity\TrackEHotspot
     */
    public function setCourse(Course $course)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     * @param \Chamilo\CoreBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set hotspotExeId
     *
     * @param integer $hotspotExeId
     * @return TrackEHotspot
     */
    public function setHotspotExeId($hotspotExeId)
    {
        $this->hotspotExeId = $hotspotExeId;

        return $this;
    }

    /**
     * Get hotspotExeId
     *
     * @return integer
     */
    public function getHotspotExeId()
    {
        return $this->hotspotExeId;
    }

    /**
     * Set hotspotQuestionId
     *
     * @param integer $hotspotQuestionId
     * @return TrackEHotspot
     */
    public function setHotspotQuestionId($hotspotQuestionId)
    {
        $this->hotspotQuestionId = $hotspotQuestionId;

        return $this;
    }

    /**
     * Get hotspotQuestionId
     *
     * @return integer
     */
    public function getHotspotQuestionId()
    {
        return $this->hotspotQuestionId;
    }

    /**
     * Set hotspotAnswerId
     *
     * @param integer $hotspotAnswerId
     * @return TrackEHotspot
     */
    public function setHotspotAnswerId($hotspotAnswerId)
    {
        $this->hotspotAnswerId = $hotspotAnswerId;

        return $this;
    }

    /**
     * Get hotspotAnswerId
     *
     * @return integer
     */
    public function getHotspotAnswerId()
    {
        return $this->hotspotAnswerId;
    }

    /**
     * Set hotspotCorrect
     *
     * @param boolean $hotspotCorrect
     * @return TrackEHotspot
     */
    public function setHotspotCorrect($hotspotCorrect)
    {
        $this->hotspotCorrect = $hotspotCorrect;

        return $this;
    }

    /**
     * Get hotspotCorrect
     *
     * @return boolean
     */
    public function getHotspotCorrect()
    {
        return $this->hotspotCorrect;
    }

    /**
     * Set hotspotCoordinate
     *
     * @param string $hotspotCoordinate
     * @return TrackEHotspot
     */
    public function setHotspotCoordinate($hotspotCoordinate)
    {
        $this->hotspotCoordinate = $hotspotCoordinate;

        return $this;
    }

    /**
     * Get hotspotCoordinate
     *
     * @return string
     */
    public function getHotspotCoordinate()
    {
        return $this->hotspotCoordinate;
    }

    /**
     * Get hotspotId
     *
     * @return integer
     */
    public function getHotspotId()
    {
        return $this->hotspotId;
    }
}
