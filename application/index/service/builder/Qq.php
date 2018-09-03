<?php
namespace app\index\service\builder;

use Yurun\OAuthLogin\QQ\OAuth2;
use app\index\service\AbstructAuth;
use Yurun\OAuthLogin\ApiException;
use app\common\model\UserQq;
class Qq extends AbstructAuth
{
    const APP_ID  = "";
    const APP_KEY = "";
    protected $type = null;
    protected $client = null;
    public function __construct()
    {
        $this->client = new OAuth2(static::APP_ID,static::APP_KEY);
    }
    public function getAuthUrl()
    {
        $url = $this->client->getAuthUrl($this->getCallBackUrl());
        $_SESSION['YURUN_QQ_STATE'] = $this->client->state;
        header('location:' . $url);
    }
    public function authCallBack()
    {
       try{
            $accessToken = $this->client->getAccessToken($_SESSION['YURUN_QQ_STATE']);
            // 用户唯一标识
            $openid = $this->client->openid;
            $info = [];
            if(!($info=UserQq::getUser($openid))){
                // 用户资料
                $userInfo = $this->client->getUserInfo($accessToken);
                if($userInfo['ret'] != 0) {
                    return false;
                }
                $info = [
                    'openid'    =>$openid,
                    'nickname'  =>$userInfo['nickname'],
                    'gender'    =>$userInfo['gender'],
                    'head_img'  =>$userInfo['figureurl_qq_1'],
                    'remark'    =>json_encode($userInfo,true),
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
    public function addUserId($userid, $where)
    {
        if(empty($where) || empty($userid) ) {
            return false;
        }
        return UserQq::updateUser($where, ['userid' => $userid]);
    }
}

?>