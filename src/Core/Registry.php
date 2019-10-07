<?php

namespace Core;

/**
 * Registry Class
 *
 * @version 1.0.1
 */
class Registry extends \ArrayObject
{
    
    /**
     * Registry object provides storage for shared objects.
     * @var Registry
     */
    private static $_registry = null;

    /**
     * Retrieves the default registry instance.
     *
     * @return Registry
     */
    public static function getInstance()
    {
        if (self::$_registry === null) {
            self::$_registry = new self();
        }

        return self::$_registry;
    }

    /**
     * Constructs a parent ArrayObject with default
     * ARRAY_AS_PROPS to allow access as an object
     *
     * @param array $array data array
     * @param integer $flags ArrayObject flags
     */
    public function __construct($array = array(), $flags = parent::ARRAY_AS_PROPS)
    {
        parent::__construct($array, $flags);
    }
    
    public function offsetExists($index)
    {
        return array_key_exists($index, $this);
    }
    
    public static function get($index)
    {
        $instance = self::getInstance();

        if (!$instance->offsetExists($index)) {
            throw new Exception("No entry is registered for key '$index'");
        }

        return $instance->offsetGet($index);
    }
    
    public static function set($index, $value)
    {
        $instance = self::getInstance();
        $instance->offsetSet($index, $value);
    }

    /**
     * Returns TRUE if the $index is a named value in the registry,
     * or FALSE if $index was not found in the registry.
     *
     * @param  string $index
     * @return boolean
     */
    public static function isRegistered($index)
    {
        if (self::$_registry === null) {
            return false;
        }
        return self::$_registry->offsetExists($index);
    }

}