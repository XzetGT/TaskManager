<?php

define ('DS', DIRECTORY_SEPARATOR);
define ('APPLICATION_PATH', realpath(dirname(__FILE__) . DS));
define ('SCRIPTS_PATH', realpath(APPLICATION_PATH . DS . 'src' . DS . 'views' . DS . 'scripts' . DS));

require APPLICATION_PATH . '/src/Bootstrap.php';

$application = new Bootstrap;
$application->init()
            ->run();