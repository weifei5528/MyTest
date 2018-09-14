<?php
namespace app\index\controller;

use app\common\model\AdminAttachment as AttModel;
use think\Db;

class Image extends Home
{
    /**
     * 浏览图片
     */
    public function index($id)
    {
        if(empty($id)) {
            return $this->error('请选择要浏览的图片！');
        }
        $info = AttModel::getImageInfo($id);
        $info['tags'] = specify_segmentation($info['tags']);
        $this->addBrowse($id);
        $this->assign('title','图片详情');
        $this->assign('info',$info);
        $this->assign('iscolect',$this->isCollect($id));//用户是否收藏
        return $this->fetch();
    }
    /**
     * 用户图片是否收藏
     */
    public function isCollect($id)
    {
        return Db::name('user_collects')->where(['userid'=>$this->user['id'] , 'att_id' => $id])->count();
    }
    
    /**
     * 用户下载
     */
    public function download($id)
    {
        
        if(empty($id)) {
            return $this->error('请选择要下载的图片！');
        }
        $info = AttModel::getImageInfo($id);
        
        if(empty($info)) {
            return $this->error('图片不存在或已被删除！');
        }
        imageDownload($info['path']);
        $this->downloadRecord($id);
        exit;    
    }
    /**
     * 用户喜爱
     */
    public function userlove($id)
    {
        if(empty($id)) {
            return $this->error("请选择图片！");
        }
        if($this->addOurLove($id)) {
            return $this->success("添加成功！");
        } else {
            return $this->error("添加失败，请重试~~");
        }
    }
    
}

?>