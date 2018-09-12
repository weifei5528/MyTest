<?php
namespace app\index\service;
use think\Db;
use app\common\model\AdminAttachment as AttModel;

use app\common\model\UserDownloads as UDLModel;
use think\Image;
class CommonFunc
{
    /**
     * 添加图片的时候更新文件夹张数
     * @param int $dirid 文件夹的id
     * @param bool $inc true 添加 false 减少
     */
    public static function updateCount($dirid,$inc=true)
    {
        if($inc) {
            return Db::name('UserDirs')->where(['id' => $dirid ])->setInc('count');
        } else {
            return Db::name('UserDirs')->where(['id' => $dirid ])->setDec('count');
        }
        
    }
    /**
     * 设置文件夹的封面
     */
    public static function setDirCover($dirid, $imgid)
    {
        return Db::name('UserDirs')->where(['id' => $dirid])->update(['cover' => $imgid, 'update_time' => time()]);
    }
    /**
     * 添加文件到文件夹
     */
    public static function addImageToDir($dirid, $imgid)
    {
        return Db::name('UserDirImages')->insertGetId(['dir_id' => $dirid, 'attr_id' => $imgid, 'create_time' => time(), 'update_time' => time()]);
    }
   

}

?>