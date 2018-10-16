<?php
namespace app\index\controller;

use think\Db;
use app\common\model\UserDirs as UDModel;
use app\common\model\UserDirImages;
use app\index\service\CommonFunc;

class Userdir extends Home
{
    protected $typeList =[
        1   =>  '收藏',
        2   =>  '下载',
        3   =>  '喜爱',
    ];
    /**
     * 我的文件夹列表
     */
    public function getmydirs($type=1)
    {
        if(empty($type)) {
            return $this->error('类型不存在！');
        }
        $list =  UDModel::where(['userid' => $this->user['id'] ,'type' => $type])->column('name','id');
        
        if($list) {
            return $this->success('查询成功！',null, $list);
        } else {
            return $this->error('没有下载文件夹,请先创建！');
        }
    }
    /**
     * 创建文件夹
     */
    public function createmydir($type=1)
    {
        $type = input('type');
        if(empty($type)) {
            return $this->error("请选择要创建的文件夹类型！");
        }
        if($this->request->isPost()) {
            $name = input('name');
            $auth = input('auth',0);
            if(empty($name)) {
                return $this->error('文件夹名称不能为空！');
            }
            if($info=UDModel::create(['type' => $type, 'userid' => $this->user['id'] ,'auth' => $auth ,'name' => $name])) {
                return $this->success("创建成功！",'',$info);
            } else {
                return $this->error('创建失败！');
            }
        }
        
        
    }
    /**
     * 添加图片到文件夹
     * @param      int $type 文件夹类型
     */
    public function addimage($type) 
    {
        if($this->request->isPost()) {
            $imgId = input('id',0);
            $dirId = input('dirid',0);
            if(empty($imgId)) {
                return $this->error('请选择要添加的图片！');
            }
            if(empty($dirId)) {
                return $this->error("请选择要放置的文件夹");
            }
            $info = $this->isAuth($type, $dirId);
            if(empty($info)) {
                return $this->error('文件夹不存在,请重新选择文件夹！');
            }
            if(UserDirImages::where(['dir' => $dirId, 'att_id' => $imgId])->find()) {
                return $this->error('文件夹中已存在此图片，不需要重复添加！');
            }
            if(CommonFunc::addImageToDir($dirId, $imgId)){
                //设置文件夹的第一张封面
                if(empty($info['count'])) {
                    CommonFunc::setDirCover($dirId, $imgId);
                }
                //更新文件夹的文件张数
                CommonFunc::updateCount($dirId);
                
               
                return $this->success("添加成功！");
            }else{
                return $this->error("添加失败,请重新添加！");
            }
        }
    }
    /**
     * 设置封面
     * @param      int $dirid 文件夹的id
     * @param post int attr_id 图片id
     * @param      int $type 文件夹类型
     */
    public function setcover($type,$dirid)
    {
        if($this->request->isPost()) {
            $imgId = input('imgid',0);
            if(empty($imgId)) {
                return $this->error('请选择要设置的封面图片！');
            }
            if(!$this->isAuth($type, $dirid)){
                return $this->error("此文件夹不存在,请重试！");
            }
            if(CommonFunc::setDirCover($dirid, $imgId)) {
                return $this->success("设置成功！");
            } else {
                return $this->error("设置失败,请重试！");
            }
        }
    }
    
    /**
     * 判断文件夹的权限
     * @param $type  int 文件夹的类型
     * @param $dirid int 文件夹的id
     * 
     */
    private function isAuth($type,$dirid)
    {
        return UDModel::where(['id' => $dirid, 'type' =>$type, 'userid' => $this->user['id']])->find();
        
    }
}

?>