<?php

namespace core;

use Phalcon\Events\ManagerInterface;

/**
 * 服务启动
 * Class Server
 * @property \core\Work $work;
 * @property \core\Task $task;
 * @property \core\App $app;
 * @package core
 */
class Server extends Base implements \Phalcon\Events\EventsAwareInterface
{
    private $server;
    private $task;
    private $work;
    private $app;


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
        $this->logo=require 'logo.php';
        $this->server = new \swoole_server($ip, $port, $mode, $tcp);
        # 设置运行参数
        $this->server->set($option);
        $this->task = new  Task();
        $this->work = new Work();
        $this->app = new App();
        # 注册进程回调函数
        $this->workCall();
        # 注册链接回调函数
        $this->tcpCall();
    }

    public function on($handler)
    {
        $this->eventsManager->attach('pms_server', $handler);
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
     * 启动服务
     */
    public function start()
    {
        $this->server->start();
    }

    /**
     * 处理连接回调
     */
    private function tcpCall()
    {
        # 设置连接回调
        $this->server->on('Connect', [$this->app, 'onConnect']);
        $this->server->on('Receive', [$this->app, 'onReceive']);
        $this->server->on('Packet', [$this->app, 'onPacket']);
        $this->server->on('Close', [$this->app, 'onClose']);
        $this->server->on('BufferEmpty', [$this->app, 'onBufferEmpty']);
        $this->server->on('BufferFull', [$this->app, 'onBufferFull']);
    }

    /**
     * 处理进程回调
     */
    private function workCall()
    {

        $this->server->on('Task', [$this->task, 'onTask']);
        $this->server->on('Finish', [$this->work, 'onFinish']);
        # 主进程启动
        $this->server->on('Start', [$this, 'onStart']);
        # 正常关闭
        $this->server->on('Shutdown', [$this, 'onShutdown']);
        # Work/Task进程 启动
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
        # work进程停止
        $this->server->on('WorkerStop', [$this->work, 'onWorkerStop']);
        # work 进程退出
        $this->server->on('WorkerExit', [$this->work, 'onWorkerExit']);
        # 进程出错 work/task
        $this->server->on('WorkerError', [$this, 'onWorkerError']);
        # 收到管道消息
        $this->server->on('PipeMessage', [$this, 'onPipeMessage']);
        # 管理进程开启
        $this->server->on('ManagerStart', [$this, 'onManagerStart']);
        # 管理进程结束
        $this->server->on('ManagerStop', [$this, 'onManagerStop']);
    }

    /**
     * 主进程开始事件
     * @param swoole_server $server
     */
    public function onStart(\Swoole\Server $server)
    {
        echo $this->logo;
        output('onStart');
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
        include_once ROOT_DIR . '/app/services.php';
        if ($server->taskworker) {
            #task
            $this->task->onWorkerStart($server, $worker_id);
        } else {
            $this->work->onWorkerStart($server, $worker_id);
        }
        if ($worker_id == 1) {
            # 热更新
            global $last_mtime;
            $last_mtime=time();
            \swoole_timer_tick(3000, [$this, 'codeUpdata'], ROOT_DIR . '/core/');
            \swoole_timer_tick(3000, [$this, 'codeUpdata'], ROOT_DIR . '/app/');
            \swoole_timer_tick(3000, [$this, 'codeUpdata'], ROOT_DIR . '/core/');

            # 应用初始化
            $this->app->init($server, $worker_id);
        }
    }


    /**
     * 重新加载
     * @param $dir
     */
    public function codeUpdata($dir)
    {
        global $last_mtime;
        // recursive traversal directory
        $dir_iterator = new \RecursiveDirectoryIterator($dir);
        $iterator = new \RecursiveIteratorIterator($dir_iterator);
        foreach ($iterator as $file) {
            if (substr($file, -1) != '.') {
                if (substr($file, -3) == 'php') {
                    // 只检查php文件
                    // 检查时间
                    $last_mtime = time();
                    $getMTime = $file->getMTime();
                    if ($last_mtime < $getMTime) {
                        echo $file . " ---|lasttime :$last_mtime and getMTime:$getMTime update and reload \n";
                        echo "关闭系统!自动重启!";
                        $this->server->shutdown();
                        break;
                    }
                }
            }
        }
    }


    /**
     * 此事件在Server正常结束时发生
     */
    public function onShutdown(\Swoole\Server $server)
    {

        output('onShutdown');
    }

    /**
     * 当工作进程收到由 sendMessage 发送的管道消息时会触发onPipeMessage事件。
     * @param \Swoole\Server $server
     * @param int $src_worker_id
     * @param mixed $message
     */
    public function onPipeMessage(\Swoole\Server $server, int $src_worker_id, mixed $message)
    {
        if ($server->taskworker) {
            $this->task->onPipeMessage($server, $src_worker_id, $message);
        } else {
            $this->work->onPipeMessage($server, $src_worker_id, $message);
        }
    }

    /**
     * 当worker/task_worker进程发生异常后会在Manager进程内回调此函数。
     * @param \Swoole\Server $server
     * @param int $worker_id 是异常进程的编号
     * @param int $worker_pid 异常进程的ID
     * @param int $exit_code 退出的状态码，范围是 1 ～255
     * @param int $signal 进程退出的信号
     */
    public function onWorkerError(\Swoole\Server $server, int $worker_id, int $worker_pid, int $exit_code, int $signal)
    {
        if ($server->taskworker) {
            $this->task->onWorkerError($server, $worker_id, $worker_pid, $exit_code, $signal);
        } else {
            $this->work->onWorkerError($server, $worker_id, $worker_pid, $exit_code, $signal);
        }
    }


    /**
     * 当管理进程启动时调用它
     * @param \Swoole\Server $server
     */
    public function onManagerStart(\Swoole\Server $server)
    {
        output('on ManagerStart');
    }

    /**
     * 当管理进程结束时调用它
     * @param \Swoole\Server $server
     */
    public function onManagerStop(\Swoole\Server $server)
    {
        output('on onManagerStop');
    }
}