<?php

namespace Core;

/**
 * Controller Class
 *
 * @version 1.0.1
 */
abstract class Controller
{
    
    /**
     * Views extension
     * @var string
     */
    protected $_viewSuffix = 'phtml';
    
    private $_disableLayout;
    
    abstract function getAccess();

    public function renderLayout($body) 
    {
        $configuration = \Core\Registry::get('configuration');
        $layoutsPath = $configuration['paths']['layouts'];

        ob_start();
        require($layoutsPath . DS . "layout." . $this->_viewSuffix);
        return ob_get_clean();
    }
    
    public function render($viewName, array $params = [])
    {
        $configuration = \Core\Registry::get('configuration');
        $viewsPath = $configuration['paths']['views'];

        extract($params);
        ob_start();
        require($viewsPath . DS . $viewName . "." . $this->_viewSuffix);
        $body = ob_get_clean();
        ob_end_clean();

        if ($this->_disableLayout) {
            return $body;
        }
        
        return $this->renderLayout($body);
    }
    
    public function disableLayout()
    {
        $this->_disableLayout = true;
    }
    
    /**
     * Get Request Object
     *
     * @return Request
     */
    public function request()
    {
        return \Core\Registry::get('request');
    }

    /**
     * Redirects to URL
     * @param string $url
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
    
}