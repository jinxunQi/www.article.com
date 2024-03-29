<?php
return [
    // 生成应用公共文件
    '__file__' => ['common.php', 'config.php', 'database.php'],

    // 定义demo模块的自动生成 （按照实际定义的文件名生成）
    'demo'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['controller', 'model', 'view'],
        'controller' => ['IndexControllerbak', 'TestController', 'UserTypeController'],
        'model'      => ['User', 'UserType'],
        'view'       => ['index/index'],
    ],
    // 其他更多的模块定义
];