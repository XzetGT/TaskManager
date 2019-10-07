<?php

return [
    'database' => [
        'taskmanager' => [
            'adapter'   => 'PDO_MYSQL',
            'host'      => '127.0.0.1',
            'dbname'    => 'xzet',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8'
        ]
    ],
    'paths' => [
        'app'           => APPLICATION_PATH,
        'controllers'   => APPLICATION_PATH . DS . 'src' . DS . 'controllers',
        'layouts'       => APPLICATION_PATH . DS . 'src' . DS . 'views' . DS . 'layouts',
        'views'         => APPLICATION_PATH . DS . 'src' . DS . 'views' . DS . 'scripts'
    ],
    'tables' => [
        'rowsperpage'   => 3
    ],
    'image' => [
        'max_width'     => 320,
        'max_height'    => 240,
        'dir'           => DS . 'public' . DS . 'upload' . DS,
        'quality'       => 100
    ]
];
