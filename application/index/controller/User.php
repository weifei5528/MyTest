<?php
namespace app\index\controller;

use app\common\model\UserDownloads as UDModel;

class User extends Home
{
    /**
     * 我下载过的图片
     */
    public function mydowns()
    {
        $list = $this->ajaxdowns(false);
        $this->assign('userinfo',$this->user);
        $this->assign('isvip',$this->isVip());
        return $this->fetch();
    }
    /**
     * 获取我下载过的文件
     */
    public function ajaxmydowns($json=true)
    {
        $list = UDModel::where(['userid' => $this->user['id']])->order(['update_time' =>'desc'])->paginate();
        if(false===$json){
            return $list;
        }else{
            return $this->success("查询成功！",'',$list);
        }
    }
    
   
}

?>