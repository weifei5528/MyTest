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
        return self::where(['status' => 1,'module' => 'web'])->order('browse desc')->field('id,thumb,size,download,browse,create_time,from_web,tags')->paginate();
    }
    /**
     * 获取图片详情
     */
    public static function getImageInfo($id)
    {
        return self::where('id','=',$id)->find();
    }
    /**
     * 获取图片的某个字段
     */
    public static function getImageValue($id,$field)
    {
        return self::where(['id' => $id])->value($field);
    }
    /**
     * 获取最新的图片
     */
    public static function getNewImgs()
    {
        return self::where(['module' => 'web','status' => 1])->order('create_time desc')->paginate();
    }
}

?>