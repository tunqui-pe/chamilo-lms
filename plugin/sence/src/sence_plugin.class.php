<?php
/**
 * Plugin class for the SENCE Conector plugin.
 *
 * @package chamilo.plugin.sence
 *
 * @author Alex Aragón Calixto    <alex.aragon@tunqui.pe>
 */
class SencePlugin extends Plugin
{
    const TABLE_SENCE_SETTINGS = 'plugin_sence_settings';
    const TABLE_SENCE_COURSES = 'plugin_sence_courses';
    const TABLE_SENCE_USERS = 'plugin_sence_users';
    const TABLE_SENCE_USERS_LOGIN = 'plugin_sence_users_login';


    public $isAdminPlugin = true;

    protected function __construct()
    {
        parent::__construct(
            '1.0',
            '
                Alex Aragón Calixto',
            ['sence_enable' => 'boolean']
        );
    }

    /**
     * @return SencePlugin
     */
    public static function create()
    {
        static $result = null;

        return $result ? $result : $result = new self();
    }


    /**
     * This method creates the tables required to this plugin.
     */
    public function install()
    {
        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_SENCE_SETTINGS." (
            id INT unsigned NOT NULL auto_increment PRIMARY KEY,
            rut_otec VARCHAR(10) NULL,
            token_otec VARCHAR(36) NULL,
            company_name VARCHAR(250) NULL,
            alert_email VARCHAR(250) NULL,
            require_logout INT,
            login_required INT,
            message LONGTEXT,
            activate INT
        )";

        Database::query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_SENCE_COURSES." (
            id INT unsigned NOT NULL auto_increment PRIMARY KEY,
            c_id INT NULL,
            code_sence VARCHAR(10) NULL,
            code_course VARCHAR(36) NULL,
            fellows VARCHAR(60) NULL,
            training_line INT NULL,
            activate INT
        )";

        Database::query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_SENCE_USERS." (
            id INT unsigned NOT NULL auto_increment PRIMARY KEY,
            user_id INT NULL,
            run_student VARCHAR(10) NULL
        )";

        Database::query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_SENCE_USERS_LOGIN." (
            id INT unsigned NOT NULL auto_increment PRIMARY KEY,
            c_id INT NULL,
            user_id INT NULL,
            username VARCHAR(100) NULL,
            code_sence VARCHAR(10) NULL,
            code_course VARCHAR(36) NULL,
            run_student VARCHAR(10) NULL,
            date_login DATETIME NULL,
            time_zone VARCHAR(100) NULL,
            training_line INT NULL,
            glosa_error INT
        )";

        Database::query($sql);
    }

    /**
     * This method drops the plugin tables.
     */
    public function uninstall()
    {
        $tablesToBeDeleted = [
            self::TABLE_SENCE_SETTINGS,
            self::TABLE_SENCE_COURSES,
            self::TABLE_SENCE_USERS,
            self::TABLE_SENCE_USERS_LOGIN,
        ];

        foreach ($tablesToBeDeleted as $tableToBeDeleted) {
            $table = Database::get_main_table($tableToBeDeleted);
            $sql = "DROP TABLE IF EXISTS $table";
            Database::query($sql);
        }
        $this->manageTab(false);

    }
}