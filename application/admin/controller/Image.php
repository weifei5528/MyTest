<?php
namespace app\admin\controller;

use GuzzleHttp\Client;
use think\File;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
class Image
{
    
    const DIR_PATH  = "public/uploads/images/";
    const SAVE_PATH = "uploads/images/";
    /**取样倍率 1~10
     * @access public
     * @staticvar int
     * */
    public static $rate = 1;
    
    /**相似度允许值 0~64
     * @access public
     * @staticvar int
     * */
   // public static $similarity = 80;
    
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
        $pathinfo =pathinfo($src);
        if($this->isExists($pathinfo['basename'])){
            return false;
        }
        $src_path=$this->downImage($src);
        if(false===$src_path)
            return false;
        $thumb_info = $this->createThumb(str_replace('\\','/',ROOT_PATH.$src_path));
        
        $this->saveFile($src_path, $thumb_info,$info);
    }
    private function saveFile($src_path, $thumb_info,$image_info)
    {
        $file = new File(ROOT_PATH.$src_path);
        $file_info = [
            'name'   => $thumb_info['filename'],
            'mime'   => $thumb_info['mime'],
            'path'   => $this->getSavePath($src_path),
            'ext'    => $thumb_info['ext'],
            'size'   => $file->getSize(),
            'md5'    => $file->hash('md5'),
            'sha1'   => $file->hash('sha1'),
            'thumb'  => $this->getSavePath($thumb_info['path']),
            'module' => 'web',
            'width'  => $thumb_info['width'],
            'height' => $thumb_info['height'],
            'hashimage'=>self::hashImage(str_replace('\\','/',ROOT_PATH.$src_path),$thumb_info),
            'from_web'=>$image_info['from'],
            'remark'  =>isset($image_info['remark'])?$image_info['remark']:null, 
            'tags'    =>$image_info['tags'],
            'create_time'=>time(),
            'update_time'=>time(),
        ];
        db('admin_attachment')->insert($file_info);
        
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
       
        $image = \think\Image::open(str_replace('\\','/',$src));
        
        $thumb_name = md5($pathinfo['basename']).".".$pathinfo['extension'];
        $image_info = [
            'path'      => $thumb.$thumb_name,
            'width'     => $image->width(),
            'height'    => $image->height(),
            'mime'      => $image->mime(),
            'ext'       => $image->type(),
            'filename'  => $pathinfo['basename'],
        ];
       
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
        
            $dir = self::DIR_PATH.date('Ymd').DS;
            $dir_path = ROOT_PATH.self::DIR_PATH.date('Ymd').DS;
            
            
            if(!file_exists($dir_path)) {
                mkdir($dir_path,0777,true);
            }
            if(!file_exists($dir_path))
                return false;
            $pathinfo = pathinfo($src);
            $file_name = md5(time().rand(1111,9999)).'.'.$pathinfo['extension'];
          
            echo $dir_path.$file_name."<br/>";
            if($this->currentDownImage($src, $dir_path.$file_name)) {
                echo "成功"."<br/>";
                return $dir.$file_name;
            }else{
                echo "失败"."<br/>";
                return false;
            }
//             echo "1"."<br/>";
//             try{
//                 $res = $client->request('get',$src,['save_to'=>$dir_path.$file_name]);
//             }catch (GuzzleException  $e){
//                 dump($e);
//                 echo "失败"."<br/>";
//                 return false;
//             }
          
//             if($res->getStatusCode()!=200){
//                 echo "失败"."<br/>";
//                 return  false;
//             }
//            // echo "成功"."<br/>";
//            return $dir.$pathinfo['basename'];
       
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
     * @param resource $src 图片 resource 
     * @return string 图片 hash 值，失败则是 null 
     * */
    public static function hashImage($src, $thumn_info)
    {
        if(!$src) return null; 
        try {
            /*缩小图片尺寸*/
            $delta = 8 * self::$rate;
            $img = imagecreatetruecolor($delta,$delta);
            // imageCopyResized($img,$src, 0,0,0,0, $delta,$delta,imagesX($src),imagesY($src));
            $func_src = self::createImage($src);
//             if($func_src===false)
//                 return null;
             
            imagecopyresized($img,$func_src, 0,0,0,0, $delta,$delta,$thumn_info['width'],$thumn_info['height']);
            
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
        } catch (\Exception $e) {
            dump($e);
            return null;
        }
       
    }
    /**
     * 获取原图的绝对路径
     * @param unknown $name
     */
    
    private function getSavePath($path)
    {
        
        return str_replace('\\', '/', str_replace( 'public/','', $path));
    }
    
    /**
     * 判断文件是否存在 
     */
    private function isExists($name)
    {
        return db('admin_attachment')->where(['name'=>$name])->count();
    }
    /**
     * 图片下载
     */
    private function currentDownImage($url,$filename)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $temp = curl_exec($ch);
        if($temp===false){
            dump(curl_errno($ch));
            return false;
        }
            return false;
        if (!file_put_contents($filename, $temp)) {
           
            return false;
        }
    }
}

?>