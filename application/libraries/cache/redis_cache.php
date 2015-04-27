<?php


/**
 * redis 缓存
 * Class RedisCache
 */
class redis_cache {
    private $_redis;

    public function __construct()
    {
        $this->_redis = new Redis();
        $this->_redis->connect('127.0.0.1',6379);
    }

    public function expire($key, $expire)
    {
        $this->_redis->expire($key, $expire);
    }

    public function exists($key)
    {
        return $this->_redis->exists($key);
    }


    /**
     * 设置值，并设置过期时间
     * @param $key
     * @param $value
     * @param $time_out
     */
    public function set($key, $value, $time_out)
    {
        $this->_redis->set($key, $value, $time_out);
    }


    /**
     * 返回值
     * @param $key
     * @return bool|string
     */
    public function get($key)
    {
        return $this->_redis->get($key);

    }


    public function hMset($key, $data)
    {
        return $this->_redis->hMset($key, $data);
    }

} 