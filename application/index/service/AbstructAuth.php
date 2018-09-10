<?php
namespace app\index\service;
abstract class AbstructAuth
{
    const HOST = "http://46814.3m.dkys.org";
    /**
     * 授权登录的页面
     */
    abstract public function getAuthUrl();
    
    /**
     * 登录授权回调的url
     */
    abstract public function authCallBack();
    /**
     * 获取回调页面的url
     */
    protected static function getCallBackUrl($type)
    {
//         $type_class = explode('\\',get_class(static::class));
//         array_filter($type_class);
//         $type = strtolower(end($type_class));
        return url('Login/callback',['type'=>$type],true,true);
    }
    /**
     * 写入userid
     */
    abstract public function addUserId($userid,$where=[]);
}

?>