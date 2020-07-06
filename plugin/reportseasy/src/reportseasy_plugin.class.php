<?php
/**
 * Plugin class for Reports Chamilo
 * @package chamilo.plugins.reportseasy
 * @author Alex Aragon Calixto <alex.aragon@tunqui.pe>
 */
class ReportsEasy extends Plugin
{
    const  SETTING_ENABLED = 'reports_easy_plugin_enabled';

    /**
     * ReportsEasy constructor.
     */
    protected  function __construct() {
        parent:: __construct(
             '1,0',
            'Alex Aragón Calixto <br> Mayra Vivanco (Translation)',
            [
                self::SETTING_ENABLED => 'boolean'
            ]
        );
    }

    /**
     * @return ReportsEasy
     */
    public static function create(){
        static $result = null;
        return $result ? $result : $result = new self();
    }

    public  function install(){

    }

    public  function  uninstall(){

    }
}
