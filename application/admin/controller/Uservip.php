<?php
namespace app\admin\controller;
use app\common\model\UserVips as UVModel;
use app\common\builder\ZBuilder;
use app\common\model\User;
use app\common\model\UserVcLog as UVLModel;
class Uservip extends Admin
{
    public function index(){
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();
        $userids = null;
        if(isset($map['username']) || isset($map['nickname']) || isset($map['email'])) {
            $where =[];
            if(isset($map['username']) && $map['username']) {
                $where['username'] = $map['username'];
                unset($map['username']);
            }
            if(isset($map['nickname']) && $map['nickname']) {
                $where['nickname'] = $map['nickname'];
                unset($map['nickname']);
            }
            if(isset($map['email']) && $map['email']) {
                $where['email'] = $map['email'];
                unset($map['email']);
            }
            $userids = User::where($where)->column('id');
            $map['userid'] = $userids ? ['in',$userids] : null;
            
        }
        if(isset($map['username|nickname|email'])) {
            $userids = User::where($map)->column('id');
            $map['userid'] =$userids ? ['in',$userids] : null;
            unset($map['username|nickname|email']);
        }
        
        
        // 数据列表
        $data_list = UVModel::where($map)->order('create_time,id desc')->paginate();
        
        foreach ($data_list as $k => &$v) {
            $info = User::getUserInfo($v['userid']);
            foreach (['username','email','head_img','nickname'] as $key => $val) {
                $v[$val] = $info[$val];
            }
        }
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        ->setPageTitle('VIP用户管理') // 设置页面标题
        ->setTableName('user_vips') // 设置数据表名
        ->setSearch([ 'username' => '用户名','nickname'=>'昵称', 'email' => '邮箱']) // 设置搜索参数
        ->addColumns([ // 批量添加列
            ['__INDEX__', '序号'],
            //['userid', '用户ID'],
            ['head_img','头像','img_url'],
            ['nickname','昵称'],
            ['username', '用户名'],
            ['email', '邮箱'],
            ['start_time','VIP开始时间','datetime'],
            ['end_time','VIP结束时间','datetime'],
            ['create_time', '创建时间', 'datetime'],
            ['right_button', '操作', 'btn']
        ])
        ->setRowList($data_list) // 设置表格数据
        ->addRightButton('custom',[
                'title' => '记录',
                'icon'  => 'fa fa-fw fa-key',
                'href'  => url('seerecord', ['id' => '__userid__'])])
        ->hideCheckbox()
        ->fetch(); // 渲染页面
    }
    /**
     * 查看用户的vip记录
     * @param int $id 用户id
     */
    public function seerecord($id)
    {
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $map =  $this->getMap();
        $map['vc_type'] = 1;
        $order = $this->getOrder(['create_time' => 'desc']);
        $data_list = UVLModel::where($map)->order($order)->paginate();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        ->setPageTitle('VIP用户充值记录') // 设置页面标题
        ->setTableName('user_vc_log') // 设置数据表名
        ->setSearch(['remark' => '关键字']) // 设置搜索参数
        ->addColumns([ // 批量添加列
            ['__INDEX__', '序号'],
            ['remark','备注'],
            ['type','类型','callback',function($val){
               return $val==1?'添加':'减少'; 
            }],
            ['start_time','开始时间','datetime'],
            ['end_time','结束时间','datetime'],
            ['create_time','添加时间','datetime']
        ])
        ->setRowList($data_list) // 设置表格数据
        ->hideCheckbox()
        ->fetch(); // 渲染页面
    }
}

?>