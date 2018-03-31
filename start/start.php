<?php
//include './logo.php';
echo "开始主程序! \n";
include '../pms/index.php';
# 进行一些项目配置
define('APP_SECRET_KEY', get_env("APP_SECRET_KEY", '77ZqeAppoLvZ1Fsc'));

define('CONFIG_SECRET_KEY', get_env("CONFIG_SECRET_KEY", '9310FBCjxfycXLVMzbKOAptEpTVuiOch'));


//注册自动加载
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(
    [
        'apps' => ROOT_DIR . '/apps/',
        'core' => ROOT_DIR . '/core/',
        'tool' => ROOT_DIR . '/tool/',
    ]
);
$loader->register();

$server = new \pms\Server('0.0.0.0', 9502, SWOOLE_BASE, SWOOLE_SOCK_TCP, [
    'daemonize' => false,
    'worker_num' => 4,
    'task_worker_num' => 4,
    'reload_async' => true,
    'open_eof_split' => true, //打开EOF检测
    'package_eof' => PACKAGE_EOF, //设置EOF
]);
$server->start();
