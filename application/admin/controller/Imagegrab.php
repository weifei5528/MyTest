<?php
namespace app\admin\controller;

use app\common\builder\ZBuilder;

use think\Db;
class Imagegrab extends Admin
{
    
    public function index()
    {
        $data_list = Db::name('grab_web')->order(['id'=>'asc'])->paginate();
        $btn_access = [
            'title' => '抓取',
            'icon'  => 'fa fa-fw fa-key',
            'href'  => url('grab', ['id' => '__id__'])
        ];
        return ZBuilder::make('table')
        ->setTableName('grab_web')
        ->addColumns([
            ['id', 'ID'],
            ['name', '网站名称'],
            ['page_num', '当前抓取的页数'],
            ['create_time', '创建时间', 'datetime'],
            ['update_time', '最后更新时间', 'datetime'],
            ['right_button', '操作', 'btn'],
        ])
        ->hideCheckbox()
        ->addRightButton('custom', $btn_access) // 添加授权按钮
        ->setRowList($data_list)
        ->fetch();
    }
    public function grab($id)
    {
        $info = Db::name('grab_web')->where(['id' => $id])->find();
        if($this->request->isPost()) {
            set_time_limit(0);
            $name = input('name');
            $pageNum = input('page_num');
            $endpage = input('endpage');
            if(empty($name) || empty($pageNum) || empty($endpage)) {
                return $this->error('缺少必要参数！');
            }
            if($endpage < $pageNum) {
                return $this->error('结束页必须大于等于开始页！');
            }
            $className =  "app\common\service\builder\\".ucfirst($name);
            $class = new $className();
            for ($i = $pageNum; $i <= $endpage ;$i++) {
                $res = $class->getPublicImageList($i);
                
                if(empty($res)) {
                    break;
                }
                foreach ($res as $img) {
                    $class->downThumbFinger($name, $img) ;
                    flush();
                }
            }
            Db::name('grab_web')->where(['id'=>$id,'name'=>$name])->update(['page_num'=>$endpage,'update_time'=>time()]);
            
            return $this->success('抓取成功！');
        }
        
         // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('抓取'.$info['name'])
            ->addHidden('name',$info['name'])
            ->addFormItems([
               // ['static', 'name', '网站名称'],
                ['number', 'page_num', '起始页码','',($info['page_num']+1)],
                ['number','endpage','终止页码','建议不要一次性抓取太多(包括此页)'],
            ])
            
            ->fetch();
    }
}

?>