<?php

/**
 * ErrorController Class
 *
 * @version 1.0.1
 */
class ErrorController extends \Core\Controller
{
    public function getAccess()
    {
        return [];
    }
    
    public function error403Action()
    {
        $params = [];
        
        return $this->render('error/error403', $params);
    }
    
    public function error404Action()
    {
        $params = [];
        
        return $this->render('error/error404', $params);
    }
    
    public function error500Action()
    {
        $params = [];
        
        return $this->render('error/error500', $params);
    }
}