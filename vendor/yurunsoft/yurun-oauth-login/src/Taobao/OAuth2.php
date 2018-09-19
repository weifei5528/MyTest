<?php
namespace Yurun\OAuthLogin\Taobao;

use Yurun\OAuthLogin\Base;

class OAuth2 extends Base
{
    /**
     * openid从哪个字段取，默认为openid
     * @var int
     */
//     public $openidMode = OpenidMode::OPEN_ID;
    
    /**
     * 是否使用unionid，默认为false
     * @var boolean
     */
    public $isUseUnionID = false;
    /**
     * api接口域名
     */
    const API_DOMAIN = 'https://oauth.taobao.com/';
    const INFO_DOMAIN = 'http://gw.api.taobao.com/router/rest';
    private $view = 'web';//可选web、tmall或wap其中一种，Web对应PC端（淘宝logo）浏览器页面样式；Tmall对应天猫的浏览器页面样式；Wap对应无线端的浏览器页面样式。
    public function setView($view)
    {
        $this->view = $view;
    }
 /* (non-PHPdoc)
     * @see \Yurun\OAuthLogin\Base::__getAccessToken()
     */
    protected function __getAccessToken($storeState, $code = null, $state = null)
    {
        // TODO Auto-generated method stub
        $result = $this->http->post($this->getUrl('token', array(
            'grant_type'	=>	'authorization_code',
            'client_id'		=>	$this->appid,
            'client_secret'	=>	$this->appSecret,
            'code'			=>	isset($code) ? $code : (isset($_GET['code']) ? $_GET['code'] : ''),
            'state'			=>	isset($state) ? $state : (isset($_GET['state']) ? $_GET['state'] : ''),
            'redirect_uri'	=>	$this->getRedirectUri(),
            'view'          =>  $this->view
        )))->body();
        $this->result = json_decode($result,true);
        if(isset($this->result['code']) && 0 != $this->result['code'])
        {
            throw new ApiException($this->result['msg'], $this->result['code']);
        }
        else
        {
            return $this->accessToken = $this->result['access_token'];
        }
    }
    /**
     * 获取url地址
     * @param string $name 跟在域名后的文本
     * @param array $params GET参数
     * @return string
     */
    public function getUrl($name, $params = array())
    {
        return static::API_DOMAIN . $name . (empty($params) ? '' : ('?' . $this->http_build_query($params)));
    }
    /**
     * 
     */
    public function getTaoUrl($name, $params = array())
    {
        return static::INFO_DOMAIN . $name . (empty($params) ? '' : ('?' . $this->http_build_query($params)));
    }

 /* (non-PHPdoc)
     * @see \Yurun\OAuthLogin\Base::getAuthUrl()
     */
    public function getAuthUrl($callbackUrl = null, $state = null, $scope = null)
    {
        // TODO Auto-generated method stub
        $option = array(
            'response_type'		=>	'code',
            'client_id'			=>	$this->appid,
            'redirect_uri'		=>	null === $callbackUrl ? $this->callbackUrl : $callbackUrl,
            'state'				=>	$this->getState($state),
            'view'				=>	$this->view
        );
        if(null === $this->loginAgentUrl)
        {
            return $this->getUrl('authorize', $option);
        }
        else
        {
            return $this->loginAgentUrl . '?' . $this->http_build_query($option);
        }
    }

 /* (non-PHPdoc)
     * @see \Yurun\OAuthLogin\Base::getUserInfo()
     */
    public function getUserInfo($accessToken = null)
    {
       return [
           'nickname'   =>$this->result['taobao_user_nick'],
           ''
       ];
        // TODO Auto-generated method stub
        
    }
    public function getUserHead()
    {
        
    }
    public function getMixNick($nick)
    {
        $result=$this->http->post($this->getTaoUrl('', array(
            'method'    =>'taobao.mixnick.get',
            'nick'      =>$nick,
            'token'     =>$this->accessToken
        )))->body();
        dump($result);exit;
    }

 /* (non-PHPdoc)
     * @see \Yurun\OAuthLogin\Base::refreshToken()
     */
    public function refreshToken($refreshToken)
    {
        // TODO Auto-generated method stub
        
    }

 /* (non-PHPdoc)
     * @see \Yurun\OAuthLogin\Base::validateAccessToken()
     */
    public function validateAccessToken($accessToken = null)
    {
        // TODO Auto-generated method stub
        
    }
    /**
     * 获取OpenID
     * @param string $accessToken
     * @return string
     */
    public function getOpenID($accessToken = null)
    {
        $this->openid = $this->result['taobao_open_uid'];
       return $this->openid;
    }
    
    
}

?>