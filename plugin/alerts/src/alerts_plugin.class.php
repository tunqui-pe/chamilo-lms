<?php
/**
 * Plugin class for Alerts Plugin Chamilo
 * @package chamilo.plugins.alerts
 * @author Alex Aragon Calixto <alex.aragon@tunqui.pe>
 */

class AlertsPlugin extends Plugin
{
    const SETTING_ENABLED = 'alerts_email_enabled';
    const ALERTS_PERCENT_DISK = 'alerts_percent_disk';
    const TABLE_ALERTS_RECORDS = 'plugin_alerts_records';
    public $isAdminPlugin = true;

    protected function __construct()
    {
        parent::__construct(
            '1.0',
            'Alex Aragón Calixto',
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
                    size_disk INT NULL,
                    current_size_disk INT NULL,
                    disk_space_free INT NULL,
                    disk_space_consumed INT NULL,
                    date_records datetime NULL
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
            'free_percent' => round(($freeDisk['value'] / $totalDisk['value']) * 100),
            'used_percent' => round((1 - ($freeDisk['value'] / $totalDisk['value'])) * 100),
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
