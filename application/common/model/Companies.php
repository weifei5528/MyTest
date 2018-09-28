<?php
namespace app\common\model;

use think\Model;
class Companies extends Model
{
    protected $autoWriteTimestamp = true;
    /**
     * 
     * @param string $daystr 需要增加的字符串  如： 1 day; 1 year
     * @param int $userid 用户id
     * @return bool 成功返回true 失败返回false
     */
    public static function addVip($daystr,$userid,$remark='VIP充值')
    {
        $time = time();
        $com_info = self::where(['userid' => $userid])->find();
        $vip_end = empty($com_info['end_time']) ? time() : ($com_info['end_time'] > time() ? $com_info['end_time'] : time());
        $end_time = strtotime("+ ".$daystr,$vip_end);
        $end = $end_time;
        //开始时间过期就覆盖
        $start_time ='';
        //开始时间为空
        if(empty($com_info['start_time'])) {
            $start = $time;
            $start_time = $time;
        } else {
            // vip没有过期续费
            if($com_info['end_time'] > $time) {
                $start = $com_info['end_time'];
                $start_time = $com_info['start_time'];
                //vip已过期
            } else {
                $start = $time;
                $start_time = $time;
            }
        }
        //第一次充值
        if(empty($com_info)) {
            $com = self::create([
                'userid'     =>  $userid,
                'start_time' =>  $start_time,
                'end_time'   =>  $end_time,
                'create_time'=>  time(),
                'update_time'=>  time(),
                'name'       =>  '',
            ]);
            if(empty($com)) {
                return false;
            }
            //添加主账号
            $com_users=CompanyUsers::create([
                'com_id'     =>  $com->id,
                'userid'     =>  $userid,
                'is_host'    =>  1,
                'create_time'=>  $time,
                'update_time'=>  $time,
            ]);
            if(empty($com_users)) {
                return false;
            }
            
            //以前充值过vip
        } else {
            if(!self::where(['id' => $com_info['id']])->update([
                'start_time' =>  $start_time,
                'end_time'   =>  $end_time,
                'update_time'=>  time(),
            ])){
                return false;
            }
        }
        
        
        //记录充值日志日志
        if(!UserVcLog::create([
            'userid'     =>  $userid,
            'remark'     =>  $remark,
            'vc_type'    =>  2,
            'type'       =>  1,
            'start_timee'=>  $start,
            'end_time'   =>  $end,
        ])) {
            return false;
        }
        return true;
    }
}

?>