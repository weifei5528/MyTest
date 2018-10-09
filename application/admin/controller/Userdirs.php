<?php
namespace app\admin\controller;

use app\common\model\UserDirs as USDModel;

use app\common\builder\ZBuilder;

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
            ['right_button', '操作', 'btn']
        ])
        ->setRowList($data_list) // 设置表格数据
        ->addFilter('auth',['公开','私有'])
        ->fetch(); // 渲染页面
    }
    /**
     * 文件夹图片列表
     */
    public function imglist($id)
    {
        
    }
}

?>