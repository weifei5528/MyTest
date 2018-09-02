<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/2
 * Time: 22:24
 */

namespace app\common\service\picture;


class FingerImage
{
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
    /**从文件建立图片
     * @param string $filePath 文件地址路径
     * @return resource 当成功开启图片则传递图片 resource ID，失败则是 false
     * */
    protected static function createImage($filePath){
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
    public static function hashImage($src)
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

            imagecopyresized($img,$func_src, 0,0,0,0, $delta,$delta,imagesX($src),imagesY($src));

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
            return null;
        }

    }
}