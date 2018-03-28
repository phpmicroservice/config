<?php

namespace core;

/**
 * 链接对象
 * Class Counnect
 * @property \swoole_server $swoole_server
 * @package core
 */
class Counnect
{
    private $swoole_server;
    private $request;
    private $fd;
    private $reactor_id;

    public function __construct(\swoole_server $server, int $fd, int $reactor_id, string $data)
    {
        echo "创建一个链接对象 \n";
        $this->swoole_server = $server;
        $this->fd = $fd;
        $this->reactor_id = $reactor_id;
        $this->request = json_decode($data);
    }

    public function getData()
    {
        return $this->request->d;
    }

    public function send($data)
    {
        $this->swoole_server->send($this->fd,serialize($data).PHP_EOL);
    }


    public function getRouter()
    {
        return $this->request->r;
    }

    public function __destruct()
    {
        echo "销毁一个链接对象 \n";
        // TODO: Implement __destruct() method.
    }
}