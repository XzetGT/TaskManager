<?php

/**
 * Bootstrap Class
 *
 * @version 1.0.1
 */
class Bootstrap
{
    
    public function init() 
    {
        $this->_initSession();
        $this->_initFatalHandler();
        $this->_initExceptionHandler();
        $this->_initAutoload();
        $this->_initConfig();
        $this->_initDbAdapters();
        $this->_initRoutes();
        $this->_initRequest();
        
        return $this;
    }
    
    protected function _initSession()
    {
        session_start();
    }

    protected function _initFatalHandler()
    {
        if (function_exists('register_shutdown_function')) {
            register_shutdown_function('Bootstrap::registerShutdown');
        }
    }

    protected function _initExceptionHandler()
    {
        if (function_exists('set_exception_handler')) {
            set_exception_handler(['Bootstrap','handleException']);
        }
    }
    
    public function handleException(\Exception $e)
    {
        $arrErrorCodes = [
            403 => 'error403',
            404 => 'error404'
        ];
        
        $errorAction = array_key_exists($e->getCode(), $arrErrorCodes) 
                ? $arrErrorCodes[$e->getCode()]
                : 'error500';
        
        $router = Core\Registry::get('router');
        echo $router->launchAction('Error', $errorAction);
    }

    public static function registerShutdown() 
    {
        $error = error_get_last();

        if (
            $error !== NULL &&
            isset($error["type"]) &&
            isset($error["file"]) &&
            isset($error["line"]) &&
            isset($error["message"]) &&
            $error["type"] == E_ERROR
        ) {
            $errMessage = "Error type: E_ERROR\n";
            $errMessage .= 'Error file: "' . $error["file"] . '"' . ' line ' . $error["line"] . "\n";
            $errMessage .= 'Error message: ' . $error["message"] . "\n";
            
            error_log($errMessage, 3, APPLICATION_PATH . "/storage/logs/error.log");
        }
    }

    protected function _initAutoload()
    {
        spl_autoload_register(['static', 'loadClass']);
    }
    
    protected function _initConfig()
    {
        $config = include APPLICATION_PATH . DS . 'config' . DS . 'application.php';

        Core\Registry::set('configuration', $config);
    }

    protected function _initDbAdapters()
    {
        $configuration = Core\Registry::get('configuration');

        $dbAdapters = array();
        foreach ($configuration['database'] as $adapterName => $params) {

            $dbAdapters[$adapterName] = new \PDO("mysql:host={$params['host']};dbname={$params['dbname']}", $params['username'], $params['password']);
            
        }

        Core\Registry::set('dbAdapters', $dbAdapters);
    }

    protected function _initRoutes()
    {
        $configuration = Core\Registry::get('configuration');
        $controllersPath = $configuration['paths']['controllers'];
        
        $router = new Core\Router();
        $router->setControllersPath($controllersPath);
        
        Core\Registry::set('router', $router);
    }

    protected function _initRequest()
    {
        $request = new Core\Request();
        
        Core\Registry::set('request', $request);
    }
    
    public static function loadClass($className)
    {
        $className = str_replace('\\', DS, $className);
        $filePath = APPLICATION_PATH . DS . 'src' . DS;
        $modelPath = APPLICATION_PATH . DS . 'src' . DS . 'models' . DS;

        if (file_exists($filePath . $className . '.php')) {
            require_once($filePath . $className . '.php');
        } else if (file_exists($modelPath . $className . '.php')) {
            require_once($modelPath . $className . '.php');
        } else {
            throw new \Exception('Class ' . $className . ' not exists');
        }
    }
    
    public function run()
    {
        $router = Core\Registry::get('router');
        $router->launch();
    }
    
}