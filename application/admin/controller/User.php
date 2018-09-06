<?php
namespace app\admin\controller;

use app\common\builder\ZBuilder;
use app\common\model\User as UserModel;
/**
 * 用户管理
 * @author Administrator
 *
 */
class User extends Admin
{
    /**
     * 用户列表
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        // 数据列表
        $data_list = UserModel::where($map)->order('create_time,id desc')->paginate();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        ->setPageTitle('用户管理') // 设置页面标题
        ->setTableName('users') // 设置数据表名
        ->setSearch(['id' => 'ID', 'username' => '用户名', 'email' => '邮箱','phone'=>'手机']) // 设置搜索参数
        ->addColumns([ // 批量添加列
            ['id', 'ID'],
            ['head_img','头像','img_url'],
            ['username', '用户名'],
            ['email', '邮箱'],
            ['phone', '手机号'],
            ['create_time', '创建时间', 'datetime'],
            ['last_login_time','最后登录时间'],
            ['status', '状态', 'switch'],
            //['right_button', '操作', 'btn']
        ])
        ->addTopButtons('enable,disable') // 批量添加顶部按钮
        ->setRowList($data_list) // 设置表格数据
        ->fetch(); // 渲染页面
    }
}

?>