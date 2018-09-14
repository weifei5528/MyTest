<?php
namespace app\index\controller;

use app\common\model\UserDownloads as UDModel;
use app\common\model\AdminAttachment as AttModel;

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
        
    }
   
}

?>