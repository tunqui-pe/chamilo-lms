<?php
/* For licensing terms, see /license.txt */
namespace Application\Migrations\Schema\V20;

use Application\Migrations\AbstractMigrationChamilo;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20
 * Migrate file to updated to Chamilo 2.0
 *
 */
class Version20 extends AbstractMigrationChamilo
{
    /**
     *
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $sql = "ALTER TABLE user ADD COLUMN email_canonical email_canonical VARCHAR(255) NOT NULL";
        $this->addSql($sql);

        $this->addSql('ALTER TABLE user ADD enabled TINYINT(1) NOT NULL, ADD locked TINYINT(1) NOT NULL, ADD expired TINYINT(1) NOT NULL, ADD expires_at DATETIME DEFAULT NULL, ADD credentials_expired TINYINT(1) NOT NULL, ADD credentials_expire_at DATETIME DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD date_of_birth DATETIME DEFAULT NULL, ADD website VARCHAR(64) DEFAULT NULL, ADD biography VARCHAR(255) DEFAULT NULL, ADD gender VARCHAR(1) DEFAULT NULL, ADD locale VARCHAR(8) DEFAULT NULL, ADD timezone VARCHAR(64) DEFAULT NULL, ADD facebook_uid VARCHAR(255) DEFAULT NULL, ADD facebook_name VARCHAR(255) DEFAULT NULL, ADD facebook_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD twitter_uid VARCHAR(255) DEFAULT NULL, ADD twitter_name VARCHAR(255) DEFAULT NULL, ADD twitter_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD gplus_uid VARCHAR(255) DEFAULT NULL, ADD gplus_name VARCHAR(255) DEFAULT NULL, ADD gplus_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD token VARCHAR(255) DEFAULT NULL, ADD two_step_code VARCHAR(255) DEFAULT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE username_canonical username_canonical VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql($sql);

        $sql = "UPDATE user SET email_canonical = email";
        $this->addSql($sql);

        $sql = "UPDATE user SET username_canonical = username;";
        $this->addSql($sql);
    }

    /**
     *
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
