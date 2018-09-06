<?php
namespace app\common\service\picture;
use think\Log;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/2
 * Time: 22:22
 */




class ThumbImage
{
    /**
     * 创建缩略图
     * @param $src  本地图片的路径
     */
    public static function createThumb($src)
    {
        $thumb = DownLoadImage::DIR_PATH.date('Ymd')."/thumb/";
        $thum_path =  ROOT_PATH.$thumb;
        if(!file_exists($thum_path)) {
            mkdir($thum_path,0777,true);
        }
        $image = \think\Image::open(str_replace('\\','/',ROOT_PATH.$src));
        $thumb_size =  config('upload_image_thumb') ?config('upload_image_thumb'): "640,426";
        // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
        list($thumb_max_width, $thumb_max_height) = explode(',', $thumb_size);
        $thumb_name = md5(time().rand(1111,9999)).".".pathinfo($src,PATHINFO_EXTENSION);
        $image_info = [
            'path'      => $thumb.$thumb_name,
            'width'     => $image->width(),
            'height'    => $image->height(),
            'mime'      => $image->mime(),
            'ext'       => $image->type(),
            'filename'  =>pathinfo($src,PATHINFO_BASENAME ),
            'thumb_width'=>$thumb_max_width,
            'thumb_height'=>$thumb_max_height,
        ];
        // 获取要生成的缩略图最大宽度和高度
       
        $image->thumb($thumb_max_width, $thumb_max_height)->save($thum_path.$thumb_name);
        Log::write($image_info,'error');
        return $image_info;

    }
}