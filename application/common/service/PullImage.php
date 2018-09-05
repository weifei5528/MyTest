<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/2
 * Time: 22:33
 */

namespace app\common\service;


use app\common\service\picture\DownLoadImage;
use app\common\service\picture\FingerImage;
use app\common\service\picture\ThumbImage;
use think\File;

class PullImage
{
    public static function addImage($src,$image_info)
    {
        $src_path = DownLoadImage::downImage($src);
        if(empty($src_path))
            return false;
        $thumb_info = ThumbImage::createThumb($src_path);
        if(empty($thumb_info))
            return false;
        $finger = FingerImage::hashImage($src_path,$thumb_info);
    try{
        $file = new File(ROOT_PATH.$src_path);
        $file_info = [
            'name'   => $thumb_info['filename'],
            'mime'   => $thumb_info['mime'],
            'path'   => self::getSavePath($src_path),
            'ext'    => $thumb_info['ext'],
            'size'   => $file->getSize(),
            'md5'    => $file->hash('md5'),
            'sha1'   => $file->hash('sha1'),
            'thumb'  => self::getSavePath($thumb_info['path']),
            'module' => 'web',
            'width'  => $thumb_info['width'],
            'height' => $thumb_info['height'],
            'hashimage'=>$finger,
            'from_web'=>$image_info['from'],
            'remark'  =>isset($image_info['remark'])?$image_info['remark']:null,
            'tags'    =>$image_info['tags'],
            'create_time'=>time(),
            'update_time'=>time(),
            'unique_id' =>isset($image_info['id'])?$image_info['id']:0,
        ];
        db('admin_attachment')->insert($file_info);
    }catch (\Exception $e){
        return false;
    }
        

    }
    /**
     * 获取原图的绝对路径
     * @param unknown $name
     */

    public static function getSavePath($path)
    {

        return str_replace('\\', '/', str_replace( 'public/','', $path));
    }
}