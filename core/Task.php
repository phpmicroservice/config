<?php

namespace core;

use Phalcon\Events\ManagerInterface;

/**
 * task进程事件
 * Class Task
 * @package core
 */
class Task  extends Base  implements \Phalcon\Events\EventsAwareInterface
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
     * 在task_worker进程内被调用
     * @param \Swoole\Server $serv
     * @param int $task_id
     * @param int $src_worker_id
     * @param mixed $data
     */
    public function onTask(\Swoole\Server $serv, int $task_id, int $src_worker_id, mixed $data)
    {

    }

    /**
     * 当工作进程收到由 sendMessage 发送的管道消息时会触发onPipeMessage事件。
     * @param \Swoole\Server $server
     * @param int $src_worker_id
     * @param mixed $message
     */
    public function onPipeMessage(\Swoole\Server $server, int $src_worker_id, mixed $message){
        output('onPipeMessage in task:');

    }

    /**
     * 此事件在Task进程启动时发生。
     *
     * @param \Swoole\Server $server
     * @param int $worker_id
     */
    public function onWorkerStart(\Swoole\Server $server, int $worker_id)
    {
        output('onWorkerStart in task');
    }

    /**
     * task进程发生异常后会在Manager进程内回调此函数。
     * @param \Swoole\Server $server
     * @param int $worker_id 是异常进程的编号
     * @param int $worker_pid 异常进程的ID
     * @param int $exit_code 退出的状态码，范围是 1 ～255
     * @param int $signal 进程退出的信号
     */
    public function onWorkerError(\Swoole\Server $server, int $worker_id, int $worker_pid, int $exit_code, int $signal)
    {
        output('task - onWorkerError');
    }
}