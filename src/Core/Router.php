<?php

namespace Core;

/**
 * Router Class
 *
 * @version 1.0.1
 */
class Router
{

    public $defaultControllerName = 'Index';
    
    public $defaultActionName = "index";

    /**
     * Parameter for controllers path
     * @var string
     */
    private $path;
    
    public function setControllersPath($path) 
    {
        if (is_dir($path) == false) {
            throw new \Exception("Invalid controllers path: '{$path}'");
        }

        $this->path = $path;
    }

    /**
     * Parse REQUEST_URI
     *
     * @return array
     */
    protected function _parseUrl()
    {
        $url = $_SERVER['REQUEST_URI'];
        $url = $this->removeQueryString($url);
        
        $arrParts = explode('/', filter_var(trim($url, '/'), FILTER_SANITIZE_URL));

        $controllerName = isset($arrParts[0]) ? $arrParts[0] : null;
        $actionName     = isset($arrParts[1]) ? $arrParts[1] : null;
        
        return [$controllerName, $actionName];
    }
    
    public function launch()
    {
        list($controllerName, $actionName) = $this->_parseUrl();
        echo $this->launchAction($controllerName, $actionName);
    }

    /**
     * Dispatch the route, creating the controller object and running the
     * action method
     *
     * @return void
     */
    public function launchAction($controllerName, $actionName)
    {
        $controllerName = ($controllerName) ? $this->convertToStudlyCaps($controllerName) : $this->defaultControllerName;
        $controllerName .= 'Controller';
        
        $controllerFile = $this->path . DS . $controllerName . '.php';
        
        if (!file_exists($controllerFile)) {
            throw new \Exception("Controller file {$controllerFile} not found", 404);
        }
        
        require_once($controllerFile);
        
        $controllerClass = "\\" . $controllerName;
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller class {$controllerClass} not found", 404);
        }
        
        $controller = new $controllerClass;

        $actionName = ($actionName) ? $this->convertToCamelCase($actionName) : $this->defaultActionName;
        $actionName .= 'Action';

        if (!method_exists($controller, $actionName)) {
            throw new \Exception("Method {$actionName} not found in controller {$controllerName}", 404);
        }
        
        if (!Auth::isLogged() && in_array($actionName, $controller->getAccess())) {
            throw new \Exception("Access denied!", 403);
        }
        
        return $controller->$actionName();
    }

    /**
     * Remove the query string variables from the URL (if any)
     *
     * @param string $url The full URL
     *
     * @return string The URL without query string
     */
    protected function removeQueryString($url)
    {
        if (($pos = strpos($url, '?')) !== false) {
            $url = substr($url, 0, $pos);
        }

        return $url;
    }

    /**
     * Convert the string with hyphens to StudlyCaps,
     * e.g. post-authors => PostAuthors
     *
     * @param string $string The string to convert
     *
     * @return string
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert the string with hyphens to camelCase,
     * e.g. add-new => addNew
     *
     * @param string $string The string to convert
     *
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }
    
}