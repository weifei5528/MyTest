<?php
namespace app\index\service\builder;

use app\index\service\AbstructAuth;
use Yurun\OAuthLogin\Taobao\OAuth2;
use app\common\model\UserQq;
class Taobao extends AbstructAuth
{
    const APP_ID  = "23527021";
    const APP_KEY = "893a4bd1a31d298be486c07a0b8c5b04";
    const NAME = "taobao";
    protected $client = null;
    public function __construct()
    {
        $this->client = new OAuth2(static::APP_ID,static::APP_KEY,$this->getCallBackUrl(self::NAME));
    }
    /* (non-PHPdoc)
     * @see \app\index\service\AbstructAuth::addUserId()
     */
    public function addUserId($userid, $where = array())
    {
        // TODO Auto-generated method stub
        
    }

 /* (non-PHPdoc)
     * @see \app\index\service\AbstructAuth::authCallBack()
     */
    public function authCallBack()
    {
        // TODO Auto-generated method stub
        $accessToken = $this->client->getAccessToken($_GET['state']);
        $this->client->getMixNick($this->client->result['taobao_user_nick']);exit;
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

 /* (non-PHPdoc)
     * @see \app\index\service\AbstructAuth::getAuthUrl()
     */
    public function getAuthUrl()
    {
        // TODO Auto-generated method stub
        $url = $this->client->getAuthUrl();
        $_SESSION['YURUN_QQ_STATE'] = $this->client->state;
        header('location:' . $url);
        exit;
    }

    
}

?>