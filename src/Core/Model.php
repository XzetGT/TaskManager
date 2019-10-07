<?php

namespace Core;

/**
 * Model Class
 *
 * @version 1.0.1
 */
abstract class Model
{
    /**
     * Db Adapter
     * 
     * @var object
     */
    protected $_db;
    
    public function __construct()
    {
        $configuration = \Core\Registry::get('dbAdapters');

        $this->_db = $configuration['taskmanager'];
    }
    
    protected function fetchAll($query, array $params = [])
    {
        if (empty($params)) {
            $stmt = $this->_db->query($query);
        } else {
            $stmt = $this->_db->prepare($query);
            $stmt->execute($params);
        }
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    protected function fetchRow($query, array $params = [])
    {
        if (empty($params)) {
            $stmt = $this->_db->query($query);
        } else {
            $stmt = $this->_db->prepare($query);
            $stmt->execute($params);
        }
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    protected function fetchColumn($query)
    {
        $stmt = $this->_db->query($query);
        return $stmt->fetchColumn();
    }
    
    public function fetchTotalRecords()
    {
        return $this->fetchColumn("SELECT FOUND_ROWS()");
    }
    
    protected function execute($query, array $params = [])
    {
        $stmt = $this->_db->prepare($query);
        $stmt->execute($params);
    }
}