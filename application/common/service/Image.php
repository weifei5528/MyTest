<?php
namespace app\common\service;

class Image
{
    protected static $link;
    public static function __callstatic($name, $arguments)
    {
        return call_user_func_array(array(static::sigle(), $name), $arguments);
    }
    /**
     *
     * @param $method
     * @param unknown $params
     * @return mixed
     */
    public function __call($method, $params)
    {
    
        $class = ucwords($params[0]);
    
        if(is_null(self::$link)) {
            $cl = 'Home\Service\adminrefund\builder\\'.$class;
            self::$link = new $cl();
        }
        unset($params[0]);
        return call_user_func_array(array(self::$link,$method), $params);
    
    }
    public static function sigle()
    {
        static $link;
        if(is_null($link)) {
            $link = new static();
        }
    
        return $link;
    }
}

?>