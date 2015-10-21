<?php

namespace Chamilo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SkillRelUser
 *
 * @ORM\Table(
 *  name="skill_rel_user",
 *  indexes={
 *      @ORM\Index(name="idx_select_cs", columns={"course_id", "session_id"}),
 *      @ORM\Index(name="idx_select_s_c_u", columns={"session_id", "course_id", "user_id"}),
 *      @ORM\Index(name="idx_select_sk_u", columns={"skill_id", "user_id"})
 *  }
 * )
 * @ORM\Entity
 */
class SkillRelUser
{
    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\UserBundle\Entity\User", inversedBy="achievedSkills", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CoreBundle\Entity\Skill", inversedBy="issuedSkills", cascade={"persist"})
     * @ORM\JoinColumn(name="skill_id", referencedColumnName="id", nullable=false)
     */
    private $skill;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="acquired_skill_at", type="datetime", nullable=false)
     */
    private $acquiredSkillAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="assigned_by", type="integer", nullable=false)
     */
    private $assignedBy;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CoreBundle\Entity\Course", inversedBy="issuedSkills", cascade={"persist"})
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CoreBundle\Entity\Session", inversedBy="issuedSkills", cascade={"persist"})
     * @ORM\JoinColumn(name="session_id", referencedColumnName="id")
     */
    private $session;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="argumentation", type="text")
     */
    private $argumentation;

    /**
     * Set user
     * @param \Chamilo\UserBundle\Entity\User $user
     * @return \Chamilo\CoreBundle\Entity\SkillRelUser
     */
    public function setUser(\Chamilo\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     * @return \Chamilo\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set skill
     * @param \Chamilo\CoreBundle\Entity\Skill $skill
     * @return \Chamilo\CoreBundle\Entity\SkillRelUser
     */
    public function setSkill(Skill $skill)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill
     * @return \Chamilo\CoreBundle\Entity\Skill
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * Set acquiredSkillAt
     *
     * @param \DateTime $acquiredSkillAt
     * @return SkillRelUser
     */
    public function setAcquiredSkillAt($acquiredSkillAt)
    {
        $this->acquiredSkillAt = $acquiredSkillAt;

        return $this;
    }

    /**
     * Get acquiredSkillAt
     *
     * @return \DateTime
     */
    public function getAcquiredSkillAt()
    {
        return $this->acquiredSkillAt;
    }

    /**
     * Set assignedBy
     *
     * @param integer $assignedBy
     * @return SkillRelUser
     */
    public function setAssignedBy($assignedBy)
    {
        $this->assignedBy = $assignedBy;

        return $this;
    }

    /**
     * Get assignedBy
     *
     * @return integer
     */
    public function getAssignedBy()
    {
        return $this->assignedBy;
    }

    /**
     * Set course
     * @param \Chamilo\CoreBundle\Entity\Course $course
     * @return \Chamilo\CoreBundle\Entity\SkillRelUser
     */
    public function setCourse(Course $course)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     * @return \Chamilo\CoreBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set session
     * @param \Chamilo\CoreBundle\Entity\Session $session
     * @return \Chamilo\CoreBundle\Entity\SkillRelUser
     */
    public function setSession(Session $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     * @return \Chamilo\CoreBundle\Entity\Session
     */
    public function getSession()
    {
        return $this->session;
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
     * Set argumentation
     * @param string $argumentation
     * @return \Chamilo\CoreBundle\Entity\SkillRelUser
     */
    public function setArgumentation($argumentation)
    {
        $this->argumentation = $argumentation;

        return $this;
    }

    /**
     * Get argumentation
     * @return string
     */
    public function getArgumentation()
    {
        return $this->argumentation;
    }

    /**
     * Get the source which the skill was obtained
     * @return string
     */
    public function getSourceName()
    {
        $source = '';

        if ($this->session) {
            $source .= "[{$this->session->getName()}] ";
        }

        if ($this->course) {
            $source .= $this->course->getTitle();
        }

        return $source;
    }

    /**
     * Get the URL for the issue
     * @return string
     */
    public function getIssueUrl()
    {
        return api_get_path(WEB_PATH) . "badge/issue/{$this->id}/user/{$this->user->getId()}/";
    }

    /**
     * Get the URL for the assertion
     * @return string
     */
    public function getAssertionUrl()
    {
        $url = api_get_path(WEB_CODE_PATH) . "badge/assertion.php?";

        $url .= http_build_query(array(
            'user' => $this->user->getId(),
            'skill' => $this->skill->getId(),
            'course' => $this->course ? $this->course->getId() : 0,
            'session' => $this->session ? $this->session->getId() : 0
        ));

        return $url;
    }
}
