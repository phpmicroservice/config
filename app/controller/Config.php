<?php

namespace app\controller;
/**
 * 配置处理
 */
class Config extends \pms\Controller
{

    /**
     * 配置获取
     * @param $data
     */
    public function acquire($data)
    {
        //output($data);
        $name = $data['n'];
        $c = $this->get_data($name);
        if (!$c) {
            output('配置不存在', 'error');
            # 配置不存在
            return $this->connect->send_error("配置不存在", $name, 404);
        }

        if (!$this->validator($c['secret'], $data)) {

            return $this->connect->send_error('秘钥验证不通过!!', $data, 403);
        }
        unset($c['secret']);
        //output('秘钥验证通过', 'success');
        return $this->connect->send_succee($c);
    }

    /**
     * 获取配置西你想
     * @param $name
     */
    private function get_data($name)
    {
        $file = ROOT_DIR . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . strtr($name, "_", DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'data.json';
        output([$file,]);
        if (is_file($file)) {
            $c = new \Phalcon\Config\Adapter\Json($file);
            return $c->toArray();
        } else {
            return false;
        }

    }


    /**
     * 验证key
     * @param $key 自己的key
     * @param $name 要获取的名字
     * @param $key2 传过来的key
     */
    protected function validator($data_secret, $data)
    {
        $key_new = md5(md5(APP_SECRET_KEY) . md5($data_secret) . md5($data['n']));
        //output([$key_new, $key2], 'hash_equals');
        if (\hash_equals($key_new, $data['k'])) {
            return true;
        }
        output(['秘钥验证不通过',$data_secret,$data,$key_new,APP_SECRET_KEY], 'error');
        return false;
    }


}