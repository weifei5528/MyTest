<?php
namespace app\index\controller;

use app\common\model\AdminAttachment as AttModel;
use think\Db;
use app\common\model\UserLoves as ULModel;
use app\common\service\picture\FingerImage;
use think\Cache;

class Image extends Home
{
    /**
     * 浏览图片
     */
    public function index($id)
    {
        echo $id;
        if(empty($id)) {
            return $this->error('请选择要浏览的图片！');
        }
        $this->addBrowse($id);
        $info = AttModel::getImageInfo($id);

        $info['tags'] = trim($info['tags'])?specify_segmentation($info['tags']):'';

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
        $where = ['userid' => $this->user['id'], 'att_id' => $id];
        if(ULModel::where($where)->count()){
            if(ULModel::where($where)->delete()) {
                return $this->success("取消喜爱成功！");
            } else {
                return $this->error("取消喜爱失败，请重试！");
            }
        }else {
            if($this->addOurLove($id)) {
                return $this->success("添加喜爱成功！");
            } else {
                return $this->error("添加喜爱失败，请重试~~");
            }
        }
        
    }
    /**
     * 相似的
     */
    public function ajaxsimilar($id)
    {
        set_time_limit(0);
        $page = input('page',1);
        $datalist = Cache::get($id."_".$page);
        $lastid = Cache::get('lastid_'.$id);
        $lastid = $lastid ? $lastid : 205;
        $last = AttModel::where('hashimage','not null')->order('id desc')->value('id');
        $hashimage =   AttModel::where('id', '=' , $id)->order('id asc')->value('hashimage');
        if(!$datalist && ($lastid < $last )) {
            $size =0 ;
            $maxsize = 10;
            $where['id'] =['>',$lastid];
            $list =[];
            while ($lastid < $last && $size < 10) {
               $pagelist = AttModel::where($where)->where('hashimage','not null')->order('id asc')->field('id,hashimage,thumb')->limit(10)->select();
            
                foreach ($pagelist as $k => $v){
                    $flag = FingerImage::isHashSimilar($hashimage, $v['hashimage']);
                    if($flag) {
                        $size++;
            
                        $list = ['id' => $v['id'],'imgurl' => get_thumb($v['id'])];
                    }
                    if($size >= $maxsize) {
                        break;
                    }
                    $lastid = $v['id'];
                }
            }
            if($list) {
                Cache::set($id."_".$page,$list);
            }
            Cache::set('lastid_'.$id,$lastid);
            $datalist = $list;
           
        } elseif($datalist) {
            return $this->success("查询成功！",'',$datalist);
        } else {
           return $this->success("没有更多了!");
        }
        
        return $this->success("查询成功！",'',$datalist);
        
        
        
    }
    
}

?>