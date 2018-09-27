<?php
namespace app\index\controller;

use app\index\service\OAuth;
use think\Db;
use app\common\model\User as UserModel;
use app\index\service\HashId;
use app\common\model\UserVips;
class Login extends Home
{
    /**
     * 用户登录页面
     */
    public function login()
    {
        if($this->request->isPost()) {
            $username = input('post.username', null);
            $password = input('post.password', null);
            $where = ['password' => getMd5Pass($password)];
            if(strpos($username,"@") !== false) {
                $where['email'] = $username;
            } else{
                $where['username'] = $username;
            }
            if($user = UserModel::where(['username' => $username,'password' => getMd5Pass($password)])->find()){
                $this->setUser($user);
                return $this->success("登录成功,正在跳转，请稍等...",$this->getAuthBackUrl());
            } else {
                return $this->error('用户名或密码错误,请重试！');
            }
            
        }else{
            return $this->fetch();
        }
    }
    /**
     * 用户注册页面
     */
    public function register()
    {
        if($this->request->isPost()) {
             $data = $this->request->post();
             
             $result = $this->validate($data, 'User');
             if ($result !== true) {
                return $this->error($result);
            } else {
                $data['password'] = getMd5Pass($data['password']);
                if($user=UserModel::create($data)) {
                    $this->setUser($user);
                    $this->awardUser($user->id);//添加奖励
                    return $this->success("注册成功,正在跳转~~",$this->getAuthBackUrl());
                } else {
                    return $this->error('注册失败，请重试！');
                }
            }
        } else {
            //注册推广的用户
            $this->generalize();
            $this->assign('title','注册');
            return $this->fetch();
        }
    }
    
    /**
     * 判断是否有推广用户
     */
    private function generalize()
    {
        $id = input('id',null);
        if($id) {
            $id = HashId::decode_hex($id);
            if($id) {
                session('generalizeid',$id);
            }
        }
    }
    
    /**
     * 用户第一次登录注册奖励活动
     * @param int 当前注册用户的id
     */
    private function awardUser($userid)
    {
        $generalizeid = session('generalizeid');
        if($generalizeid) {
            Db::startTrans();
            try{
                if(!UserVips::addVip("5 days", $generalizeid)){
                    Db::rollback();
                } else {
                    Db::name('')
                }
                if(!UserVips::addVip("1 day", $userid)){
                    Db::rollback();
                }
                
                Db::commit();
                
            }catch (\Exception $e) {
                Db::rollback();
                
            }
            session('generalizeid',null);
        }
    }
    
    /**
     * 用户授权QQ登录
     */
    public function authlogin()
    {
        $type = input('type',null);
        if(empty($type))
            return $this->error('请选择登录方式',url('Login/login'));
       $url = OAuth::getAuthUrl($type);
       header('location:' . $url);
       exit;
    }
    /**
     * 登录回调的Url
     */
    public function callback()
    {
        $class = input('type');
        $userinfo = OAuth::authCallBack($class);
        if(false!==$userinfo) {
            $userid = $userinfo['userid'];
            if(empty($userid)) {//用户第一次登录
                $userid = $this->userfirstlogin($userinfo);
                
                if(false === $userid) {
                    $this->awardUser($userid);//添加奖励
                    return $this->success("登录失败，请重试！",url('login/login'));
                }
            }
            $user = UserModel::getUserInfo($userid);
            $this->setUser($user);
            return $this->redirect($this->getAuthBackUrl());
        } else {
          return $this->error('获取个人信息失败，请重试！',url('Login/login'));  
        }
    }
    /**
     * 用户第一次登录
     */
    private function userfirstlogin($user)
    {
        Db::startTrans();
        try{
            $userid = Db::name('users')->insertGetId([
                    'last_login_ip' => get_client_ip(), 
                    'create_time'   => time(),
                    'update_time'   =>time(),
                    'head_img'      => $user['head_img'],
                    'nickname'      =>empty($user['nickname'])?'拍蒜用户':$user['nickname'],
                    'username'      =>'paike'.date('Ymdhis'),
                ]);
            Db::name('user_qq')->where(['id' => $user['id']])->update(['userid'=>$userid, 'update_time'=>time()]);
            Db::commit();
            return $userid;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
        
    }
    /**
     * 用户绑定帐号密码
     */
    public function bindUserName($class = null, $id = null)
    {
       
        $class = $class ? $class :input('class');
        $id    = $id ? $id :input('id');
        
        if($this->request->isPost()) {
            $username = input('username', null);
            $password = input('password', null);
            if(empty($class) || empty($id)) {
                return $this->error('注册失败！');
            }
            if(empty($username) || empty($password)) {
                return $this->error('请输入用户名和密码！');
            }
            Db::startTrans();
            try{
                $user = UserModel::create(['username' => $username, 'password' =>getMd5Pass($password), 'last_login_ip' => get_client_ip()]);
                OAuth::addUserId($class, $user['id'], ['openid' => $id]);
                Db::commit();
            }catch (\Exception $e) {
                Db::rollback();
                return $this->error('注册失败,请重试！');
            }
            $this->setUser($user);
            return $this->redirect($this->getAuthBackUrl()); //注册成功跳转首页
        } else {
            $this->assign('class', $class);
            $this->assign('id', $id);
            return $this->fetch('login/bindusername.html');
        }
    }
    /**
     *获取授权成功的回调页面
     */
    private function getAuthBackUrl()
    {
        $url = session('backurl');
        session('backurl', null);
        if(empty($url))
            $url = url('index/index');
        return $url;
    }
    /**
     * 验证邮件地址是否已经存在
     */
    public function emailisexits(){
        if($this->request->isPost()) {
            $email = input("email");
            if(UserModel::where(['email'=>$email])->count()){
                //return $this->error("此邮箱已注册，请使用其他邮箱注册！");
                return false;
            } else {
                //return $this->success('恭喜你,此邮箱可以使用！');
                return true;
            }
        }
    }
    /**
     * 验证邮件地址是否已经存在
     */
    public function usernameisexits(){
        if($this->request->isPost()) {
            $username = input("username");
            if(UserModel::where(['username'=>$username])->count()){
                //return $this->error("此用户名义存在！");
                return false;
            } else {
                //return $this->success('恭喜你,用户名可以使用！');
                return true;
            }
        }
    }
}

?>