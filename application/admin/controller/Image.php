<?php
namespace app\admin\controller;

use GuzzleHttp\Client;
use think\File;
class Image
{
    
    const DIR_PATH = "public/uploads/images/";
    /**取样倍率 1~10
     * @access public
     * @staticvar int
     * */
    public static $rate = 8;
    
    /**相似度允许值 0~64
     * @access public
     * @staticvar int
     * */
    public static $similarity = 80;
    
    /**图片类型对应的开启函数
     * @access private
     * @staticvar string
     * */
    private static $_createFunc = array(
        IMAGETYPE_GIF   =>'imageCreateFromGIF',
        IMAGETYPE_JPEG  =>'imageCreateFromJPEG',
        IMAGETYPE_PNG   =>'imageCreateFromPNG',
        IMAGETYPE_BMP   =>'imageCreateFromBMP',
        IMAGETYPE_WBMP  =>'imageCreateFromWBMP',
        IMAGETYPE_XBM   =>'imageCreateFromXBM',
    );
    
    public function index($src,$info=[])
    {
        $src_path=$this->downImage($src);
        if(false===$src_path)
            return false;
        $thumb_info = $this->createThumb($src_path);
        $thumb_info['from'] = $info['from'];
        $this->saveFile($src_path, $thumb_info);
    }
    private function saveFile($src_path, $thumb_info)
    {
        $file = new File(ROOT_PATH.$src_path);
        $file_info = [
            'uid'    => $thumb_info['from'],
            'name'   => $thumb_info['filename'],
            'mime'   => $thumb_info['mime'],
            'path'   => $src_path,
            'ext'    => $thumb_info['ext'],
            'size'   => $file->getSize(),
            'md5'    => $file->hash('md5'),
            'sha1'   => $file->hash('sha1'),
            'thumb'  => $thumb_info['path'],
            'module' => '',
            'width'  => $thumb_info['width'],
            'height' => $thumb_info['height'],
            'hashimage'=>self::hashImage(ROOT_PATH.$src_path,$thumb_info),
        ];
        dump($file_info);exit;
    }
    /**
     * 创建缩略图
     * @param $src  本地图片的路径
     */
    private function createThumb($src)
    {
        $thumb = self::DIR_PATH.date('Ymd')."/thumb/";
        $thum_path =  ROOT_PATH.$thumb;
        if(!file_exists($thum_path)) {
            mkdir($thum_path,0777,true);
        }
        $pathinfo = pathinfo($src);
        echo ROOT_PATH.$src;
        $image = \think\Image::open(ROOT_PATH.$src);
        echo "=====";exit;
        $thumb_name = md5($pathinfo['basename']).".".$pathinfo['extension'];
        $image_info = [
            'path'      => $thumb.$thumb_name,
            'width'     => $image->width(),
            'height'    => $image->height(),
            'mime'      => $image->mime(),
            'ext'       => $image->type(),
            'filename'  => $pathinfo['basename'],
        ];
        if(file_exists($thum_path.$thumb_name)){
            return $image_info;
        }
        // 获取要生成的缩略图最大宽度和高度
        $thumb_size =  config('upload_image_thumb') ?config('upload_image_thumb'): "640,426";
       // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
        list($thumb_max_width, $thumb_max_height) = explode(',', $thumb_size);
        
        
        $image->thumb($thumb_max_width, $thumb_max_height)->save($thum_path.$thumb_name);
        return $image_info;
        
    }
    
    
    /**
     * 下载图片到指定的目录
     * @param unknown $src
     * @return boolean|string  错误返回 false 正确返回 图片路径
     */
    private function downImage($src)
    {
        $client = new Client(['verify' => false]);
        try{
            $dir = self::DIR_PATH.date('Ymd').DS;
            $dir_path = ROOT_PATH.self::DIR_PATH.date('Ymd').DS;
            if(!file_exists($dir_path)) {
                mkdir($dir_path,0777,true);
            }
            if(!file_exists($dir_path))
                return false;
            $pathinfo = pathinfo($src);
            if(file_exists($dir_path.$pathinfo['basename'])){
                return $dir.$pathinfo['basename'];
            }
            
            $client->request('get',$src,['save_to'=>$dir_path.$pathinfo['basename']]);
            return $dir.$pathinfo['basename'];
        }catch (\Exception $e){
            return false;
        } 
    }
    private function getExt($path)
    {
        return strrchr($path,".");
    }
    
    /**从文件建立图片
     * @param string $filePath 文件地址路径
     * @return resource 当成功开启图片则传递图片 resource ID，失败则是 false
     * */
    public static function createImage($filePath){
        if(!file_exists($filePath)){ return false; }
    
        /*判断文件类型是否可以开启*/
        $type = exif_imagetype($filePath);
        if(!array_key_exists($type,self::$_createFunc)){ return false; }
    
        $func = self::$_createFunc[$type];
        if(!function_exists($func)){ return false; }
    
        return $func($filePath);
    }
    
    
    /**hash 图片
     * @param resource $src 图片 resource ID
     * @return string 图片 hash 值，失败则是 false
     * */
    public static function hashImage($src,$thumn_info){
        if(!$src){ return false; }
    
        /*缩小图片尺寸*/
        $delta = 8 * self::$rate;
        $img = imageCreateTrueColor($delta,$delta);
        imageCopyResized($img,$src, 0,0,0,0, $delta,$delta,imagesX($thumn_info['width']),imagesY($thumn_info['height']));
    
        /*计算图片灰阶值*/
        $grayArray = array();
        for ($y=0; $y<$delta; $y++){
            for ($x=0; $x<$delta; $x++){
                $rgb = imagecolorat($img,$x,$y);
                $col = imagecolorsforindex($img, $rgb);
                $gray = intval(($col['red']+$col['green']+$col['blue'])/3)& 0xFF;
    
                $grayArray[] = $gray;
            }
        }
        imagedestroy($img);
    
        /*计算所有像素的灰阶平均值*/
        $average = array_sum($grayArray)/count($grayArray);
    
        /*计算 hash 值*/
        $hashStr = '';
        foreach ($grayArray as $gray){
            $hashStr .= ($gray>=$average) ? '1' : '0';
        }
    
        return $hashStr;
    }
}

?>