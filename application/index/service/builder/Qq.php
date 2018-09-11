<?php
namespace app\index\service\builder;

use Yurun\OAuthLogin\QQ\OAuth2;
use app\index\service\AbstructAuth;
use Yurun\OAuthLogin\ApiException;
use app\common\model\UserQq;
class Qq extends AbstructAuth
{
    const APP_ID  = "101504118";
    const APP_KEY = "8652552ef0ad592c7854d31dd4a728c4";
    const NAME = "qq";
    protected $client = null;
    public function __construct()
    {
        $this->client = new OAuth2(static::APP_ID,static::APP_KEY,$this->getCallBackUrl(self::NAME));
    }
    public function getAuthUrl()
    {
        $url = $this->client->getAuthUrl();
        $_SESSION['YURUN_QQ_STATE'] = $this->client->state;
        header('location:' . $url);
        exit;
    }
    public function authCallBack()
    {
        $accessToken = $this->client->getAccessToken($_GET['state']);
        $this->client->getOpenID($accessToken);
       try{
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
        return UserQq::updateUser($where, ['userid' => $userid]);
    }
}

?>