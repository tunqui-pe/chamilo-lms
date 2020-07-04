<?php
/**
 * Plugin class for Alerts Plugin Chamilo
 * @package chamilo.plugins.alerts
 * @author Alex Aragon Calixto <alex.aragon@tunqui.pe>
 */

class DiskAlertPlugin extends Plugin
{
    const SETTING_ENABLED = 'alerts_email_enabled';
    const ALERTS_PERCENT_DISK = 'alerts_percent_disk';
    const TABLE_ALERTS_RECORDS = 'plugin_alerts_records';

    protected function __construct()
    {
        parent::__construct(
            '1.0',
            'Alex Aragón Calixto <br> Mayra Vivanco (Translation)',
            [
                self::SETTING_ENABLED => 'boolean',
                self::ALERTS_PERCENT_DISK => [
                    'type' => 'select',
                    'options' => [
                        50 => '50 %',
                        60 => '60 %',
                        70 => '70 %',
                        80 => '80 %',
                        90 => '90 %'
                    ]
                ]
            ]
        );
        $this->isAdminPlugin = true;
    }

    public static function create()
    {
        static $result = null;

        return $result ? $result : $result = new self();
    }


    public function install()
    {

        //Creando las tablas
        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_ALERTS_RECORDS." (
                    id INT unsigned NOT NULL auto_increment PRIMARY KEY,
                    date_records datetime NULL,
                    disk_space_used VARCHAR(255) NULL,
                    disk_space_free VARCHAR(255) NULL,
                    percent_disk_used FLOAT NULL,
                    percent_disk_free FLOAT NULL
                 );
            ";

        Database::query($sql);

    }

    public function uninstall()
    {

        $tablesToBeDeleted = [
            self::TABLE_ALERTS_RECORDS,
        ];

        foreach ($tablesToBeDeleted as $tableDeleted) {
            $table = Database::get_main_table($tableDeleted);
            $sql = "DROP TABLE IF EXISTS $table";
            Database::query($sql);
        }
    }

    function saveStatusDisk(){
        $info = self::getInfoDisk();
        $table = Database::get_main_table(self::TABLE_ALERTS_RECORDS);
        $date = date('Y-m-d h:i:s', time());
        $params = [
            'date_records' => $date,
            'disk_space_used' => $info['used_disk'],
            'disk_space_free' => $info['free_disk'],
            'percent_disk_used' => $info['used_percent'],
            'percent_disk_free' => $info['free_percent']
        ];

        $id = Database::insert($table, $params);

        if ($id > 0) {
            return $id;
        }
    }

    public function getStatusDisk(){
        $records = [];
        $tableRecordsList = Database::get_main_table(self::TABLE_ALERTS_RECORDS);
        $sql = "SELECT * FROM $tableRecordsList";

        $result = Database::query($sql);
        if (Database::num_rows($result) > 0) {
            while ($row = Database::fetch_array($result)) {
                $record = [
                    'id' => $row['id'],
                    'date_records' => $row['date_records'],
                    'disk_space_used' => $row['disk_space_used'],
                    'disk_space_free' => $row['disk_space_free'],
                    'percent_disk_used' => $row['percent_disk_used'],
                    'percent_disk_free' => $row['percent_disk_free']
                ];
                $records[] = $record;
            }
        }
        return $records;
    }

    public function deleteStatusDisk($id){
        if (empty($id)) {
            return false;
        }
        $tableRecordsList = Database::get_main_table(self::TABLE_ALERTS_RECORDS);
        $sql = "DELETE FROM $tableRecordsList WHERE id = $id";
        $result = Database::query($sql);

        if (Database::affected_rows($result) != 1) {
            return false;
        }
        return true;
    }

    public function getDiskTotalSpace($type)
    {
        $dir = '/';
        $total = null;

        switch ($type) {
            case 'total' :
                $total = disk_total_space($dir);
                break;
            case 'free' :
                $total = disk_free_space($dir);
                break;
            case 'used' :
                $total = disk_total_space($dir) - disk_free_space($dir);
                break;
        }
        $totalSpaceGB = self::convertFromBytes($total);

        return $totalSpaceGB;
    }

    function getInfoDisk()
    {

        $totalDisk = self::getDiskTotalSpace('total');
        $freeDisk = self::getDiskTotalSpace('free');
        $usedDisk = self::getDiskTotalSpace('used');

        $status = [
            'total_disk' => $totalDisk['value'].' '.$totalDisk['unit'],
            'free_disk' => $freeDisk['value'].' '.$freeDisk['unit'],
            'used_disk' => $usedDisk['value'].' '.$usedDisk['unit'],
            'free_percent' => round(($freeDisk['value'] / $totalDisk['value']) * 100, 1),
            'used_percent' => round((1 - ($freeDisk['value'] / $totalDisk['value'])) * 100, 1),
        ];

        return $status;
    }

    function convertFromBytes($bytes)
    {
        $bytes /= 1024;
        if ($bytes >= 1024 * 1024) {
            $bytes /= 1024;

            return [
                'value' => number_format($bytes / 1024, 1),
                'unit' => 'GB',
            ];

        } elseif ($bytes >= 1024 && $bytes < 1024 * 1024) {
            return [
                'value' => number_format($bytes / 1024, 1),
                'unit' => 'MB',
            ];

        } else {
            return [
                'value' => number_format($bytes, 1),
                'unit' => 'KB',
            ];
        }
    }
}
