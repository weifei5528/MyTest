<?php
namespace app\common\service\pay\notice;

use Payment\Notify\PayNotifyInterface;
use think\Db;
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
                   $vip_info = Db::name('user_vips')->where(['userid' => $info['userid']])->find();
                   $vip_end = empty($vip_info['end_time']) ? $time : ($vip_info['end_time'] > $time ? $vip_info['end_time'] : $time);
                   $end_time = strtotime("+ ".$good_info['date_type'],$vip_end);
                   $end = $end_time;
                   $start_time ='';
                   //开始时间为空
                   if(empty($vip_info['start_time'])) {
                       $start = $time;
                       $start_time = $time;
                   } else {
                       // vip没有过期续费
                       if($vip_info['end_time'] > $time) {
                           $start = $vip_info['end_time'];
                           $start_time = $vip_info['start_time'];
                       //vip已过期    
                       } else {
                           $start = $time;
                           $start_time = $time;
                       }
                   }
                   //第一次充值
                   if(empty($vip_info)) {
                       Db::name('user_vips')->insert([
                           'userid'     =>  $info['userid'],
                           'start_time' =>  $start_time,
                           'end_time'   =>  $end_time,
                           'create_time'=>  time(),
                           'update_time'=>  time(),
                       ]);
                    //以前充值过vip
                   } else {
                       Db::name('user_vips')->where(['id' => $vip_info['id']])->update([
                           'start_time' =>  $start_time,
                           'end_time'   =>  $end_time,
                           'update_time'=>  time(),
                       ]);
                   }
                   
               } elseif($good_info['type'] == 2) {
                   $com_info = Db::name('companies')->where(['userid' => $info['userid']])->find();
                   $vip_end = empty($com_info['end_time']) ? time() : ($com_info['end_time'] > time() ? $com_info['end_time'] : time());
                   $end_time = strtotime("+ ".$good_info['date_type'],$vip_end);
                   $end = $end_time;
                   //开始时间过期就覆盖
                    $start_time ='';
                   //开始时间为空
                   if(empty($com_info['start_time'])) {
                       $start = $time;
                       $start_time = $time;
                   } else {
                       // vip没有过期续费
                       if($com_info['end_time'] > $time) {
                           $start = $com_info['end_time'];
                           $start_time = $com_info['start_time'];
                       //vip已过期    
                       } else {
                           $start = $time;
                           $start_time = $time;
                       }
                   }
                //第一次充值
                   if(empty($com_info)) {
                       $com_id = Db::name('companies')->insertGetId([
                                   'userid'     =>  $info['userid'],
                                   'start_time' =>  $start_time,
                                   'end_time'   =>  $end_time,
                                   'create_time'=>  time(),
                                   'update_time'=>  time(),
                                   'name'       =>  '',
                               ]);
                       //添加主账号
                       Db::name('company_users')->insert([
                           'com_id'     =>  $com_id,
                           'userid'     =>  $info['userid'],
                           'is_host'    =>  1,
                           'create_time'=>  $time,
                           'update_time'=>  $time,
                       ]);
                    //以前充值过vip
                   } else {
                       Db::name('companies')->where(['id' => $com_info['id']])->update([
                           'start_time' =>  $start_time,
                           'end_time'   =>  $end_time,
                           'update_time'=>  time(),
                       ]);
                   }
                   
               }
               //记录充值日志日志
               Db::name('user_vc_log')->insert([
                   'userid'     =>  $info['userid'],
                   'remark'     =>  'VIP充值',
                   'vc_type'    =>  $good_info['type'],
                   'type'       =>  1,
                   'start_timee'=>  $start,
                   'end_time'   =>  $end,
               ]);
               Db::commit();
               return true;
           } catch (\Exception $e) {
               Db::rollback();
               return false;
           }
       }
    
    

    
}

?>