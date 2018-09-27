<?php
namespace app\common\service\pay\notice;

use Payment\Notify\PayNotifyInterface;
use think\Db;
use app\common\model\UserVips;
use app\common\model\Companies;
class Notify implements PayNotifyInterface
{
 /* (non-PHPdoc)
     * @see \Payment\Notify\PayNotifyInterface::notifyProcess()
     */
    public function notifyProcess(array $data)
    {
         $orderid = $data['out_trade_no'];
           Db::startTrans();
           $where = ['orderid' => $orderid];
           $info = Db::name('order')->where($where)->find();
           if($info['is_pay'] == 1) {
               Db::rollback();
               return true;
           }
           $where['is_pay'] = 0;
           try{
               Db::name('order')->where($where)->update(['is_pay' => 1,'pay_time' => time(), 'pay_money' => $data['point_amount']]);
               Db::name('user_pay_log')->insert(
                        [
                            'money'     =>$data['point_amount'],
                            'type'      =>$info['type'],
                            'pay_type'  =>'alipay',
                            'trade_id'  =>$data['trade_no'],
                            'remark'    =>json_encode($data),
                            'orderid'   =>$orderid
                        ]
                   );
               $good_info = Db::name('money_type')->where(['id' => $info['good_id']])->find();
               //vip用户
               $end = '';
               $start = '';
               $time =time();
               if($good_info['type'] == 1) {
                 if(empty(UserVips::addVip($good_info['date_type'], $info['userid']))){
                     Db::rollback();
                     return false;
                 }
                   
               } elseif($good_info['type'] == 2) {
                  if(empty(Companies::addVip($good_info['date_type'],$info['userid']))) {
                      Db::rollback();
                      return false;
                  }
                   
               }
               
               Db::commit();
               return true;
           } catch (\Exception $e) {
               Db::rollback();
               return false;
           }
       }
    
    

    
}

?>