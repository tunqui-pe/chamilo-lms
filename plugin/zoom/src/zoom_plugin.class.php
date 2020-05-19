<?php

/**
 * Plugin class for the Zoom Conector plugin.
 *
 * @package chamilo.plugin.zoom
 *
 * @author Alex Aragón Calixto    <alex.aragon@tunqui.pe>
 */
class ZoomPlugin extends Plugin
{
    const TABLE_ZOOM_COURSES = 'plugin_zoom_courses';
    const TABLE_ZOOM_LIST = 'plugin_zoom_room';
    const SETTING_TITLE = 'tool_title';
    const SETTING_ENABLED = 'zoom_enabled';

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
     * @return ZoomPlugin
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
        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_ZOOM_LIST." (
            id INT unsigned NOT NULL auto_increment PRIMARY KEY,
            room_name VARCHAR(250) NULL,
            room_url VARCHAR(250) NULL,
            room_id VARCHAR(10) NULL,
            room_pass VARCHAR(6) NULL,
            zoom_email VARCHAR(250) NULL,
            zoom_pass VARCHAR(250),
            activate INT
        )";

        Database::query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_ZOOM_COURSES." (
            id INT unsigned NOT NULL auto_increment PRIMARY KEY,
            c_id INT NULL,
            id_room INT NULL
        )";

        Database::query($sql);

        $src1 = api_get_path(SYS_PLUGIN_PATH).'zoom/resources/img/64/zoom.png';
        $src2 = api_get_path(SYS_PLUGIN_PATH).'zoom/resources/img/64/zoom_na.png';
        $dest1 = api_get_path(SYS_CODE_PATH).'img/icons/64/zoom.png';
        $dest2 = api_get_path(SYS_CODE_PATH).'img/icons/64/zoom_na.png';

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
            self::TABLE_ZOOM_COURSES,
            self::TABLE_ZOOM_LIST,
        ];

        foreach ($tablesToBeDeleted as $tableToBeDeleted) {
            $table = Database::get_main_table($tableToBeDeleted);
            $sql = "DROP TABLE IF EXISTS $table";
            Database::query($sql);
        }

        $this->manageTab(false);

    }

    /**
     * @return ZoomPlugin
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
            ->execute(['category' => 'plugin', 'link' => 'zoom/start.php%']);
    }

    public function getIdRoomAssociateCourse($idCourse)
    {
        if (empty($idCourse)) {
            return false;
        }
        $idRoom = 0;
        $tableZoomCourse = Database::get_main_table(self::TABLE_ZOOM_COURSES);
        $sql = "SELECT id_room FROM $tableZoomCourse
        WHERE c_id = $idCourse";
        $result = Database::query($sql);
        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {
                $idRoom = $row['id_room'];
            }
        }

        return $idRoom;
    }

    public function removeRoomZoomCourse($idCourse, $idRoom)
    {
        if (empty($idCourse) || empty($idRoom)) {
            return false;
        }
        $tableZoomCourse = Database::get_main_table(self::TABLE_ZOOM_COURSES);
        $sql = "DELETE FROM $tableZoomCourse
                WHERE
                    c_id = $idCourse AND
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
        $tableZoomList = Database::get_main_table(self::TABLE_ZOOM_LIST);
        $sql = "SELECT * FROM $tableZoomList
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
                    'activate' => $row['activate'],
                ];
            }
        }

        return $room;

    }

    public function associateRoomCourse($idCourse, $idRoom)
    {
        if (empty($idCourse) || empty($idRoom)) {
            return false;
        }
        $table = Database::get_main_table(self::TABLE_ZOOM_COURSES);
        $params = [
            'c_id' => $idCourse,
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

        $tableZoomList = Database::get_main_table(self::TABLE_ZOOM_LIST);
        $sql = "DELETE FROM $tableZoomList WHERE id = $idRoom";
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
        $table = Database::get_main_table(self::TABLE_ZOOM_LIST);

        $params = [
            'room_name' => $values['room_name'],
            'room_url' => $values['room_url'],
            'room_id' => $values['room_id'],
            'room_pass' => $values['room_pass'],
            'zoom_email' => $values['zoom_email'],
            'zoom_pass' => $values['zoom_pass'],
            'activate' => 1,
        ];
        $id = Database::insert($table, $params);

        if ($id > 0) {
            return $id;
        }
    }

    public function updateRoom($values){
        if (!is_array($values) || empty($values['room_name'])) {
            return false;
        }
        $table = Database::get_main_table(self::TABLE_ZOOM_LIST);

        $params = [
            'room_name' => $values['room_name'],
            'room_url' => $values['room_url'],
            'room_id' => $values['room_id'],
            'room_pass' => $values['room_pass'],
            'zoom_email' => $values['zoom_email'],
            'zoom_pass' => $values['zoom_pass'],
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


    public function listZooms()
    {
        $list = [];
        $tableZoomList = Database::get_main_table(self::TABLE_ZOOM_LIST);
        $sql = "SELECT * FROM $tableZoomList";
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


                $list[] = [
                    'id' => $row['id'],
                    'room_name' => $row['room_name'],
                    'room_url' => $row['room_url'],
                    'room_id' => $row['room_id'],
                    'room_pass' => $row['room_pass'],
                    'zoom_email' => $row['zoom_email'],
                    'zoom_pass' => $row['zoom_pass'],
                    'activate' => $active,
                    'actions' => $action,
                ];
            }
        }

        return $list;
    }

}