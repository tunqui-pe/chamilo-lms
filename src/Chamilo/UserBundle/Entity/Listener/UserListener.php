<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\UserBundle\Entity\Listener;

use Chamilo\CoreBundle\Entity\AccessUrl;
use Chamilo\CoreBundle\Entity\AccessUrlRelCourse;
use Chamilo\CoreBundle\Entity\AccessUrlRelUser;
use Chamilo\CoreBundle\Entity\Repository\CourseRepository;

use Chamilo\UserBundle\Repository\UserRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Chamilo\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserListener
 * User entity listener, when a user is created/edited
 * @package Chamilo\CoreBundle\EventListener
 */
class UserListener
{
    /**
     * This code is executed when a new item is created.
     *
     * new object : prePersist
     * edited object: preUpdate
     *
     * @param User $user
     * @param LifecycleEventArgs $args
     *
     * @throws \Exception
     */
    public function prePersist(User $user, LifecycleEventArgs $args)
    {
        /** @var AccessUrlRelUser $urlRelUser */
        $urlRelUser = $user->getPortals()->first();
        if ($urlRelUser) {
            $url = $urlRelUser->getPortal();

            $repo = $args->getEntityManager()->getRepository('ChamiloUserBundle:User');

            $this->checkLimit($repo, $user, $url);
        }
    }

    /**
     * This code is executed when a item is updated
     *
     * @param User $user
     * @param LifecycleEventArgs $args
     * @throws \Exception
     */
    public function preUpdate(User $user, LifecycleEventArgs $args)
    {
    }

    /**
     * @param UserRepository $repo
     * @param User $user
     * @param AccessUrl $url
     * @throws \Exception
     */
    private function checkLimit($repo, User $user, AccessUrl $url)
    {
        /*$limit = $url->getLimitUsers();

        if (!empty($limit)) {
            $count = $repo->getCountUsersByUrl($url);
            if ($count >= $limit) {
                api_warn_hosting_contact('hosting_limit_users', $limit);

                throw new \Exception('PortalUsersLimitReached');
            }
        }*/

        $groups = $user->getGroups();

        /*if (in_array('ROLE_USER', $roles)) {
            $limit = $url->getLimitTeachers();

            if (!empty($limit)) {
                $count = $repo->getCountTeachersByUrl($url);
                var_dump($count);exit;
                if ($count >= $limit) {
                    api_warn_hosting_contact('hosting_limit_users', $limit);

                    throw new \Exception('PortalUsersLimitReached');
                }
            }
        }*/
    }
}
