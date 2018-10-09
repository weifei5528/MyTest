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

use app\common\model\AdminAttachment;
use app\common\service\PayMoney;
use app\common\model\UserDirs as UDModel;
use app\index\service\OAuth;
use think\Log;
/**
 * 前台首页控制器
 * @package app\index\controller
 */
class Index extends Home
{
    public function index()
    {
        return $this->fetch();
        

   }
    /**
     * ajax获取图片
     */
   public function getimages()
   {
       $list = AdminAttachment::getImages();
       foreach ($list as &$v) {
           $v['thumb'] = get_thumb($v['id']);
           $v['url'] = url('Image/index',['id'=>$v['id']]);
       }
      
       return $this->success("查询成功！",'',$list);
   }
   /**
    * 支付成功通知
    */
   public function paynotify()
   {
       Log::write($_POST,'notifyerror');
       Log::write($_GET,'notifyerror');
       exit('ok');
      
   }
   
   /**
    * 搜索
    */
   public function searchfromname()
   {
       $name = input('name');
      $this->assign('title','搜索');
      $this->assign('name',$name);
      //$this->assign('type','text');
      return $this->fetch();
       
   }
   /**
    * 搜索
    */
   public function ajaxgetname()
   {
       try{
           $name = input('name');
           if(empty($name)) {
               return $this->error("请输入搜索的描述~~");
           }
           $searchDirs = UDModel::getSearchName($name);
           $model = AdminAttachment::where('tags' , 'like' ,"%$name%");
           if($searchDirs) {
               $model->whereOr('id' , 'in',$searchDirs);
           }
           $list = $model->order(['browse' =>'desc'])->field('id,thumb')->paginate();
           
           foreach ($list as &$v){
               $v['thumb'] = PUBLIC_PATH.$v['thumb'];
           } 
       }catch (\Exception $e) {
           print_r($e);exit;
       }
      
       $this->success("查询成功！",'',$list);
   }
   /**
    * 关于
    */
   public function about()
   {
       $this->assign('title','关于');
       return $this->fetch();
   }
  /**
   * 收藏合集
   */
   public function getallcollect()
   {
       $this->assign('title',"收藏合集");
       return $this->fetch();
   }
}
