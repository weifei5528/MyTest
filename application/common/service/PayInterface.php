<?php
namespace app\common\service;

interface PayInterface
{
    /**
     * 网站支付接口
     * @param unknown $params
     */
    public function webPayMoney($params=[]);
    public function notice($data = []);
}

?>