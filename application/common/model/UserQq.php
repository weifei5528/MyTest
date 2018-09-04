<?php
namespace app\common\model;

use think\Model;
class UserQq extends Model
{
    protected $autoWriteTimestamp = true;
    
    public static function addUserQq($data)
    {
        return  self::create($data);
    }
    /**
     * 判断用户是否存在
     */
    public static function getUser($openid,$type='qq')
    {
        return self::where(['openid' => $openid,'type' => $type])->find();
    }
    
    /**
     * 更新
     */
    public static function updateUser($where, $data)
    {
        return self::where($where)->update($data);
    }
}

?>