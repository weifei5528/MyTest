<?php
namespace app\common\service\picture;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/2
 * Time: 22:07
 */
class DownLoadImage
{
    const DIR_PATH  = "public/uploads/images/";
    const SAVE_PATH = "uploads/images/";
    public static function downImage($src, $type=null)
    {
        $client = new Client(['verify' => false,'connect_timeout' => 30]);

        $dir = self::DIR_PATH.date('Ymd').DS;
        $dir_path = ROOT_PATH.self::DIR_PATH.date('Ymd').DS;


        if(!file_exists($dir_path)) {
            mkdir($dir_path,0777,true);
        }
        if(!file_exists($dir_path))
            return false;
        if(empty($type)) {
            $type = pathinfo($src,PATHINFO_EXTENSION);
        }
        $file_name = md5(time().rand(1111,9999)).'.'.$type;
        $res = null;
        try{
            $res = $client->request('get',$src,['save_to'=>$dir_path.$file_name]);
        }catch (RequestException  $e){
            return false;
        }

         if($res->getStatusCode()!=200){
             return  false;
         }
//            // echo "成功"."<br/>";
        return $dir.$file_name;
    }
}