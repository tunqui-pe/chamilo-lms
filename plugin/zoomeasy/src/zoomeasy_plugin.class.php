<?php

/**
 * Plugin class for the Zoom Easy Conector plugin.
 *
 * @package chamilo.plugin.zoomeasy
 *
 * @author Alex Aragón Calixto    <alex.aragon@tunqui.pe>
 */
class ZoomEasyPlugin extends Plugin
{
    const TABLE_ZOOMEASY_COURSES = 'plugin_zoomeasy_courses';
    const TABLE_ZOOMEASY_LIST = 'plugin_zoomeasy_room';
    const SETTING_TITLE = 'tool_title';
    const SETTING_ENABLED = 'zoomeasy_enabled';
    const VIEW_CREDENTIALS = 'view_credentials';

    public $isCoursePlugin = true;

    protected function __construct()
    {
        parent::__construct(
            '1.0',
            '
                Alex Aragón Calixto',
            [
                self::SETTING_ENABLED => 'boolean',
                self::SETTING_TITLE => 'text',
                self::VIEW_CREDENTIALS => 'boolean',
            ]
        );

        $this->isAdminPlugin = true;
    }

    /**
     * @return string
     */
    public function getToolTitle()
    {
        $title = $this->get(self::SETTING_TITLE);

        if (!empty($title)) {
            return $title;
        }

        return $this->get_title();
    }

    /**
     * @return ZoomeasyPlugin
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
        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_ZOOMEASY_LIST." (
            id INT unsigned NOT NULL auto_increment PRIMARY KEY,
            room_name VARCHAR(250) NULL,
            room_url VARCHAR(250) NULL,
            room_id VARCHAR(15) NULL,
            room_pass VARCHAR(15) NULL,
            zoom_email VARCHAR(250) NULL,
            zoom_pass VARCHAR(250),
            type_room INT NOT NULL,
            user_id INT NULL NOT NULL,
            activate INT
        )";

        Database::query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_ZOOMEASY_COURSES." (
            id INT unsigned NOT NULL auto_increment PRIMARY KEY,
            c_id INT NULL,
            id_session INT NULL,
            id_room INT NULL
        )";

        Database::query($sql);

        $src1 = api_get_path(SYS_PLUGIN_PATH).'zoomeasy/resources/img/64/zoomeasy.png';
        $src2 = api_get_path(SYS_PLUGIN_PATH).'zoomeasy/resources/img/64/zoomeasy_na.png';
        $dest1 = api_get_path(SYS_CODE_PATH).'img/icons/64/zoomeasy.png';
        $dest2 = api_get_path(SYS_CODE_PATH).'img/icons/64/zoomeasy_na.png';

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
            self::TABLE_ZOOMEASY_COURSES,
            self::TABLE_ZOOMEASY_LIST,
        ];

        foreach ($tablesToBeDeleted as $tableToBeDeleted) {
            $table = Database::get_main_table($tableToBeDeleted);
            $sql = "DROP TABLE IF EXISTS $table";
            Database::query($sql);
        }

        $this->manageTab(false);

    }

    /**
     * @return ZoomeasyPlugin
     */
    public function performActionsAfterConfigure()
    {
        $em = Database::getManager();

        $this->deleteCourseToolLinks();

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
            ->execute(['category' => 'plugin', 'link' => 'zoomeasy/start.php%']);
    }

    public function getIdRoomAssociateCourse($idCourse, $idSession)
    {
        if (empty($idCourse)) {
            return false;
        }
        $idRoom = 0;
        $tableZoomEasyCourse = Database::get_main_table(self::TABLE_ZOOMEASY_COURSES);
        $sql = "SELECT id_room FROM $tableZoomEasyCourse
        WHERE c_id = $idCourse AND 	id_session = $idSession";
        $result = Database::query($sql);
        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {
                $idRoom = $row['id_room'];
            }
        }

        return $idRoom;
    }

    public function removeRoomZoomEasyCourse($idCourse, $idRoom, $idSession)
    {
        if (empty($idCourse) || empty($idRoom)) {
            return false;
        }
        $tableZoomEasyCourse = Database::get_main_table(self::TABLE_ZOOMEASY_COURSES);
        $sql = "DELETE FROM $tableZoomEasyCourse
                WHERE
                    c_id = $idCourse AND id_session = $idSession AND
                    id_room = '".intval($idRoom)."'";
        $result = Database::query($sql);

        if (Database::affected_rows($result) != 1) {
            return false;
        }

        return true;
    }

    public function getRoomInfo($idRoom)
    {
        if (empty($idRoom)) {
            return false;
        }
        $room = [];
        $tableZoomEasyList = Database::get_main_table(self::TABLE_ZOOMEASY_LIST);
        $sql = "SELECT * FROM $tableZoomEasyList
        WHERE id = $idRoom";

        $result = Database::query($sql);
        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {
                $room = [
                    'id' => $row['id'],
                    'room_name' => $row['room_name'],
                    'room_url' => $row['room_url'],
                    'room_id' => $row['room_id'],
                    'room_pass' => $row['room_pass'],
                    'zoom_email' => $row['zoom_email'],
                    'zoom_pass' => $row['zoom_pass'],
                    'type_room' => $row['type_room'],
                    'user_id' => $row['user_id'],
                    'activate' => $row['activate'],
                ];
            }
        }

        return $room;

    }

    public function associateRoomCourse($idCourse, $idRoom, $idSession)
    {
        if (empty($idCourse) || empty($idRoom)) {
            return false;
        }
        $table = Database::get_main_table(self::TABLE_ZOOMEASY_COURSES);
        $params = [
            'c_id' => $idCourse,
            'id_session' => $idSession,
            'id_room' => $idRoom,
        ];

        $id = Database::insert($table, $params);

        if ($id > 0) {
            return $id;
        }
    }

    public function deleteRoom($idRoom)
    {
        if (empty($idRoom)) {
            return false;
        }

        $tableZoomEasyList = Database::get_main_table(self::TABLE_ZOOMEASY_LIST);
        $sql = "DELETE FROM $tableZoomEasyList WHERE id = $idRoom";
        $result = Database::query($sql);

        if (Database::affected_rows($result) != 1) {
            return false;
        }

        return true;

    }

    public function saveRoom($values)
    {

        if (!is_array($values) || empty($values['room_name'])) {
            return false;
        }
        $table = Database::get_main_table(self::TABLE_ZOOMEASY_LIST);

        $params = [
            'room_name' => $values['room_name'],
            'room_url' => $values['room_url'],
            'room_id' => str_replace(' ','',$values['room_id']),
            'room_pass' => $values['room_pass'],
            'zoom_email' => $values['zoom_email'],
            'zoom_pass' => $values['zoom_pass'],
            'type_room' => $values['type_room'],
            'user_id' => api_get_user_id(),
            'activate' => 1,
        ];
        $id = Database::insert($table, $params);

        if ($id > 0) {
            return $id;
        }
    }

    public function updateRoom($values)
    {
        if (!is_array($values) || empty($values['room_name'])) {
            return false;
        }
        $table = Database::get_main_table(self::TABLE_ZOOMEASY_LIST);

        $params = [
            'room_name' => $values['room_name'],
            'room_url' => $values['room_url'],
            'room_id' => str_replace(' ','',$values['room_id']),
            'room_pass' => $values['room_pass'],
            'zoom_email' => $values['zoom_email'],
            'zoom_pass' => $values['zoom_pass'],
            'type_room' => $values['type_room'],
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

    public function listZoomEasysAdmin($typeRoom, $array = false)
    {
        $list = [];
        $tableZoomEasyList = Database::get_main_table(self::TABLE_ZOOMEASY_LIST);
        $sql = "SELECT * FROM $tableZoomEasyList WHERE type_room = $typeRoom AND activate = 1";

        $result = Database::query($sql);

        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {

                $action = Display::url(
                    Display::return_icon(
                        'edit.png',
                        get_lang('Edit'),
                        [],
                        ICON_SIZE_SMALL
                    ),
                    'list.php?action=edit&id_room='.$row['id']
                );

                $action .= Display::url(
                    Display::return_icon(
                        'delete.png',
                        get_lang('Delete'),
                        [],
                        ICON_SIZE_SMALL
                    ),
                    'list.php?action=delete&id_room='.$row['id'],
                    [
                        'onclick' => 'javascript:if(!confirm('."'".
                            addslashes(api_htmlentities(get_lang("ConfirmYourChoice")))
                            ."'".')) return false;',
                    ]
                );

                $active = Display::return_icon('accept.png', null, [], ICON_SIZE_TINY);
                if (intval($row['activate']) != 1) {
                    $active = Display::return_icon('error.png', null, [], ICON_SIZE_TINY);
                }

                if ($array) {
                    $list[] = [
                        'id' => $row['id'],
                        'room_name' => $row['room_name'],
                        'room_url' => $row['room_url'],
                        'room_id' => $row['room_id'],
                        'room_pass' => $row['room_pass'],
                        'zoom_email' => $row['zoom_email'],
                        'zoom_pass' => $row['zoom_pass'],
                        'type_room' => $row['type_room'],
                        'user_id' => $row['user_id'],
                        'activate' => $row['activate'],
                    ];
                } else {
                    $list[] = [
                        'id' => $row['id'],
                        'room_name' => $row['room_name'],
                        'room_url' => $row['room_url'],
                        'room_id' => $row['room_id'],
                        'room_pass' => $row['room_pass'],
                        'zoom_email' => $row['zoom_email'],
                        'zoom_pass' => $row['zoom_pass'],
                        'type_room' => $row['type_room'],
                        'user_id' => $row['user_id'],
                        'activate' => $active,
                        'actions' => $action,
                    ];
                }
            }

            return $list;
        }
    }

    public function listZoomEasys($typeRoom, $userID, $array = false)
    {
        $list = [];
        $tableZoomEasyList = Database::get_main_table(self::TABLE_ZOOMEASY_LIST);
        $sql = "SELECT * FROM $tableZoomEasyList WHERE type_room = $typeRoom AND user_id = $userID AND activate = 1";

        $result = Database::query($sql);


        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {

                $action = Display::url(
                    Display::return_icon(
                        'edit.png',
                        get_lang('Edit'),
                        [],
                        ICON_SIZE_SMALL
                    ),
                    'list.php?action=edit&id_room='.$row['id']
                );

                $action .= Display::url(
                    Display::return_icon(
                        'delete.png',
                        get_lang('Delete'),
                        [],
                        ICON_SIZE_SMALL
                    ),
                    'list.php?action=delete&id_room='.$row['id'],
                    [
                        'onclick' => 'javascript:if(!confirm('."'".
                            addslashes(api_htmlentities(get_lang("ConfirmYourChoice")))
                            ."'".')) return false;',
                    ]
                );

                $active = Display::return_icon('accept.png', null, [], ICON_SIZE_TINY);
                if (intval($row['activate']) != 1) {
                    $active = Display::return_icon('error.png', null, [], ICON_SIZE_TINY);
                }

                if ($array) {
                    $list[] = [
                        'id' => $row['id'],
                        'room_name' => $row['room_name'],
                        'room_url' => $row['room_url'],
                        'room_id' => $row['room_id'],
                        'room_pass' => $row['room_pass'],
                        'zoom_email' => $row['zoom_email'],
                        'zoom_pass' => $row['zoom_pass'],
                        'type_room' => $row['type_room'],
                        'user_id' => $row['user_id'],
                        'activate' => $row['activate'],
                    ];
                } else {
                    $list[] = [
                        'id' => $row['id'],
                        'room_name' => $row['room_name'],
                        'room_url' => $row['room_url'],
                        'room_id' => $row['room_id'],
                        'room_pass' => $row['room_pass'],
                        'zoom_email' => $row['zoom_email'],
                        'zoom_pass' => $row['zoom_pass'],
                        'type_room' => $row['type_room'],
                        'user_id' => $row['user_id'],
                        'activate' => $active,
                        'actions' => $action,
                    ];
                }

            }
        }

        return $list;
    }

}
