<?php

namespace pms;

/**
 * 路由器
 * Class Router
 * @property \pms\Counnect $connect
 * @package pms
 */
class Router extends Base
{
    private $connect;

    /**
     * 构造函数
     * Router constructor.
     * @param \swoole_server $server
     * @param int $fd
     * @param int $reactor_id
     * @param array $data
     */
    public function __construct(\swoole_server $server, int $fd, int $reactor_id, array $data)
    {
        $this->eventsManager->fire('router:construct', $this, [$fd, $reactor_id, $data]);
        $this->connect = new bear\Counnect($server, $fd, $reactor_id, $data);
    }


    /**
     * 处理
     * @param \swoole_server $server
     * @param int $fd
     * @param int $reactor_id
     * @param string $data
     */
    public function handle(\swoole_server $server, int $fd, int $reactor_id, array $data)
    {
        $router_string = $this->connect->getRouter();
        $arr = $this->analysis($router_string);
        $this->handleCall($arr[0], $arr[1]);
    }

    /**
     * 解析路由
     * @param $router_string
     */
    public function analysis($router_string): array
    {
        $arr = explode('_', $router_string);
        $this->eventsManager->fire('router:analysis', $this, $arr);
        return $arr;
    }


    /**
     * 处理
     * @param $controller_name 控制器名字
     * @param $action_name 动作名字
     */
    private function handleCall($controller_name, $action_name)
    {

        if ($this->eventsManager->fire('router:analysis', $this, [$controller_name, $action_name],true) === false) {
            return 1;
        }
        $class_name = '\\app\\controller\\' . ucfirst($controller_name);
        output($class_name, 'class_name');
        $faultcontroller = 'app\controller\Fault';
        if (class_exists($class_name)) {
            $controller = new $class_name($this->connect);
            if (method_exists($controller, $action_name)) {
                $controller->$action_name($this->connect->getData());
            } else {
                $controller->action($this->connect->getData());
            }
        } else {
            # 不合法的控制器
            $controller = new $faultcontroller($this->connect);
            $controller->controller($this->connect->getData());
        }
    }


    /**
     * 销毁
     */
    public function __destruct()
    {
//        echo "销毁一个路由";
        // TODO: Implement __destruct() method.
    }

}