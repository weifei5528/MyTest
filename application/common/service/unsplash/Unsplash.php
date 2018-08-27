<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27
 * Time: 21:57
 */

namespace app\common\service\unsplash;


use app\common\service\Base;
use Crew\Unsplash\Photo;

class Unsplash extends Base
{
    const PUBLIC_PHOTOS = "https://api.unsplash.com/photos";
    const ACCESS = "213dd1485346e67e2d3f1a194c77da264aa403b66a2b32ebbc388369e4ef57da";
    const SECRET = "d3cea798e3c255f83dc9959cef6cdf58298309ef3d7c135fd195cb2bc9974908";
    protected $header = [];
    /**
     * 获取授权的token
     * @return mixed
     *
     */
    public  function auth()
    {

    }

    /**
     * 获取公共的图片 不需要授权
     * @return mixed
     */
    public  function getPublicImageList($page=1)
    {
        $url =  self::PUBLIC_PHOTOS.'?client_id='.static::ACCESS;
        $res=$this->client->request(
            'GET',
            $url
            );
        dump($res->getBody());exit;
        $photos = Photo::all();
        dump($photos);exit;
    }

    /**
     * 获取
     */
    public  function getMyImageList()
    {

    }

    private function setHeader(){
        $this->header['Authorization: Client-ID'] = $this->access;
    }

}