<?php
/**
 * Plugin class for Alerts Plugin Chamilo
 * @package chamilo.plugins.alerts
 * @author Alex Aragon Calixto <alex.aragon@tunqui.pe>
 */

class AlertsPlugin extends Plugin
{
        const SETTING_ENABLED = 'alerts_plugin_enabled';
        const TABLE_ALERTS_RECORDS = 'plugin_alerts_records';
        public $isAdminPlugin = true;

        protected function __construct()
        {
            parent::__construct(
                '1.0',
                'Alex Aragón Calixto',
                [
                    'show_main_menu_tab' => 'boolean',
                    self::SETTING_ENABLED => 'boolean'
                ]
            );
        }

        public static function create(){
            static $result = null;
            return $result ? $result : $result = new self();
        }


        public function install(){

            //Creando las tablas
            $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_ALERTS_RECORDS." (
                    id INT unsigned NOT NULL auto_increment PRIMARY KEY,
                    size_disk INT NULL,
                    current_size_disk INT NULL,
                    disk_space_free INT NULL,
                    disk_space_consumed INT NULL,
                    date_records datetime NULL
                 );
            ";

            Database::query($sql);

        }

        public function uninstall(){

            $tablesToBeDeleted = [
                self::TABLE_ALERTS_RECORDS,
            ];

            foreach ($tablesToBeDeleted as $tableDeleted){
                $table = Database::get_main_table($tableDeleted);
                $sql = "DROP TABLE IF EXISTS $table";
                Database::query($sql);
            }
        }

        public function getDiskTotalSpace(){
            $dir = '/';
            $totalSpace = disk_total_space($dir);
            $freeSpace = disk_free_space($dir);

            $totalSpaceGB = self::convertFromBytes($totalSpace) ;
            $freeSpaceGB = self::convertFromBytes($freeSpace) ;

            return [$totalSpaceGB, $freeSpaceGB];
        }


        function convertFromBytes($bytes)
        {
            $bytes /= 1024;
            if ($bytes >= 1024 * 1024) {
                $bytes /= 1024;
                return number_format($bytes / 1024, 1) . ' GB';
            } elseif($bytes >= 1024 && $bytes < 1024 * 1024) {
                return number_format($bytes / 1024, 1) . ' MB';
            } else {
                return number_format($bytes, 1) . ' KB';
            }
        }
}
