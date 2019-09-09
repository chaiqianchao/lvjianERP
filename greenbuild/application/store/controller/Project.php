<?php
namespace app\store\controller;

use app\store\model\GreenProject;
use think\Controller;
use think\Request;
use think\Session;
use think\Db;

class Project extends Controller
{
    //登陆界面
    public function project(Request $request)
    {
//        //防止重复登录登录
//        $this->alreadyLogin();
//        $this->view->assign('title', '用户登录');
//        $this->view->assign('keywords', 'AR智游');
//        $this->view->assign('desc', '后台管理');
//        $this->view->assign('copyRight', '视时空');
        //获取字段名
        $user = new GreenProject();
        $data = $user->column('');
        $arr=[];
        $num=[];

        foreach ($data as $key => $value) {  //把对象数据变为数组
            $arr['num'][] = $value;
        }
        foreach ($arr['num'][0] as $key => $value){
            $num[]=$key;
        }
        $this->view->assign('order_column', $num);

        //获取字段备注
        $data = Db::query('SHOW FULL COLUMNS FROM '.'green_project');
        $note=[];
        $i=0;
        foreach ($data as $key => $value) {  //把对象数据变为数组
            $note[$i++]=$value['Comment'];
        }
        $this -> view -> assign('note', $note);

        $record =GreenProject::paginate(10);
        //获取记录数量
        $count = GreenProject::count();
        $this -> view -> assign('orderList', $record);
        $this -> view -> assign('count', $count);

        return $this->view->fetch('project');
    }


}