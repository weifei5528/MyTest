<?php
namespace app\index\service;

abstract class AbstructAuth
{
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
    protected function getCallBackUrl()
    {
        $type_class = explode('\\',get_class($this));
        array_filter($type_class);
        $type = strtolower(end($type_class));
        return url('User/callback',['type'=>$type]);
    }
    /**
     * 写入userid
     */
    abstract public function addUserId($userid,$where);
}

?>