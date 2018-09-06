<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27
 * Time: 21:57
 */

namespace app\common\service\builder;

use app\common\service\Base;

use Crew\Unsplash\HttpClient;
use think\Db;
use app\common\service\PullImage;
use app\common\service\Translate;
class Unsplash extends Base
{
    const HOST_NAME   =  "https://api.unsplash.com/";
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
        HttpClient::init([
            'applicationId'	=> self::ACCESS,
            'secret'		=> self::SECRET,
            'callbackUrl'	=>"46814.3m.dkys.org".url('Index/getCode'), 
        ]);
        $httpClient = new \Crew\Unsplash\HttpClient();
        $owner = $httpClient::$connection->getResourceOwner();
        dump($owner);exit;
    }

    /**
     * 获取公共的图片 不需要授权
     * @return mixed
     */
    public  function getPublicImageList($page=1, $params=[])
    {
        //$url =  self::PUBLIC_PHOTOS.'?client_id='.static::ACCESS;
        $res=$this->client->request(
            'GET',
            self::HOST_NAME."photos",
            [
                'query'   =>[
                    'page'      =>$page,
                    'per_page'  =>$this->pageSize,
                    'client_id' =>static::ACCESS
                ]
            ]
            );
        if(200 != $res->getStatusCode()) {
            return false;
        }
        $data = $res->getBody()->getContents();
        if(empty($data)) {
            return false;
        }
        return json_decode($data,true);
        
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
    public function downThumbFinger($from, $data=[])
    {
        if(!Db::name('admin_attachment')->where(['unique_id'=>$data['id'],'from_web'=>$from])->count()){
            try{
                parse_str(parse_url($data['urls']['full'],PHP_URL_QUERY),$url_params);
                PullImage::addImage(
                    $data['urls']['raw'],
                    [
                        'from'=>$from,
                        'id'  =>$data['id'] ,
                        'tags'=>empty($data['description'])?'':Translate::getInstance()->translateStr($data['description']),
                        //'tags'=>$data['tags'],
                        'color'=>$data['color'],
                        'remark'=>json_encode($data),
                        'type'  =>isset($url_params['fm'])?$url_params['fm']:'jpg',
                    ]);
            }catch (\Exception $e){
                return false;
            }
        }
    }
}