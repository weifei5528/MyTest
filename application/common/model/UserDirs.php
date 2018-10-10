<?php
namespace app\common\model;

use think\Model;

class UserDirs extends Model
{
    protected $autoWriteTimestamp = true;
    /**
     * 获取搜索的文字
     */
    public static function getSearchName($name)
    {
        $ids = self::where(['name' =>['like',"%$name%"],'auth' => 0])->column('id');
        if(empty($ids))
            return false;
        $att_ids = UserDirImages::where(['dir' => ['in',$ids]])->column('att_id');
        if(empty($att_ids))
            return false;
        return array_unique($att_ids);
    }
    
    /**
     * 获取指定文件夹
     */
    public static function getDirsList($where=[],$order=[])
    {
        $model = self::where(['auth' =>'0']);
        if($where) {
            $model->where($where);
        }
        if($order) {
            $model->order($order);
        }
        return $model->paginate();
    }
}

?>