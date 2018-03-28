<?php
namespace app\controller;
/**
 * 配置处理
 */
class Config extends \core\Controller
{

    /**
     * 配置获取
     * @param $data
     */
    public function acquire($data)
    {
        var_dump($data);
        $this->connect->send('wocao!');
    }



}