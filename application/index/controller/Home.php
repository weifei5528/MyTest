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
use app\common\model\UserVips;
use app\common\model\CompanyUsers;
use app\common\model\Companies;
use app\common\model\UserLoves as ULModel;

use think\Db;
use think\Log;
use app\common\model\UserDirs;
use app\common\model\UserDirBrowse;
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
        $user = Db::name('users')->where(['id'=>10])->find();
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

    /**
     * 下载图片添加记录
     */
    protected function downloadRecord($id)
    {
        $where = ['userid' => $this->user['id'], 'att_id' => $id];
        //用户以前浏览过 更新浏览时间
        if(UDLModel::where($where)->count()) {
            UDLModel::where($where)->update(['update_time' => time()]);
        } else {
            UDLModel::create($where);
        }
        AttModel::where(['id' => $id])->setInc('download');
    }
    /**
     * 添加到喜爱
     */
    protected function addOurLove($id)
    {
        Db::startTrans();
        try{
            $where = ['userid' => $this->user['id'], 'att_id' => $id];
            //用户以前浏览过 更新浏览时间
            if(ULModel::where($where)->count()) {
                ULModel::where($where)->update(['update_time' => time()]);
            } else {
                ULModel::create($where);
            }
            AttModel::where(['id' => $id])->setInc('love');
            Db::commit();
            return true;
        } catch(\Exception $e) {
            Db::rollback();
            return false;
        }
        
    }
    /**
     * 添加浏览记录
     * @param int $id 图片的id
     */
    protected function addBrowse($id)
    {
        $where = ['userid' => $this->user['id'], 'att_id' => $id];
        //用户以前浏览过 更新浏览时间
        if(UBModel::where($where)->count()) {
            UBModel::where($where)->update(['update_time' => time()]);
        } else {
            UBModel::create($where);
        }
        AttModel::where(['id' => $id])->setInc('browse');
    }
    /**
     * 查询用户是否为vip
     * @return int  0 普通用户 1：vip用户 2：企业用户
     */
    protected function isVip($userid = null)
    {
        $userid = empty($userid) ? $this->user['id'] : $userid;
        $time=time();
        if(UserVips::where(['userid' => $userid,'end_time' => ['gt',$time]])->count()){
            return 1;
        }
        $comid = CompanyUsers::where(['userid' => $userid])->value('com_id');
        if($comid){
            if(Companies::where(['id' => $comid ,'end_time' => ['gt' ,$time]])->count()){
                return 2;
            }
        }
        return 0;
    }
    /**
     * 用户浏览文件夹
     */
    protected function userBrowseDir($dirid)
    {
        UserDirs::where(['id' => $dirid])->setInc('browse');
        UserDirBrowse::create(['userid'=>$this->user['id'],'dir_id' => $dirid]);
    }
}
