<?php

/**
 * IndexController Class
 *
 * @version 1.0.1
 */
class IndexController extends \Core\Controller
{
    public function getAccess()
    {
        return [];
    }
    
    public function indexAction()
    {
        $page           = filter_var($this->request()->getParam("page", 1), FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)));
        $rowsPerPage    = filter_var($this->request()->getParam("rowsPerPage", 3), FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)));
        $sortField      = filter_var($this->request()->getParam("sortField", "id"), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sortDirection  = filter_var($this->request()->getParam("sortDirection", "DESC"), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if (!$page) { $page = 1; }
        if (!$rowsPerPage) { $rowsPerPage = 3; } else if ($rowsPerPage > 10) { $rowsPerPage = 10; }
        if (!in_array(strtolower($sortField), ["id", "user_name", "email", "status"])) { $sortField = "id"; }
        if (!in_array(strtoupper($sortDirection), ["ASC", "DESC"])) { $sortDirection = "DESC"; }
        
        $tasks = \Task::getInstance()->fetchEntries($page, $rowsPerPage, $sortField, $sortDirection);
        $totalRecords = \Task::getInstance()->fetchTotalRecords();
        
        $params = array(
            'tasks'         => $tasks,
            'totalPages'    => ceil($totalRecords / $rowsPerPage),
            'page'          => $page,
            'rowsPerPage'   => $rowsPerPage,
            'sortField'     => $sortField,
            'sortDirection' => $sortDirection
        );
        
        return $this->render('index/index', $params);
    }
}