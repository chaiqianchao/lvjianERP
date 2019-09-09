<?php
namespace app\store\controller;

use app\store\model\GreenProjectbuildtype;
use app\store\model\GreenProjectdesign;
use app\store\model\GreenProjectdesigner;
use app\store\model\GreenProjectphase;
use app\store\model\GreenProjectdraw;
use think\Controller;
use app\store\model\GreenProject;
use think\Request;
use think\Session;
use think\Db;
class Index extends Controller
{

    protected function _initialize()
    {
        parent::_initialize();
        define('USER_ID', Session::get('user_id'));
    }


    //判断用户是否登陆,放在系统后台入口前面: index/index
    protected function isLogin()
    {
        if (!is_null(USER_ID)) {
            $this->error('用户未登陆,无权访问', url('index/login'));
        }
    }


    //防止用户重复登陆,放在登陆操作前面:index/login
    protected function alreadyLogin()
    {
        if (USER_ID) {
            $this->error('用户已经登陆,请勿重复登陆', url('index/index'));
        }
    }


    //主页面
    public function index()
    {
        $this->isLogin();  //判断用户是否登录
//        $this->view->assign('title', 'AR智游后台管理系统');
        return $this->view->fetch();
    }

    //登陆界面
    public function login()
    {
//        //防止重复登录登录
//        $this->alreadyLogin();
//        $this->view->assign('title', '用户登录');
//        $this->view->assign('keywords', 'AR智游');
//        $this->view->assign('desc', '后台管理');
//        $this->view->assign('copyRight', '视时空');
        return $this->view->fetch('login');
    }

    //验证登录
    public function checkLogin(Request $request)
    {
        //初始返回参数
        $status = 0; //验证失败标志
        $result = '验证失败'; //失败提示信息
        $data = $request->param();

        //验证规则
        $rule = [
            'name|姓名' => 'require',
            'password|密码' => 'require',
        ];

        //自定义验证失败的提示信息
        $msg = [
            'name' => ['require' => '用户名不能为空，请检查'],
            'password' => ['require' => '密码不能为空，请检查'],
        ];

        //验证数据 $this->validate($data, $rule, $msg)
        $result = $this->validate($data, $rule, $msg);

        //通过验证后,进行数据表查询
        //此处必须全等===才可以,因为验证不通过,$result保存错误信息字符串,返回非零
        if (true === $result) {

            $password = md5($data['password']);
            $ret = model('ArtourAdministrators')->get(['administrators_name' => $data['name'], 'administrators_password' => $password]);
            if (!$ret) {
                $result = '没有该用户,请检查';
            } else {
                $res = model('ArtourAdministrators')->get(['administrators_name' => $data['name'], 'administrators_password' => $password, 'administrators_status' => 1]);
                if ($res) {
                    $result = '该用户已被停用';
                } else {
                    $status = 1;
                    $result = '验证通过,点击[确定]后进入后台';
                }

                //创建session,用来检测用户登陆状态和防止重复登陆
                Session::set('user_id', Db::table('artour_administrators')
                    ->where('administrators_name', $data['name'])
                    ->value('administrators_id'));
                Session::set('user_name', Db::table('artour_administrators')
                    ->where('administrators_name', $data['name'])
                    ->value('administrators_name'));
                Session::set('user_lastTime', Db::table('artour_administrators')
                    ->where('administrators_name', $data['name'])
                    ->value('administrators_lastTime'));
                Session::set('user_password', Db::table('artour_administrators')
                    ->where('administrators_name', $data['name'])
                    ->value('administrators_password'));
                Session::set('user_role', Db::table('artour_administrators')
                    ->where('administrators_name', $data['name'])
                    ->value('administrators_role'));
            }
        }

        return ['status' => $status, 'message' => $result, 'data' => $data];
    }


    //退出登录
    public function logout()
    {
        //退出前先更新登录时间字段,下次登录时就知道上次登录时间了
        ArtourAdministrators::update(['administrators_lastTime' => time()], ['administrators_id' => Session::get('user_id')]);
        Session::delete('user_id');
        Session::delete('user_name');
        Session::delete('user_password');
        Session::delete('user_role');
        Session::delete('user_lastTime');

        $this->success('注销登陆,正在返回', url('index/login'));
    }
    //渲染工程界面
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
        $data = $user->column('project_id,project_name,project_contractor,project_agent,project_leader');
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
//            ->column('project_id,project_name,project_contractor,project_agent,project_leader');
//        dump($data);

        $note=[];
        $i=0;
        foreach ($data as $key => $value) {  //把对象数据变为数组
            $note[$i++]=$value['Comment'];
        }
        $this -> view -> assign('note', $note);

//        dump($note);
        $record =GreenProject::paginate(10);
        //获取记录数量
        $count = GreenProject::count();
        $this -> view -> assign('orderList', $record);
        $this -> view -> assign('count', $count);

        return $this->view->fetch('project');
    }
    // 工程项目筛选
public function ProjectSelect(Request $request)
    {
        $data = $request -> param();
        $info=[];
        foreach ($data as $key => $value)
        {  //把对象数据变为数组
            if($value)
            {
                $info[$key]=$value;
            }
        }
        $result =Db::table('green_project')
            ->where($info)
            ->field('project_id,project_name,project_contractor,project_agent,project_leader')
            ->select();
        return $result;
    }
    public function retrieval()
    {
        return $this->view->fetch('retrieval');
    }
    //编辑工程号
    public function edit1(Request $request)
    {

        // {id:afsdgfad1545, column:project_buildtype, content:总包}
        //获取数据
        $data = $request -> param();
        $ar=new GreenProject();
        $result = $ar
            ->where([
                'project_id'=>$data['project_id'],
            ])
            ->update([
                $data['column'].''=>$data['content'],
            ]);

        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    // 编辑设计人员
    public function edit2(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $ar=new GreenProjectdesigner();
        $len = count($data['project_design_before']);
        for ($i=0; $i < $len ; $i++) { 
            $result = $ar
            ->where([
                'project_id'=>$data['project_id'],
                'project_design'=>$data['project_design_before'][$i]
            ])
            ->update([
                'project_design'=>$data['project_design_after'][$i],
                'projectdesigner_company'=>$data['company'][$i],
                'projectdesigner_type'=>$data['type'][$i],
                'projectdesigner_design'=>$data['design'][$i],
                'projectdesigner_contractor'=>$data['contractor'][$i]
            ]);
        }
        for ($i=$len; $i < count($data['project_design_after'][$i]); $i++) { 
          
            $result = $ar
            ->insert([
                'project_id'=>$data['project_id'],
                'project_design'=>$data['project_design_after'][$i],
                'projectdesigner_company'=>$data['company'][$i],
                'projectdesigner_type'=>$data['type'][$i],
                'projectdesigner_design'=>$data['design'][$i],
                'projectdesigner_contractor'=>$data['contractor'][$i]
            ]);
        }
        if (null!=$result) {
                return ['status'=>1, 'message'=>'更新成功'];
            } else {
                return ['status'=>0, 'message'=>'更新失败,请检查'];
            }
    }
    // 出图计划
        public function edit3(Request $request)
        {
            //获取数据
            $data = $request -> param();

            $ar=new GreenProjectdraw();
            $result = $ar
                ->where([
                    'project_id'=>$data['project_id']
                ])
                ->update([
                    'draw_concept'=>$data['concept'],
                    'draw_plan'=>$data['plan'],
                    'draw_preliminary'=>$data['preliminary'],
                    'draw_work'=>$data['work'],
                    'draw_modify'=>$data['modify'],
                ]);

            if (null!=$result) {
                return ['status'=>1, 'message'=>'更新成功'];
            } else {
                return ['status'=>0, 'message'=>'更新失败,请检查'];
            }
    }

    //渲染工程详情界面
    public function project_details(Request $request)
    {
        //获取到要编辑的工程号
        $project_id = $request -> param('id');
        $data=['project_id'=>$project_id];

        //给当前页面seo变量赋值
        $this->view->assign('title','工程详情信息');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');

        //根据ID和手机号进行查询
        $result =GreenProject::get($data);
        $result1 =GreenProjectdesign::get(['project_id'=>$project_id]);
        $result2 =GreenProjectdesigner::get($data);
        $result3 =GreenProjectphase::get(['project_id'=>$project_id]);
        $result4 =GreenProjectbuildtype::get($data);
        // $result5=GreenProjectdraw::get($data);
        $result5=Db::table('green_projectdraw')->where($data)->select();

        //给当前编辑模板赋值
        $this->view->assign('project_info1',$result1->getData());
        $this->view->assign('project_info2',$result2->getData());
        $this->view->assign('project_info3',$result3->getData());
        $this->view->assign('project_info4',$result4->getData());
        $this->view->assign('project_info5',$result5);
        //获取字段备注
        $data = Db::query('SHOW FULL COLUMNS FROM '.'green_project');
        $note=[];
        $i=0;
        foreach ($data as $key => $value) {  //把对象数据变为数组
            $note[$i++]=$value['Comment'];
        }
        $this -> view -> assign('note', $note);

        $aKey=[];
        $i=0;
        foreach ($result->getData() as $key => $value) {  //把对象数据变为数组
            $aKey[$i++]=$key;
        }
        $this->view->assign('column',$aKey);
        $this->view->assign('content',array_values($result->getData()));

        $info=[];
        $index=0;

        $data_designer = Db::query('SHOW FULL COLUMNS FROM '.'green_projectdesigner');
        $note_designer=[];
        $i=0;
        foreach ($data_designer as $key => $value) {  //把对象数据变为数组
            $note_designer[$i++]=$value['Comment'];
        }
        $note_designer=array_slice($note_designer,1,count($note_designer)-1);
        $info[$index]=Db::table('green_projectdesigner')->where([
            'project_id'=>$project_id,
        ])->field('project_design,projectdesigner_type,projectdesigner_design,projectdesigner_contractor')->select();
        $info[$index]=array_merge([$note_designer], $info[$index]);

        $index++;
        $data_draw = Db::query('SHOW FULL COLUMNS FROM '.'green_projectdraw');
        $note_draw=[];
        $i=0;
        foreach ($data_draw as $key => $value) {  //把对象数据变为数组
            $note_draw[$i++]=$value['Comment'];
        }
        $note_draw=array_slice($note_draw,1,count($note_draw)-1);
        $project_draw=array_slice($result5,1,count($result5)-1);
        $info[$index]=array_merge([$note_draw],[$project_draw]);
        $this -> view -> assign('project_infoes', $info);
        //渲染编辑模板
        return $this->view->fetch('project_details');
    }

    public function detailAdd()
    {
        return $this->view->fetch('detail-add');
    }
    public function detailEdit(Request $resquset)
    {
        $data=$resquset->param();
        // dump($data);
        return $this->view->fetch('detail-edit');
    }

    public function newContract()
    {

        return $this->view->fetch('newcontract');
    }
    public function orderlist1(Request $request)
   {
        $data = $request -> param();
        $info=[];
        foreach ($data as $key => $value){  
            //把对象数据变为数组
            if($value){
                $info[$key]=$value;
            }
        }
        $count = Db::table('green_contract')->where($info)->count();
        $list = Db::table('green_contract')->where($info)->paginate(10);  

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        //渲染管理员列表模板
        return $this -> view -> fetch('orderlist1');
    }
    // 合同台账管理
     public function taizhangguanli(Request $request)
   {
        $data = $request -> param();
        $info=[];
        foreach ($data as $key => $value){  
            //把对象数据变为数组
            if($value){
                $info[$key]=$value;
            }
        }
        $count = Db::table('green_contractledger')->where($info)->count();
        $list = Db::table('green_contractledger')->where($info)->paginate(10);  

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        //渲染管理员列表模板
        return $this -> view -> fetch('taizhangguanli');
    }
     // 招投标项目
     public function zhaotoubiao(Request $request)
   {
        $data = $request -> param();
        $info=[];
        foreach ($data as $key => $value){  
            //把对象数据变为数组
            if($value){
                $info[$key]=$value;
            }
        }
        $count = Db::table('green_bid')->where($info)->count();
        $list = Db::table('green_bid')->where($info)->paginate(10);  

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        //渲染管理员列表模板
        return $this -> view -> fetch('zhaotoubiao');
    }
     // 管理员列表
     public function adminlist(Request $request)
   {
        // $data = $request -> param();
        // $info=[];
        // foreach ($data as $key => $value){  
        //     //把对象数据变为数组
        //     if($value){
        //         $info[$key]=$value;
        //     }
        // }
        // $count = Db::table('green_bid')->where($info)->count();
        // $list = Db::table('green_bid')->where($info)->paginate(10);  

        // $this -> view -> assign('orderList', $list);
        // $this -> view -> assign('count', $count);
        //渲染管理员列表模板
        return $this -> view -> fetch('adminlist');
    }
     // 管理员列表
     public function personnel(Request $request)
   {
        // $data = $request -> param();
        // $info=[];
        // foreach ($data as $key => $value){  
        //     //把对象数据变为数组
        //     if($value){
        //         $info[$key]=$value;
        //     }
        // }
        // $count = Db::table('green_bid')->where($info)->count();
        // $list = Db::table('green_bid')->where($info)->paginate(10);  

        // $this -> view -> assign('orderList', $list);
        // $this -> view -> assign('count', $count);
        //渲染管理员列表模板
        return $this -> view -> fetch('personnel');
    }
    // 管理员列表
     public function poweradmin(Request $request)
   {
        // $data = $request -> param();
        // $info=[];
        // foreach ($data as $key => $value){  
        //     //把对象数据变为数组
        //     if($value){
        //         $info[$key]=$value;
        //     }
        // }
        // $count = Db::table('green_bid')->where($info)->count();
        // $list = Db::table('green_bid')->where($info)->paginate(10);  

        // $this -> view -> assign('orderList', $list);
        // $this -> view -> assign('count', $count);
        //渲染管理员列表模板
        return $this -> view -> fetch('powerAdmin');
    }
     // 招投标项目
     public function kehu(Request $request)
   {
        $data = $request -> param();
        $info=[];
        foreach ($data as $key => $value){  
            //把对象数据变为数组
            if($value){
                $info[$key]=$value;
            }
        }
        $count = Db::table('green_bid')->where($info)->count();
        $list = Db::table('green_bid')->where($info)->paginate(10);  

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        //渲染管理员列表模板
        return $this -> view -> fetch('kehu');
    }
    // 工程产值
     public function gongchengchanzhi(Request $request)
   {
        $data = $request -> param();
        $info=[];
        foreach ($data as $key => $value){  
            //把对象数据变为数组
            if($value){
                $info[$key]=$value;
            }
        }
        $count = Db::table('green_bid')->where($info)->count();
        $list = Db::table('green_bid')->where($info)->paginate(10);  

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        //渲染管理员列表模板
        return $this -> view -> fetch('gongchengchanzhi');
    }
    // 院产值
     public function yuanchanzhi(Request $request)
   {
        $data = $request -> param();
        $info=[];
        foreach ($data as $key => $value){  
            //把对象数据变为数组
            if($value){
                $info[$key]=$value;
            }
        }
        $count = Db::table('green_bid')->where($info)->count();
        $list = Db::table('green_bid')->where($info)->paginate(10);  

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        //渲染管理员列表模板
        return $this -> view -> fetch('yuanchanzhi');
    }
    // 个人产值
     public function gerenchanzhi(Request $request)
   {
        $data = $request -> param();
        $info=[];
        foreach ($data as $key => $value){  
            //把对象数据变为数组
            if($value){
                $info[$key]=$value;
            }
        }
        $count = Db::table('green_bid')->where($info)->count();
        $list = Db::table('green_bid')->where($info)->paginate(10);  

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        //渲染管理员列表模板
        return $this -> view -> fetch('gerenchanzhi');
    }
     // 收款确认函
     public function shoukuanquerenhan(Request $request)
   {
        $data = $request -> param();
        $info=[];
        foreach ($data as $key => $value){  
            //把对象数据变为数组
            if($value){
                $info[$key]=$value;
            }
        }
        $count = Db::table('green_bid')->where($info)->count();
        $list = Db::table('green_bid')->where($info)->paginate(10);  

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        //渲染管理员列表模板
        return $this -> view -> fetch('shoukuanquerenhan');
    }
    public function contractAdd(Request $request)
    {
        $data=$request->param();
        // echo ($data);
        $status = 0;
        $message = '添加失败';

        $res=Db::table('green_contract')
            // ->allowField(true)
            ->insert([
                'contract_id'=>$data['contract_id'],
                'project_id'=>$data['project_id'],
                'contract_type'=>$data['contract_type'],
                'contract_signtime'=>$data['contract_signtime'],
                'project_name'=>$data['project_name'],
                'contract_constructor'=>$data['contract_constructor'],
                'contract_agent'=>$data['contract_agent'],
                'contract_amount'=>$data['contract_amount'],
                'contract_unitprice'=>$data['contract_unitprice'],
                'contract_design'=>$data['contract_design'],
                'contract_content'=>$data['contract_content'],
                'contract_phase'=>$data['contract_phase'],
                // 'contract_reward'=>$data['contract_reward'],
                'contract_operator'=>$data['contract_operator'],
                'contract_projectleader'=>$data['contract_projectleader'],
                'contract_approver'=>$data['contract_approver'],
                'contract_remarks'=>$data['contract_remarks'],
            ]);
        if ($res === null) {
            $status = 0;
            $message = '添加失败~~';
        }
        else{
            $status = 1;
            $message = '添加成功';
        }
        return ['status'=>$status, 'message'=>$message];
    }
    //合同筛选接口
    public function ContractSelect(Request $request)
    {
        $data = $request -> param();
        $info=[];
        foreach ($data as $key => $value)
        {  //把对象数据变为数组
            if($value)
            {
                $info[$key]=$value;
            }
        }
        $result =Db::table('green_contract')
            ->where($info)
            ->field('contract_id,project_id,contract_type,contract_signtime,project_name,contract_constructor,contract_agent')
            ->select();
        $this->view->assign('contract_info',$result);
        return json($result);
    }
    public function ContractSelectAll(Request $request){
    $data=$request->param();
    $res=Db::table('green_contract')
        ->whereor([
            'contract_id'=>['like','%'.$data['content'].'%'],
            'project_id'=>['like', '%'.$data['content'].'%'],
            'contract_type'=>['like', '%'.$data['content'].'%'],
            'contract_signtime'=>['like','%'.$data['content'].'%'],
            'project_name'=>['like', '%'.$data['content'].'%'],
            'contract_constructor'=>['like', '%'.$data['content'].'%'],
            'contract_agent'=>['like', '%'.$data['content'].'%'],
                ])
        ->select();
    return json($res);
}
}