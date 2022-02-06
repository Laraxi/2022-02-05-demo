<?php

$capsule = new \Illuminate\Database\Capsule\Manager;
// 创建链接
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '192.168.56.200',
    'database' => 'caiji',
    'username' => 'root',
    'password' => '123456',
    'charset' => 'utf8mb4',
    'port' => 3306,
    'collation' => 'utf8mb4_general_ci',
    'prefix' => '',
]);
// 设置全局静态可访问DB
$capsule->setAsGlobal();
//// 启动Eloquent （如果只使用查询构造器，这个可以注释）
//$capsule->bootEloquent();

