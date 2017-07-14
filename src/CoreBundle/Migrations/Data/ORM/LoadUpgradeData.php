<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\MigrationBundle\Entity\DataMigration;
use Oro\Bundle\MigrationBundle\Migrations\Schema\v1_1\OroMigrationBundle;
use Sonata\PageBundle\Model\SiteInterface;
use Sonata\PageBundle\Model\PageInterface;

use Symfony\Cmf\Bundle\RoutingBundle\Tests\Unit\Doctrine\Orm\ContentRepositoryTest;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Oro\Bundle\MigrationBundle\Fixture\VersionedFixtureInterface;

/**
 * Class LoadPageData
 * @package Chamilo\CoreBundle\Migrations\Data\ORM
 */
class LoadUpgradeData extends AbstractFixture implements
    ContainerAwareInterface,
    OrderedFixtureInterface,
    VersionedFixtureInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return '2.0.0';
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $migration = new DataMigration();
        $migration->setVersion('V1_1');
        $migration->setLoadedAt(new \DateTime());
        $migration->setBundle('OroMigrationBundle');
        $manager->persist($migration);

        $migration = new DataMigration();
        $migration->setVersion('V2_0_0');
        $migration->setLoadedAt(new \DateTime());
        $migration->setBundle('ChamiloCoreBundle');
        $manager->persist($migration);

        $migration = new DataMigration();
        $migration->setVersion('V2_0_0');
        $migration->setLoadedAt(new \DateTime());
        $migration->setBundle('ChamiloNotebookBundle');
        $manager->persist($migration);

        $manager->flush();

        return;
    }
}
