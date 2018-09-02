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
use app\common\service\Translate;
/**
 * 前台首页控制器
 * @package app\index\controller
 */
class Index extends Home
{
    public function index()
    {
//         set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $pixabay = new Pixabay();
        
        for ($i = 2;$i < 3; $i++) {
//            ob_flush();
//            flush();
            $res=$pixabay->getPublicImageList($i,['category'=>'nature']);


            if(empty($res))
                break;


            $image = new Image();
            foreach ($res['hits'] as $img) {
                $this->downloadImage( $img['largeImageURL']);
//                try{
//                    $image->index(
//                        $img['largeImageURL'],
//                        [
//                            'from'=>'pixabay',
//                            'tags'=>Translate::getInstance()->translateStr($img['tags']),
//                            'remark'=>json_encode($img),
//                        ]);
//                }catch (\Exception $e){
//                    continue;
//                }
               
            }
        }
        
    
//        if (config('home_default_module') != 'index') {
//            $this->redirect(config('home_default_module'). '/index/index');
//        }
//        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> '.config("dolphin.product_name").' '.config("dolphin.product_version").'<br/><span style="font-size:30px">极速 · 极简 · 极致</span></p></div>';
    }

    public function downloadImage($url, $path='uploads/images/')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);

        $this->saveAsImage($url, $file, $path);
    }

    private function saveAsImage($url, $file, $path)
    {
        $filename = pathinfo($url, PATHINFO_BASENAME);
        $resource = fopen($path . $filename, 'a');
        fwrite($resource, $file);
        fclose($resource);
    }

}
