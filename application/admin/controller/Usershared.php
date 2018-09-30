<?php
namespace app\admin\controller;

use app\common\model\UserSharedApply as USAModel;

use app\common\builder\ZBuilder;

use think\Db;
use app\common\model\User as UserModel;
use app\common\model\UserSharedApply;
use app\common\model\UserVips;
use app\common\model\UserVcLog;

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
        ->addRightButton('edit')
        ->hideCheckbox()
        ->fetch(); // 渲染页面
    }
    /**
     * 审核
     * @param int $id user_shared_apply 表id
     */
    public function edit($id = '')
    {
        if ($id === 0) $this->error('参数错误');
        
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
        
            // 验证
            $result = $this->validate($data, 'UserSharedApply.update');
            if(true !== $result) $this->error($result);
                    
            if($data['status'] ==  1) {
                $info = UserSharedApply::get($id);
                $res = UserVips::addVip('15 days',$info['userid'] ,'群推广用户增加15天VIP');
                if($res) {
                    if(UserSharedApply::update($data)){
                        return $this->success("操作成功！",url('index'));
                    } else {
                        return $this->error("操作失败！");
                    }
                }
            } elseif($data['status'] == 2) {
                if(UserSharedApply::update($data)) {
                    return $this->success("操作成功！",url('index'));
                } else {
                    return $this->error("操作失败，请重试！");
                }
            } else {
                return $this->error("没有修改");
            }
            
        
          
        }
        
        // 获取数据
        $info = USAModel::get($id);
        $userinfo = UserModel::get($info['userid']);
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
        ->setPageTitle('审核')
        ->addHidden('id')
        ->addFormItems([
            ['static','nickname','用户昵称','',$userinfo['nickname']],
            ['gallery','att_id','审核图片'],
            ['radio','status','审核状态','',['审核','通过','拒绝']],
            ['textarea','remark','拒绝原因'],
        ])
        ->setTrigger('status',2,'remark')
        ->setFormData($info)
        ->fetch();
    }
    
}

?>