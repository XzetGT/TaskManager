<?php

/**
 * TaskController Class
 *
 * @version 1.0.1
 */
class TaskController extends \Core\Controller
{
    public function getAccess()
    {
        return ['editAction'];
    }
    
    public function viewAction()
    {
        $id = filter_var($this->request()->getParam("id", 0), FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)));
        
        if (!$id) {
            throw new Exception('Must be provided task id', 404);
        }
        
        $task = \Task::getInstance()->fetchEntryById($id);
        
        $params = array(
            'task' => $task
        );
        
        return $this->render('task/view', $params);
    }
    
    public function addAction()
    {
        $params = [];
        
        if ($this->request()->isPost()) {
            $userName = filter_var($this->request()->getParam('user_name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_var($this->request()->getParam('email'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $body = filter_var($this->request()->getParam('body'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $errorMessages = [];
            
            if (!$userName) {
                $errorMessages[] = 'Please enter user name';
            }
            if (!$email) {
                $errorMessages[] = 'Please enter email';
            } else  {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errorMessages[] = "Invalid email format"; 
                }
            }
            if (!$body) {
                $errorMessages[] = 'Please enter body';
            }
            
            $params['errorMessages'] = $errorMessages;
            $params['userName'] = $userName;
            $params['email'] = $email;
            $params['body'] = $body;
            
            if (!$errorMessages) {
                \Task::getInstance()->save($userName, $email, $body);

                $params['successMessage'] = 'Task has been successfully saved';
                
                $this->redirect('/');
            }
        }

        return $this->render('task/add', $params);
    }
    
    public function editAction()
    {
        $id = filter_var($this->request()->getParam("id", 0), FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)));
        
        if (!$id) {
            throw new Exception('Must be provided task id', 404);
        }
        
        $task = \Task::getInstance()->fetchEntryById($id);
        
        $listStatuses = \Task::listStatuses();
        
        $params = [];
        
        if ($this->request()->isPost()) {
            $task['status'] = filter_var($this->request()->getParam('status'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $task['body'] = filter_var($this->request()->getParam('body'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $errorMessages = [];
            
            if (!in_array($task['status'], $listStatuses)) {
                $errorMessages[] = 'Wrong status';
            }
            if (!$task['body']) {
                $errorMessages[] = 'Please enter body';
            }
            
            $params['errorMessages'] = $errorMessages;
            
            if (!$errorMessages) {
                $data = [
                    'status'    => $task['status'],
                    'body'      => $task['body'],
                ];
                
                \Task::getInstance()->update($id, $data);
                
                $params['successMessage'] = 'Task has been successfully updated';
            }
        }
        
        $params['task'] = $task;
        $params['listStatuses'] = $listStatuses;

        return $this->render('task/edit', $params);
    }
    
    public function previewAction()
    {
        $this->disableLayout();
        
        $params = [];

        try {
        
            $params['userName']     = filter_var($this->request()->getParam('user_name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $params['email']        = filter_var($this->request()->getParam('email'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $params['body']         = filter_var($this->request()->getParam('body'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        } catch (\Exception $e) {
            
            $params['errorMessage'] = $e->getMessage();
            
        }

        return $this->render('task/preview', $params);
    }
}