<?php
namespace app\common\service\builder;

use app\common\service\Base;
use think\Db;
use app\common\service\PullImage;
class Pixabay extends Base
{
    /**
     * @var array
     */
    private $options = [];
    const API_ROOT = 'https://pixabay.com/api/';
    const SEGMENT_IMAGES = '';
    const SEGMENT_VIDEOS = 'videos/';
    const KEY = "9949411-643a9c8f6121981ba1efad425";
    const PER_PAGE = 20; //最大可设置为200
    /**
     * @var array
     */
    private $optionsList = [
        'key',
        'response_group',
        'id',
        'q',
        'lang',
        'callback',
        'image_type',
        'orientation',
        'category',
        'min_width',
        'min_height',
        'editors_choice',
        'safesearch',
        'page',
        'per_page',
        'pretty',
        'response_group',
        'order',
        'video_type'
    ];
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
    public  function getPublicImageList($page=1, $params=[])
    {   
        $params['key'] = self::KEY;
        $params['page'] = $page;
        $params['per_page'] = self::PER_PAGE;
        $params['lang'] ='zh';
        $params['image_type'] ="all";
        $this->parseOptions($params);
        $response = $this->client->request('GET', self::API_ROOT . self::SEGMENT_IMAGES, ['query' => $this->options]);
        $data = $response->getBody()->getContents();
        $res = \GuzzleHttp\json_decode($data,true);
        if(empty($res) || empty($res['hits']))
            return false;
        return $res['hits'];
    }
    
    /**
     * 获取
    */
    public  function getMyImageList()
    {
        
    }
    
    /**
     * Parse Provided options
     *
     * @param array $options
     * @param bool $resetOptions
     */
    private function parseOptions(array $options, $resetOptions = false)
    {
        foreach ($this->optionsList as $option) {
            if (isset($options[$option])) {
                $this->options[$option] = $options[$option];
            } elseif ($resetOptions && isset($this->options[$option])) {
                unset($this->options[$option]);
            }
        }
    }
    public function downThumbFinger($from, $data=[])
    {
    
        if(!Db::name('admin_attachment')->where(['unique_id'=>$data['id'],'from_web'=>$from])->count()){
            try{
                PullImage::addImage(
                    $data['largeImageURL'],
                    [
                        'from'=>$from,
                        'id'  =>$data['id'] ,
                        //'tags'=>Translate::getInstance()->translateStr($img['tags']),
                        'tags'=>$data['tags'],
                        'remark'=>json_encode($data),
                    ]);
            }catch (\Exception $e){
                return false;
            }
        }
    
    }
}

?>