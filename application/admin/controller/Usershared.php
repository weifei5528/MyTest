<?php
namespace app\admin\controller;

use app\common\model\UserSharedApply as USAModel;

use app\common\builder\ZBuilder;

use think\Db;

class Usershared extends Admin
{
    /**
     * 审核列表
     */
    public function index()
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $order = $this->getOrder(['create_time' => 'desc']);
        // 数据列表
        $data_list = USAModel::where($map)->order($order)->paginate();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        ->setPageTitle('用户管理') // 设置页面标题
        ->setTableName('user_shared_apply') // 设置数据表名
        ->setSearch() // 设置搜索参数
        ->addColumns([ // 批量添加列
            ['__INDEX__','序号'],
            ['userid','用户ID'],
            ['nickname', '用户昵称','callback',function($data){
                return Db::name('users')->where(['id'=>$data['userid']])->value("nickname");
            },'__data__'],
            ['att_id','分享图片','picture'],
            
            ['status', '审核', 'callback',function($val){
                switch ($val){
                    case 1:
                        return "<font color='green'>通过</font>";
                        break;
                    case 2:
                        return "<font color='red'>拒绝</font>";
                        break;
                    default:
                        return "申请审核";
                        break;
                }
            }],
            ['remark','拒绝原因'],
            ['create_time', '创建时间', 'datetime'],
            ['update_time','最后更新时间','datetime'],
            ['right_button', '操作', 'btn']
        ])
        ->addFilter('status' ,['status'=>["申请",'通过','拒绝']]) // 添加筛选
        ->setRowList($data_list) // 设置表格数据
        ->hideCheckbox()
        ->fetch(); // 渲染页面
    }
    /**
     * 审核
     * @param int $id user_shared_apply 表id
     */
    public function edit($id = '')
    {
        
    }
}

?>