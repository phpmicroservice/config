<?php
/**
 * Created by PhpStorm.
 * User: Dongasai
 * Date: 2018/3/29
 * Time: 17:41
 */

namespace core;

/**
 * work进程
 * Class Work
 * @package core
 */
class Work
{
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

    public function onBufferFull()
    {

    }

}