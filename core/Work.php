<?php

namespace core;

use Phalcon\Events\ManagerInterface;

/**
 * work进程
 * Class Work
 * @package core
 */
class Work  extends Base  implements \Phalcon\Events\EventsAwareInterface
{

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
     * 有新的连接进入时，在worker进程中回调。
     * @param \Swoole\Server $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect(\Swoole\Server $server, int $fd, int $reactorId)
    {

    }

    /**
     * 接收到数据时回调此函数，发生在worker进程中。
     * @param \Swoole\Server $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     */
    public function onReceive(\Swoole\Server $server, int $fd, int $reactorId, string $data)
    {

    }


    /**
     * 接收到UDP数据包时回调此函数，发生在worker进程中。
     * @param \Swoole\Server $server
     * @param string $data
     * @param array $client_info
     */
    public function onPacket(\Swoole\Server $server, string $data, array $client_info)
    {

    }


    /**
     * TCP客户端连接关闭后，在worker进程中回调此函数
     * @param \Swoole\Server $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(\Swoole\Server $server, int $fd, int $reactorId)
    {

    }


    /**
     * task_worker中完成时,触发
     * @param swoole_server $serv
     * @param int $task_id
     * @param string $data
     */
    public function onFinish(\Swoole\Server $serv, int $task_id, string $data)
    {

    }

    /**
     * 当工作进程收到由 sendMessage 发送的管道消息时会触发onPipeMessage事件。
     * @param \Swoole\Server $server
     * @param int $src_worker_id
     * @param mixed $message
     */
    public function onPipeMessage(\Swoole\Server $server, int $src_worker_id, mixed $message)
    {
        output('onPipeMessage in work:');
    }


    /**
     * 此事件在Worker进程启动时发生。
     * 这里创建的对象可以在进程生命周期内使用
     * @param \Swoole\Server $server
     * @param int $worker_id
     */
    public function onWorkerStart(\Swoole\Server $server, int $worker_id)
    {
        output('onWorkerStart in task');
    }

    /**
     * 当工作进程停止
     * @param \Swoole\Server $server
     * @param int $worker_id
     */
    public function onWorkerStop(\Swoole\Server $server, int $worker_id)
    {

    }

    /**
     * 当worker。
     * @param \Swoole\Server $server
     * @param int $worker_id 是异常进程的编号
     * @param int $worker_pid 异常进程的ID
     * @param int $exit_code 退出的状态码，范围是 1 ～255
     * @param int $signal 进程退出的信号
     */
    public function onWorkerError(\Swoole\Server $server, int $worker_id, int $worker_pid, int $exit_code, int $signal)
    {
        output('worker - onWorkerError');
    }

    /**
     * 仅在开启reload_async特性后有效。
     * @param \Swoole\Server $server
     * @param int $worker_id
     */
    public function onWorkerExit(\Swoole\Server $server, int $worker_id)
    {

    }


}