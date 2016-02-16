<?php

use Chamilo\CoreBundle\Framework\Container;

/**
 * Chamilo session (i.e. the session that maintains the connection open after usr login)
 *
 * Usage:
 *
 *
 *      use ChamiloSession as Session;
 *
 *      Session::read('name');
 *
 * Or
 *
 *      Chamilo::session()->...
 *      session()->...
 *
 * @license see /license.txt
 * @author Laurent Opprecht <laurent@opprecht.info> for the Univesity of Geneva
 */
/**
 * @todo replace all $_SESSION calls with this class.
 * ChamiloSession class definition
 */
class ChamiloSession implements \ArrayAccess
{
    /**
     * @param string $variable
     * @param null $default
     * @return mixed|null
     */
    static function read($variable, $default = null)
    {
        $session = Container::getSession();
        $result = null;
        if (isset($session)) {
            $result = $session->get($variable);
        }

        // Check if the value exists in the $_SESSION array
        if (empty($result)) {
            return $default;
        } else {
            return $result;
        }
    }

    /**
     * @param string $variable
     * @param mixed $value
     */
    static function write($variable, $value)
    {
        //$_SESSION[$variable] = $value;
        $session = Container::getSession();
        // Writing the session in 2 instances because
        $_SESSION[$variable] = $value;
        $session->set($variable, $value);
    }

    /**
     * @param string $variable
     */
    static function erase($variable)
    {
        $variable = (string) $variable;
        $session = Container::getSession();
        $session->remove($variable);

        if (isset($GLOBALS[$variable])) {
            unset($GLOBALS[$variable]);
        }
        if (isset($_SESSION[$variable])) {
            unset($_SESSION[$variable]);
        }
    }

    /**
     * Returns true if session has variable set up, false otherwise.
     *
     * @param string $variable
     *
     * @return bool
     */
    static function has($variable)
    {
        return isset($_SESSION[$variable]);
    }

    /**
     * Clear
     */
    static function clear()
    {
        $session = Container::getSession();
        $session->clear();
    }

    /**
     * Destroy
     */
    static function destroy()
    {
        $session = Container::getSession();
        $session->invalidate();
    }

    /*
     * ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($_SESSION[$offset]);
    }

    /**
     * It it exists returns the value stored at the specified offset.
     * If offset does not exists returns null. Do not trigger a warning.
     *
     * @param string $offset
     * @return any
     */
    public function offsetGet($offset)
    {
        return self::read($offset);
    }

    public function offsetSet($offset, $value)
    {
        self::write($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($_SESSION[$offset]);
    }

    /**
     * @param string $name
     */
    public function __unset($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return self::has($name);
    }

    /**
     * It it exists returns the value stored at the specified offset.
     * If offset does not exists returns null. Do not trigger a warning.
     *
     * @param string $name
     *
     * @return mixed
     *
     */
    function __get($name)
    {
        return self::read($name);
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     */
    function __set($name, $value)
    {
        self::write($name, $value);
    }
}
