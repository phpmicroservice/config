<?php

namespace core;

/**
 * App类,主管应用的产生调度
 * @property \Phalcon\Logger\Adapter\File $logger
 */
class App extends \Phalcon\Di\Injectable
{

    /**
     * 应用初始化,进行配置初始话,配置依赖注入器
     */
    public function init(\swoole_server $server, int $worker_id)
    {
        # 配置初始化
        $this->logger->info('demo_log',['dadq','sss']);
    }

    /**
     * 产生链接的回调函数
     */
    public function connect(\swoole_server $server, int $fd, int $reactorId)
    {
        echo "connect:" .$fd;

    }

    /**
     * 数据接收 回调函数
     */
    public function receive(\swoole_server $server, int $fd, int $reactor_id, string $data)
    {
        echo "receive:" . $fd;
    }

    /**
     * 链接关闭 的回调函数
     */
    public function close(\swoole_server $server, int $fd, int $reactorId)
    {
        echo "close:" .$server ;
    }
}