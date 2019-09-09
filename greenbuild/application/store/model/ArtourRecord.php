<?php


namespace app\store\model;

use think\Model;
class ArtourRecord extends Model
{
    //设置当前表默认日期时间显示格式
    protected $dateFormat = 'Y/m/d H:i:s';

    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    protected $createTime = 'record_createTime';

    protected $updateTime = 'record_update';

    //定义与用户表的一对多关联
    public function user()
    {
        return $this->belongsTo('artour_user');
    }

}