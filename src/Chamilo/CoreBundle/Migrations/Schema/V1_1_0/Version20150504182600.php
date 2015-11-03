<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Schema\V1_1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;

/**
 * Class Version20150504182600
 *
 * @package Application\Migrations\Schema\V1_1_0
 */
class Version20150504182600 implements Migration
{
    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        return;
        // Set parent language to Spanish for all close-by languages. Same for Italian,
        // French, Portuguese and Chinese
        $connection = $this->connection;
        $sql = "SELECT id, english_name
                FROM language
                WHERE english_name IN ('spanish', 'italian', 'portuguese', 'simpl_chinese', 'french')";
        $result = $connection->executeQuery($sql);
        $dataList = $result->fetchAll();
        $languages = array();

        if (!empty($dataList)) {
            foreach ($dataList as $data) {
                $languages[$data['english_name']] = $data['id'];
            }
        }
        $queries->addQuery(
            "
            UPDATE language SET parent_id = " . $languages['spanish'] . " WHERE english_name = 'quechua_cusco'
        ");
        $queries->addQuery(
            "
            UPDATE language SET parent_id = " . $languages['spanish'] . " WHERE english_name = 'galician'
        ");
        $queries->addQuery(
            "
            UPDATE language SET parent_id = " . $languages['spanish'] . " WHERE english_name = 'esperanto'
        ");
        $queries->addQuery(
            "
            UPDATE language SET parent_id = " . $languages['spanish'] . " WHERE english_name = 'catalan'
        ");
        $queries->addQuery(
            "
            UPDATE language SET parent_id = " . $languages['spanish'] . " WHERE english_name = 'asturian'
        ");
        $queries->addQuery(
            "
            UPDATE language SET parent_id = " . $languages['spanish'] . " WHERE english_name = 'friulian'
        ");
        $queries->addQuery(
            "
            UPDATE language SET parent_id = " . $languages['french'] . " WHERE english_name = 'occitan'
        ");
        $queries->addQuery(
            "
            UPDATE language SET parent_id = " . $languages['portuguese'] . " WHERE english_name = 'brazilian'
        ");
        $queries->addQuery(
            "
            UPDATE language SET parent_id = " . $languages['simpl_chinese'] . " WHERE english_name = 'trad_chinese'
        ");
    }

    /**
     * We don't allow downgrades yet
     * @param Schema $schema
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery(
            "
            UPDATE language SET parent_id = 0 WHERE english_name IN ('trad_chinese', 'brazilian', 'occitan', 'friulian', 'asturian', 'catalan', 'esperanto', 'galician', 'quechua_cusco')
        ");
    }
}
