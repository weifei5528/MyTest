<?php
namespace app\common\model;

use think\Model;
class UserLoves extends Model
{
    protected $autoWriteTimestamp = true;
    public static function getWhereCount($where)
    {
        return self::where($where)->count();
    }
}

?>