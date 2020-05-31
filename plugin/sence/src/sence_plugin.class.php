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
    const TABLE_SENCE_COURSES = 'plugin_sence_courses';
    const TABLE_SENCE_USERS = 'plugin_sence_users';
    const TABLE_SENCE_USERS_LOGIN = 'plugin_sence_users_login';
    const SETTING_TITLE = 'tool_title';
    const SETTING_ENABLED = 'sence_enabled';
    const RUT_OTEC = 'rut_otec';
    const TOKEN_OTEC = 'token_otec';
    const COMPANY_NAME = 'company_name';
    const ALERT_EMAIL = 'alert_email';
    const REQUIRE_LOGOUT = 'require_logout';
    const LOGIN_REQUIRED = 'login_required';
    const TRAINING_LINE = 3;
    const URL_SENCE_LOGIN_TEST = 'https://sistemas.sence.cl/rcetest/Registro/IniciarSesion';
    const URL_SENCE_LOGOUT_TEST = 'https://sistemas.sence.cl/rcetest/Registro/CerrarSesion';
    const URL_SENCE_LOGIN_PRO = 'https://sistemas.sence.cl/rce/Registro/IniciarSesion';
    const URL_SENCE_LOGOUT_PRO = 'https://sistemas.sence.cl/rce/Registro/CerrarSesion';

    public $isCoursePlugin = true;

    protected function __construct()
    {
        parent::__construct(
            '1.0',
            '
                Alex Aragón Calixto',
            [
                self::SETTING_ENABLED => 'boolean',
                self::RUT_OTEC => 'text',
                self::TOKEN_OTEC => 'text',
                self::COMPANY_NAME => 'text',
                self::ALERT_EMAIL => 'text',
                self::REQUIRE_LOGOUT => 'boolean',
                self::LOGIN_REQUIRED => 'boolean'
            ]
        );

        $this->isAdminPlugin = true;
    }

    /**
     * @return string
     */
    public function getToolTitle()
    {
        $title = $this->get_lang('tool_title');

        if (!empty($title)) {
            return $title;
        }

        return $this->get_title();
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

        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_SENCE_COURSES." (
            id INT unsigned NOT NULL auto_increment PRIMARY KEY,
            c_id INT NULL,
            code_sence VARCHAR(10) NULL,
            code_course VARCHAR(36) NULL,
            id_group INT NULL,
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

        $src1 = api_get_path(SYS_PLUGIN_PATH).'sence/resources/img/64/sence.png';
        $src2 = api_get_path(SYS_PLUGIN_PATH).'sence/resources/img/64/sence_na.png';
        $dest1 = api_get_path(SYS_CODE_PATH).'img/icons/64/sence.png';
        $dest2 = api_get_path(SYS_CODE_PATH).'img/icons/64/sence_na.png';

        copy($src1, $dest1);
        copy($src2, $dest2);

    }

    /**
     * This method drops the plugin tables.
     */
    public function uninstall()
    {
        $this->deleteCourseToolLinks();

        $tablesToBeDeleted = [
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

    /**
     * @return SencePlugin
     */
    public function performActionsAfterConfigure()
    {
        $em = Database::getManager();

        $delete = $this->deleteCourseToolLinks();
        var_dump($delete);

        if ('true' === $this->get(self::SETTING_ENABLED)) {
            $courses = $em->createQuery('SELECT c.id FROM ChamiloCoreBundle:Course c')->getResult();

            foreach ($courses as $course) {
                $this->createLinkToCourseTool($this->getToolTitle(), $course['id']);
            }
        }

        return $this;
    }

    private function deleteCourseToolLinks()
    {
        Database::getManager()
            ->createQuery('DELETE FROM ChamiloCourseBundle:CTool t WHERE t.category = :category AND t.link LIKE :link')
            ->execute(['category' => 'plugin', 'link' => 'sence/start.php%']);
    }

    //Get list group course;

    public function getListGroupCourse(){

        $list = [];
        $listGroups = GroupManager::get_group_list();

        foreach ($listGroups as $group){
            $list[$group['id']] = $group['name'].' - '.$group['id'];
        }

        return $list;

    }

    //Registro codigo SENCE en un curso, para asignarlo

    public function registerCodeSenceCourse($values){
        if (!is_array($values) || empty($values['code_sence'])) {
            return false;
        }
        $table = Database::get_main_table(self::TABLE_SENCE_COURSES);

        $idCourse = api_get_course_int_id();
        $codeCourse = api_get_course_id();

        $params = [
            'c_id' => $idCourse,
            'code_sence' => $values['code_sence'],
            'code_course' => $codeCourse,
            'id_group' => $values['id_group'],
            'training_line' => 3,
            'activate' => $values['activate'],
        ];

        $id = Database::insert($table, $params);

        if ($id > 0) {
            return $id;
        }
    }

    public function updateCodeSenceCourse($values){

        if (!is_array($values) || empty($values['code_sence'])) {
            return false;
        }

        $idCourse = api_get_course_int_id();
        $codeCourse = api_get_course_id();
        $table = Database::get_main_table(self::TABLE_SENCE_COURSES);

        $params = [
            'c_id' => $idCourse,
            'code_sence' => $values['code_sence'],
            'code_course' => $codeCourse,
            'id_group' => $values['id_group'],
            'training_line' => 3,
            'activate' => $values['activate'],
        ];

        Database::update(
            $table,
            $params,
            [
                'id = ?' => [
                    $values['id'],
                ],
            ]
        );

        return true;

    }

    public function deleteSenceCourse($idSence){
        if (empty($idSence)) {
            return false;
        }

        $tableZoomList = Database::get_main_table(self::TABLE_SENCE_COURSES);
        $sql = "DELETE FROM $tableZoomList WHERE id = $idSence";
        $result = Database::query($sql);

        if (Database::affected_rows($result) != 1) {
            return false;
        }

        return true;
    }

    public function getSenceInfo($idCourse)
    {
        if (empty($idCourse)) {
            return false;
        }

        $sence = [];
        $tableSenceCourse = Database::get_main_table(self::TABLE_SENCE_COURSES);
        $sql = "SELECT * FROM $tableSenceCourse
        WHERE c_id = $idCourse";

        $result = Database::query($sql);
        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {
                $sence = [
                    'id' => $row['id'],
                    'c_id' => $row['c_id'],
                    'code_sence' => $row['code_sence'],
                    'code_course' => $row['code_course'],
                    'id_group' => $row['id_group'],
                    'training_line' => $row['training_line'],
                    'activate' => $row['activate']
                ];
            }
        }

        return $sence;
    }
}