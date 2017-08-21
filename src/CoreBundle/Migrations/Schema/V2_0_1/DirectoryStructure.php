<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V2_0_1;

use Chamilo\CoreBundle\Migrations\AbstractMigrationChamilo;
use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\ParametrizedMigrationQuery;
use Chamilo\CoreBundle\Entity\ExtraField;
use Chamilo\CourseBundle\Entity\CSurvey;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class Version20170627122900
 * @package Application\Migrations\Schema\V200
 */
class DirectoryStructure extends AbstractMigrationChamilo implements OrderedMigrationInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
    * {@inheritdoc}
    */
    public function getOrder()
    {
        return 1;
    }

    /**
     * @param Schema $schema
     * @param QueryBag $queries
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $fs = $this->container->get('filesystem');
        /** @var \Chamilo\Kernel $kernel */
        $kernel = $this->container->get('kernel');
        $rootDir = $kernel->getRealRootDir();

        // Rename app/upload to public/uploads
        if ($fs->exists($rootDir.'app/upload')) {
            $fs->rename($rootDir.'app/upload', $rootDir.'public/uploads', true);
        }
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
