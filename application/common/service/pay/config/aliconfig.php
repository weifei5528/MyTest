<?php
/**
 * @author: helei
 * @createTime: 2016-07-15 17:19
 * @description:
 */

// 一下配置均为本人的沙箱环境，贡献出来，大家测试

// 个人沙箱帐号：
/*
 * 商家账号   naacvg9185@sandbox.com
 * appId     2016073100130857
 */

/*
 * !!!作为一个良心人，别乱改测试账号资料
 * 买家账号    aaqlmq0729@sandbox.com
 * 登录密码    111111
 * 支付密码    111111
 */

return [
    'use_sandbox'               => true,// 是否使用沙盒模式

    //'app_id'                    => '2016080400161803',
    'app_id'                    =>'2016080700186622',
    'sign_type'                 => 'RSA2',// RSA  RSA2

    // 可以填写文件路径，或者密钥字符串  当前字符串是 rsa2 的支付宝公钥(开放平台获取)
    'ali_public_key'            => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAq/o6Jq9kPMsNPnJlY/m0R9/8CEDR2efVaIw0D1oGgEnxNzx7C3dnbAKPHaqy/Y0V3PDVhwdm+eDmyQC8U9EhTkelAOM30sHF4FXdNC3QZlXfP4VRzLHwdThxBtg64oEwA2H///L7BjB4LciizfZAesKE9lIbFN8/fk34gAr6TkPd9j7f7FcSug3htugtLbOgElC9z6G3gkmp5/7jM0gw5ZRqsuSOMfPBb6Hj3zSjWCjzjkjhRea47jwtVihii2rIlSIhl60g/kzTEV07zh8VnBRJkPaAr+LhmjTjAMwONEhpbYxnhDtfRaKVDUuZ66Ld7/v1WAlKlBl4pJGE/KuLXQIDAQAB',

    // 可以填写文件路径，或者密钥字符串  我的沙箱模式，rsa与rsa2的私钥相同，为了方便测试
    'rsa_private_key'           => 'MIIEowIBAAKCAQEAq/o6Jq9kPMsNPnJlY/m0R9/8CEDR2efVaIw0D1oGgEnxNzx7C3dnbAKPHaqy/Y0V3PDVhwdm+eDmyQC8U9EhTkelAOM30sHF4FXdNC3QZlXfP4VRzLHwdThxBtg64oEwA2H///L7BjB4LciizfZAesKE9lIbFN8/fk34gAr6TkPd9j7f7FcSug3htugtLbOgElC9z6G3gkmp5/7jM0gw5ZRqsuSOMfPBb6Hj3zSjWCjzjkjhRea47jwtVihii2rIlSIhl60g/kzTEV07zh8VnBRJkPaAr+LhmjTjAMwONEhpbYxnhDtfRaKVDUuZ66Ld7/v1WAlKlBl4pJGE/KuLXQIDAQABAoIBABayMkRyty7SuU+5dyoHhURLCg7oxwkT8HUYs7Im9l6t43ifsblkq7B0cgC/S1ns6aq72eI1FuD5TlvOjgwduGCFUgBY/EADNKMTkdrWBP/Ma2cNqCr0fMBrj3cQyEymMfjwUQGtRnWdpe6c4Od/jSjmTPfZBBvwDogTsCztxfTYBpXidsAW/vyMSPtf/kSgixQfI2WAQrLjDmcQpo34FjTpAp/CSbgCcujdvUmnCCoQMVjw++U4UDexWPCYcFdHLCkzgY2Y1tsd2GbwWkRgXzq+ypBd5zhVmxPptSnRIBL3lEbr1+efhMz5lrV4r6+IWBf0Qm5bWQKzSFefcM/xYh0CgYEA3edTChQDJ5SpffouF05tde+MyUmomdQhKa0Fy+uyuoQOpz3KqcmieXFHKpRIJlJGb6XHERgPho0B8ZDJEPigMyXRFLJUoYtuEEKhifvMMKN1J5K7SewBzAneXKtpDQ3covEf/9bl1/oXU9sDx8wm3762thotWgwJVgEkOJ029EMCgYEAxmcJD2X3LulE2efaY2cUNeV9US2hf2GuolMYLlGZThjZ3hLV8ubvqrI8lnF7R0TNIf3O89bur289lsn6gcxYhs1Neee7SQSQdPCDPl1cK6ZOhq77Q14BXUQvxxSG+GLXp922DUxTiGe9dq396G8aruNx3uEaYdWS16NJDnhuV98CgYA32PZPPQYmOgYGTKa4+uYVnnqCVmV2garvADrLPcC3rHC7qpOM89BOXDIbB0K5Uk+j3cciCDowFOD+WQAho1E+TjA200L1fU/wC7T4M0UKoLjlgspcKmexYHJ0RDemL2zuHV1+WCl0l96zHhXvfPPzr93wKtprJUhcOuFQ1lB0XQKBgQC0icXg0TRg57vTXfuIQKBPewjR+effdg/nfh2e6HnMayi1wAYYBrVIavBfJdy8OYtAxyivEoIX+e3dbOYbfst/vwolSA1QoGqjPsju0x2wSqFiRZA2+S/cotOnILLBC5CaHFIxP6czlNL/T8yg2Zk6U5Wm7seHB0WcG+W62TPvmQKBgHr5D7UcNaTwl108PFCzArnVR/rwH8IJS3PFR29XqmVn6U0T2b9iZDilcxCaXPqfFPer6pkGsqRSmAYbYZnQyyelhkGfCpucJkPnnckFYA6IgrZCPY1qGeZnXKyKkvijdCJLhho/NkX5QdSec3uQHSj02nUq5+upGpYbhSJMWTKC',

    'limit_pay'                 => [
//         'balance',// 余额
//         'moneyFund',// 余额宝
//         'debitCardExpress',// 	借记卡快捷
//         'creditCard',//信用卡
        'creditCardExpress',// 信用卡快捷
        'creditCardCartoon',//信用卡卡通
        'credit_group',// 信用支付类型（包含信用卡卡通、信用卡快捷、花呗、花呗分期）
    ],// 用户不可用指定渠道支付当有多个渠道时用“,”分隔

    // 与业务相关参数
    'notify_url'                => 'http://image.xu96.com/index/index/paynotify.html',
    'return_url'                => 'http://image.xu96.com/index/order/paysuccess.html',

    'return_raw'                => true,// 在处理回调时，是否直接返回原始数据，默认为 true
];
