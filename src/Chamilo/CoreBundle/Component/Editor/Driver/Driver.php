<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Component\Editor\Driver;

use Chamilo\CoreBundle\Component\Editor\Connector;
use FM\ElFinderPHP\Driver\ElFinderVolumeLocalFileSystem;
use FM\ElFinderPHP\ElFinder;

/**
 * Class Driver
 *
 * @package Chamilo\CoreBundle\Component\Editor\Driver
 */
class Driver extends ElFinderVolumeLocalFileSystem
{
    /** @var string */
    public $name;

    /** @var Connector */
    public $connector;

    protected $encoding = 'utf-8';

    /**
     * Gets driver name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets driver name.
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set connector
     * @param Connector $connector
     */
    public function setConnector(Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * @return array
     */
    public function getAppPluginOptions()
    {
        return $this->getOptionsPlugin('chamilo');
    }

    /**
     * @return Connector
     */
    public function setConnectorFromPlugin()
    {
        $options = $this->getAppPluginOptions();
        $this->setConnector($options['connector']);
    }

    /**
     * This is a copy of rename function only to be used when uploading a file
     * @inheritdoc
     **/
    public function customRename($hash, $name)
    {
        if (!$this->nameAccepted($name)) {
            return $this->setError(ElFinder::ERROR_INVALID_NAME, $name);
        }

        if (!($file = $this->file($hash))) {
            return $this->setError(ElFinder::ERROR_FILE_NOT_FOUND);
        }

        if ($name == $file['name']) {
            return $file;
        }

        if (!empty($file['locked'])) {
            return $this->setError(ElFinder::ERROR_LOCKED, $file['name']);
        }

        $path = $this->decode($hash);
        $dir = $this->_dirname($path);
        $stat = $this->stat($this->_joinPath($dir, $name));

        if ($stat) {
            return $this->setError(ElFinder::ERROR_EXISTS, $name);
        }

        if (!$this->allowCreate($dir, $name, ($file['mime'] === 'directory'))) {
            return $this->setError(ElFinder::ERROR_PERM_DENIED);
        }

        $this->rmTmb($file); // remove old name tmbs, we cannot do this after dir move

        return $this->stat($path);

    }

    /**
     * Return parent directory path (with convert encording)
     *
     * @param  string $path file path
     * @return string
     * @author Naoki Sawada
     **/
    protected function dirnameCE($path)
    {
        return (!$this->encoding) ? $this->_dirname($path) : $this->convEncOut(
            $this->_dirname($this->convEncIn($path))
        );
    }

    protected function joinPathCE($dir, $name)
    {
        return (!$this->encoding) ? $this->_joinPath(
            $dir,
            $name
        ) : $this->convEncOut(
            $this->_joinPath($this->convEncIn($dir), $this->convEncIn($name))
        );
    }

    /**
     * Converts character encoding from UTF-8 to server's one
     *
     * @param  mixed $var target string or array var
     * @param  bool $restoreLocale do retore global locale, default is false
     * @param  string $unknown replaces character for unknown
     * @return mixed
     * @author Naoki Sawada
     */
    public function convEncIn($var = null, $restoreLocale = false, $unknown = '_')
    {
        return (!$this->encoding) ? $var : $this->convEnc(
            $var,
            'UTF-8',
            $this->encoding,
            $this->options['locale'],
            $restoreLocale,
            $unknown
        );
    }

    /**
     * Converts character encoding from server's one to UTF-8
     *
     * @param  mixed $var target string or array var
     * @param  bool $restoreLocale do retore global locale, default is true
     * @param  string $unknown replaces character for unknown
     * @return mixed
     * @author Naoki Sawada
     */
    public function convEncOut($var = null, $restoreLocale = true, $unknown = '_')
    {
        return (!$this->encoding) ? $var : $this->convEnc(
            $var,
            $this->encoding,
            'UTF-8',
            $this->options['locale'],
            $restoreLocale,
            $unknown
        );
    }

    /**
     * Converts character encoding (base function)
     *
     * @param  mixed $var target string or array var
     * @param  string $from from character encoding
     * @param  string $to to character encoding
     * @param  string $locale local locale
     * @param  string $unknown replaces character for unknown
     * @return mixed
     */
    protected function convEnc(
        $var,
        $from,
        $to,
        $locale,
        $restoreLocale,
        $unknown = '_'
    ) {
        if (strtoupper($from) !== strtoupper($to)) {
            if ($locale) {
                @setlocale(LC_ALL, $locale);
            }
            if (is_array($var)) {
                $_ret = array();
                foreach ($var as $_k => $_v) {
                    $_ret[$_k] = $this->convEnc(
                        $_v,
                        $from,
                        $to,
                        '',
                        false,
                        $unknown = '_'
                    );
                }
                $var = $_ret;
            } else {
                $_var = false;
                if (is_string($var)) {
                    $_var = $var;
                    if (false !== ($_var = @iconv(
                            $from,
                            $to.'//TRANSLIT',
                            $_var
                        ))
                    ) {
                        $_var = str_replace('?', $unknown, $_var);
                    }
                }
                if ($_var !== false) {
                    $var = $_var;
                }
            }
            if ($restoreLocale) {
                setlocale(LC_ALL, elFinder::$locale);
            }
        }

        return $var;
    }

}
