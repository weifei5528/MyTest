<?php
namespace app\common\model;

use think\Model;
class Types extends Model
{
    protected $autoWriteTimestamp = true;
    
    /**
     * 获取所有的分类类型
     */
    public static function getList($size=10)
    {
        return self::where('name','not null')->order('sort asc')->limit($size)->column('name','id');
        
    }
}

?>