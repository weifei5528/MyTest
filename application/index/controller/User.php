<?php
namespace app\index\controller;

use app\common\model\UserDownloads as UDModel;
use app\common\model\AdminAttachment as AttModel;
use app\common\model\UserBrowses as UBModel;
use app\common\model\UserLoves as ULModel;
use app\common\model\UserDirs as UDSModel;
use app\common\model\UserDirLoves;
use app\common\model\UserDirBrowse;

use app\common\model\User as UserModel;

class User extends Home
{
    /**
     * 我下载过的图片
     */
    public function mydowns()
    {
       // $list = $this->ajaxdowns(false);
        $this->assign('userinfo',$this->user);
        $this->assign('isvip',$this->isVip());
        $this->assign('count',UDModel::getMyDownloadCount(['userid' => $this->user['id']]));
        $this->assign('type',"download");
        return $this->fetch();
    }
    /**
     * 获取我下载过的文件
     */
    public function ajaxmydowns()
    {
        $list = UDModel::where(['userid' => $this->user['id']])->order(['update_time' =>'desc'])->paginate();
        foreach ($list as &$v) {
            $v['thumb'] = get_file_path($v['id']);
            $v['url'] = url('Image/index',['id'=>$v['id']]);
        }
        return $this->success("查询成功！",'',$list);
        
    }
    /**
     * 我的浏览记录
     */
    public function mybrowses()
    {
        $this->assign('userinfo',$this->user);
        $this->assign('isvip',$this->isVip());
        $this->assign('count',UBModel::getWhereCount(['userid' => $this->user['id']]));
        $this->assign('type',"browse");
        return $this->fetch();
    }
    /**
     * 获取浏览过的文件
     */
    public function ajaxmybrowse()
    {
        $list = UBModel::where(['userid' => $this->user['id']])->order(['update_time' =>'desc'])->paginate();
        foreach ($list as &$v) {
            $v['thumb'] = get_file_path($v['id']);
            $v['url'] = url('Image/index',['id'=>$v['id']]);
        }
        return $this->success("查询成功！",'',$list);
        
    }
    /**
     * 我喜爱的记录
     */
    public function myloves()
    {
        $this->assign('userinfo',$this->user);
        $this->assign('isvip',$this->isVip());
        $this->assign('count',ULModel::getWhereCount(['userid' => $this->user['id']]));
        $this->assign('type',"love");
        return $this->fetch();
    }
    /**
     * 获取浏览过的文件
     */
    public function ajaxmyloves()
    {
        $list = ULModel::where(['userid' => $this->user['id']])->order(['update_time' =>'desc'])->paginate();
        foreach ($list as &$v) {
            $v['thumb'] = get_file_path($v['id']);
            $v['url'] = url('Image/index',['id'=>$v['id']]);
        }
        return $this->success("查询成功！",'',$list);
    
    }
    /**
     * 我收藏的记录
     */
    public function mycollects()
    {
        $this->assign('userinfo',$this->user);
        $this->assign('isvip',$this->isVip());
        //$this->assign('count',ULModel::getWhereCount(['userid' => $this->user['id']]));
        $this->assign('type',"collect");
        return $this->fetch();
    }
    /**
     * 获取浏览过的文件
     */
    public function ajaxmycollects()
    {
        $list = UDSModel::where(['userid' => $this->user['id'] ,'type' => 1])->order(['update_time' =>'desc'])->paginate();
        foreach ($list as &$v) {
            $v['thumb'] = get_file_path($v['att_id']);
            $v['url'] = url('Image/index',['id'=>$v['id']]);
        }
        return $this->success("查询成功！",'',$list);
    
    }
    /**
     * 收藏夹
     */
    public function getusercollects($id)
    {
        $this->assign('name','收藏夹');
        $dirinfo = UDSModel::where(['id' => $id])->find();
        $userinfo = UserModel::where(['id' => $dirinfo['userid']])->find();
        $this->assign('dirinfo', $dirinfo);
        $this->assign('userinfo',$userinfo);
        $this->assign('isvip',$this->isVip($userinfo['id']));
        $browseusers = UserDirBrowse::where(['dir_id' => $id])->order('create_time desc')->paginate(3);
        $this->userBrowseDir($id);
        foreach ($browseusers as &$v) {
            $v['info'] = UserModel::getUserInfo($v['userid']);
        }
        $this->assign('browseusers', $browseusers);
        return $this->fetch();
    }
}

?>