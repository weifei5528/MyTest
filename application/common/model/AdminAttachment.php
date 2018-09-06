<?php
namespace app\common\model;

use think\Model;
class AdminAttachment extends Model
{
    protected $autoWriteTimestamp = true;
    /**
     * 图片 按浏览量排序
     */
    public static function getImages()
    {
        return self::where(['status' => 1])->order('browse desc')->field('id,thumb,size,download,browse,create_time,from_web,tags')->paginate();
    }
}

?>