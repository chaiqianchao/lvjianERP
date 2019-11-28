<?php


namespace app\store\model;

use think\Model;
class ArtourOrder extends Model
{
    //定义与用户表的一对多关联
    public function user()
    {
        return $this->belongsTo('artour_user');
    }
}