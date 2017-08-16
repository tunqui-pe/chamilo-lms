<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\InstallerBundle\Persister;

use Symfony\Component\Dotenv\Dotenv;

/**
 * Class EnvPersister
 * @package Chamilo\InstallerBundle\Persister
 */
class EnvPersister
{
    /**
     * Path to parameters.yml file
     *
     * @var string
     */
    protected $paramFile;

    /**
     * @param string $dir Path to parameters storage directory
     * @param string $env Current environment
     */
    public function __construct($dir, $env)
    {
        if (file_exists($file = $dir.'/.env')) {
            $this->paramFile = $file;
        } elseif (file_exists($dir.'/.env.dist')) {
            $this->paramFile = $dir.'/.env';
        } else {
            $this->paramFile = $dir.'/.env';
        }
    }

    /**
     * @return array
     */
    public function parse()
    {
        $dotEnv = new Dotenv();
        $dotEnv->load($this->paramFile);
        /*
        $parameters = array();
        foreach ($data['parameters'] as $key => $value) {
            $section = explode('_', $key);
            $section = isset($section[1]) ? $section[0] : 'system';

            if (!isset($parameters[$section])) {
                $parameters[$section] = array();
            }

            $parameters[$section]['chamilo_installer_'.$key] = $value;
        }

        return $parameters;*/
    }

    /**
     * @param array $data
     */
    public function dump(array $data)
    {
        $dotEnv = new Dotenv();
        $parameters = array();

        foreach ($data as $section) {
            if (!empty($section)) {
                foreach ($section as $key => $value) {
                    $parameters[str_replace(
                        'chamilo_installer_',
                        '',
                        $key
                    )] = $value;
                }
            }
        }

        $dotEnv->populate($parameters);
    }
}
