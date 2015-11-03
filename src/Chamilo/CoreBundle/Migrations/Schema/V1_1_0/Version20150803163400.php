<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Class Version20150803163400
 *
 * @package Application\Migrations\Schema\V1_1_0
 */
class Version20150803163400 implements Migration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->addSettingCurrent(
            'cron_remind_course_expiration_activate',
            null,
            'radio',
            'Crons',
            'false',
            'CronRemindCourseExpirationActivateTitle',
            'CronRemindCourseExpirationActivateComment',
            null,
            null,
            1,
            true,
            false,
            [
                0 => ['value' => 'true', 'text' => 'Yes'],
                1 => ['value' => 'false', 'text' => 'No']
            ]
        );

        $this->addSettingCurrent(
            'cron_remind_course_expiration_frequency',
            null,
            'textfield',
            'Crons',
            '2',
            'CronRemindCourseExpirationFrecuenqyTitle',
            'CronRemindCourseExpirationFrecuenqyComment',
            null,
            null,
            1,
            true,
            false
        );

        $this->addSettingCurrent(
            'cron_course_finished_activate',
            null,
            'radio',
            'Crons',
            'false',
            'CronCourseFinishedActivateTitle',
            'CronCourseFinishedActivateComment',
            null,
            null,
            1,
            true,
            false,
            [
                0 => ['value' => 'true', 'text' => 'Yes'],
                1 => ['value' => 'false', 'text' => 'No']
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

        $deleteOptions->delete('ChamiloCoreBundle:SettingsOptions', 'o')
            ->andWhere(
                $deleteOptions->expr()->in(
                    'o.variable',
                    [
                        'cron_remind_course_expiration_activate',
                        'cron_course_finished_activate'
                    ]
                )
            );
        $deleteOptions->getQuery()->execute();

        $deleteSettings = $entityManage->createQueryBuilder();
        $deleteSettings->delete('ChamiloCoreBundle:SettingsCurrent', 's')
            ->andWhere(
                $deleteSettings->expr()->in(
                    's.variable',
                    [
                        'cron_remind_course_expiration_activate',
                        'cron_remind_course_expiration_frequency',
                        'cron_course_finished_activate'
                    ]
                )
            );
        $deleteSettings->getQuery()->execute();
    }

}
