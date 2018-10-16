<?php
namespace app\index\controller;

class About extends Home
{
    /**
     * 授权执照
     */
    public function index()
    {
       $this->assign('title','授权执照');
       $this->setType();
       return $this->fetch();
    }
            
    private function setType()
    {
        $this->assign('menuindex',$this->request->action());
    }
    /**
     * 拍蒜宣言
     */
     public function access()
     {
         $this->assign('title','拍蒜宣言');
         $this->setType();
         return $this->fetch();
     }
     /**
      * 隐私政策
      */
     public function privacy()
     {
         $this->assign('title','隐私政策');
         $this->setType();
         return $this->fetch();
     }
     /**
      * 条款与条件
      */
     public function clause()
     {
         $this->assign('title','条款与条件');
         $this->setType();
         return $this->fetch();
     }
     /**
      * 版权政策
      */
     public function copyright()
     {
         $this->assign('title','版权政策');
         $this->setType();
         return $this->fetch();
     }
}

?>