<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 河源市卓锐科技有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\index\controller;

use app\common\controller\Common;

use app\common\model\AdminAttachment as AttModel;

use app\common\model\UserBrowses as UBModel;

use think\Image;

use app\common\model\UserDownloads as UDLModel;

use think\Db;
use think\Log;
/**
 * 前台公共控制器
 * @package app\index\controller
 */
class Home extends Common
{
    protected $user = [];
    protected $public_controllers = ['login','index'];
    /**
     * 初始化方法
     * @author 蔡伟明 <314013107@qq.com>
     */
    protected function _initialize()
    {
        
        // 系统开关
        if (!config('web_site_status')) {
            $this->error('站点已经关闭，请稍后访问~');
        }
        $controller = request()->controller();
        if(!$this->getUser() && !in_array(strtolower($controller), $this->public_controllers)) {
            if(empty($this->getUser())) {
                $backurl = null;
                if($controller!='login'){
                    $backurl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
                    session('backurl',$backurl);
                }
                return $this->redirect(url('Login/login'));
            }
        }
    }
    protected  function getUser()
    {
        $user = session('user');
        $user = [
            'id'    =>  10,
            'username'=>'weifei',
        ];
        if(empty($user))
            return false;
        $this->user = $user;
        return $this->user;
    }
    
    /**
     * 设置用户
     */
    protected function setUser($user)
    {
        session('user',$user);
    }
    
    /**
     * 获取图片
     */
    public function getimage($id)
    {
        if(empty($id)) {
            exit("");
        }
        $path = AttModel::where(['id' => $id])->value('path');
        $image = Image::open(ROOT_PATH.'public/'.$path);
        $image->thumb(840, 580)->save('./thumb.png');
        echo file_get_contents('./thumb.png');
    }
   
}
