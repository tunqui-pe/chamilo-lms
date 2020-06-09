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
    const TABLE_SENCE_LOGS = 'plugin_sence_logs';
    const TABLE_SENCE_USERS_LOGIN = 'plugin_sence_users_login';
    const SETTING_TITLE = 'tool_title';
    const SETTING_ENABLED = 'sence_enabled';
    const RUT_OTEC = 'rut_otec';
    const TOKEN_OTEC = 'token_otec';
    const COMPANY_NAME = 'company_name';
//    const ALERT_EMAIL = 'alert_email';
    const REQUIRE_LOGOUT = 'require_logout';
    const LOGIN_REQUIRED = 'login_required';
    const ENVIRONMENT = 'environment';
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
//                self::ALERT_EMAIL => 'text',
                self::REQUIRE_LOGOUT => 'boolean',
                self::LOGIN_REQUIRED => 'boolean',
                self::ENVIRONMENT => 'boolean',
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
            id_session INT NULL,
            code_sence VARCHAR(10) NULL,
            code_course VARCHAR(36) NULL,
            id_group INT NULL,
            training_line INT NULL,
            activate INT
        )";

        Database::query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_SENCE_LOGS." (
            id INT unsigned NOT NULL auto_increment PRIMARY KEY,
            c_id INT NULL,
            id_session INT NULL,
            user_id INT NULL,
            username VARCHAR(100) NULL,
            firstname VARCHAR(250) NULL,
            lastname VARCHAR(250) NULL,
            code_sence VARCHAR(10) NULL,
            id_session_sence VARCHAR(150) NULL,
            code_course VARCHAR(36) NULL,
            run_student VARCHAR(10) NULL,
            date_login DATETIME NULL,
            time_zone VARCHAR(100) NULL,
            training_line INT NULL,
            glosa_error INT NULL,
            type_login INT NULL
        )";

        Database::query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_SENCE_USERS_LOGIN." (
            id INT unsigned NOT NULL auto_increment PRIMARY KEY,
            c_id INT NULL,
            id_session INT NULL,
            user_id INT NULL,
            username VARCHAR(100) NULL,
            firstname VARCHAR(250) NULL,
            lastname VARCHAR(250) NULL,
            code_sence VARCHAR(10) NULL,
            id_session_sence VARCHAR(150) NULL,
            code_course VARCHAR(36) NULL,
            run_student VARCHAR(10) NULL,
            date_login DATETIME NULL,
            time_zone VARCHAR(100) NULL,
            training_line INT NULL,
            glosa_error INT NULL,
            type_login INT NULL
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
            self::TABLE_SENCE_LOGS,
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

    public function getListGroupCourse()
    {

        $list = [];
        $listGroups = GroupManager::get_group_list();

        foreach ($listGroups as $group) {
            $list[$group['id']] = $group['name'].' - '.$group['id'];
        }

        return $list;

    }

    //Registro codigo SENCE en un curso, para asignarlo

    public function registerCodeSenceCourse($values)
    {
        if (!is_array($values) || empty($values['code_sence'])) {
            return false;
        }
        $table = Database::get_main_table(self::TABLE_SENCE_COURSES);

        $idCourse = api_get_course_int_id();
        $idSession = api_get_session_id();

        $params = [
            'c_id' => $idCourse,
            'id_session' => $idSession,
            'code_sence' => $values['code_sence'],
            'code_course' => $values['code_course'],
            'id_group' => $values['id_group'],
            'training_line' => $values['training_line'],
            'activate' => 1,
        ];

        $id = Database::insert($table, $params);

        if ($id > 0) {
            return $id;
        }
    }

    public function updateCodeSenceCourse($values)
    {

        if (!is_array($values) || empty($values['code_sence'])) {
            return false;
        }

        $idCourse = api_get_course_int_id();
        $idSession = api_get_session_id();
        $table = Database::get_main_table(self::TABLE_SENCE_COURSES);

        $params = [
            'c_id' => $idCourse,
            'id_session' => $idSession,
            'code_sence' => $values['code_sence'],
            'code_course' => $values['code_course'],
            'id_group' => $values['id_group'],
            'training_line' => $values['training_line'],
            'activate' => 1,
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

    public function deleteSenceCourse($idSence)
    {
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
        $idSession = api_get_session_id();
        $tableSenceCourse = Database::get_main_table(self::TABLE_SENCE_COURSES);
        $sql = "SELECT * FROM $tableSenceCourse
        WHERE c_id = $idCourse AND id_session = $idSession";

        $result = Database::query($sql);
        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {
                $sence = [
                    'id' => $row['id'],
                    'c_id' => $row['c_id'],
                    'id_session' => $row['id_session'],
                    'code_sence' => $row['code_sence'],
                    'code_course' => $row['code_course'],
                    'id_group' => $row['id_group'],
                    'training_line' => $row['training_line'],
                    'activate' => $row['activate'],
                ];
            }

            return $sence;
        } else {
            return false;
        }
    }

    public function getSenceGroupUser($idCourse)
    {
        if (empty($idCourse)) {
            return false;
        }
        $idScholar = null;
        $idSession = api_get_session_id();
        $tableSenceCourse = Database::get_main_table(self::TABLE_SENCE_COURSES);
        $sql = "SELECT id_group FROM $tableSenceCourse WHERE c_id = $idCourse AND id_session = $idSession";

        $result = Database::query($sql);
        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {
                $idScholar = $row['id_group'];
            }

            return $idScholar;
        } else {
            return false;
        }
    }

    public function getURLSenceLogin($status)
    {

        $env = 'PRO';

        if ($status) {
            $env = 'TEST';
        }

        switch ($env) {
            case 'TEST':
                return [
                    'login' => self::URL_SENCE_LOGIN_TEST,
                    'logout' => self::URL_SENCE_LOGOUT_TEST,
                ];
                break;
            case 'PRO':
                return [
                    'login' => self::URL_SENCE_LOGIN_PRO,
                    'logout' => self::URL_SENCE_LOGOUT_PRO,
                ];
                break;
        }
    }

    //Register User Login Sence for course
    public function registerLoginUserSence($values)
    {
        if (!is_array($values) || empty($values['code_sence'])) {
            return false;
        }

        $tableUserLogin = Database::get_main_table(self::TABLE_SENCE_USERS_LOGIN);
        $id = Database::insert($tableUserLogin, $values);

        if ($id > 0) {
            return $id;
        }
    }

    //Register User Logins Table Logs
    public function registerLogs($values)
    {
        if (!is_array($values)) {
            return false;
        }

        $tableLogs = Database::get_main_table(self::TABLE_SENCE_LOGS);

        $id = Database::insert($tableLogs, $values);

        if ($id > 0) {
            return $id;
        }
    }

    public function getLoginUserSenceInfo($idCourse, $idUser)
    {
        if (empty($idCourse) || empty($idUser)) {
            return false;
        }

        $tableUserLogin = Database::get_main_table(self::TABLE_SENCE_USERS_LOGIN);
        $idSession = api_get_session_id();
        $UserSence = null;

        $sql = "SELECT * FROM $tableUserLogin
        WHERE c_id = $idCourse AND id_session = $idSession AND user_id = $idUser ";
        $result = Database::query($sql);

        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {
                $UserSence = [
                    'id' => $row['id'],
                    'c_id' => $row['c_id'],
                    'id_session' => $row['id_session'],
                    'user_id' => $row['user_id'],
                    'username' => $row['username'],
                    'firstname' => $row['firstname'],
                    'lastname' => $row['lastname'],
                    'code_sence' => $row['code_sence'],
                    'id_session_sence' => $row['id_session_sence'],
                    'code_course' => $row['code_course'],
                    'run_student' => $row['run_student'],
                    'date_login' => $row['date_login'],
                    'time_zone' => $row['time_zone'],
                    'training_line' => $row['training_line'],
                    'glosa_error' => $row['glosa_error'],
                ];
            }

            return $UserSence;

        } else {

            return false;

        }
    }

    public function getLogsHistory($idCourse)
    {

        if (empty($idCourse)) {
            return false;
        }

        $tableLogs = Database::get_main_table(self::TABLE_SENCE_LOGS);
        $idSession = api_get_session_id();
        $list = [];

        $sql = "SELECT * FROM $tableLogs WHERE c_id = $idCourse AND id_session = $idSession";
        $result = Database::query($sql);

        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {
                $item = [
                    'id' => $row['id'],
                    'c_id' => $row['c_id'],
                    'id_session' => $row['id_session'],
                    'username' => $row['username'],
                    'firstname' => $row['firstname'],
                    'lastname' => $row['lastname'],
                    'code_sence' => $row['code_sence'],
                    'id_session_sence' => $row['id_session_sence'],
                    'code_course' => $row['code_course'],
                    'run_student' => $row['run_student'],
                    'date_login' => $row['date_login'],
                    'time_zone' => $row['time_zone'],
                    'training_line' => $row['training_line'],
                    'type_login' => $row['type_login'],
                    'glosa_error' => $row['glosa_error'],
                    'details_error' => self::getErrorLoginMessage($row['glosa_error'])

                ];
                $list[] = $item;
            }

            return $list;

        } else {

            return false;

        }
    }


    public function getIdSessionSenceUser($idUser)
    {
        if (empty($idUser)) {
            return false;
        }

        $tableUserLogin = Database::get_main_table(self::TABLE_SENCE_USERS_LOGIN);

        $idSence = null;

        $sql = "SELECT id_session_sence FROM $tableUserLogin
        WHERE user_id = $idUser ";
        $result = Database::query($sql);

        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {
                $idSence = $row['id_session_sence'];
            }

            return $idSence;

        } else {

            return false;

        }
    }

    public function deteteLoginUserSence($idCourse, $idUser)
    {
        if (empty($idCourse) || empty($idUser)) {
            return false;
        }

        $idSession = api_get_session_id();
        $tableUserLogin = Database::get_main_table(self::TABLE_SENCE_USERS_LOGIN);

        $sql = "DELETE FROM $tableUserLogin
                WHERE
                    c_id = $idCourse AND id_session = $idSession AND 
                    user_id = '".intval($idUser)."'";

        $result = Database::query($sql);

        if (Database::affected_rows($result) != 1) {
            return false;
        }

        return true;

    }

    //Forces the user to login with SENCE when starting the course
    public function loadLoginSence()
    {

        $enabledLoginRequired = self::get('login_required') == 'true';
        $idStudent = api_get_user_id();
        $idCourse = api_get_course_int_id();

        $idGroupUser = self::getUserInGroup($idStudent);
        $idGroupCourse = self::getSenceGroupUser($idCourse);

        $isTeacher = api_is_teacher();
        $isAdmin = api_is_course_admin();

        if ($isAdmin || $isTeacher) {
            return false;
        } else {
            if ($idGroupUser != $idGroupCourse) {
                if ($enabledLoginRequired) {

                    $res = self::getLoginUserSenceInfo($idCourse, $idStudent);

                    if (!$res) {
                        $urlLoginSence = api_get_path(WEB_PLUGIN_PATH).'sence/start.php?'.api_get_cidreq();
                        header('Location: '.$urlLoginSence);
                    } else {
                        $logoutRequired = self::get('require_logout') == 'true';
                        if ($logoutRequired) {
                            $html = self::getModalSence($res);

                            return $html;
                        }
                    }
                }
            }
        }
    }

    public function getTrainingLines()
    {
        $list = [
            1 => self::get_lang('SocialProgramLaborScholarships'),
            3 => self::get_lang('BoostsPeople'),
        ];

        return $list;
    }

    //Login popup

    public function getModalSence($info)
    {
        $urlLoginSence = api_get_path(WEB_PLUGIN_PATH).'sence/start.php?'.api_get_cidreq();
        $tpl = new Template(null, false, false, false, false, false, false);
        $tpl->assign('sence', $info);
        $tpl->assign('url_session', $urlLoginSence);
        $tpl->assign('company_name', self::get('company_name'));
        $tpl->assign('rut_otec', self::get('rut_otec'));
        $html = $tpl->fetch('sence/view/sence_modal.tpl');

        return $html;
    }

    //get Errors Texts.

    public function getErrorLoginMessage($idError)
    {

        $string = null;

        switch ($idError) {
            case 0:
                $string = self::get_lang('NotErrorSence');
                break;
            case 100:
                $string = self::get_lang('ErrorSence100');
                break;
            case 200:
                $string = self::get_lang('ErrorSence200');
                break;
            case 201:
                $string = self::get_lang('ErrorSence201');
                break;
            case 202:
                $string = self::get_lang('ErrorSence202');
                break;
            case 203:
                $string = self::get_lang('ErrorSence203');
                break;
            case 204:
                $string = self::get_lang('ErrorSence204');
                break;
            case 205:
                $string = self::get_lang('ErrorSence205');
                break;
            case 206:
                $string = self::get_lang('ErrorSence206');
                break;
            case 207:
                $string = self::get_lang('ErrorSence207');
                break;
            case 208:
                $string = self::get_lang('ErrorSence208');
                break;
            case 209:
                $string = self::get_lang('ErrorSence209');
                break;
            case 210:
                $string = self::get_lang('ErrorSence210');
                break;
            case 211:
                $string = self::get_lang('ErrorSence211');
                break;
            case 212:
                $string = self::get_lang('ErrorSence212');
                break;
            case 300:
                $string = self::get_lang('ErrorSence300');
                break;
            case 301:
                $string = self::get_lang('ErrorSence301');
                break;
            case 302:
                $string = self::get_lang('ErrorSence302');
                break;
            case 303:
                $string = self::get_lang('ErrorSence303');
                break;
            case 304:
                $string = self::get_lang('ErrorSence304');
                break;
            case 305:
                $string = self::get_lang('ErrorSence305');
                break;
            case 306:
                $string = self::get_lang('ErrorSence306');
                break;
            case 307:
                $string = self::get_lang('ErrorSence307');
                break;
            case 308:
                $string = self::get_lang('ErrorSence308');
                break;
            case 309:
                $string = self::get_lang('ErrorSence309');
                break;
            case 310:
                $string = self::get_lang('ErrorSence310');
                break;
            default:
                $string = self::get_lang('ErrorSence000');
                break;
        }

        return $string;
    }

    //Get Group ID User
    public function getUserInGroup($idUser)
    {
        if (empty($idUser)) {
            return false;
        }

        $idCourse = api_get_course_int_id();
        $idSession = api_get_session_id();

        $sql = "SELECT cg.id FROM c_group_info cg INNER JOIN c_group_rel_user cgu ON 
        cg.id = cgu.group_id WHERE cgu.user_id = $idUser AND cgu.c_id = $idCourse AND cg.session_id = $idSession";
        $result = Database::query($sql);
        $idGroup = null;

        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {
                $idGroup = $row['id'];
            }

            return $idGroup;

        } else {

            return 0;

        }
    }
}