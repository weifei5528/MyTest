<?php
namespace app\common\model;

use think\Model;
class User extends Model
{
    protected $name = "users";
    protected $autoWriteTimestamp =true;
    /**
     * 获取用户的信息
     * @param int $userid
     * @return array  返回用户的信息
     */
    public static function getUserInfo($userid)
    {
        return self::where(['userid' => $userid])->find();
    }
    /**
     * 添加用户
     */
    public static function addUser($data)
    {
        return self::save($data);
    }
}

?>