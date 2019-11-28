<?php

namespace app\store\model;

use think\Model;
class ArtourGame extends Model
{

    //设置当前表默认日期时间显示格式
    protected $dateFormat = 'Y/m/d H:i:s';

    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    protected $createTime = 'game_createTime';

    protected $updateTime = 'game_updateTime';

    //状态字段:status返回值处理
    public function getStatusAttr($value)
    {
        $status = [
            0=>'已启用',
            1=> '已停用'
        ];
        return $status[$value];
    }

//    // 定义关联方法
//    public function teacher()
//    {
//        // 班级表与教师表是1对1关联
//        return $this->hasOne('Teacher');
//    }
//
//    // 定义关联方法
//    public function student()
//    {
//        return $this->hasMany('student');
//    }
}