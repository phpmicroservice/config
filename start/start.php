<?php
//include './logo.php';
echo "开始主程序! \n";
# 加载函数库
include './tool/function.php';

# 设置php常用配置
date_default_timezone_set("PRC");

# 设置 常量
define('ROOT_DIR', dirname(__DIR__));
define('STTART_DIR', __DIR__);
echo '项目目录为:' . ROOT_DIR . '启动文件目录为:' . STTART_DIR . " \n";
define("SERVICE_NAME", "CONFIG");# 设置服务名字
define('RUNTIME_DIR', './runtime/');# 运行目录
define('CACHE_DIR', './runtime/cache/');# 缓存目录
define('APP_DEBUG', boolval(get_env("APP_DEBUG", 1)));# debug 的开启
define('APP_SECRET_KEY', get_env("APP_SECRET_KEY", '77ZqeAppoLvZ1Fsc'));
define('PACKAGE_EOF', '_pms_');
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

$server = new \core\Server('0.0.0.0', 9502, SWOOLE_BASE, SWOOLE_SOCK_TCP, [
    'daemonize' => false,
    'worker_num' => 4,
    'task_worker_num' => 4,
    'reload_async' => true,
    #
    'open_eof_split' => true, //打开EOF检测
    'package_eof' => PACKAGE_EOF, //设置EOF
]);
$server->start();
