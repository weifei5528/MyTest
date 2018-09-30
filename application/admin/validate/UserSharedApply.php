<?php
namespace app\admin\validate;

use think\Validate;
class UserSharedApply extends Validate
{  
    //定义验证规则
    protected $rule = [
        'remark'=>'requireIf:status,2'
    
    ];
    
    //定义验证提示
    protected $message = [
        'remark' => '请填写拒绝原因',
        
    ];
    // 定义场景，供快捷编辑时验证
    protected $scene = [
        'update'  => ['remark'],
    ];
}

?>