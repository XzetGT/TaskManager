<?php

/**
 * User Class
 *
 * @version 1.0.1
 */
class User extends \Core\Model
{
    
    /**
     * Db table name
     * 
     * @var string
     * 
     */
    protected $_name = 'users';
    
    /**
     * Singleton instance
     * @var Task
     */
    protected static $_objInstance = NULL;
    
    /**
     * Get singleton instance
     * @return Task
     */
    public static function getInstance() {
        if (is_null(self::$_objInstance)) {
            self::$_objInstance = new self();
        }
        
        return self::$_objInstance;
    }
    
    /**
     * Get user by name
     *
     * @return array
     */
    public function fetchEntryByName($name)
    {
        $query = "SELECT * FROM {$this->_name} WHERE user_name = :name";
        
        $params = [':name' => $name];
        
        return $this->fetchRow($query, $params);
    }
}