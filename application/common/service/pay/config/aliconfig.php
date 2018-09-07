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

    'app_id'                    => '2016080400161803',
    'sign_type'                 => 'RSA',// RSA  RSA2

    // 可以填写文件路径，或者密钥字符串  当前字符串是 rsa2 的支付宝公钥(开放平台获取)
    'ali_public_key'            => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDIgHnOn7LLILlKETd6BFRJ0GqgS2Y3mn1wMQmyh9zEyWlz5p1zrahRahbXAfCfSqshSNfqOmAQzSHRVjCqjsAw1jyqrXaPdKBmr90DIpIxmIyKXv4GGAkPyJ/6FTFY99uhpiq0qadD/uSzQsefWo0aTvP/65zi3eof7TcZ32oWpwIDAQAB',

    // 可以填写文件路径，或者密钥字符串  我的沙箱模式，rsa与rsa2的私钥相同，为了方便测试
    'rsa_private_key'           => 'MIICWwIBAAKBgQC3ISyB34CWZXigH2Hq5C8kBLuW7d+D6yhgnFax7cK/Mp+4IFhzjYt+sgtzNkvAp2GmYm+QqznpfW0UFUQ9b6DUsuDQXOickUW+9tHHnZPJp9J2wAdVDPf6zQap+rlAIBN+FJI1RMhq8St93X/u2dA3jhGOSQbg0A/d9xMZflRdSQIDAQABAoGAE2DgS9Hx0nhkXlVT0D5bOq2BiEQdreD5gdepWOS3AfKCckKB+aBVzY9bpNJvC7DqpWevNJjZ5PpPy5tAgFvKoelR7PuIEJCSl2zgHJr+YWzdgQJZl1yiOfGI4WLWU+GLiYeT3J9HSlE3BhgZdgFfypfqUcqqsiBo9UDJBBEgu90CQQDtqFSPyh2vYNKnNTH6tRvWdq7tZApovOH8MGNprZqULRZavrSW2si2NMO+WU75p77kWCeUZ3Kw5J7avK+IMvozAkEAxUN5DX7pd5NXwEEFAKHnGX3R8VQMIzGpmiL8fX3VK6Y1aFeTBUU+/H4LCHcOg3lZ/4Qbn2ENg2y+qKN1lYiGkwJAeoaacjeF7nFAqawnRFYzL/KoZQN6ylz3NYnM9yLl2xcTu10uxceuSyIQ+QYaTWRKand4kG51FvYHYEZ/SPzfOQJBALFQRhaQdpFqWx1QFIwN++oZA8aWEtbrxBTtz+GJYzz6CNXCqj+5j7VsIsS4J86cHP6lpCKuHXR+Ih751y7TaosCPyFVhlg93ZfbN5iV7G0i9HOWHRXDH4EQNg0FHaoofzhD/71kPhVfkUdMH72sMovarum6o6CY0P9Pdmi07P4P2w==',

    'limit_pay'                 => [
        'balance',// 余额
        'moneyFund',// 余额宝
        'debitCardExpress',// 	借记卡快捷
        'creditCard',//信用卡
        'creditCardExpress',// 信用卡快捷
        'creditCardCartoon',//信用卡卡通
        'credit_group',// 信用支付类型（包含信用卡卡通、信用卡快捷、花呗、花呗分期）
    ],// 用户不可用指定渠道支付当有多个渠道时用“,”分隔

    // 与业务相关参数
    'notify_url'                => 'http://feiyueweb.com/',
    'return_url'                => 'http://feiyueweb.com/',

    'return_raw'                => true,// 在处理回调时，是否直接返回原始数据，默认为 true
];
