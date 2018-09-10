<?php
namespace app\index\controller;
use think\Db;
use app\common\service\PayMoney;
class Order extends Home
{
    public function pay()
    {
        $good_id = input('param.goodid',0);
        if(empty($good_id))
            return $this->error('请选择要充值的类型！');
        $type = input('type','');
        if(empty($type)) {
            return $this->error('请选择支付方式！');
        }
        $good_info = Db::name('money_type')->where(['id' => $good_id])->find();
        if(empty($good_info))
            return $this->error('充值类型不存在！');
        $sum = $good_info['money'];
        $orderid = self::createOrderNum($type);
       
         if(Db::name('order')->insert([
                'orderid'       =>$orderid,
                'pay_type'      =>$this->getPayType($type),
                'sum'           =>$sum,
                'good_id'       =>$good_id,
                'good_info'     =>json_encode($good_info),
                'userid'        =>$this->user['id'],
                'create_time'   =>time(),
                'update_time'   =>time(),
            ])) {
                return PayMoney::webPayMoney($type,['orderid'=>$orderid, 'money' => $sum, 'title' => $good_info['name'],'discription' => $good_info['name'],'pay_type' => 'ali_web']);
         } else {
             return $this->error('创建订单失败，请重试！');
         }
            
        
        
        
    }
    public static function createOrderNum($type)
    {
        $str = '';
        switch ($type) {
            case 'alipay':
                 $str = "AWB_";
                break;
                
        }
        return $str.=time().rand(1111,9999);
    }
    private function getPayType($type)
    {
        
        switch ($type) {
            case 'alipay':
                return 1;
                break;
            case 'wchpay':
                return 2;
        
        }
        
    }
    /**
     * 支付成功页面
     */
    public function paysuccess()
    {
        $orderid = input('get.out_trade_no');
        if(empty($orderid)) { 
            return $this->fetch('payfail');
        }   
        if(Db::name('order')->where(['orderid' => $orderid ,'userid' => $this->user['id'], 'is_pay' => 1])) {
            return $this->fetch();
        } else {
            return $this->fetch('payfail');
        }
    }
}

?>