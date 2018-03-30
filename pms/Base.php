<?php

namespace pms;

use Phalcon\Events\ManagerInterface;

/**
 * Class Base
 * @property \Phalcon\Cache\BackendInterface $cache
 * @property \Phalcon\Config $config
 * @property \Swoole\Server $swoole_server
 * @package pms
 */
abstract class Base extends \Phalcon\Di\Injectable implements \Phalcon\Events\EventsAwareInterface
{
    protected $swoole_server;

    public function __construct(\Swoole\Server $server)
    {
//        $this->logo = require 'logo.php';
        $this->swoole_server = $server;
    }

    /**
     * 设置事件管理器
     * @param ManagerInterface $eventsManager
     */
    public function setEventsManager(ManagerInterface $eventsManager)
    {
        $this->eventsManager = $eventsManager;
    }

    /**
     * 设置事件管理器
     * @return  ManagerInterface $eventsManager
     */
    public function getEventsManager()
    {
        return $this->eventsManager;
    }

}