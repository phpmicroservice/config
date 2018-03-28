<?php
namespace app\controller;

/**
 * Empty 不合法的请求而的处理
 */
class Fault extends \core\Controller
{

    /**
     * 不合法的控制器名字
     * @param $data
     */
    public function controller()
    {
        $this->connect->send('不合法的控制器!');
    }

    /**
     * 不合法的处理器名字
     */
    public function action()
    {
        $this->connect->send('不合法的方法!');
    }



}