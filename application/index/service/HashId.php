<?php
namespace app\index\service;

use Hashids\Hashids;
class HashId
{
    const STRPASS = 'abc12345defghijklmnopqrst67890uvwxyz';
    const LENGTH  = 10;
    protected static $link;
    public static function __callstatic($name, $arguments)
    {
        return call_user_func_array(array(static::sigle(), $name), $arguments);
    }
    public static function sigle()
    {
        static $client;
        if(is_null($client)) {
            $client = new static();
        }
    
        return $client;
    }
    public function __call($method, $params)
    {
        if(is_null(self::$link)) {
            self::$link = new Hashids('',self::LENGTH,self::STRPASS);
        }
        return call_user_func_array(array(self::$link, $method), $params);
    
    }
}

?>