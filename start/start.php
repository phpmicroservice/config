<?php
#进行必要配置
define("SERVICE_NAME", "CONFIG");# 设置服务名字
define('ROOT_DIR', dirname(__DIR__));
#引入扩展
require ROOT_DIR . '/vendor/autoload.php';
# 进行一些项目配置
define('APP_SECRET_KEY', get_env("APP_SECRET_KEY"));
define('REGISTER_SECRET_KEY', get_env("REGISTER_SECRET_KEY"));
define('REGISTER_ADDRESS', get_env("REGISTER_ADDRESS"));
define('REGISTER_PORT', get_env("REGISTER_PORT"));

//注册自动加载
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(
    [
        'apps' => ROOT_DIR . '/apps/',
        'tool' => ROOT_DIR . '/tool/',
    ]
);
$loader->register();

$server = new \pms\Server('0.0.0.0', 9502, SWOOLE_BASE, SWOOLE_SOCK_TCP, [
    'daemonize' => false,
    'worker_num' => 2,
    'task_worker_num' => 2,
    'reload_async' => false,
    'open_eof_split' => true, //打开EOF检测
    'package_eof' => PACKAGE_EOF, //设置EOF
]);
$server->onBind('onWorkerStart',new \app\guidance());
$server->start();
