<?php
/* For license terms, see /license.txt */

/**
 * Plugin class for the Easy certificate plugin.
 *
 * @package chamilo.plugin.easycertificate
 *
 * @author Alex Aragon Calixto <aragcar@gmail.com>
 */
class EasyCertificatePlugin extends Plugin
{
    const TABLE_EASYCERTIFICATE = 'plugin_easycertificate';
    public $isCoursePlugin = true;

    // When creating a new course this settings are added to the course
    public $course_settings = [
        [
            'name' => 'easycertificate_course_enable',
            'type' => 'checkbox',
        ],
        [
            'name' => 'use_certificate_default',
            'type' => 'checkbox',
        ],
    ];

    /**
     * Constructor.
     */
    protected function __construct()
    {
        parent::__construct(
            '1.0',
            'Alex Aragón Calixto',
            [
                'enable_plugin_easycertificate' => 'boolean',
            ]
        );

        $this->isAdminPlugin = true;
    }

    /**
     * Instance the plugin.
     *
     * @staticvar null $result
     *
     * @return EasyCertificatePlugin
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
        //Installing course settings
        $this->install_course_fields_in_all_courses();

        $tablesToBeCompared = [self::TABLE_EASYCERTIFICATE];
        $em = Database::getManager();
        $cn = $em->getConnection();
        $sm = $cn->getSchemaManager();
        $tables = $sm->tablesExist($tablesToBeCompared);

        if ($tables) {
            return false;
        }

        require_once api_get_path(SYS_PLUGIN_PATH).'easycertificate/database.php';
    }

    /**
     * This method drops the plugin tables.
     */
    public function uninstall()
    {
        // Deleting course settings.
        $this->uninstall_course_fields_in_all_courses();

        $tablesToBeDeleted = [self::TABLE_EASYCERTIFICATE];
        foreach ($tablesToBeDeleted as $tableToBeDeleted) {
            $table = Database::get_main_table($tableToBeDeleted);
            $sql = "DROP TABLE IF EXISTS $table";
            Database::query($sql);
        }
        $this->manageTab(false);
    }

    /**
     * This method update the previous plugin tables.
     */
    public function update()
    {
        $oldCertificateTable = 'gradebook_certificate_alternative';
        $base = api_get_path(WEB_UPLOAD_PATH);
        if (Database::num_rows(Database::query("SHOW TABLES LIKE '$oldCertificateTable'")) == 1) {
            $sql = "SELECT * FROM $oldCertificateTable";
            $res = Database::query($sql);
            while ($row = Database::fetch_assoc($res)) {
                $pathOrigin = $base.'certificates/'.$row['id'].'/';
                $params = [
                    'access_url_id' => api_get_current_access_url_id(),
                    'c_id' => $row['c_id'],
                    'session_id' => $row['session_id'],
                    'front_content' => $row['front_content'],
                    'back_content' => $row['back_content'],
                    'background_h' => $row['background_h'],
                    'background_v' => $row['background_v'],
                    'orientation' => $row['orientation'],
                    'margin_left' => intval($row['margin_left']),
                    'margin_right' => intval($row['margin_right']),
                    'margin_top' => intval($row['margin_top']),
                    'margin_bottom' => intval($row['margin_bottom']),
                    'certificate_default' => 0,
                    'show_back' => intval($row['show_back']),
                    'date_change' => intval($row['date_change']),
                ];

                $certificateId = Database::insert(self::TABLE_EASYCERTIFICATE, $params);

                // Image manager
                $pathDestiny = $base.'certificates/'.$certificateId.'/';

                if (!file_exists($pathDestiny)) {
                    mkdir($pathDestiny, api_get_permissions_for_new_directories(), true);
                }

                $imgList = [
                    'background_h',
                    'background_v',
                ];
                foreach ($imgList as $value) {
                    if (!empty($row[$value])) {
                        copy(
                            $pathOrigin.$row[$value],
                            $pathDestiny.$row[$value]
                        );
                    }
                }

                if ($row['certificate_default'] == 1) {
                    $params['c_id'] = 0;
                    $params['session_id'] = 0;
                    $params['certificate_default'] = 1;
                    $certificateId = Database::insert(self::TABLE_EASYCERTIFICATE, $params);
                    $pathOrigin = $base.'certificates/default/';
                    $pathDestiny = $base.'certificates/'.$certificateId.'/';
                    foreach ($imgList as $value) {
                        if (!empty($row[$value])) {
                            copy(
                                $pathOrigin.$row[$value],
                                $pathDestiny.$row[$value]
                            );
                        }
                    }
                }
            }

            $sql = "DROP TABLE IF EXISTS $oldCertificateTable";
            Database::query($sql);

            echo get_lang('MessageUpdate');
        }
    }

    /**
     * By default new icon is invisible.
     *
     * @return bool
     */
    public function isIconVisibleByDefault()
    {
        return false;
    }

    public static function getCodeCertificate($catId, $userId){
        $userId = (int) $userId;
        $catId = (int) $catId;
        $list = [];

        $certificateTable = Database::get_main_table(TABLE_MAIN_GRADEBOOK_CERTIFICATE);
        $categoryTable = Database::get_main_table(TABLE_MAIN_GRADEBOOK_CATEGORY);

        $sql = "SELECT cer.id as id_certificate, CONCAT(cer.id,'-',cer.cat_id,'-',cer.user_id) as code_certificate
                FROM $certificateTable cer
                INNER JOIN $categoryTable cat
                ON (cer.cat_id = cat.id AND cer.user_id = $userId)
                WHERE cer.cat_id = $catId";

        $rs = Database::query($sql);
        if (Database::num_rows($rs) > 0) {
            $row = Database::fetch_assoc($rs);
            $list = [
                'id_certificate' => $row['id_certificate'],
                'code_certificate' => $row['code_certificate'],
                'code_certificate_md5' => md5($row['code_certificate'])
            ];
        }
        return $list;
    }
    /**
     * Get certificate data.
     *
     * @param int $id     The certificate
     * @param int $userId
     *
     * @return array
     */
    public static function getCertificateData($id, $userId)
    {
        $id = (int) $id;
        $userId = (int) $userId;

        if (empty($id) || empty($userId)) {
            return [];
        }

        $certificateTable = Database::get_main_table(TABLE_MAIN_GRADEBOOK_CERTIFICATE);
        $categoryTable = Database::get_main_table(TABLE_MAIN_GRADEBOOK_CATEGORY);
        $sql = "SELECT cer.user_id AS user_id, cat.session_id AS session_id, cat.course_code AS course_code , cer.cat_id AS cat_id
                FROM $certificateTable cer
                INNER JOIN $categoryTable cat
                ON (cer.cat_id = cat.id AND cer.user_id = $userId)
                WHERE cer.id = $id";

        $rs = Database::query($sql);

        if (Database::num_rows($rs) > 0) {
            $row = Database::fetch_assoc($rs);
            $courseCode = $row['course_code'];
            $sessionId = $row['session_id'];
            $userId = $row['user_id'];
            $categoryId = $row['cat_id'];

            if (api_get_course_setting('easycertificate_course_enable', $courseCode)) {
                return [
                    'course_code' => $courseCode,
                    'session_id' => $sessionId,
                    'user_id' => $userId,
                    'category_id' => $categoryId
                ];
            }
        }

        return [];
    }

    /**
     * Check if it redirects.
     *
     * @param certificate $certificate
     * @param int         $certId
     * @param int         $userId
     */
    public static function redirectCheck($certificate, $certId, $userId)
    {
        $certId = (int) $certId;
        $userId = !empty($userId) ? $userId : api_get_user_id();

        if (api_get_plugin_setting('easycertificate', 'enable_plugin_easycertificate') === 'true') {
            $infoCertificate = self::getCertificateData($certId, $userId);
            //var_dump($infoCertificate);
            if (!empty($infoCertificate)) {

                if ($certificate->user_id == api_get_user_id() && !empty($certificate->certificate_data)) {
                    $certificateId = $certificate->certificate_data['id'];
                    $extraFieldValue = new ExtraFieldValue('user_certificate');
                    $value = $extraFieldValue->get_values_by_handler_and_field_variable(
                        $certificateId,
                        'downloaded_at'
                    );
                    if (empty($value)) {
                        $params = [
                            'item_id' => $certificate->certificate_data['id'],
                            'extra_downloaded_at' => api_get_utc_datetime(),
                        ];
                        $extraFieldValue->saveFieldValues($params);
                    }
                }

                $url = api_get_path(WEB_PLUGIN_PATH).'easycertificate/src/print_certificate.php'.
                    '?student_id='.$infoCertificate['user_id'].
                    '&course_code='.$infoCertificate['course_code'].
                    '&session_id='.$infoCertificate['session_id'].
                    '&cat_id='.$infoCertificate['category_id'];
                header('Location: '.$url);
                exit;
            }
        }
    }

    /**
     * Get certificate info.
     *
     * @param int $courseId
     * @param int $sessionId
     * @param int $accessUrlId
     *
     * @return array
     */
    public static function getInfoCertificate($courseId, $sessionId, $accessUrlId)
    {
        $courseId = (int) $courseId;
        $sessionId = (int) $sessionId;
        $accessUrlId = !empty($accessUrlId) ? (int) $accessUrlId : 1;

        $table = Database::get_main_table(self::TABLE_EASYCERTIFICATE);
        $sql = "SELECT * FROM $table
                WHERE
                    c_id = $courseId AND
                    session_id = $sessionId AND
                    access_url_id = $accessUrlId";
        $result = Database::query($sql);
        $resultArray = [];
        if (Database::num_rows($result) > 0) {
            $resultArray = Database::fetch_array($result);
        }

        return $resultArray;
    }

    /**
     * Get default certificate info.
     *
     * @param int $accessUrlId
     *
     * @return array
     */
    public static function getInfoCertificateDefault($accessUrlId)
    {
        $accessUrlId = !empty($accessUrlId) ? (int) $accessUrlId : 1;

        $table = Database::get_main_table(self::TABLE_EASYCERTIFICATE);
        $sql = "SELECT * FROM $table
                WHERE certificate_default = 1 AND access_url_id = $accessUrlId";
        $result = Database::query($sql);
        $resultArray = [];
        if (Database::num_rows($result) > 0) {
            $resultArray = Database::fetch_array($result);
        }

        return $resultArray;
    }
}