<?php

namespace core;

use Phalcon\Events\ManagerInterface;

/**
 * App类,主管应用的产生调度
 */
class App extends Base implements \Phalcon\Events\EventsAwareInterface
{
    /**
     * 应用初始化,进行配置初始话,配置依赖注入器
     */
    public function init(\swoole_server $server, $worker_id)
    {
        # task 测试  5

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


    /**
     * 产生链接的回调函数
     */
    public function onConnect(\Swoole\Server $server, int $fd, int $reactorId)
    {
        output([$fd, $reactorId], 'connect');
    }

    /**
     * 数据接收 回调函数
     */
    public function onReceive(\Swoole\Server $server, int $fd, int $reactor_id, string $data)
    {
        $data = \swoole_serialize::unpack(rtrim($data, PACKAGE_EOF));
        output($data, 'receive');
        $router = new Router($server, $fd, $reactor_id, $data);
        $router->handle($server, $fd, $reactor_id, $data);
    }

    /**
     * upd 收到数据
     * @param \Swoole\Server $server
     * @param string $data
     * @param array $client_info
     */
    public function onPacket(\Swoole\Server $server, string $data, array $client_info)
    {

    }


    /**
     * 当缓存区达到最高水位时触发此事件。
     * @param \Swoole\Server $serv
     * @param int $fd
     */
    public function onBufferFull(\Swoole\Server $serv, int $fd)
    {

    }

    /**
     * 当缓存区低于最低水位线时触发此事件
     * @param \Swoole\Server $serv
     * @param int $fd
     */
    public function onBufferEmpty(\Swoole\Server $serv, int $fd)
    {

    }


    /**
     * 链接关闭 的回调函数
     * @param \Swoole\Server $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(\Swoole\Server $server, int $fd, int $reactorId)
    {
       output([$fd,$reactorId],'close');
    }
}