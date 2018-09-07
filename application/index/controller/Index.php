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
/**
 * 前台首页控制器
 * @package app\index\controller
 */
class Index extends Home
{
    public function index()
    {
        return PayMoney::webPayMoney('alipay',['orderid'=>time(), 'total_money' => 0.01, 'title' => '测试']);
//         $list = AdminAttachment::getImages();
//         $this->assign('list' , $list);
//         return $this->fetch();
   }
    /**
     * ajax获取图片
     */
   public function getimages()
   {
       $list = AdminAttachment::getImages();
       return json_encode($list,true);
       return $this->success("查询成功！",'',$list);
   }
}
