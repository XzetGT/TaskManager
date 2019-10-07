<?php

namespace Core;

/**
 * Request Class
 *
 * @version 1.0.1
 */
class Request
{

    /**
     * Request parameters
     * @var array
     */
    protected $_params = [];

    public function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->_params = $_POST;
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->_params = $_GET;
        }
    }

    /**
     * Set an action parameter
     *
     * A $value of null will unset the $key if it exists
     *
     * @param string $key
     * @param mixed $value
     * @return Request
     */
    public function setParam($key, $value)
    {
        $key = (string) $key;

        if ((null === $value) && isset($this->_params[$key])) {
            unset($this->_params[$key]);
        } elseif (null !== $value) {
            $this->_params[$key] = $value;
        }

        return $this;
    }

    /**
     * Get an action parameter
     *
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return mixed
     */
    public function getParam($key, $default = null)
    {
        $key = (string) $key;
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }

        return $default;
    }

    /**
    * Get all action parameters
    *
    * @return array
    */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Unset all user parameters
     *
     * @return Request
     */
    public function clearParams()
    {
        $this->_params = [];
        return $this;
    }
    
    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
}