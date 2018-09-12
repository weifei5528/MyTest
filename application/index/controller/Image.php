<?php
namespace app\index\controller;

use app\common\model\AdminAttachment as AttModel;
use app\index\service\CommonFunc;

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
        CommonFunc::addBrowse($id);
        $this->assign('title','图片详情');
        $this->assign('info',$info);
        return $this->fetch();
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
        CommonFunc::downloadRecord($id);
        exit;    
    }
    
}

?>