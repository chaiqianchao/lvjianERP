<?php

namespace app\store\model;

use think\Model;
class ArtourUser extends Model
{
    // 时间字段取出后的默认时间格式
    protected $dateFormat = 'Y年m月d日';
    // 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
    protected $autoWriteTimestamp = true; //自动写入
    // 创建时间字段
    protected $createTime = 'user_createTime';
//    // 更新时间字段
//    protected $updateTime = 'user_lastTime';

    //密码修改器
    public function setPasswordAttr($value)
    {
        return md5($value);
    }
    // 定义关联方法
    public function record()
    {
        return $this->hasMany('artour_record');
    }

    public function mallOrder()
    {
        return $this->hasMany('artour_order');
    }
}