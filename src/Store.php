<?php

namespace Greendrake\KeyValueStore;

class Store
{

    private $redis;

    private $prefix = '';

    public function __construct(\Redis $client = null)
    {
        if ($client === null) {
            $redis = new \Redis;
            $redis->connect('127.0.0.1', '6379');
        }
        $this->setClient($redis);
    }

    public function setClient(\Redis $redis)
    {
        $this->redis = $redis;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function addPrefix($prefix)
    {
        $this->prefix .= $prefix;
    }

    public function getRedis()
    {
        return $this->redis;
    }

    public function get($key = '')
    {
        $value = $this->redis->get($this->prefix . $key);
        if ($value === false) {
            return null;
        }
        return $value;
    }

    public function incr($key = '')
    {
        return $this->redis->incr($this->prefix . $key);
    }

    public function set($key = '', $value)
    {
        $this->redis->set($this->prefix . $key, $value);
    }

    public function delete($key)
    {
        $this->redis->delete($this->prefix . $key);
    }

    public function flush()
    {
        if (empty($this->prefix)) {
            $this->redis->flushDB();
            return;
        }
        $pattern = $this->prefix . '*';
        $it = null;
        while(false !== ($key = $this->redis->scan($it, $pattern))){
            $this->redis->delete($key);
        }
    }

}