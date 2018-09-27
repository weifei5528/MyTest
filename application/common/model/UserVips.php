<?php
namespace app\common\model;

use think\Model;
class UserVips extends Model
{
    protected $autoWriteTimestamp = true;
    /**
     * 
     * @param string $day 要充值的天数 如 2 days ; 1 year
     * @param int $userid 要充vip的用户id
     * @param string  $remark 充值日志备注
     * @return bool 正确返回true 错误返回false
     */
    public static function addVip($day,$userid,$remark = 'VIP充值') 
    {
        $time =time();
        $vip_info = self::where(['userid' => $userid])->find();
        $vip_end = empty($vip_info['end_time']) ? $time : ($vip_info['end_time'] > $time ? $vip_info['end_time'] : $time);
        $end_time = strtotime("+ ".$day,$vip_end);
        $end = $end_time;
        $start_time ='';
        $start =null;
        //开始时间为空
        if(empty($vip_info['start_time'])) {
            $start = $time;
            $start_time = $time;
        } else {
            // vip没有过期续费
            if($vip_info['end_time'] > $time) {
                $start = $vip_info['end_time'];
                $start_time = $vip_info['start_time'];
                //vip已过期
            } else {
                $start = $time;
                $start_time = $time;
            }
        }
        //第一次充值

        if(empty($vip_info)) {
            $vip_flag=self::create([
                'userid'     =>  $userid,
                'start_time' =>  $start_time,
                'end_time'   =>  $end_time,
                'create_time'=>  time(),
                'update_time'=>  time(),
            ]);
            if(!$vip_flag) {
                return false;
            }
            //以前充值过vip
        } else {
           $vip_flag = self::where(['id' => $vip_info['id']])->update([
                'start_time' =>  $start_time,
                'end_time'   =>  $end_time,
                'update_time'=>  time(),
            ]);
           if(!$vip_flag) {
               return false;
           }
        }
        //记录充值日志日志
       $log_flag= UserVcLog::create([
            'userid'     =>  $userid,
            'remark'     =>  $remark,
            'vc_type'    =>  1,
            'type'       =>  1,
            'start_time'=>  $start,
            'end_time'   =>  $end,
        ]);
        if(!$log_flag) {
            return false;
        }
        return true;
    }
}

?>