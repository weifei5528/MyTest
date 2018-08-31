<?php
namespace app\common\service;

use GuzzleHttp\Client;
class Translate
{
    static $translation = null;
    const APP_ID = '20180826000198843';
    const SECRET = 'd5OYgu_6qlUhCyq6Y7hG';
    const URL = 'http://api.fanyi.baidu.com/api/trans/vip/translate';
    const SALT = 'translate_picture';
    protected $query =[
        'from'      =>'auto',
        'to'        =>'zh',
        'appid'     =>self::APP_ID,
        'salt'      =>self::SALT,
        
    ];
    protected $client =null;
    private function __construct() {}
    public static function getInstance()
    {
        if(empty(self::$translation)) {
            self::$translation = new static();
        }
       
        return self::$translation;
    }
    /**
     * 
     * @param string $str 需要翻译的英文字符串
     * @return 查询失败 返回 null  成功 返回 string
     */
    public function translateStr($str)
    {
        $this->client =new Client(['verify' => false]);
        $query = $this->getQuery($str);
        $res = $this->client->request('post',self::URL,['query' => $query]);
        $contents = $res->getBody()->getContents();
        if(empty($contents))
            return null;
        $list = json_decode($contents,true);
        return $list['trans_result'][0]['dst'];
        
    }
    private function getQuery($str)
    {
        $this->query['q'] = $str;
        $this->query['sign'] = $this->getSign($str);
        return $this->query;
    }
    
     private function getSign($str)
     {
         return md5(self::APP_ID.$str.self::SALT.self::SECRET);
     }
}

?>