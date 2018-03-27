<?php
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

# 进行服务注册,依赖注入
include './core/services.php';

$server = new swoole_server('0.0.0.0', 9502, SWOOLE_BASE, SWOOLE_SOCK_TCP);

# 设置运行参数
$server->set(array(
    'daemonize' => false,
));

# 主进程启动
$server->on('Start', function ($server) {
    # 配置自动加载
    var_dump(get_included_files()); //此数组中的文件表示进程启动前就加载了，所以无法reload
    include ROOT_DIR . "/filemonitor.php";
});
# Work进行 启动
$server->on('WorkerStart', function ($server, $worker_id){
    # 应用初始化
    include_once './core/services.php';
    $app=new \core\App();
    $app->init($server,$worker_id);
});

# 设置基本回调
$server->on('Connect', '\core\App::connect');
$server->on('Receive', '\core\App::receive');
$server->on('Close', '\core\App::close');

$server->start();
