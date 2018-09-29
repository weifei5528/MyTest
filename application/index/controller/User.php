<?php
namespace app\index\controller;

use app\common\model\UserDownloads as UDModel;

use app\common\model\UserBrowses as UBModel;
use app\common\model\UserLoves as ULModel;
use app\common\model\UserDirs as UDSModel;

use app\common\model\UserDirBrowse;

use app\common\model\User as UserModel;
use app\index\service\HashId;

use app\common\model\UserSharedApply as USAModel;

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
    /**
     * 分享url
     */
    public function sharedurl()
    {
        $this->assign('isvip',$this->isVip());
        $this->assign('userinfo',$this->user);
        $this->assign('type',-1);
        $this->assign('title','分享');
        $hasid =  HashId::encode_hex($this->user['id']);
        $this->assign('shareurl',url('Login/register',['id' => $hasid],true,true));
        return $this->fetch();
    }
    /**
     * 上传分享群
     */
    public function uploadImg()
    {
        if($this->request->isPost()) {
            $att = new Attachment();
            $resjson = $att->upload('images','','usershared');
            $res = json_decode($resjson,true);
            if($res['code']!=1){
                return $this->error($res['info']);
            } else {
                $info = USAModel::where(['userid' => $this->user['id'],'att_id' => $res['id']])->find();
                if($info) {
                    if($info['status'] == 0){
                        return json_encode(['code'=>0,'info' =>'您已经提交申请，请等待管理员审核!']);
                    } elseif ($info['status'] == 2) {
                        return json_encode(['code'=>0,'info' =>'您的申请未通过,原因为:'.$info['remark']]);
                    } else {
                        return json_encode(['code'=>0,'info' =>'你提交的申请,已经处理完毕！处理时间为:'.date('Y-m-d H:i:s',$info['update_time'])]);
                    }
                } else {
                    if(USAModel::create(['userid' => $this->user['id'],'att_id' => $res['id']])){
                        $res['info'] = "提交成功,请等待管理员审核...";
                        return json_encode($res);
                    } else {
                        $res['info'] = "提交失败,请重试！";
                        return json_encode($res);
                    }
                }
                
               
            }
        }
    }
}

?>