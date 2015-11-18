<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Block;

use Chamilo\CoreBundle\Entity\Course;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\SeoBundle\Block\Breadcrumb\BaseBreadcrumbMenuBlockService;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DefaultBreadcrumbBlockService
 * @package Sonata\ProductBundle\Block
 */
class DefaultBreadcrumbBlockService extends BaseBreadcrumbMenuBlockService
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        parent::setDefaultSettings($resolver);

        $resolver->setDefaults(
            array(
                'menu_template' => 'SonataSeoBundle:Block:breadcrumb.html.twig',
                'include_homepage_link' => false,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'chamilo.corebundle.block.breadcrumb';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMenu(BlockContextInterface $blockContext)
    {
        $menu = $this->getRootMenu($blockContext);
        //$menu = parent::getMenu($blockContext);

        $menu->addChild('home', ['route' => 'home']);

        // Add course
        /** @var Course $course */
        if ($course = $blockContext->getBlock()->getSetting('course')) {
            $menu->addChild(
                $course->getTitle(),
                array(
                    'route' => 'course_home',
                    'routeParameters' => array(
                        'course' => $course->getCode(),
                    ),
                )
            );
        }


        return $menu;
    }
}
