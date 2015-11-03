<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Calendar color
 */
class Version20150813200000 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $entityManage = $this->getEntityManager();

        $deleteOptions = $entityManage->createQueryBuilder();
        $deleteSettings = $entityManage->createQueryBuilder();

        $deleteOptions->delete('ChamiloCoreBundle:SettingsOptions', 'o')
            ->andWhere(
                $deleteOptions->expr()->in(
                    'o.variable',
                    [
                        'math_mimetex'
                    ]
                )
            );
        $deleteOptions->getQuery()->execute();

        $deleteSettings->delete('ChamiloCoreBundle:SettingsCurrent', 's')
            ->andWhere(
                $deleteSettings->expr()->in(
                    's.variable',
                    [
                        'math_mimetex'
                    ]
                )
            );
        $deleteSettings->getQuery()->execute();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $this->addSettingCurrent(
            'math_mimetex',
            null,
            'radio',
            'Editor',
            'false',
            'MathMimetexTitle',
            'MathMimetexComment',
            null,
            null,
            1,
            false,
            true,
            [
                0 => ['value' => 'true', 'text' => 'Yes'],
                1 => ['value' => 'false', 'text' => 'No']
            ]
        );
    }
}
