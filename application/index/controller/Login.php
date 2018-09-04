<?php
namespace app\index\controller;

use app\index\service\OAuth;
use think\Db;
use app\common\model\User;
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
            if($user = User::where(['username' => $username,'password' => getMd5Pass($password)])){
                $this->setUser($user);
                return $this->redirect($this->getAuthBackUrl());
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
                if($user=User::create($data)) {
                    $this->setUser($user);
                    return $this->redirect($this->getAuthBackUrl());
                } else {
                    return $this->error('注册失败，请重试！');
                }
            }
        } else {
            return $this->fetch();
        }
    }
    
    /**
     * 用户授权QQ登录
     */
    public function authlogin($backurl = null)
    {
        $type = input('type',null);
        if(empty($type))
            return $this->error('请选择登录方式',url('Login/login'));
       OAuth::getAuthUrl($type);
    }
    /**
     * 登录回调的Url
     */
    public function callback()
    {
        $class = input('type');
        $userinfo = OAuth::authCallBack($class);
        if(false!==$userinfo) {
            if(empty($userinfo['userid'])) {
                return $this->bindUserName($class, $userinfo['openid']);
            }
            $user = User::getUserInfo($userinfo['userid']);
            $this->setUser($user);
            return $this->redirect($this->getAuthBackUrl());
        } else {
          return $this->error('获取个人信息失败，请重试！',url('Login/login'));  
        }
    }
    /**
     * 用户绑定帐号密码
     */
    public function bindUserName($class = null, $id = null)
    {
       
        $class = $class ? $class :input('class');
        $id    = $id ? $id :input('id');
        
        if(!$this->request->isPost()) {
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
                $user = User::create(['username' => $username, 'password' =>getMd5Pass($password), 'last_login_ip' => get_client_ip()]);
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
    
}

?>