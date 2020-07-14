<?php

/* For licensing terms, see /license.txt */

/**
 * Plugin class for the Skill records for students plugin.
 *
 * @author Alex Aragón Calixto <alex.aragon@tunqui.pe>
 */

class SkillRecordsPlugin extends Plugin
{
    const SETTING_ENABLED = 'skill_record_plugin_enabled';


    protected function __construct()
    {
        parent::__construct(
            '1.0',
            'Alex Aragón Calixto',
            [
                self::SETTING_ENABLED =>'boolean'

            ]
        );
    }



    public static function create(){
        static $result = null;
        return $result ? $result : $result = new self();
    }
    public static function install(){

    }
    public static function uninstall(){

    }


}

