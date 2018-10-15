<?php
namespace app\admin\controller;

use app\common\model\UserDirs as USDModel;

use app\common\builder\ZBuilder;

Use app\common\model\UserDirImages as UDIModel;

use app\common\model\User as UserModel;
class Userdirs extends Admin
{
    const TYPE = 1;
    /**
     * 用户收藏文件夹列表
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $map['type'] = self::TYPE;
        // 数据列表
        $data_list = USDModel::where($map)->order('create_time,id desc')->paginate();
        
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        ->setPageTitle('文件夹列表') // 设置页面标题
        ->setTableName('user_dirs') // 设置数据表名
        ->setSearch([ 'name' => '名称']) // 设置搜索参数
        ->addColumns([ // 批量添加列
            ['id', 'ID'],
            ['cover','封面','picture'],
            ['userid','用户昵称','callback',function($val){
                return UserModel::where('id','=',$val)->value('nickname');
            }],
            ['name', '文件夹名称'],
            ['auth', '权限','callback',function($val){
                return $val ? '私有' : '公开';
            }],
            ['count', '图片张数'],
            ['browse', '浏览次数'],
            ['loves', '喜爱人数'],
            ['create_time', '创建时间', 'datetime'],
            ['top','推荐','switch',['取消','推荐']],
            ['right_button', '操作', 'btn']
        ])
        ->addRightButton('custom',[
            'title' => '查看图片',
            'icon'  => 'fa fa-fw fa-list',
            'href'  => url('imglist', ['id' => '__id__'])])
        ->setRowList($data_list) // 设置表格数据
        ->addFilterList('auth',['公开','私有'])
        ->hideCheckbox()
        ->fetch(); // 渲染页面
    }
    
    /**
     * 文件夹图片列表
     */
    public function imglist($id)
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $map['dir'] = $id;
        $orde = $this->getOrder('create_time desc');
        // 数据列表
        $data_list = UDIModel::where($map)->order($orde)->paginate();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        ->setPageTitle('收藏夹图片') // 设置页面标题
        ->setTableName('user_dir_image') // 设置数据表名
        ->hideCheckbox()
        //->setSearch() // 设置搜索参数
        ->addColumns([ // 批量添加列
            ['id', 'ID'],
            ['att_id','图片','picture'],
            ['create_time', '创建时间', 'datetime'],
           // ['right_button', '操作', 'btn']
            ])
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染页面
    }
    /**
     * 推荐
     */
    public function quickEdit($record = [])
    {
        $field           = input('post.name', '');
        $value           = input('post.value', '');
        $id              = input('post.pk', '');
           if($val=USDModel::where(['id' => $id])->value('auth')){
                return $this->error("此收藏夹不公开，不能操作！");
           } else {
              return parent::quickEdit([]);
           
           } 
      
        
    }
}

?>