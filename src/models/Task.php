<?php

/**
 * Task Class
 *
 * @version 1.0.1
 */
class Task extends \Core\Model
{
    const STATUS_NEW        = 'new';
    const STATUS_REJECTED   = 'rejected';
    const STATUS_FINISHED   = 'finished';
    
    /**
     * Db table name
     * 
     * @var string
     * 
     */
    protected $_name = 'tasks';
    
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
    
    public static function listStatuses()
    {
        return [
            self::STATUS_NEW,
            self::STATUS_REJECTED,
            self::STATUS_FINISHED
        ];
    }
    
    /**
     * Get all the tasks as an associative array
     *
     * @return array
     */
    public function fetchEntries($page, $rowsPerPage, $sortField, $sortDirection)
    {
        $page = ($page) ? $page-1 : 0;
        if (!$rowsPerPage) {
            $configuration = \Core\Registry::get('configuration');
            $rowsPerPage = $configuration['tables']['rowsperpage'];	
        }
        
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->_name}";
        
        $query .= " ORDER BY {$sortField} {$sortDirection}";
        
        $offset = $page * $rowsPerPage;
        $query .= " LIMIT {$offset}, {$rowsPerPage}";

        $params = [];
        
        return $this->fetchAll($query, $params);
    }
    
    /**
     * Get task by id
     *
     * @return array
     */
    public function fetchEntryById($id)
    {
        $query = "SELECT * FROM {$this->_name} WHERE id = :id";
        
        $params = [':id' => $id];
        
        return $this->fetchRow($query, $params);
    }
    
    /**
     * Save new task
     *
     * @return array
     */
    public function save($userName, $email, $body)
    {
        $query = "INSERT INTO {$this->_name} (user_name, email, body, status) VALUES (:userName, :email, :body, :status)";

        $params = [
            ':userName'     => $userName,
            ':email'        => $email,
            ':body'         => $body,
            ':status'       => self::STATUS_NEW
        ];
        
        return $this->execute($query, $params);
    }
    
    /**
     * Update task
     *
     * @return array
     */
    public function update($id, $data)
    {
        $query = "UPDATE {$this->_name} SET status = :status, body = :body WHERE id = :id";

        $params = [
            ':status'   => $data['status'],
            ':body'     => $data['body'],
            ':id'       => $id
        ];
        
        return $this->execute($query, $params);
    }
}