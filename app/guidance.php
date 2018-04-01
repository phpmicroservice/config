<?php
# 引导文件,初始化文件
namespace app;

use Phalcon\Events\Event;

class guidance  extends \Phalcon\Di\Injectable
{

    /**
     * 启动事件
     * @param Event $event
     * @param \pms\Server $pms_server
     * @param \Swoole\Server $server
     */
    public function onWorkerStart(Event $event,\pms\Server $pms_server,\Swoole\Server $server)
    {
        output(19,'guidance');
        # 判断是否完成配置初始化
        swoole_timer_tick(5000, function ($timeid) {
            $config = \Phalcon\Di::getDefault()->get('config');
            if($config->database){
                output('准备好了','ready');
                \swoole_timer_clear($timeid);
                \Phalcon\Di::getDefault()->get('dConfig')->ready=true;
            }
        });
    }

}