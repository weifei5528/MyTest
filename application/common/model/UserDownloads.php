<?php
namespace app\common\model;

use think\Model;

class UserDownloads extends Model
{
    protected $autoWriteTimestamp = true;
    /**
     * 一共下载的图片的张数
     */
    public static function getMyDownloadCount($where)
    {
        return self::where($where)->count();
    }
}

?>