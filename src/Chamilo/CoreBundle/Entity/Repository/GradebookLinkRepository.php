<?php
/* For licensing terms, see /license.txt */
namespace Chamilo\CoreBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository; 
use Doctrine\Common\Collections\Criteria;

/**
 * Description of GradebookLinkRepository
 *
 * @author aquiroz
 */
class GradebookLinkRepository extends EntityRepository
{
    /**
     * Get the gradebook links by course and refId and type
     * @param \Chamilo\CoreBundle\Entity\Course $course
     * @param int $refId
     * @param int $type
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getLinksByCourseAndReferenceAndType(\Chamilo\CoreBundle\Entity\Course $course, $refId, $type)
    {
        $criteria = Criteria::create();
        $criteria
            ->where(
                Criteria::expr()->eq('refId', $refId)
            )
            ->andWhere(
                Criteria::expr()->eq('course', $course)
            )
            ->andWhere(
                Criteria::expr()->eq('type', $type)
            );

        return $this->matching($criteria);
    }
}
