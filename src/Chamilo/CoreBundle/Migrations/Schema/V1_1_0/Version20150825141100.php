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
class Version20150825141100 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->addSettingCurrent(
            'show_full_skill_name_on_skill_wheel',
            null,
            'radio',
            'Platform',
            'false',
            'ShowFullSkillNameOnSkillWheelTitle',
            'ShowFullSkillNameOnSkillWheelComment',
            null,
            null,
            1,
            false,
            true,
            [
                ['value' => 'true', 'text' => 'Yes'],
                ['value' => 'false', 'text' => 'No']
            ]
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $entityManage = $this->getEntityManager();

        $deleteOptions = $entityManage->createQueryBuilder();
        $deleteSettings = $entityManage->createQueryBuilder();

        $deleteOptions->delete('ChamiloCoreBundle:SettingsOptions', 'o')
            ->andWhere(
                $deleteOptions->expr()->in(
                    'o.variable',
                    ['show_full_skill_name_on_skill_wheel']
                )
            );
        $deleteOptions->getQuery()->execute();

        $deleteSettings->delete('ChamiloCoreBundle:SettingsCurrent', 's')
            ->andWhere(
                $deleteSettings->expr()->in(
                    's.variable',
                    ['show_full_skill_name_on_skill_wheel']
                )
            );
        $deleteSettings->getQuery()->execute();
    }
}
