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



use app\common\service\unsplash\Unsplash;
use app\common\service\builder\Pixabay;
use app\admin\controller\Image;
/**
 * 前台首页控制器
 * @package app\index\controller
 */
class Index extends Home
{
    public function index()
    {
//         $pixabay = new Pixabay();
//         $res=$pixabay->getPublicImageList(1,['key'=>'9949411-643a9c8f6121981ba1efad425','category'=>'nature']);
//         dump($res);exit;
            $image = new Image();
            $image->index("https://pixabay.com/get/ea33b30d2df5083ed1584d05fb1d4f9fe77ee6d418ac104496f5c778a2e5b0be_1280.jpg",['from'=>'pixabay']);
            
//        if (config('home_default_module') != 'index') {
//            $this->redirect(config('home_default_module'). '/index/index');
//        }
//        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> '.config("dolphin.product_name").' '.config("dolphin.product_version").'<br/><span style="font-size:30px">极速 · 极简 · 极致</span></p></div>';
    }

}
