<?php

namespace core;

/**
 * 服务启动
 * Class Server
 * @package core
 */
class Server
{
    private $server;

    /**
     * 初始化
     * Server constructor.
     * @param $ip
     * @param $port
     * @param $mode
     * @param $tcp
     * @param array $option
     */
    public function __construct($ip, $port, $mode, $tcp, $option = [])
    {

        $this->server = new \swoole_server($ip, $port, $mode, $tcp);

        # 设置运行参数
        $this->server->set($option);
        # 注册进程回调函数
        $this->workCall();
        # 注册链接回调函数
        $this->tcpCall();
    }

    /**
     * 启动服务
     */
    public function start()
    {
        $this->server->start();
    }

    private function tcpCall()
    {
        # 设置基本回调
        $this->server->on('Connect', '\core\App::connect');
        $this->server->on('Receive', '\core\App::receive');
        $this->server->on('Close', '\core\App::close');
        $this->server->on('Task', '\core\App::task');
        $this->server->on('Finish', '\core\App::finish');
    }

    private function workCall()
    {

        # 主进程启动
        $this->server->on('Start', [$this, 'onStart']);
        # 正常关闭
        $this->server->on('Shutdown', [$this, 'onShutdown']);
        # Work/Task进程 启动
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->server->on('WorkerStop', [$this, 'onWorkerStop']);
        $this->server->on('WorkerExit', [$this, 'onWorkerExit']);

        $this->server->on('ManagerStart', function (\swoole_server $server, $worker_id) {
            output('on ManagerStart ManagerStart');
        });
    }

    /**
     * 主进程开始事件
     * @param swoole_server $server
     */
    public function onStart(\Swoole\Server $server)
    {
        output('on Start');
    }

    /**
     *
     * 此事件在Worker进程/Task进程启动时发生。
     * 这里创建的对象可以在进程生命周期内使用
     */
    public function onWorkerStart(\Swoole\Server $server, int $worker_id)
    {
        output('on WorkerStart');
        # 加载依赖注入器
        include_once ROOT_DIR . '/core/services.php';
        # 应用初始化
        $app = new \core\App();
        $app->init($server, $worker_id);
    }

    /**
     * 此事件在worker进程终止时发生
     * @param \Swoole\Server $server
     * @param int $worker_id
     */
    public function onWorkerStop(\Swoole\Server $server, int $worker_id)
    {

    }

    /**
     * 仅在开启reload_async特性后有效。
     * @param \Swoole\Server $server
     * @param int $worker_id
     */
    public function onWorkerExit(\Swoole\Server $server, int $worker_id)
    {

    }

    /**
     * 此事件在Server正常结束时发生
     */
    public function onShutdown(\Swoole\Server $server)
    {

    }

    public function WorkerStop()
    {

    }
}