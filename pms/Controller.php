<?php

namespace pms;

/**
 * 控制器
 * Class Controller
 * @property \pms\Counnect $connect
 * @package pms
 */
class Controller extends \Phalcon\Di\Injectable
{

    protected $connect;

    /**
     * 构造函数
     * Controller constructor.
     * @param Counnect $connect
     */
    public function __construct(Counnect $connect)
    {
        $this->connect = $connect;
        $this->onInitialize();
    }

    // 初始化事件
    protected function onInitialize()
    {
    }
}