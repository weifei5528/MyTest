<?php
namespace app\common\validate;

use think\Validate;
class User extends Validate
{
    //定义验证规则
    protected $rule = [
        'username|用户名'      => 'require|unique:User',
        'password|密码'       => 'require',
        
    ];
    
    //定义验证提示
    protected $message = [
        'username.require' => '用户名不能为空',
        'username.unique'  => '用户名已存在',
        'password.require'  =>'密码不能为空'
    ];
    
}

?>