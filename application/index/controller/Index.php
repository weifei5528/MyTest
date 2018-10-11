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
use app\common\model\Types;
use app\common\model\User;
/**
 * 前台首页控制器
 * @package app\index\controller
 */
class Index extends Home
{
    public $sort_list = ['browse'=>'人气','create_time'=>'最新'];
    public $default_sort = 'browse';
    protected $data_h_list = [300,437,300,298];
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
       $cl_list = Types::getList();
       array_unshift($cl_list, "全部收藏");
       $this->assign('clttypelist',$cl_list);
       $this->assign('sortlist',$this->sort_list);
       $this->assign('sortval',$this->default_sort);
       $this->assign('cltype',0);
       return $this->fetch();
   }
   /**
    * ajax获取分类的合集列表
    */
   public function ajaxgetallcollect()
   {
       $sortval = input('sortval/s',$this->default_sort);
       
       $order = ["$sortval"=>'desc']; 
       $cltype = input('cltype/d',0);
       $where = [];
       if($cltype) {
           $info = Types::get($cltype);
           if($info) {
               $where['name'] = ['like',"%".$info['name']."%"];
           }
       }
       
       $list = UDModel::getDirsList($where,$order);
       $unsetlist = ['userid','auth','type'];
       foreach ($list as $k => &$val) {
           $val['coverimg'] = get_thumb($val['cover']);
           $val['userimg']  = User::where('id','=',$val['userid'])->value('head_img');
           foreach ($unsetlist as $key => $value) {
               unset($val[$value]);
           }
       }
       
       $this->assign('list',$list);
       $itemHtml = $this->fetch('index/getallcollect_item');
       
       $this->success("查询成功",'',$itemHtml);
       
   }
   /**
    * 最新的图片
    */
   public function newimg()
   {
       $this->assign('title','搜索');
       return $this->fetch();
   }
   /**
    * 
    */
   public function ajaxgetnewimgs()
   {
       
       $list = AdminAttachment::getNewImgs();
       $this->assign('data_h_list' ,$this->data_h_list);
       $this->assign('data_h_count',count($this->data_h_list)-1);
       $this->assign('list',$list);
       $html = $this->fetch('index/newimg_item');
       $this->success("查询成功！",'',$html);
   }
}
