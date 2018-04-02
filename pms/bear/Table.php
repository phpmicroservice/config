<?php

namespace pms\bear;


abstract class Table
{
    protected $size = 65536;#2��13�η�
    protected $column = [
        ''
    ];
    protected $swoole_table;


    /**
     * ���ʵ����
     * Table constructor.
     */
    public function __construct()
    {
        $this->swoole_table = new \Swoole\Table($this->size);
        foreach ($this->column as $value) {
            $this->swoole_table->column($value['name'], $value['type'], $value['size']);
        }
        $this->swoole_table->create();

    }

    /**
     * ����
     * @param string $key
     * @param array $value
     */
    public function set(string $key, array $value)
    {
        return $this->swoole_table->set($key, $value);
    }

    /**
     * ԭ������������
     * @param string $key
     * @param string $column
     * @param int $incrby
     */
    public function incr(string $key, string $column, $incrby = 1)
    {
        return $this->swoole_table->incr($key, $column, $incrby);
    }

    /**
     * ԭ���Լ�������
     * @param string $key
     * @param string $column
     * @param int $incrby
     */
    public function decr(string $key, string $column, $incrby = 1)
    {
        return $this->swoole_table->decr($key, $column, $incrby);
    }

    /**
     * ��ȡһ������
     * @param string $key
     * @param string|null $field
     * @return array
     */
    public function get(string $key, string $field = null)
    {
        return $this->swoole_table->get($key, $field);
    }

    /**
     * �ж�һ�������Ƿ����
     * @param string $key
     * @return mixed
     */
    public function exist(string $key)
    {
        return $this->swoole_table->exist($key);
    }

    /**
     * ɾ��һ������
     * @param string $key
     * @return mixed
     */
    public function del(string $key)
    {
        return $this->swoole_table->del($key);
    }


    /**
     * ͳ�Ʊ������
     * @param int $mode
     * @return mixed
     */
    public function count($mode = 0)
    {
        return $this->swoole_table->count($mode);
    }

    /**
     * ���������
     * @return mixed
     */
    public function destroy()
    {
        return $this->swoole_table->destroy();
    }


}