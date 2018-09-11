<?php
namespace app\index\service\builder;

use app\index\service\AbstructAuth;
use Yurun\OAuthLogin\Weibo\OAuth2;
use app\common\model\UserQq;
use Yurun\OAuthLogin\ApiException;
class Weibo extends AbstructAuth
{
    const APP_ID  = "4280330499";
    const APP_KEY = "38bcfefda451724bf506392266deb758";
    protected $client = null;
    const NAME = "weibo";
    public function __construct()
    {
        $this->client = new OAuth2(static::APP_ID,static::APP_KEY,self::getCallBackUrl(self::NAME));
    }
    public function getAuthUrl()
    {
        $url = $this->client->getAuthUrl();
       // $_SESSION['YURUN_WEIBO_STATE'] = $this->client->state;
        header('location:' . $url);
    }
    public function authCallBack()
    {
        $accessToken = $this->client->getAccessToken($_GET['state']);  
        $this->client->getOpenID($accessToken);
        try{
            // 用户唯一标识
            $openid = $this->client->openid;
            $info = [];
            if(!($info=UserQq::getUser($openid,self::NAME))){
                // 用户资料
                $userInfo = $this->client->getUserInfo($accessToken);
                
                if(empty($userInfo)) {
                    return false;
                }
                $info = [
                    'openid'    =>$openid,
                    'nickname'  =>$userInfo['name'],
                    'gender'    =>$userInfo['gender']=='m'?'男':'女',
                    'head_img'  =>$userInfo['avatar_large'],
                    'remark'    =>json_encode($userInfo,true),
                    'type'      =>self::NAME
                ];
                if(!UserQq::addUserQq($info)) {
                    return false;
                }
            }
    
            return $info;
             
        } catch (ApiException $e) {
            return false;
        }
    
    }
    /**
     * 添加用户的id
     * @see \app\index\service\AbstructAuth::addUserId()
     */
    public function addUserId($userid, $where=[])
    {
        if(empty($where) || empty($userid) ) {
            return false;
        }
        $where['type'] = self::NAME;
        return UserQq::updateUser($where, ['userid' => $userid]);
    }
}

?>