<?php
namespace app\common\service\builder;

use app\common\service\Base;
class Pixabay extends Base
{
    /**
     * @var array
     */
    private $options = [];
    const API_ROOT = 'https://pixabay.com/api/';
    const SEGMENT_IMAGES = '';
    const SEGMENT_VIDEOS = 'videos/';
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
        $this->parseOptions($params);
        $response = $this->client->request('GET', self::API_ROOT . self::SEGMENT_IMAGES, ['query' => $this->options]);
        $data = $response->getBody()->getContents();
        return \GuzzleHttp\json_decode($data,true);
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
}

?>