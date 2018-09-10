<?php
namespace app\common\service\pay;

use app\common\service\PayInterface;
use Payment\Client\Charge;
use Payment\Common\PayException;
use Payment\Config;
use Payment\Client\Notify;
use think\Log;
use think\Db;

class Alipay implements PayInterface
{
    
    protected $aliConfig = [];
    public function __construct()
    {
        $this->aliConfig = require APP_PATH.'common/service/pay/config/aliconfig.php';
    }
    
    
 /* (non-PHPdoc)
     * @see \app\common\service\PayInterface::notice()
     */
    public function notice($data=[])
    {
       try{
           return Notify::run('ali_charge', $this->aliConfig, function($data){
               $orderid = $data['out_trade_no'];
               Db::startTrans();
               $where = ['orderid' => $orderid];
               $info = Db::name('order')->where($where)->find();
               if($info['is_pay'] == 1) {
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
                            ]
                       );
                   $good_info = Db::name('money_type')->where(['id' => $info['good_id']])->find();
                   $vip_info = Db::name('user_vips')->where(['userid' => $info['userid'], 'type' => $good_info['type']])->find();
                   $vip_end = empty($vip_info['end_time']) ? time() : $vip_info['end_time'];
                   $end_time = strtotime("+ ".$good_info['date_type'],$vip_end);
                   $start_time = empty($vip_info['start_time']) ? time() : ($vip_info['start_time'] > time() ? $vip_info['start_time'] :time());
                   if(empty($vip_info)) {
                       Db::name('user_vips')->where(['id' => $vip_info['id']])->insert([
                           'userid'     =>  $info['userid'],
                           'start_time' =>  $start_time,
                           'end_time'   =>  $end_time,
                           'type'       =>  $good_info['type'],
                           'create_time'=>  time(),
                           'update_time'=>  time(),
                           'name'       =>  ''
                       ]);
                   } else {
                       Db::name('user_vips')->where(['id' => $vip_info['id']])->update([
                           'start_time' =>  $start_time,
                           'end_time'   =>  $end_time,
                           'update_time'=>  time(),
                           'name'       =>  ''
                       ]);
                   }
                   
                       

                   
                   
               } catch (\Exception $e) {
                   
               }
           });
       } catch(PayException $e) {
           Log::write($e->errorMessage(),'alipayerror');
           return false;
       }
    }

 /* (non-PHPdoc)
     * @see \app\common\service\PayInterface::webPayMoney()
     */
    public function webPayMoney($params = array())
    {
        
        $payData = [
            'body'    => $params['discription'],
            'subject'    => $params['title'],
            'order_no'    => $params['orderid'],
            'timeout_express' => time() + 600,// 表示必须 600s 内付款
            'amount'    => $params['money'],// 单位为元 ,最小为0.01
            'return_param' => 'ali_web',
            // 'client_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',// 客户地址
            'goods_type' => '0',// 0—虚拟类商品，1—实物类商品
            'store_id' => '',
            'operator_id' => '',
            'terminal_id' => '',// 终端设备号(门店号或收银设备ID) 默认值 web
        
        ];
        //$this->aliConfig['notify_url']=url('index\notice\notice',['type'=>'ali_charge'],true,true);
        if(isset($params['return_url'])) {
            $this->aliConfig['return_url'] = $params['return_url'];
        }
        if(isset($params['notify_url'])) {
            $this->aliConfig['notify_url'] = $params['notify_url'];
        }
        
        //$this->aliConfig=array_merge($this->aliConfig,$config);
        try {
            $url = Charge::run($this->getPayType($params['pay_type']), $this->aliConfig, $payData);
        } catch (PayException $e) {
            $this->ajaxreturn(90001,$e->errorMessage());
            exit;
        }
        header('Location:'.$url);
        exit;
        
    }
    protected function getPayType($pay_type){
        switch ($pay_type){
            case 'ali_web':
                return Config::ALI_CHANNEL_WEB;
                break;
            case "ali_app":
                return Config::ALI_CHANNEL_APP;
                break;
//             case "wx_web":
//                 return Config::WX_CHANNEL_QR;
//                 break;
//             case "wx_app":
//                 return Config::WX_CHANNEL_APP;
//                 break;
        }
    }
    
}

?>