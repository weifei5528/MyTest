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
use think\Log;
/**
 * 前台首页控制器
 * @package app\index\controller
 */
class Index extends Home
{
    public function index()
    {
        
        
        
        //return PayMoney::webPayMoney('alipay',['orderid'=>time(), 'money' => 0.01, 'title' => '测试','discription' => 'ceshi','pay_type' => 'ali_web']);
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
   /**
    * 支付成功通知
    */
   public function paynotify()
   {
       parse_str('body=test+body&subject=test+subject&sign_type=RSA2&buyer_logon_id=aaq***%40sandbox.com&auth_app_id=2016073100130857&notify_type=trade_status_sync&out_trade_no=14893825198432&point_amount=0.00&version=1.0&fund_bill_list=%5B%7B%22amount%22%3A%220.01%22%2C%22fundChannel%22%3A%22ALIPAYACCOUNT%22%7D%5D&passback_params=123&buyer_id=2088102169940354&total_amount=0.01&trade_no=2017031321001004350200145287&notify_time=2017-03-13+13%3A23%3A05&charset=UTF-8&invoice_amount=0.01&gmt_payment=2017-03-13+13%3A23%3A04&trade_status=TRADE_SUCCESS&sign=SrfDm1whLHx8PeFcPbAEn7S43%2BOTMy5ZnTxv42jpCeRXz8poKS0n542Nf4eAq7%2BJfta1vMqybMFf9C4Cl%2B3WEPFbndU2WGpboyU2CPUcSoYaBE68H1%2FImNUomEi3vMjJe3H4s%2Fz%2BLOnVcH8luO0bbSB79kKupec0fdm9V9Wg2axaZD9UkRLwBvoXsDx9tFOAwhqHyY1ZPq%2F1SQj5cwhQ2luKhJaqjO4L4Z819b%2BvHZfuaKX3xt5pgCQXiSVLo%2BfA%2FY0RmDfNngZML8UndYyXpXmgTMH2grR7D65ODPlatDt3JsNe9U2Kj%2F7uVXdPR2Tey3ikL4W4Pn4%2FULq8ow3YHw%3D%3D&gmt_create=2017-03-13+13%3A23%3A03&buyer_pay_amount=0.01&receipt_amount=0.01&seller_id=2088102169252684&app_id=2016073100130857&seller_email=naacvg9185%40sandbox.com&notify_id=27d63b0f7da1e21d932b6ec9176a052ipa', $data);
       PayMoney::notice('alipay',$data);
   }
   
}
