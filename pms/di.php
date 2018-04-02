<?php

/**
 * Services are globally registered in this file
 * �����ȫ��ע�ᶼ����,����ע��
 */

use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Events\Manager;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;


//ע���Զ�����
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(
    [
        'app' => ROOT_DIR . '/./app/',
        'core' => ROOT_DIR . '/./core/',
        'tool' => ROOT_DIR . '/./tool/',
    ]
);
$loader->register();


/**
 * The FactoryDefault Dependency Injector automatically registers the right
 * services to provide a full stack framework.
 */
$di = new Phalcon\DI\FactoryDefault();

$di->setShared('dConfig', function () {
    #Read configuration
    $config = new Phalcon\Config(require ROOT_DIR . '/config/config.php');
    return $config;
});
$di->setShared('config', function () {
    #Read configuration
    $config = new Phalcon\Config([]);
    return $config;
});


/**
 * ���ػ���
 */
$di->setShared('cache', function () {
    // Create an Output frontend. Cache the files for 2 days
    $frontCache = new \Phalcon\Cache\Frontend\Data(
        [
            "lifetime" => 172800,
        ]
    );

    $cache = new \Phalcon\Cache\Backend\File(
        $frontCache, [
            "cacheDir" => CACHE_DIR,
        ]
    );
    return $cache;
});

/**
 * ȫ�ֻ���
 */
$di->setShared('gCache', function () use ($di) {
    // Create an Output frontend. Cache the files for 2 days
    $frontCache = new \Phalcon\Cache\Frontend\Data(
        [
            "lifetime" => 172800,
        ]
    );

    $cache = new \Phalcon\Cache\Backend\Redis(
        $frontCache, [
            [
                "host" => $di['config']->cache->host,
                "port" => $di['config']->cache->port,
                "auth" => $di['config']->cache->auth,
                "persistent" => $di['config']->cache->persistent,
                'prefix' => $di['config']->cache->prefix,
                "index" => $di['config']->cache->index,
            ]
        ]
    );
    return $cache;
});


//ע�������,����˼����Զ�����˷���
$di->setShared('filter', function () {
    $filter = new \Phalcon\Filter();
//    $filter->add('json', new \core\Filter\JsonFilter());
    return $filter;
});
//�¼�������
$di->setShared('eventsManager', function () {
    $eventsManager = new \Phalcon\Events\Manager();
    return $eventsManager;
});


//ע�������,����˼����Զ�����˷���
$di->setShared('filter', function () {
    $filter = new \Phalcon\Filter();
//    $filter->add('json', new \core\Filter\JsonFilter());
    return $filter;
});


$di->set(
    "modelsManager", function () {
    return new \Phalcon\Mvc\Model\Manager();
});


$di->setShared('logger', function () {
    $logger = new \pms\Logger\Adapter\MysqlLog('log');
    return $logger;
});


/**
 * Database connection is created based in the parameters defined in the
 * configuration file
 */
$di["db"] = function () use ($di) {
    var_dump($di['config']->database);
    return new DbAdapter(
        [
            "host" => $di['config']->database->app_mysql_host,
            "port" => $di['config']->database->app_mysql_port,
            "username" => $di['config']->database->app_mysql_username,
            "password" => $di['config']->database->app_mysql_password,
            "dbname" => $di['config']->database->app_mysql_dbname,
            "options" => [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                \PDO::ATTR_CASE => \PDO::CASE_LOWER,
            ],
        ]
    );
};





