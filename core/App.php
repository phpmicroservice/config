<?php

namespace core;
/**
 * App类,主管应用的产生调度
 */
class App extends \Phalcon\Di\Injectable
{


    /**
     * 应用初始化,进行配置初始话,配置依赖注入器
     */
    public function init(\swoole_server $server,$worker_id)
    {
       
    }

    public static function task()
    {

    }

    public static function finish(){

    }
    /**
     * 产生链接的回调函数
     */
    public function connect(\swoole_server $server, int $fd, int $reactorId)
    {
        echo "\n connect: " .$fd;


    }

    /**
     * 数据接收 回调函数
     */
    public function receive(\swoole_server $server, int $fd, int $reactor_id, string $data)
    {
        $data =rtrim($data,PACKAGE_EOF);
        echo "\n receive:" . $fd .'d: '.var_export(\swoole_serialize::unpack($data),true)."  \n";
        $router=new Router($server,$fd,$reactor_id,$data);
        $router->handle($server,$fd,$reactor_id,$data);
    }



    /**
     * 链接关闭 的回调函数
     */
    public function close(\swoole_server $server, int $fd, int $reactorId)
    {
        echo "\n close:"  .$fd;
    }
}