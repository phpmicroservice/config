<?php
//include './logo.php';
echo "开始主程序! \n";
# 加载函数库
include './tool/function.php';

# 设置 常亮
define('ROOT_DIR', __DIR__);
define("SERVICE_NAME", "CONFIG");# 设置服务名字
define('RUNTIME_DIR',  './runtime/');# 运行目录
define('CACHE_DIR',  './runtime/cache/');# 缓存目录
define('APP_DEBUG', boolval(get_env("APP_DEBUG", 1)));# debug 的开启
define('APP_SECRET_KEY', get_env("APP_SECRET_KEY", '77ZqeAppoLvZ1Fsc'));

//注册自动加载
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(
    [
        'apps'    => ROOT_DIR . '/./apps/',
        'core'    => ROOT_DIR . '/./core/',
        'tool'    => ROOT_DIR . '/./tool/',
    ]
);
$loader->register();
$server =new \core\Server();
$server->start();
