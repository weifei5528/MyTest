<?php
namespace app\common\service\pay;

use app\common\service\PayInterface;
use Yurun\PaySDK\Alipay\Params\Pay\Request;
use Yurun\PaySDK\Alipay\SDK;
use Yurun\PaySDK\Alipay\Params\PublicParams;
class Alipay implements PayInterface
{
    //支付的网关
    const API_HOST      = 'https://openapi.alipaydev.com/gateway.do';
    //商户的商户号
    const APP_ID        = '2016080700186622';
    //md5加密的方式
    const MD5_KEY     = '1shf5fcfevbtp5pglli2haqxm9yih0uu';
    //公钥
    const PUBLIC_KEY    = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3Srpi62nEeOTFI2yeuNihg0S+WmC5jc12OGlKsXYyJxfKT4S7DUh9O5MZDJBHrSzNEw1q54Qo/4psu3GETdhmU4j59X28J/wBBVVm80+kP9d1tUn3wW4IE7MmWnMwP8h6ulPK5/j5GFPnrV9Z52SqWedGafuCvnPIstekDYHJUFpsWOODua15DSzSNzgW+BxeXVlDrdpg2gLWknEc87E14FwUtnWan16ltjujoaAtapoNBKvX4P4JGXImaw7qmOv6hu9oV4WD6zSbMqadjp1+AdlvGhlgCRIcJoMLfOEXHpRCtGnCJu6Lm+CPTeRoDrGmhfPmiXKIXxEQrrwVO7TvQIDAQAB';
    //私钥 
    const PRIVATE_KEY   = 'MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDdKumLracR45MU
                            jbJ642KGDRL5aYLmNzXY4aUqxdjInF8pPhLsNSH07kxkMkEetLM0TDWrnhCj/imy
                            7cYRN2GZTiPn1fbwn/AEFVWbzT6Q/13W1SffBbggTsyZaczA/yHq6U8rn+PkYU+e
                            tX1nnZKpZ50Zp+4K+c8iy16QNgclQWmxY44O5rXkNLNI3OBb4HF5dWUOt2mDaAta
                            ScRzzsTXgXBS2dZqfXqW2O6OhoC1qmg0Eq9fg/gkZciZrDuqY6/qG72hXhYPrNJs
                            ypp2OnX4B2W8aGWAJEhwmgwt84RcelEK0acIm7oub4I9N5GgOsaaF8+aJcohfERC
                            uvBU7tO9AgMBAAECggEBANQK3Wjmb6RuDMiK5dB+Cyq8hDGN4Es6Ka0rAYDVuPb1
                            PSM8eUXv1wOOKL1zlQ2Mb7e3TUmhvRCNkIsU/m3pK/Cggzo14JBotuQjVDWQ/Ohl
                            fSGIsbZYNGT9R5naXs22jl07nIUVwZdnWL+v/3CFGWTDi1Jat5XcVaRlWN9ofUdP
                            /HCuEtBn6kKNHWM7yGMSczBbA25K3OKjkG9JWFX+297ob63pl4k3jkBL+/GpQJAQ
                            XBKuhyRWMKkWdACBW9S17C3hiMrPuF46l8a5SXA+Nbp8g37zm0md+Al+HrE2GYJu
                            2j2rswe6bNvRPFyU7zvAveH5hMXl0h6YkyUZpvU9FJ0CgYEA7yoJlcEUnYGKYasG
                            lOpNgucj9rH3c0OQxiyolQI/B1p2qMVjrBH5UMrZF8Ky724ORWuhlBaYAZ0KBhVX
                            HfYIzgKHiGMrn+BMiVzrXPDheBaVCq6xJwq8nfVHpUcv3xD22sVkas7ki/up9ELa
                            +jGGlMtqGTxbX0weapFuuiuVthcCgYEA7LyPQnl6UvrgES8srveLpWG7OJF0JZH7
                            GnOlsWqMgG2QB+mqcNon4fnRYgtzJPagIdd2FxSq5pCFY/1L98bSGcrfZXPgvit8
                            PkBps01s4e2cjqkmTdvIX+/Ewij/LSI/Y9pvn78W+ABIXf7ra7EuHXtahC+JctZ2
                            vcO+K1YpPUsCgYAJChC0rUiHL3c4e8tS44wsb2oHj/BnVd1p8BQrFZumPoAPnu5G
                            eaNvr0sHP9+ddw5pB0ljHHuATBwt4K6bPkpU5vmSaRUkBMk/w9hNefk7nbbiYXnm
                            nNxGKBgeIhOoHa8G08EY3Fr9A3UH+2LlY+vPQeTvsT1O28SmiHqj5LPS3wKBgQCO
                            BtoT0Xl3yxceeCTgm5bmE2oVF/6Mg7YYOoWPmRLOAe1FMgSVS+xdgFkD70aXSHbt
                            lqw8UKPvS4kbYd1vu5JU8wdvgEO3E7OoTVCcx7ipGrqwQ/68+zyNgfWTXrEozMEn
                            EOei+Su4gcLo0YU/yL6X5Wd6omJdyRjX5FV0/m4jXQKBgQCVnQfbzXjciRcJtqQP
                            6kWOCDx0vGDCRT5XQyx3WjZFLHPCaJBudQb4MaRnZGWlILNRbkngalgYMl2awPxs
                            zvo+/puh172shqga4wm1nhrg07Wv3sNiLQ9IjCAML7U7jdgbkz1CQt2vqKF6eh9u
                            YSOj3dtGDS+k65n7YY2t1i974w==';
    //通知的地址
    const NOTICE_URL    = '';
    protected $alipay = null;
    public function __construct()
    {
        $params = new PublicParams();
        $params->appID = static::APP_ID;
        $params->md5Key = static::MD5_KEY;
        $params->sign_type = 'MD5';
        $params->apiDomain =static::API_HOST;
        $params->appPrivateKey = static::PRIVATE_KEY;
        $params->appPrivateKeyFile =null;
        $this->alipay = new SDK($params);
    }
    
    
 /* (non-PHPdoc)
     * @see \app\common\service\PayInterface::notice()
     */
    public function notice()
    {
        // TODO Auto-generated method stub
        
    }

 /* (non-PHPdoc)
     * @see \app\common\service\PayInterface::webPayMoney()
     */
    public function webPayMoney($params = array())
    {
        // TODO Auto-generated method stub
        $request = new Request();
        $request->notify_url = $_SERVER['SERVER_NAME'].url('index/index/notice');
        $request->return_url = $_SERVER['SERVER_NAME'].url('index/index/payreturn');
        
//         $request->businessParams->out_trade_no = $params['orderid'];
//         $request->businessParams->total_fee = $params['total_money'];
//         $request->businessParams->goods_type = 0;//虚拟类商品
//         $request->businessParams->subject = $params['title'];
//         $request->businessParams->enable_paymethod = "";
//        // $request->businessParams->extra_common_param ="alipay_web";
//         $request->businessParams->seller_id = "2088102170319862";
        $request->businessParams->seller_id = 2088102170319862; // 卖家支付宝用户号
        $request->businessParams->out_trade_no = 'test' . mt_rand(10000000,99999999); // 商户订单号
        $request->businessParams->total_fee = 0.01; // 价格
        $request->businessParams->subject = '测试商品'; // 商品标题
        $request->businessParams->show_url = 'http://www.yurunsoft.com'; // 用户付款中途退出返回商户网站的地址。
        return $this->alipay->redirectExecute($request);
        
        
    }

    
}

?>