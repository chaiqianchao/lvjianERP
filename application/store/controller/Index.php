<?php
namespace app\store\controller;

// use app\store\model\GreenProjectbuildtype;
// use app\store\model\GreenProjectdesign;
use app\store\model\GreenProjectdesigner;
use app\store\model\GreenProjectphase;
use app\store\model\GreenProjectdraw;
use app\store\model\GreenAdministrators;
use app\store\model\GreenBid;
use app\store\model\GreenBidcompensation;
use app\store\model\GreenBiddeposite;
use app\store\model\GreenBidphase;
use app\store\model\GreenBidtype;
use app\store\model\GreenContract;
use app\store\model\GreenCustomer;
use app\store\model\GreenProjectdrawplan;
use app\store\model\GreenContractledger;
use app\store\model\GreenDrawingFee;
// green_drawing_fee


use think\Controller;
use app\store\model\GreenProject;
use think\Request;
use think\Session;
use think\Db;
use think\paginator\driver\Bootstrap;
class Index extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();
        define('USER_ID', Session::get('staff_id'));
        define('USER_STARUS', Session::get('admin_status'));
    }

    //判断用户是否登陆,放在系统后台入口前面: index/index
    protected function isLogin()
    {
        if (is_null(USER_ID)) {
            $this->error('用户未登陆,无权访问', url('index/login'));
        }
    }
    //防止用户重复登陆,放在登陆操作前面:index/login
    protected function alreadyLogin()
    {
        if ( GreenAdministrators::where('staff_id',Session::get('staff_id'))->value('admin_status')===1) {
            $this->error('用户已经登陆,请勿重复登陆', url('index/index'));
        }
    }
    //无权限显示页面
    public function noPower(){
        echo("您无权限修改！");
        $this->view->fetch("noPower");
    }
    //主页面
    public function index()
    {
        $sid=Session::get('staff_id');
        $name=Db::table('green_administrators')->where('staff_id',$sid)->value("staff_name");
        $this->view->assign('name', $name);
        $this->isLogin();  //判断用户是否登录

       // 权限渲染回前端页面
        $sid=Session::get('staff_id');
        $limits=Db::table('green_administrators')->where('staff_id',$sid)->select();
        $this->view->assign('limits', $limits[0]);
        return $this->view->fetch();
    }
    //欢迎页面
    public function welcome()
    {
        $sid=Session::get('staff_id');
        $name=Db::table('green_administrators')->where('staff_id',$sid)->value("staff_name");
        $this->view->assign('name', $name);
        $this->isLogin();  //判断用户是否登录
        return $this->view->fetch();
    }
//错误页面
    public function errors()
    {
        return $this->view->fetch('error');
    }

    //登陆界面
    public function login()
    {
//        //防止重复登录登录
        $this -> alreadyLogin();
        // Db:table('green_administrators')
        //         ->where('administrators_name', $data['administrators_name'])
        //         ->update(['admin_status'=>1]);
        GreenAdministrators::where('staff_id',Session::get('staff_id'))->update(['admin_status'=>1]);
        return $this->view->fetch('login');
    }
    //新增招投标
    public function zhaotoubaioNew()
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('bid');
        if($limit!=2)
        {
        return $this->view->fetch('noPower');
        }
        else
            {
        return $this->view->fetch('zhaotoubaioNew');
        }
    }
    //新增客户信息
    public function kehuNew()
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('customer');
        if ($limit==2) {
            return $this->view->fetch('kehuNew');
        }
        else{
            return $this->view->fetch('noPower');
        }
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
            'administrators_name|姓名' => 'require',
            'administrators_password|密码' => 'require',
        ];

        //自定义验证失败的提示信息
        $msg = [
            'administrators_name' => ['require' => '用户名不能为空，请检查'],
            'administrators_password' => ['require' => '密码不能为空，请检查'],
        ];

        //验证数据 $this->validate($data, $rule, $msg)
        $result = $this->validate($data, $rule, $msg);

        //通过验证后,进行数据表查询
        //此处必须全等===才可以,因为验证不通过,$result保存错误信息字符串,返回非零
        if (true === $result) {

            $password = md5($data['administrators_password']);
            $ret = model('GreenAdministrators')->get(['administrators_name' => $data['administrators_name']]);
            $administrators_password = model('GreenAdministrators')->where("administrators_name",$data['administrators_name'])->value("administrators_password");
            if (!$ret) {
                $result = '没有该用户,请检查';
            }
            else if($administrators_password!=$password){
                $result = '密码错误,请检查';
            }
            else {
                $res = model('GreenAdministrators')->get(['administrators_name' => $data['administrators_name'], 'administrators_password' => $password, 
                    'staff_enable' => 0]);
                if ($res) {
                    $result = '该用户已被停用';
                } else {
                    $status = 1;
                    $result = '验证通过,点击[确定]后进入后台';
                }
                
                //创建session,用来检测用户登陆状态和防止重复登陆
                Session::set('staff_id', Db::table('green_administrators')
                    ->where('administrators_name', $data['administrators_name'])
                    ->value('staff_id'));
                Session::set('staff_name', Db::table('green_administrators')
                    ->where('administrators_name', $data['administrators_name'])
                    ->value('staff_name'));
                Session::set('administrators_name', Db::table('green_administrators')
                    ->where('administrators_name', $data['administrators_name'])
                    ->value('administrators_name'));
                Session::set('administrators_password', Db::table('green_administrators')
                    ->where('administrators_name', $data['administrators_name'])
                    ->value('administrators_password'));
                Session::set('admin', Db::table('green_administrators')
                    ->where('administrators_name', $data['administrators_name'])
                    ->value('admin'));
                 Session::set('enable', Db::table('green_administrators')
                    ->where('administrators_name', $data['administrators_name'])
                    ->value('enable'));
                 Session::set('administrators_lastTime', Db::table('green_administrators')
                    ->where('administrators_name', $data['administrators_name'])
                    ->value('administrators_lastTime'));
                 Session::set('admin_status', Db::table('green_administrators')
                    ->where('administrators_name', $data['administrators_name'])
                    ->value('admin_status'));
            }
        }
        return ['status' => $status, 'message' => $result, 'data' => $data];
    }


    //退出登录
    public function logout()
    {
        //退出前先更新登录时间字段,下次登录时就知道上次登录时间了
        GreenAdministrators::where('staff_id',Session::get('staff_id'))->update(['administrators_lastTime' => time()], ['admin_status'=>0],['staff_id' => Session::get('staff_id')]);
        GreenAdministrators::where('staff_id',Session::get('staff_id'))->update(['admin_status'=>0]);
        Session::delete('staff_id');
        Session::delete('staff_name');
        Session::delete('administrators_name');
        Session::delete('administrators_password');
        Session::delete('admin');
        Session::delete('enable');        
        Session::delete('administrators_lastTime');
        Session::delete('admin_status');  
        $this->success('注销登陆,正在返回', url('login'));
    }
    //编辑权限判别
    public function editPower(Request $request)
    {
        $data = $request -> param();
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("project_view");
        if ($limit!=2) {
            $this->success('0', url('noPower'));
        }
       $this->success('1', url());
    }
   //渲染工程界面
   public function project(Request $request)
    {  
        $data = $request -> param();
        $result =Db::table('green_project')
            ->field('project_id,project_name,project_contractor,project_agent,project_leader,project_landarea,aboveground_area,underground_area,among_area,project_totalarea,project_design,project_buildtype')
            ->order('project_id desc')
            ->paginate(10);
        $count =Db::table('green_project')
            ->field('project_id,project_name,project_contractor,project_agent,project_leader,project_landarea,aboveground_area,underground_area,among_area,project_totalarea,project_design,project_buildtype')
            ->count();
        //获取记录数量
        $this -> view -> assign('orderList', $result);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber',10);
        return $this->view->fetch('project');

    }
   //工程筛选接口
    public function ProjectSelect(Request $request)
    {
        $data = $request -> param();
        $info=[];
        foreach ($data as $key => $value)
        {  //把对象数据变为数组
            if($value && $key != "pagenumber"&&$key != "page")
            {
                $info[$key]=$value;
            }
        }
        $result =Db::table('green_project')
            ->where($info)
            ->order('project_id desc')
            ->paginate($data['pagenumber'],false,["query"=>$data]);
        $count =Db::table('green_project')
            ->where($info)
            ->count();

        $this-> view ->assign('orderList',$result);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', $data['pagenumber']);
         return $this->view->fetch('project');
    }
    //工程模糊筛选
    public function ProjectselectAll(Request $request){
        $data=$request->param();
        $res=Db::table('green_project')
            ->whereor([
                'project_id'=>['like','%'.$data['content'].'%'],
                'project_name'=>['like', '%'.$data['content'].'%'],
                'project_contractor'=>['like', '%'.$data['content'].'%'],
                'project_agent'=>['like','%'.$data['content'].'%'],
                'project_leader'=>['like', '%'.$data['content'].'%'],
                'project_landarea'=>['like', '%'.$data['content'].'%'],
                'aboveground_area'=>['like', '%'.$data['content'].'%'],
                'underground_area'=>['like', '%'.$data['content'].'%'],
                'among_area'=>['like', '%'.$data['content'].'%'],
                'project_totalarea'=>['like', '%'.$data['content'].'%'],
            ])
            ->order('project_id desc')
            ->paginate($data['pagenumber'],false,["query"=>$data]);
        $count=Db::table('green_project')
            ->whereor([
                'project_id'=>['like','%'.$data['content'].'%'],
                'project_name'=>['like', '%'.$data['content'].'%'],
                'project_contractor'=>['like', '%'.$data['content'].'%'],
                'project_agent'=>['like','%'.$data['content'].'%'],
                'project_leader'=>['like', '%'.$data['content'].'%'],
                'project_landarea'=>['like', '%'.$data['content'].'%'],
                'aboveground_area'=>['like', '%'.$data['content'].'%'],
                'underground_area'=>['like', '%'.$data['content'].'%'],
                'among_area'=>['like', '%'.$data['content'].'%'],
                'project_totalarea'=>['like', '%'.$data['content'].'%'],
            ])
            ->count();
        $this-> view ->assign('orderList',$res);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber',$data["pagenumber"]);
         return $this->view->fetch('project');
    }

    public function equipment_fee_new(){
        return $this->view->fetch("equipment_fee_new");
    }
    // public function structure_fee_new(){
    //     return $this->view->fetch("structure_fee_new");
    // }
    // 新增工程
    public function projectAdd(Request $request)
        {
            $data=$request->param();
            if (GreenProject::get(['project_id'=> $data['project_id']])) {
        //如果在表中查询到该用户名
        $status = 0;
        $message1 = '工程已存在,请重新输入~~';
        return ['status'=>$status, 'message'=>$message1];
    }
    else{
            foreach ($data as $key => $value){
                if($value=='')
                    {$data[$key]=null;}
            }
            $status = 1;
            $message = '添加成功';
            if ($data['project_buildtype']=='其他') {
                $data['project_buildtype'] = $data['project_buildtype_self'];
            }
            $res=Db::table('green_project')
                // ->allowField(true)
                ->insert([
                    'project_id'=>$data['project_id'],
                    'project_name'=>$data['project_name'],
                    'project_contractor'=>$data['project_contractor'],
                    'project_agent'=>$data['project_agent'],
                    'project_landarea'=>$data['project_landarea'],
                    'aboveground_area'=>$data['aboveground_area'],
                    'underground_area'=>$data['underground_area'],
                    'among_area'=>$data['among_area'],
                    'project_totalarea'=>$data['project_totalarea'],
                    'project_leader'=>$data['project_leader'],
                    'project_buildtype'=>$data['project_buildtype'],
                    'project_design'=>$data['project_design'],
                    'project_remark'=>$data['project_remark'],
                    'project_notice'=>'',
                ]);
            $res1=Db::table('green_projectphase')
                ->insert([
                    'project_id'=>$data['project_id'],
                    'phase_name1'=>$data['phase_name1'],
                    'phase_name2'=>$data['phase_name2'],
                    'phase_name3'=>$data['phase_name3'],
                    'phase_name4'=>$data['phase_name4'],
                    'phase_name5'=>$data['phase_name5'],
                    'phase_name6'=>$data['phase_name6'],
                    'phase_name7'=>$data['phase_name7'],
                    'phase_name8'=>$data['phase_name8'],
                    'phase_name9'=>$data['phase_name9'],
                    'phase_name10'=>$data['phase_name10'],
                    'phase_name11'=>$data['phase_name11'],
                    'phase_name12'=>$data['phase_name12'],
                    'phase_name13'=>$data['phase_name13'],
                    'phase_name14'=>$data['phase_name14'],
                    'phase_name15'=>$data['phase_name15'],
                    'phase_name16'=>$data['phase_name16'],
                    'phase_name17'=>$data['phase_name17'],
                    'phase_name18'=>$data['phase_name18'],
                    'phase_name19'=>$data['phase_name19'],
                    'phase_name20'=>$data['phase_name20'],
                ]);
                $res2=Db::table('green_projectdraw')
                ->insert([
                    'project_id'=>$data['project_id'],
                    'draw_concept'=>$data['draw_concept'],
                    'draw_plan'=>$data['draw_plan'],
                    'draw_preliminary'=>$data['draw_preliminary'],
                    'draw_pile'=>$data['draw_pile'],
                    'draw_work'=>$data['draw_work'],
                    'draw_modify'=>$data['draw_modify'],
                    'contact'=>$data['contact'],
                    'others'=>$data['others'],
                ]);
                // 新建工程时，新建设计人员数据表
                $sql1="CREATE TABLE `greenbuild`.`projectdesigner".$data['project_id']."`( 
                        `project_id` VARCHAR(30) NOT NULL COMMENT '工程编号' , 
                        `project_design` VARCHAR(200) NOT NULL COMMENT '设计内容' , 
                        `projectdesigner_type` VARCHAR(200) NULL DEFAULT NULL COMMENT '工种负责人' , 
                        `projectdesigner_design` VARCHAR(200) NULL DEFAULT NULL COMMENT '设计人' , 
                        `project_subcontractor` VARCHAR(200) NULL DEFAULT NULL COMMENT '分包人' , 
                        PRIMARY KEY (`project_id`)) 
                        ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci COMMENT = '".$data['project_id']."设计人员';
                                ";
                $sql2="ALTER TABLE `projectdesigner".$data['project_id']."`
                        DROP PRIMARY KEY,
                        ADD PRIMARY KEY(
                        `project_id`,
                        `project_design`);";
                // 新建工程时，新建对应工程产值数据表
                $sql3="CREATE TABLE IF NOT EXISTS`greenbuild`.`green_projectvalue".$data['project_id']."` ( 
                        `id` INT NOT NULL AUTO_INCREMENT COMMENT '自增ID' , 
                        `project_id` VARCHAR(50) NOT NULL COMMENT '工程号' , 
                        `project_name` VARCHAR(200) NOT NULL COMMENT '单体名称' , 
                        `entry_name` VARCHAR(200) NOT NULL COMMENT '项目名称' , 
                        `project_subcontractor` VARCHAR(300) NULL COMMENT '分包人' , 
                        `design_area` VARCHAR(50) NULL COMMENT '设计面积' , 
                        `contract_amount` VARCHAR(50) NULL COMMENT '主体合同额' , 
                        `stage_proportions` VARCHAR(50) NULL COMMENT '阶段比例' , 
                        `difficulty_system` VARCHAR(11) NULL COMMENT '难度系统' ,  
                        `distribution_ratio` VARCHAR(11) NULL COMMENT '分配比例' , 
                        `residual_coefficient` VARCHAR(11) NULL COMMENT '其他系数' , 
                        `drawplan_major` VARCHAR(11) NULL COMMENT '专业' , 
                        `designer` VARCHAR(300) NULL COMMENT '设计人员' , 
                        `design_price` FLOAT(10,2) NULL COMMENT '设计单价' , 
                        `design_value` FLOAT(10,2) NULL COMMENT '设计产值' , 
                        `proofreader` VARCHAR(300) NULL COMMENT '校对人员' , 
                        `proofreading_price` FLOAT(10,2) NULL COMMENT '校对单价' , 
                        `proofreading_value` FLOAT(10,2) NULL COMMENT '校对产值' , 
                        `auditor` VARCHAR(300) NULL COMMENT '审核人员' , 
                        `audit_price` FLOAT(10,2) NULL COMMENT '审核单价' , 
                        `audit_value` FLOAT(10,2) NULL COMMENT '审核产值' , 
                        `work_boss` VARCHAR(300) NULL COMMENT '工种负责人' , 
                        `work_basenumber` FLOAT(10,2) NULL COMMENT '工种单价' , 
                        `work_value` FLOAT(10,2) NULL COMMENT '工种产值' , 
                        `project_boss` VARCHAR(300) NULL COMMENT '工程人员' , 
                        `project_basenumber` FLOAT(10,2) NULL COMMENT '工程单价' , 
                        `project_value` FLOAT(10,2) NULL COMMENT '工程产值' , 
                        `other_expenses` FLOAT(10,2) NULL DEFAULT '0' COMMENT '其他费用' , 
                        `value_subtotal` FLOAT(10,2) NULL COMMENT '小计' , 
                        `department` VARCHAR(30) NULL COMMENT '部门' , 
                        `drawing_time` DATE NULL COMMENT '出图时间' , 
                        `remarks` VARCHAR(140) NULL COMMENT '备注' , 
                        PRIMARY KEY (`id`)) ENGINE = InnoDB COMMENT = '".$data['project_id']."工程产值';";
                Db::execute($sql1);
                Db::execute($sql2);
                Db::execute($sql3);
                for($i=1;$i<=23;$i++)
                {$result=Db::table('projectdesigner'.$data['project_id'])
                    ->insert([
                        'project_id'=>$data['project_id'],
                        'project_design'=>$data['name'.$i],
                        'projectdesigner_type'=>$data['projectdesigner_type'.$i],
                        'projectdesigner_design'=>$data['projectdesigner_design'.$i],
                        'project_subcontractor'=>$data['project_subcontractor'.$i],
                    ]);}
                        if ($res === null) {
                            $status = 0;
                            $message = '添加失败~~';
                        }
                        return ['status'=>$status, 'message'=>$message];
        }
    }


    //渲染工程详情界面
    public function project_details(Request $request)
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("project_view");
        $contract_amount_limit=Db::table('green_administrators')->where('staff_id',$sid)->value("contract_amount_limit");
        $this -> view -> assign('limit', $limit);
        $this -> view -> assign('contract_amount_limit', $contract_amount_limit);
        //获取到要编辑的工程号
        $project_id = $request -> param('id');
        $data=['project_id'=>$project_id];
        
        //根据ID和手机号进行查询
        $result =GreenProject::get($data);
        if (!$result) {
            return ['status'=>0,'msg'=>"无匹配数据"];
        }

        // $result1 =GreenProjectdesign::get($data);
        $result2=Db::table('projectdesigner'.$project_id)->where($data)->select();
        $result3=Db::table('green_projectphase')->where($data)->select();
        // $result3 =GreenProject::get($data);
        // $result4 =GreenProjectbuildtype::get($data);
        $result5=Db::table('green_projectdraw')->where($data)->select();
        $result6=Db::table('green_projectvalue'.$project_id)->select();

        $test = Db::query('SHOW FULL COLUMNS FROM '.'green_projectdraw');
        $test2 = Db::query('SHOW FULL COLUMNS FROM '.'green_projectphase');
        //给当前编辑模板赋值
        $this->view->assign('jibenxinxi',$result);
        $this->view->assign('shejirenyuan',$result2);
        $this->view->assign('projectvalue',$result6);
        //获取字段备注
        // $data = Db::query('SHOW FULL COLUMNS FROM '.'green_project');
        $note=[];
        if (count($result5)>0) {
            
            $i=0;
            foreach ($test as $key => $value) {  //把对象数据变为数组
            if($i == 0)
                $note[$i]["cont"]=$result5[0]['project_id'];
            else if($i == 1)
                $note[$i]["cont"]=$result5[0]['draw_concept'];
            else if($i == 2)
                $note[$i]["cont"]=$result5[0]['draw_plan'];
            else if($i == 3)
                $note[$i]["cont"]=$result5[0]['draw_preliminary'];
            else if($i == 4)
                $note[$i]["cont"]=$result5[0]['draw_pile'];
            else if($i == 5)
                $note[$i]["cont"]=$result5[0]['draw_work'];
            else if($i == 6)
                $note[$i]["cont"]=$result5[0]['draw_modify'];
            else if($i == 7)
                $note[$i]["cont"]=$result5[0]['contact']; 
            else if($i == 8)
                $note[$i]["cont"]=$result5[0]['others'];

            $note[$i]["Field"]=$value['Field'];

               
            $note[$i++]["Comment"]=$value['Comment'];

            // $note[$i]=$result5[]
            }
            // 删除多余一项 工程id
            array_splice($note,0,1);
        }
        $this -> view -> assign('note', $note);
        $plan=[];
        if (count($result3)>0) {
            
            $j=0;
            foreach ($test2 as $key => $value) {  //把对象数据变为数组
                if($j == 0)
                    $plan[$j]["cont"]=$result3[0]['project_id'];
                else if($j == 1)
                    $plan[$j]["cont"]=$result3[0]['phase_name1'];
                else if($j == 2)
                    $plan[$j]["cont"]=$result3[0]['phase_name2'];
                else if($j == 3)
                    $plan[$j]["cont"]=$result3[0]['phase_name3'];
                else if($j == 4)
                    $plan[$j]["cont"]=$result3[0]['phase_name4'];
                else if($j == 5)
                    $plan[$j]["cont"]=$result3[0]['phase_name5'];
                else if($j == 6)
                    $plan[$j]["cont"]=$result3[0]['phase_name6'];
                else if($j == 7)
                    $plan[$j]["cont"]=$result3[0]['phase_name7'];
                else if($j == 8)
                    $plan[$j]["cont"]=$result3[0]['phase_name8'];
                else if($j == 9)
                    $plan[$j]["cont"]=$result3[0]['phase_name9'];
                else if($j == 10)
                    $plan[$j]["cont"]=$result3[0]['phase_name10'];
                else if($j == 11)
                    $plan[$j]["cont"]=$result3[0]['phase_name11']; 
                else if($j == 12)
                    $plan[$j]["cont"]=$result3[0]['phase_name12'];
                else if($j == 13)
                    $plan[$j]["cont"]=$result3[0]['phase_name13'];
                else if($j == 14)
                    $plan[$j]["cont"]=$result3[0]['phase_name14'];
                else if($j == 15)
                    $plan[$j]["cont"]=$result3[0]['phase_name15'];
                else if($j == 16)
                    $plan[$j]["cont"]=$result3[0]['phase_name16'];
                else if($j == 17)
                    $plan[$j]["cont"]=$result3[0]['phase_name17'];
                else if($j == 18)
                    $plan[$j]["cont"]=$result3[0]['phase_name18'];
                else if($j == 19)
                    $plan[$j]["cont"]=$result3[0]['phase_name19'];
                else if($j == 20)
                    $plan[$j]["cont"]=$result3[0]['phase_name20'];
                $plan[$j]["Field"]=$value['Field'];
                $plan[$j++]["Comment"]=$value['Comment'];
                // 删除多余一项 工程id
            }
                array_splice($plan,0,1);
        }
        
        // $count = Db::table('green_projectvalue')->where($data)->count();
        // $list = Db::table('green_projectvalue')->where($data)->select();
        // $this -> view -> assign('list', $list);
        // $this -> view -> assign('count', $count);
        $this -> view -> assign('plan', $plan);
            
        //渲染编辑模板
        if ($request -> param('type')) {
            return ['status'=>1, 'plan'=>$plan, 'jibenxinxi'=>$result, 'shejirenyuan'=>$result2, 'projectvalue'=>$result6,'note'=>$note];
        }
        else
            return $this->view->fetch('project_details');
    }
   
   //编辑基本信息
    public function projectEdit(Request $request)
    {
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
        if($data['column']=='aboveground_area'||$data['column']=='underground_area'){
            $a=$ar->where([
                'project_id'=>$data['project_id'],
            ])->value('aboveground_area');
            $b=$ar->where([
                'project_id'=>$data['project_id'],
            ])->value('underground_area');
            $ar
            ->where([
                'project_id'=>$data['project_id'],
            ])
            ->update([
                'project_totalarea'=>$a+$b,
            ]);
        }
        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    // 设计人员编辑
    public function designerEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result =Db::table('projectdesigner'.$data['project_id'])
            ->where([
                'project_id'=>$data['project_id'],
                'project_design'=>$data['column']
            ])
            ->update([
                'projectdesigner_design'=>$data['projectdesigner_design'],
                'projectdesigner_type'=>$data['projectdesigner_type'],
                'project_subcontractor'=>$data['project_subcontractor'],
            ]);
        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    // // 分包人单条编辑
    // public function subcontractorEdit(Request $request)
    // {
    //     //获取数据
    //     $data = $request -> param();
    //     $result =Db::table('green_projectdesigner')
    //         ->where([
    //             'project_id'=>$data['project_id'],
    //             'project_design'=>$data['column']
    //         ])
    //         ->update([
    //             'project_subcontractor'=>$data['content'],
    //         ]);

    //     if (null!=$result) {
    //         return ['status'=>1, 'message'=>'更新成功'];
    //     } else {
    //         return ['status'=>0, 'message'=>'更新失败,请检查'];
    //     }
    // }
    //工程设计阶段编辑
    public function projectPhaseEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result =Db::table('green_projectphase')
            ->where([
                'project_id'=>$data['project_id'],
            ])
            ->update([
                $data['column']=>$data['content'],
            ]);

        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //工程完成计划编辑
    public function ProjectDrawEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result =Db::table('green_projectdraw')
            ->where([
                'project_id'=>$data['project_id']
            ])
            ->update([
                $data['column']=>$data['content'],
            ]);

        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //合同基本信息
    public function contractEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result = Db::table('green_contract')
            ->where([
                'contract_id'=>$data['contract_id'],
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
    // 合同 设计单价编辑
    public function contractUnitpriceEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result =Db::table('green_contractunitprice')
            ->where([
                'contract_id'=>$data['contract_id'],
                'contract_content'=>$data['column']
            ])
            ->update([
                'contract_unitprice'=>$data['unitPrice'],
                'contract_floatingrate'=>$data['floatingrate'],
                'contract_remarks'=>$data['remarks'],
            ]);

        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //设计阶段编辑
    public function contractPhaseEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result =Db::table('green_contractphase')
            ->where([
                'contract_id'=>$data['contract_id'],
            ])
            ->update([
                $data['column']=>$data['content'],
            ]);

        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //合同台账
    public function ledgerListEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result = Db::table('green_contractledger')
            ->where([
                'contract_id'=>$data['contract_id'],
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
    //合同台账已完成阶段单条修改
    public function accountPhaseEdit()
    {
        $data = $request -> param();
        $result = Db::table('green_contractaccountphase')
            ->where([
                'contract_id'=>$data['contract_id'],
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
    //合同台账发票单条修改
    public function confirmEdit(Request $request)
    {
        $data = $request -> param();
        $result = Db::table('green_confirm')
            ->where([
                'confirm_id'=>$data['confirm_id'],
            ])
            ->update([
                'invoice_date'=>$data['invoice_date'],
                'invoice_amount'=>$data['invoice_amount'],
                'payment_date'=>$data['payment_date'],
                'payment_amount'=>$data['payment_amount'],
                'confirm_drawer'=>$data['confirm_drawer'],
                'confirm_applicant'=>$data['confirm_applicant'],
            ]);

        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //合同台账支付修改
    public function paymentEdit(Request $request)
    {
        $data = $request -> param();
        $result = Db::table('green_ledgernode')
            ->where([
                'id'=>$data['id'],
            ])
            ->update([
                'ledgernode_paymentratio'=>$data['ledgernode_paymentratio'],
                'ledgernode_payment'=>$data['ledgernode_payment'],
                'ledgernode_require'=>$data['ledgernode_require'],
                'ledgernode_status'=>$data['ledgernode_status'],
            ]);

        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }

//招投标
    public function bidListEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result = Db::table('green_bid')
            ->where([
                'toubiao_id'=>$data['toubiao_id'],
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
    //补偿费修改
    public function depositeEdit(Request $request)
    {
        $data = $request -> param();
        foreach ($data as $k=>$v)
        {
            if($v==''){$data[$k]=NULL;}
        }
        $result = Db::table('green_biddeposite')
            ->where([
                'id'=>$data['id'],
            ])
            ->update([
                'deposite_invoice_id'=>$data['deposite_invoice_id'],
                'deposite_invoice_price'=>$data['deposite_invoice_price'],
                'deposite_invoice_object'=>$data['deposite_invoice_object'],
                'deposite_invoice_date'=>$data['invoice_date'],
                'deposite_invoice_amount'=>$data['invoice_amount'],
                'deposite_payment_date'=>$data['payment_date'],
                'deposite_payment_amount'=>$data['payment_amount'],
            ]);
        $res1=Db::table('green_biddeposite')
            ->where('toubiao_id',$data['toubiao_id'])
            ->sum('deposite_payment_amount');
        Db::table('green_bid')
            ->where('toubiao_id',$data['toubiao_id'])
            ->update(['bid_deposite'=>$res1]);
        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //保证金修改
    public function compensationEdit(Request $request)
    {
        $data = $request -> param();
        foreach ($data as $k=>$v)
        {
            if($v==''){$data[$k]=NULL;}
        }
        $result = Db::table('green_bidcompensation')
            ->where([
                'id'=>$data['id'],
            ])
            ->update([
                'compensation_price'=>$data['price'],
                'compensation_invoice_date'=>$data['invoice_date'],
                'compensation_invoice_amount'=>$data['invoice_amount'],
                'compensation_payment_date'=>$data['payment_date'],
                'compensation_payment_amount'=>$data['payment_amount'],
            ]);
        $res1=Db::table('green_bidcompensation')
            ->where('toubiao_id',$data['id'])
            ->sum('compensation_payment_amount');
        Db::table('green_bid')
            ->where('toubiao_id',$data['id'])
            ->update(['bid_compensation'=>$res1]);
        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //进展阶段修改
    public function bidPhaseEdit(Request $request)
    {
        $phase = ["预审完成","投标完成","公示完成","合同签订","已结算","已归档","其他"];
        $data = $request -> param();
        foreach ($data as $k=>$v)
        {
            if($v==''){$data[$k]=NULL;}
        }
        $result = Db::table('green_bidphase')
            ->where([
                'toubiao_id'=>$data['toubiao_id'],
            ])
            ->update([
                $data['column'].''=>$data['content'],
            ]);
        $res = Db::table('green_bidphase')
            ->where([
                'toubiao_id'=>$data['toubiao_id'],
            ])->select();
        $phase_now = '';
            // 用来更新bid表中的进展阶段
        for ($i=1; $i <8; $i++) { 
            if ($res[0]["bidphase_phase".$i])
            {
                $phase_now = $phase[$i-1];
            }
        }
        $res1 = Db::table('green_bid')
            ->where([
                'toubiao_id'=>$data['toubiao_id'],
            ])
            ->update([
                'bid_progress'=>$phase_now,
            ]);
        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
//渲染工程产值编辑页面
    public function projectValue_Edit(Request $request)
    {
        $data=$request->param();
        $res=Db::table('green_projectvalue'.$data['project_id'])->where('id',$data['id'])->select();
        $this->view->assign('content',$res[0]);
        return $this->view->fetch('projectValue_edit');
    }
    //渲染个人产值详情
    public function personalValue_Edit(Request $request)
    {
        $data=$request->param();
        $staff_name = Db::table('green_personalvalue')->where('id',$data["Big_id"])->value("staff_name");
        $res=Db::table('green_personalvalue'.$staff_name)->where('id',$data["id"])->select();
        $this->view->assign('content',$res[0]);
        $this->view->assign('Big_id',$data["Big_id"]);
        return $this->view->fetch('personalValue_edit');
    }
   //工程产值编辑修改
    public function projectValueEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result = Db::table('green_projectvalue'.$data['project_id'])
            ->where([
                'id'=>$data['id']
            ])
            ->update([
                'entry_name'=>$data['entry_name'],
                'design_area'=>$data['design_area'],
                'contract_amount'=>$data['contract_amount'],
                'stage_proportions'=>$data['stage_proportions'],
                'difficulty_system'=>$data['difficulty_system'],
                'distribution_ratio'=>$data['distribution_ratio'],
                'residual_coefficient'=>$data['residual_coefficient'],
                'drawplan_major'=>$data['drawplan_major'],
                'designer'=>$data['designer'],
                'design_price'=>$data['design_price'],
                'design_value'=>$data['design_value'],
                'proofreader'=>$data['proofreader'],
                'proofreading_price'=>$data['proofreading_price'],
                'proofreading_value'=>$data['proofreading_value'],
                'auditor'=>$data['auditor'],
                'audit_price'=>$data['audit_price'],
                'audit_value'=>$data['audit_value'],
                'work_boss'=>$data['work_boss'],
                'work_basenumber'=>$data['work_basenumber'],
                'work_value'=>$data['work_value'],
                'project_boss'=>$data['project_boss'],
                'project_basenumber'=>$data['project_basenumber'],
                'project_value'=>$data['project_value'],
                'other_expenses'=>$data['other_expenses'],
                'value_subtotal'=>$data['value_subtotal'],
                'department'=>$data['department'],
                'drawing_time'=>$data['drawing_time'],
                'remarks'=>$data['remarks'],
            ]);

        if ($result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //渲染院产值编辑页面
    public function departmentValue_Edit(Request $request)
    {
        $data=$request->param('id');
        $res=Db::table('green_departmentvalue')->where('id',$data)->select();
        $this->view->assign('content',$res[0]);
        return $this->view->fetch('departmentValue_edit');
    }
//院产值编辑修改
    public function departmentValueEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result = Db::table('green_departmentvalue')
            ->where([
                'id'=>$data['id']
            ])
            ->update([
                'staff_department'=>$data['staff_department'],
                'staff_name'=>$data['staff_name'],
                'draw_date'=>$data['draw_date'],
                'total_personalvalue'=>$data['total_personalvalue'],
                'reward_coefficient'=>$data['reward_coefficient'],
                'special_allowance'=>$data['special_allowance'],
                'yearend_personalvalue'=>$data['yearend_personalvalue'],
                'departmentvalue_remarks'=>$data['departmentvalue_remarks'],
            ]);

        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }

    //院产值删除接口
    public function departmentValueDel(Request $request)
    {
        $data=$request->param();
        $res=Db::table('green_departmentvalue')->where('id',$data['id'])->delete();
        if (null!=$res) {
            return ['status'=>1, 'message'=>'删除成功'];
        } else {
            return ['status'=>0, 'message'=>'删除失败,请检查'];
        }
    }
    //个人产值编辑修改
    public function personalValueEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $staff_name = Db::table('green_personalvalue')->where('id',$data["Big_id"])->value("staff_name");
        $result = Db::table('green_personalvalue'.$staff_name)
            ->where([
                'id'=>$data['id']
            ])
            ->update([
                'draw_date'=>$data['draw_date'],
                'project_id'=>$data['project_id'],
                'project_name'=>$data['project_name'],
                'entry_name'=>$data['entry_name'],
                'output_value'=>$data['output_value'],
                'staff_remarks'=>$data['staff_remarks'],
            ]);
        $output_value=Db::table('green_personalvalue'.$staff_name)
                    ->sum('output_value');
        $result1 = Db::table('green_personalvalue')
            ->where([
                'staff_name'=>$staff_name
            ])
            ->update([
                'output_value'=>$output_value,
            ]);
        if ($result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    // 删除单条员工信息
    public function personalvalueDel(Request $request){
        $data = $request -> param();
        $res=Db::table('green_personalvalue')->where('id',$data['id'])->delete();
        if ($res) {
            return ['status'=>1, 'message'=>'删除成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    // 新增字段
    public function detailAdd()
    {
        return $this->view->fetch('detail-add');
    }
    // 新增公告
    public function notice_add()
    {
        return $this->view->fetch('notice-add');
    }
     //工程添加公告接口
    public function noticeAdd(Request $request)
    {
        $sid=Session::get('staff_id');
        $name=Db::table('green_administrators')->where('staff_id',$sid)->value("staff_name")."留言:";
        $date=date('Y-m-d', time()).'';
        $data=$request->param();
        
        $res=Db::table('green_project')->where('project_id',$data['project_id'])->value('project_notice').$date.' '.$name.$data['project_notice'].";";
        $result=Db::table('green_project')->where('project_id',$data['project_id'])->update(['project_notice'=>$res]);
        if (null!=$result) {
            return ['status'=>1, 'message'=>'添加成功'];
        } else {
            return ['status'=>0, 'message'=>'添加失败,请检查'];
        }

    }
    //工程公告修改接口
    public function noticeEdit(Request $request)
    {
        $data=$request->param();
        $res=Db::table('green_project')->where('project_id',$data['project_id'])->update(['project_notice'=>$data['project_notice']]);
    }
    // 新增管理员页面渲染
    public function adminAdd()
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('adminstrator');

        if ($limit == 2) {
            return $this->view->fetch('adminAdd');
        }
        else{
            return $this->view->fetch('noPower');
        }
    }
    public function detailEdit()
    {
        return $this->view->fetch('detail-edit');
    }
    // 新建工程
    public function newProject()
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('project_view');
        if($limit!=2)
            {
                return $this->view->fetch('noPower');
            }
        else
            {
                return $this->view->fetch('newproject');
            }
    }
     // 新建项目
    public function newProjectvalue()
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('project_view');
        if($limit!=2)
            {
                return $this->view->fetch('noPower');
            }
        else
            {
                return $this->view->fetch('newprojectvalue');
            }
    }
 //工程项目新增（包括三样产值新增）
    public function entryAdd(Request $request)
    {
        $data=$request->param();
//        if(Db::query('SHOW TABLES LIKE '."'".'green_project'.$data['project_id']."'")===[])
        $res=Db::table('green_projectvalue'.$data['project_id'])
            ->insert([
                'project_id'=>$data['project_id'],
                'project_name'=>$data['project_name'],
                'entry_name'=>$data['entry_name'],
                'project_subcontractor'=>$data['project_subcontractor'],
                'design_area'=>$data['design_area'],
                'stage_proportions'=>$data['stage_proportions'],
                'difficulty_system'=>$data['difficulty_system'],
                'distribution_ratio'=>$data['distribution_ratio'],
                'residual_coefficient'=>$data['residual_coefficient'],
                'drawplan_major'=>$data['major'],
                'designer'=>$data['designer'],
                'design_price'=>$data['design_price'],
                'design_value'=>$data['design_value'],
                'proofreader'=>$data['proofreader'],
                'proofreading_price'=>$data['proofreading_price'],
                'proofreading_value'=>$data['proofreading_value'],
                'auditor'=>$data['auditor'],
                'audit_price'=>$data['audit_price'],
                'audit_value'=>$data['audit_value'],
                'work_boss'=>$data['work_boss'],
                'work_basenumber'=>$data['work_basenumber'],
                'work_value'=>$data['work_value'],
                'project_boss'=>$data['project_boss'],
                'project_basenumber'=>$data['project_basenumber'],
                'project_value'=>$data['project_value'],
                'other_expenses'=>$data['other_expenses'],
                'value_subtotal'=>$data['value_subtotal'],
                'department'=>Db::table('green_staff')->where('staff_name',$data['designer'])->value('staff_department'),
                'drawing_time'=>$data['drawing_time'],
                'remarks'=>$data['remarks'],
            ]);
        $design_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('design_value');
        $proofreading_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('proofreading_value');
        $audit_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('audit_value');
        $work_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('work_value');
        $project_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('project_value');
        $other_expenses=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('other_expenses');
        $value_subtotal=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('value_subtotal');
        $design_area=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('design_area');
        $ground_floor_area=Db::table('green_projectvalue'.$data['project_id'])
            ->where(['ground_floor'=>'1'])
            ->sum('design_area');
        $underground_building_area=Db::table('green_projectvalue'.$data['project_id'])
            ->where(['ground_floor'=>'0'])
            ->sum('design_area');
        $total_building_area=$ground_floor_area+$underground_building_area;

        Db::table('green_project_totalvalue')
            ->where('project_id',$data['project_id'])
            ->update([
                'project_id'=>$data['project_id'],
                'project_name'=>$data['project_name'],
                'entry_name'=>$data['entry_name'],
                'project_subcontractor'=>$data['project_subcontractor'],
                'subject_contract_amount'=>$design_area,
                'design_value'=>$design_value,
                'check_value'=>$proofreading_value,
                'worktype_value'=>$work_value,
                'examine_value'=>$audit_value,
                'project_value'=>$project_value,
                'other_expenses'=>$other_expenses,
                'design_area'=>$proofreading_value,
                'ground_floor_area'=>$ground_floor_area,
                'underground_building_area'=>$underground_building_area,
                'total_building_area'=>$total_building_area,
                'total_department'=>$value_subtotal
            ]);
        Db::table('green_personalvalue')
              ->insert([
                 'staff_department'=>Db::table('green_staff')->where('staff_name',$data['designer'])->value('staff_department'),
                 'staff_name'=>$data['designer'],
                 'draw_date'=>$data['drawing_time'],
                 'project_id'=>$data['project_id'],
                 'project_name'=>$data['project_name'],
                 'entry_name'=>$data['entry_name'],
                 'output_value'=>$data['design_value'],
                 'staff_remarks'=>'暂无',
              ]);
         Db::table('green_personalvalue')
              ->insert([
                 'staff_department'=>Db::table('green_staff')->where('staff_name',$data['proofreader'])->value('staff_department'),
                 'staff_name'=>$data['proofreader'],
                 'draw_date'=>$data['drawing_time'],
                 'project_id'=>$data['project_id'],
                 'project_name'=>$data['project_name'],
                 'entry_name'=>$data['entry_name'],
                 'output_value'=>$data['proofreading_value'],
                 'staff_remarks'=>'暂无',
              ]);
         Db::table('green_personalvalue')
             ->insert([
                 'staff_department'=>Db::table('green_staff')->where('staff_name',$data['auditor'])->value('staff_department'),
                 'staff_name'=>$data['auditor'],
                 'draw_date'=>$data['drawing_time'],
                 'project_id'=>$data['project_id'],
                 'project_name'=>$data['project_name'],
                 'entry_name'=>$data['entry_name'],
                 'output_value'=>$data['audit_value'],
                 'staff_remarks'=>'暂无',
              ]);
         Db::table('green_personalvalue')
              ->insert([
                 'staff_department'=>Db::table('green_staff')->where('staff_name',$data['work_boss'])->value('staff_department'),
                 'staff_name'=>$data['work_boss'],
                 'draw_date'=>$data['drawing_time'],
                 'project_id'=>$data['project_id'],
                 'project_name'=>$data['project_name'],
                 'entry_name'=>$data['entry_name'],
                 'output_value'=>$data['work_value'],
                 'staff_remarks'=>'暂无',
              ]);
         Db::table('green_personalvalue')
              ->insert([
                 'staff_department'=>Db::table('green_staff')->where('staff_name',$data['project_boss'])->value('staff_department'),
                 'staff_name'=>$data['project_boss'],
                 'draw_date'=>$data['drawing_time'],
                 'project_id'=>$data['project_id'],
                 'project_name'=>$data['project_name'],
                 'entry_name'=>$data['entry_name'],
                 'output_value'=>$data['project_value'],
                 'staff_remarks'=>'暂无',
              ]);
         Db::table('green_departmentvalue')
             ->insert([
                 'staff_department'=>Db::table('green_staff')->where('staff_name',$data['proofreader'])->value('staff_department'),
                 'staff_name'=>$data['proofreader'],
                 'draw_date'=>$data['drawing_time'],
                 'total_personalvalue'=>$data['proofreading_value'],
                 'reward_coefficient'=>1,
                 'special_allowance'=>0,
                 'yearend_personalvalue'=>$data['proofreading_value'],
                 'departmentvalue_remarks'=>'',
             ]);
        Db::table('green_departmentvalue')
            ->insert([
                'staff_department'=>Db::table('green_staff')->where('staff_name',$data['auditor'])->value('staff_department'),
                'staff_name'=>$data['auditor'],
                'draw_date'=>$data['drawing_time'],
                'total_personalvalue'=>$data['audit_value'],
                'reward_coefficient'=>1,
                'special_allowance'=>0,
                'yearend_personalvalue'=>$data['audit_value'],
                'departmentvalue_remarks'=>'',
            ]);
        Db::table('green_departmentvalue')
            ->insert([
                'staff_department'=>Db::table('green_staff')->where('staff_name',$data['work_boss'])->value('staff_department'),
                'staff_name'=>$data['work_boss'],
                'draw_date'=>$data['drawing_time'],
                'total_personalvalue'=>$data['work_value'],
                'reward_coefficient'=>1,
                'special_allowance'=>0,
                'yearend_personalvalue'=>$data['work_value'],
                'departmentvalue_remarks'=>'',
            ]);
        Db::table('green_departmentvalue')
            ->insert([
                'staff_department'=>Db::table('green_staff')->where('staff_name',$data['designer'])->value('staff_department'),
                'staff_name'=>$data['designer'],
                'draw_date'=>$data['drawing_time'],
                'total_personalvalue'=>$data['design_value'],
                'reward_coefficient'=>1,
                'special_allowance'=>0,
                'yearend_personalvalue'=>$data['design_value'],
                'departmentvalue_remarks'=>'',
            ]);
        Db::table('green_departmentvalue')
            ->insert([
                'staff_department'=>Db::table('green_staff')->where('staff_name',$data['project_boss'])->value('staff_department'),
                'staff_name'=>$data['project_boss'],
                'draw_date'=>$data['drawing_time'],
                'total_personalvalue'=>$data['project_value'],
                'reward_coefficient'=>1,
                'special_allowance'=>0,
                'yearend_personalvalue'=>$data['project_value'],
                'departmentvalue_remarks'=>'',
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

    // 新建合同
    public function newContract()
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('contract_view');
        if($limit!=2)
            {
                return $this->view->fetch('noPower');
            }
        else
            {
                return $this->view->fetch('newcontract');
            }
    }
    // 新增开票
    public function invoice()
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('invoice_new');
        if($limit==2)
            {
                return $this->view->fetch('invoice');
            }
        else{
                return $this->view->fetch('noPower');
            }        
    }
    // 开票记录
    public function invoiceHistory(Request $request)
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('invoice_new');
        $data = $request -> param();
  
        $count = Db::table('green_biddeposite')->count()+ Db::table('green_bidcompensation')->count();
        $list = Db::table('green_biddeposite')->order('toubiao_id desc')->paginate(10);
        $list1 = Db::table('green_bidcompensation')->paginate(10);
        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('orderList1', $list1);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', 10);

        if($limit==2)
            {
                return $this->view->fetch('invoiceHistory');
            }
        else{
                return $this->view->fetch('noPower');
            }        
    }
    public function invoiceAdd(Request $request)
    {
        $data = $request -> param();
        $type = $data['type'];
        $invoice = explode('^', $data['invoice']);
        for ($i=1; $i < count($invoice); $i++) {
            $contents = explode('*', $invoice[$i]);
            if ($type=="合同设计费") {
                $res2=Db::table('green_confirm')
                ->insert([
                    'contract_id'=>$data['id'],
                    'invoice_date'=>$contents[0],
                    'invoice_amount'=>$contents[1],
                    'payment_date'=>$contents[2],
                    'payment_amount'=>$contents[3],
                ]);
            }
            elseif ($type=="补偿费") {
                $res2=Db::table('green_biddeposite')
                ->insert([
                    'toubiao_id'=>$data['id'],
                    'deposite_invoice_id'=>$contents[0],
                    'deposite_invoice_price'=>$contents[1],
                    'deposite_invoice_object'=>$contents[2],
                    'deposite_invoice_date'=>$contents[3],
                    'deposite_invoice_amount'=>$contents[4],
                    'deposite_payment_date'=>$contents[5],
                    'deposite_payment_amount'=>$contents[6],
                ]);
                $re=Db::table('green_bidcompensation')
                    ->where('toubiao_id',$data['id'])
                    ->sum('compensation_payment_amount');
                Db::table('green_bid')
                    ->where('toubiao_id',$data['id'])
                    ->update(['bid_compensation'=>$re]);
            }
            elseif ($type=="保证金") {
                $res2=Db::table('green_bidcompensation')
                ->insert([
                    'toubiao_id'=>$data['id'],
                    'compensation_price'=>$contents[0],
                    'compensation_invoice_date'=>$contents[1],
                    'compensation_invoice_amount'=>$contents[2],
                    'compensation_payment_date'=>$contents[3],
                    'compensation_payment_amount'=>$contents[4],
                ]);
                $re=Db::table('green_biddeposite')
                    ->where('toubiao_id',$data['id'])
                    ->sum('deposite_payment_amount');
                Db::table('green_bid')
                    ->where('toubiao_id',$data['id'])
                    ->update(['bid_deposite'=>$re]);
            }
        }
        if ($res2 === null) {
            $status = 0;
            $message = '添加失败~~';
        }
        else{$status = 1;
            $message = '添加成功';
        }
        return json(['status'=>$status, 'message'=>$message]);
    }
    //添加员工
    public function roleAdd()
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('staff');
        if ($limit != 2) {
            return $this->view->fetch('noPower');
        }
        else{
            return $this->view->fetch('roleAdd');
        }
    }
   //添加员工
public function StaffAdd(Request $request)
{
    $data=$request->param();
    $yuan=  [1,1,1,1,0,1,0,2,2,2,0,0];
    $sheng= [2,2,0,0,2,2,2,2,2,0,0,0];
    $cai=   [1,1,2,2,0,1,0,0,1,2,2,2];
    $ban=   [2,1,0,0,0,2,0,2,1,0,0,2];
    $rest=  [1,0,0,0,0,1,0,0,1,0,0,0];
    $temp=[];
    // $count = Db::table('green_staff')->field(max('staff_id'));
    $checkName = Db::table('green_administrators')->where('administrators_name',$data['staff_telphole_1'])->select();
    if($checkName){
        return ['status'=>3, 'message'=>"该手机号码已存在用户"];
    }
    $res=Db::table('green_staff')
        // ->allowField(true)
        ->insert([
            'enable'=>$data['enable'],
            'administrators_name'=>$data['staff_telphole_1'],
            'staff_name'=>$data['name'],
            'staff_phone'=>$data['phone'],
            'staff_telphole_1'=>$data['staff_telphole_1'],
            'staff_extension'=>$data['extension'],
            'staff_position'=>$data['position'],
            'staff_department'=>$data['department'],
            'staff_status'=>$data['status'],
            'staff_shortmobile'=>$data['shortmobile'],
            'staff_practising'=>$data['practising'],
            'staff_title'=>$data['staff_title'],
            'staff_QQ'=>$data['QQ'],
            'staff_wechat'=>$data['wechat'],
            'staff_dingding'=>$data['staff_dingding'],
            'staff_email'=>$data['staff_email'],
            'staff_other'=>$data['staff_other'],
        ]);
    switch ($data['department'])
    {
        case '院长室':
           $temp=$yuan;
            break;
        case '生产经营部':
            $temp=$sheng;
            break;
        case '财务部':
            $temp=$cai;
            break;
        case '办公室':
            $temp=$ban;
            break;
        default:
            $temp=$rest;
    }
    if($data['enable']==1){
        $data['admin']='普通管理员';
    }
    else{
        $data['admin']=0;
    }
    // 默认用户名为电话号码staff_telphole_1
    Db::table('green_administrators')
        ->insert([
            'staff_name'=>$data['name'],
            'administrators_name'=>$data['staff_telphole_1'],
            'administrators_password'=>md5($data['administrators_password']),
            'admin'=>$data['admin'],
            'enable'=>$data['enable'],
            'project_view'=>$temp[0],
            'contract_view'=>$temp[1],
            'ledger'=>$temp[2],
            'bid'=>$temp[3],
            'adminstrator'=>$temp[4],
            'staff'=>$temp[5],
            'jurisdiction'=>$temp[6],
            'customer'=>$temp[7],
            'project_value'=>$temp[8],
            'department_value'=>$temp[9],
            'staff_value'=>$temp[10],
            'invoice_new'=>$temp[11],
            'update_time'=>'00:00:00',
            'administrators_lastTime'=>time(),
            'admin_status'=>0,
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
     // 员工列表
     public function personal(Request $request)
   {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("staff");
        // dump($sid);
        $this -> view -> assign('limit', $limit);
        $data = $request -> param();
        if($limit != 2)
        {
            // 用户为本人，且无员工管理权限
            $staff_name = Db::table('green_administrators')->where('staff_id',$sid)->value("staff_name");
            $count = Db::table('green_staff')->where('staff_name',$staff_name)->count();
            $list = Db::table('green_staff')->where('staff_name',$staff_name)->paginate(10); 
        }
        else{
            $count = Db::table('green_staff')->count();
            $list = Db::table('green_staff')->order("staff_name desc")->paginate(10); 
        }
        

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', 10);
        //渲染管理员列表模板
        return $this -> view -> fetch('personal');
    
    }
   //员工统计模糊筛选
    public function StaffSelectAll(Request $request){

        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("staff");
        $this -> view -> assign('limit', $limit);

        $data=$request->param();
        $res=Db::table('green_staff')
            ->whereor([
                'staff_id'=>['like','%'.$data['content'].'%'],
                'staff_name'=>['like','%'.$data['content'].'%'],
                'staff_phone'=>['like', '%'.$data['content'].'%'],
                'staff_extension'=>['like','%'.$data['content'].'%'],
                'staff_position'=>['like','%'.$data['content'].'%'],
                'staff_department'=>['like','%'.$data['content'].'%'],
                'staff_status'=>['like','%'.$data['content'].'%'],
                'staff_shortmobile'=>['like','%'.$data['content'].'%'],
                'staff_practising'=>['like','%'.$data['content'].'%'],
                'staff_QQ'=>['like','%'.$data['content'].'%'],
                'staff_wechat'=>['like','%'.$data['content'].'%'],
            ])
            ->order("staff_name desc")
            ->paginate($data['pagenumber'],false,["query"=>$data]);
        $count=Db::table('green_staff')
            ->whereor([
                'staff_id'=>['like','%'.$data['content'].'%'],
                'staff_name'=>['like','%'.$data['content'].'%'],
                'staff_phone'=>['like', '%'.$data['content'].'%'],
                'staff_extension'=>['like','%'.$data['content'].'%'],
                'staff_position'=>['like','%'.$data['content'].'%'],
                'staff_department'=>['like','%'.$data['content'].'%'],
                'staff_status'=>['like','%'.$data['content'].'%'],
                'staff_shortmobile'=>['like','%'.$data['content'].'%'],
                'staff_practising'=>['like','%'.$data['content'].'%'],
                'staff_QQ'=>['like','%'.$data['content'].'%'],
                'staff_wechat'=>['like','%'.$data['content'].'%'],
            ])
            ->count();
    $this -> view -> assign('orderList', $res);
    $this -> view -> assign('count', $count);
    $this -> view -> assign('pagenumber', $data['pagenumber']);
    return $this -> view -> fetch('personal');
    }
     // 员工详细
     public function personal_details(Request $request)
   {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("staff");
        $power=Db::table('green_administrators')->where('staff_id',$sid)->value("admin");
        $isself = 0;
        $staff_id = $request -> param('staff_id');
        $staff_name = Db::table('green_staff')->where(['staff_id'=>$staff_id])->value("staff_name");
        if ($staff_id == $sid) {
            $isself = 1;
        }

         $this -> view -> assign('limit', $limit);
         $this -> view -> assign('power', $power);
         $this -> view -> assign('isself', $isself);
        $col = Db::query('SHOW FULL COLUMNS FROM '.'green_staff');

        $result1=Db::table('green_staff')->where(['staff_id'=>$staff_id])->select();
        $this->view->assign('staff',$result1[0]);
        $admin = Db::query('SHOW FULL COLUMNS FROM '.'green_administrators');
        $id = Db::table('green_administrators')->where(['staff_name'=>$staff_name])->value("staff_id");
        $result2=Db::table('green_administrators')->where(['staff_id'=>$id])->select();
        $admin = [];
        foreach ($result2[0] as $key => $value)
        {
            if($value == "0")
            {
                $admin[$key]="不可读";
            }
            else if($value == "1")
            {
                $admin[$key]="可读不可写";
            }
            else if($value == "2")
            {
                $admin[$key]="可读可写";
            }
        }
        $this->view->assign('admin',$admin);
        //渲染管理员列表模板
        return $this -> view -> fetch('personal_details');
    }
    //编辑员工
    public function personalEdit(Request $request)
    {
        //获取数据
        $data = $request -> param(); 
        // 修改员工姓名
        if($data['column'] == 'staff_name'){
            $name = Db::table('green_staff')
            ->where([
                'staff_id'=>$data['id'],
            ])
            ->value("staff_name");
            // $id = Db::table('green_administrators')
            // ->where([
            //     'staff_name'=>$name,
            // ])
            // ->value("staff_id");
            $result2 = Db::table('green_administrators')
            ->where([
                'staff_name'=>$name,
            ])
            ->update([
                'staff_name'=>$data['content'],
            ]);
            // exit;
        }
            // 修改登录名情况
        if($data['column'] == 'administrators_name'){
            $name = Db::table('green_staff')
            ->where([
                'staff_id'=>$data['id'],
            ])
            ->value("staff_name");
             $result2 = Db::table('green_administrators')
            ->where([
                'staff_name'=>$name,
            ])
            ->update([
                'administrators_name'=>$data['content'],
            ]);
        }

        $result = Db::table('green_staff')
            ->where([
                'staff_id'=>$data['id'],
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
    // 删除单条员工信息
    public function del_person(Request $request){
        $data = $request -> param();
        $res=Db::table('green_staff')->where('staff_id',$data['staff_id'])->delete();
        if ($res) {
            return ['status'=>1, 'message'=>'删除成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //编辑权限
    public function adminEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result = Db::table('green_administrators')
            ->where([
                'staff_id'=>$data['id'],
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

    //修改密码页面
    public function passwordEdit(Request $request){
        $data = $request -> param('id');
        
        $content = ['id'=>$data];
        $this -> view -> assign('content', $content);
        return $this -> view -> fetch("password_edit");
    }
    //修改密码
    public function passwordChange(Request $request) {
        //获取数据
        $data = $request -> param();
        $name =Db::table('green_staff')->where('staff_id',$data['id'])->value("staff_name");
        $pass = Db::table('green_administrators')
            ->where([
                'staff_name'=>$name,
            ])->value("administrators_password");
        if (strlen($data["newpass"])<6||strlen($data["newpass"])>16) {
            return ['status'=>0, 'message'=>'新密码长度应为6~16,请检查'];
        }
        if (md5($data["prepass"])== $pass) {
            $result = Db::table('green_administrators')
            ->where(['staff_name'=>$name])
            ->update(['administrators_password'=>md5($data['newpass'])]);
            return ['status'=>1, 'message'=>'修改成功'];
        }
        else
        {
            return ['status'=>0, 'message'=>'原密码错误'];
        }
        
    }


    // 查看合同
    public function contract(Request $request)
   {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("contract_view");
        if ($limit ==0) {
            return $this -> view -> fetch('noPower');
        }
        $this -> view -> assign('limit', $limit);
        $data = $request -> param();

        $count = Db::table('green_contract')->count();
        $list = Db::table('green_contract')->order("project_id desc")->paginate(10);  
        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber',10);
        //渲染管理员列表模板
        return $this -> view -> fetch('contract');
    }
    //合同筛选接口
    public function ContractSelect(Request $request)
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("ledger");
        if ($limit ==0) {
            return $this -> view -> fetch('noPower');
        }
        $this -> view -> assign('limit', $limit);
        $data = $request -> param();
        $info=[];
        $res=[];
        $sum=0;
        foreach ($data as $key => $value)
        {  
            if($value&&$key!="pagenumber"&&$key!="page"&&$key!="start"&&$key!="end")
            {
                $info[$key]=$value;
            }
        }
        foreach ($data as $key => $value){
            if($data['start']&&$data['end'])
                {
                    $count=Db::table('green_contract')
                        ->where($info)
                        ->where('contract_signtime','BETWEEN',[$data['start'],$data['end']])
                        ->count();
                    if ($data['pagenumber'] == "全部") {
                        $data['pagenumber'] = $count;
                    }
                    $res=Db::table('green_contract')
                        ->where($info)
                        ->where('contract_signtime','BETWEEN',[$data['start'],$data['end']])
                        ->order("contract_signtime desc")
                        ->paginate($data['pagenumber'],false,["query"=>$data]);
                }
            else
                {
                $count=Db::table('green_contract')
                    ->where($info)
                    ->count();
                if ($data['pagenumber'] == "全部") {
                    $data['pagenumber'] = $count;
                }
                $res=Db::table('green_contract')
                    ->where($info)
                    ->order("project_id desc")
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                }
        }
            $this -> view -> assign('orderList', $res);
            $this -> view -> assign('count', $count);
            $this -> view -> assign('pagenumber', $data["pagenumber"]);
         //渲染管理员列表模板
         return $this -> view -> fetch('contract');
    }


//合同模糊筛选
    public function ContractselectAll(Request $request){
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("ledger");
        if ($limit ==0) {
            return $this -> view -> fetch('noPower');
        }
        $this -> view -> assign('limit', $limit);
        $data=$request->param();
        $res=Db::table('green_contract')
            ->whereor([
                'contract_id'=>['like','%'.$data['content'].'%'],
                'project_id'=>['like', '%'.$data['content'].'%'],
                'contract_type'=>['like', '%'.$data['content'].'%'],
                'contract_signtime'=>['like','%'.$data['content'].'%'],
                'contract_amount'=>['like','%'.$data['content'].'%'],
                'contract_compute'=>['like','%'.$data['content'].'%'],
                'contract_operator'=>['like','%'.$data['content'].'%'],
                // 'project_name'=>['like', '%'.$data['content'].'%'],
                'contract_projectleader'=>['like', '%'.$data['content'].'%'],
                'contract_approver'=>['like', '%'.$data['content'].'%'],
                    ])
            ->order("project_id desc")
            ->paginate($data['pagenumber'],false,["query"=>$data]);
        $count=Db::table('green_contract')
            ->whereor([
                'contract_id'=>['like','%'.$data['content'].'%'],
                'project_id'=>['like', '%'.$data['content'].'%'],
                'contract_type'=>['like', '%'.$data['content'].'%'],
                'contract_signtime'=>['like','%'.$data['content'].'%'],
                'contract_amount'=>['like','%'.$data['content'].'%'],
                'contract_compute'=>['like','%'.$data['content'].'%'],
                'contract_operator'=>['like','%'.$data['content'].'%'],
                // 'project_name'=>['like', '%'.$data['content'].'%'],
                'contract_projectleader'=>['like', '%'.$data['content'].'%'],
                'contract_approver'=>['like', '%'.$data['content'].'%'],
                    ])
            ->count();
        $this -> view -> assign('orderList', $res);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', $data["pagenumber"]);
        return $this -> view -> fetch('contract');
    }
    // 合同台账管理
     public function taizhangguanli(Request $request)
   {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("ledger");
        if ($limit ==0) {
            return $this -> view -> fetch('noPower');
        }
        $this -> view -> assign('limit', $limit);
        $data = $request -> param();

        $count = Db::table('green_contractledger')->count();
        $list = Db::table('green_contractledger')->order("contractledger_signtime desc")->paginate(10);  

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber',10);
        $confirm = false;
        $this -> view -> assign('confirm',$confirm);
        //渲染管理员列表模板
        return $this -> view -> fetch('taizhangguanli');
    }
     //合同台账管理详情
    public function taizhangguanli_details(Request $request){
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("ledger");
        $this -> view -> assign('limit', $limit);
        //获取到要编辑的工程号
        $contract_id = $request -> param('id');
        $data=['contract_id'=>$contract_id];

        $contract_invoiced=Db::table('green_confirm')
        ->where('contract_id',$contract_id)
        ->sum('invoice_amount');
        $payment_amount=Db::table('green_confirm')
        ->where('contract_id',$contract_id)
        ->sum('payment_amount');
        //求比例
        $contractledger_actual=Db::table('green_contractledger')
        ->where('contract_id',$contract_id)
        ->value('contractledger_actual');
        $contract_value=Db::table('green_contractledger')
        ->where('contract_id',$contract_id)
        ->value('contract_value');
   
        Db::table('green_contractledger')
        ->where([
            'contract_id'=>$contract_id,
        ])
        ->update([
            'contract_invoiced'=>$contract_invoiced,
            'contract_accepted'=>$payment_amount,
            'contract_acceptedratio'=>$payment_amount/(double)$contractledger_actual,
            'contract_unaccepted'=>(double)$contractledger_actual-$payment_amount,
            'contract_receivables'=>$contract_value-$contract_invoiced,
        ]);


        $res=Db::table('green_contractledger')->where($data)->select();
        $res1=Db::table('green_confirm')->where($data)->select();
        $total_invoice_amount = Db::table('green_confirm')
        ->where($data)
        ->sum('invoice_amount');
        $total_payment_amount = Db::table('green_confirm')
        ->where($data)
        ->sum('payment_amount');
        $res2=Db::table('green_contractaccountphase')->where($data)->select();
        $res3=Db::table('green_ledgernode')->where($data)->select(); 
        $this->view->assign(['ledgerList'=>$res[0],'confirm'=>$res1,'phase'=>$res2[0],'node'=>$res3,"total_invoice_amount"=>$total_invoice_amount,"total_payment_amount"=>$total_payment_amount]);
        return $this->view->fetch('taizhangguanli_details');
    }

    //新建台账
    public function ledgerAdd(Request $request)
    {
        $data=$request->param();

        if (GreenContractledger::get(['contract_id'=> $data['contract_id']])) {
    //如果在表中查询到该用户名
    $status = 0;
    $message1 = '该合同台账已存在,请重新输入~~';
    return ['status'=>$status, 'message'=>$message1];
        }
        else{
            $payment = explode('^', $data['payment']);
        for ($i=1; $i < count($payment); $i++) {
            $contents = explode('*', $payment[$i]);
            $res2=Db::table('green_ledgernode')
                ->insert([
                    'contract_id'=>$data['contract_id'],
                    'ledgernode_paymentratio'=>$contents[0],
                    'ledgernode_payment'=>$contents[1],
                    'ledgernode_require'=>$contents[2],
                    'ledgernode_status'=>$contents[3],
                ]);
            if (!$res2) {
                $status = 0;
                $message = '添加失败~~';
            }
            else{
                $status = 1;
                $message = '添加成功';
            }
        }
        $confirm = explode('^', $data['confirm']);
        for ($i=1; $i < count($confirm); $i++) {
            $contents = explode('*', $confirm[$i]);
            $res2=Db::table('green_confirm')
                ->insert([
                    'contract_id'=>$data['contract_id'],
                    'invoice_date'=>$contents[0],
                    'invoice_amount'=>floatval($contents[1]),
                    'payment_date'=>$contents[2],
                    'payment_amount'=>floatval($contents[3]),
                ]);
            if ($res2 === null) {
                $status = 0;
                $message = '添加失败~~';
            }
            else{
                $status = 1;
                $message = '添加成功';
            }
        }
        $res=Db::table('green_contractledger')
            ->insert([
                'contract_id'=>$data['contract_id'],
                'project_name'=>$data['project_name'],
                'contractledger_signtime'=>$data['contractledger_signtime'],
                'project_constructor'=>$data['project_constructor'],
                'project_agent'=>$data['project_agent'],
                'contractledger_amount'=>$data['contractledger_amount'],
                'contractledger_actual'=>$data['contractledger_actual'],
                'contractledger_remarks'=>$data['contractledger_remarks'],
            ]);
        if ($res === null) {
            $status = 0;
            $message = '添加失败~~';
        }
        else{
            $status = 1;
            $message = '添加成功';
        }
        
        for ($i=1; $i < 15; $i++) { 
            if ($data['contract_account_phase'.$i]=='') {
                $data['contract_account_phase'.$i]='0000-01-01';
            }
        }
        $res3=Db::table('green_contractaccountphase')
            ->insert([
                'contract_id'=>$data['contract_id'],
                'contract_account_phase1'=>$data['contract_account_phase1'],
                'contract_account_phase2'=>$data['contract_account_phase2'],
                'contract_account_phase3'=>$data['contract_account_phase3'],
                'contract_account_phase4'=>$data['contract_account_phase4'],
                'contract_account_phase5'=>$data['contract_account_phase5'],
                'contract_account_phase6'=>$data['contract_account_phase6'],
                'contract_account_phase7'=>$data['contract_account_phase7'],
                'contract_account_phase8'=>$data['contract_account_phase8'],
                'contract_account_phase9'=>$data['contract_account_phase9'],
                'contract_account_phase10'=>$data['contract_account_phase10'],
                'contract_account_phase11'=>$data['contract_account_phase11'],
                'contract_account_phase12'=>$data['contract_account_phase12'],
                'contract_account_phase13'=>$data['contract_account_phase13'],
                'contract_account_phase14'=>$data['contract_account_phase14'],
            ]);
        if (!$res3) {
            $status = 0;
            $message = '添加失败~~';
        }
        else{
            $status = 1;
            $message = '添加成功';
        }
        return ['status'=>$status, 'message'=>$message];
    }
    }

    //合同台账筛选接口新
    public function ContractLedgerSelect1(Request $request)
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('ledger');
        if($limit!=2)
            {
                return $this->view->fetch('noPower');
            }
        $this -> view -> assign('limit', $limit);
        $data = $request -> param();
        $info=[];
        $info1=['start'=>'','end'=>''];
        $info2=['start'=>'','end'=>''];
        $info3=['start'=>'','end'=>''];
        $info4=['contract_invoiced_unpaid'=>'=0','contract_receivables'=>'','contract_accepted'=>''];
        foreach ($data as $key => $value)
        {  //把对象数据变为数组
            if($value && ($key=='contractledger_amount'||$key=='contractledger_actual'||$key=='invoice_amount'||$key=='payment_amount'))
            {
                $info[$key]=$value;
            }
            elseif ($value== 'on' && $key=='contract_invoiced_unpaid') {
                    $info4['contract_invoiced_unpaid']='>0';
            }
            elseif ($value && $key=='contract_receivables') {
                $info4['contract_receivables']=$value;
            }
            elseif ($value && $key=='contract_accepted') {
                $info4['contract_accepted']=$value;
            }
            if($data['start']&&$data['end'])
            {
                $info1['start']=$data['start']; 
                $info1['end']=$data['end'];
            }
        }
        if($data['start1']&&$data['end1'])
        {
            
            $info2['start']=$data['start1']; 
            $info2['end']=$data['end1'];
        }
        if($data['start2']&&$data['end2'])
        {
            $info3['start']=$data['start2']; 
            $info3['end']=$data['end2'];
        }
        //第一个(id输入 三个日期都没输入)
        if($data['contract_id'] && $info1['start'] =='' && $info1['end'] == ''  && $info2['start'] == '' && $info2['end'] == '' && $info3['start'] == '' && $info3['end'] == '')
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") //取一个别名
                    //与category表进行关联，取名i，并且a表的categoryid字段等于category表的id字段
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    //想要的字段
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    //查询
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->order("contractledger_signtime desc")
                    ->paginate($data['pagenumber'],false,["query"=>$data]);

                $count = Db::name('green_contractledger')
                    ->alias("a") //取一个别名
                    //与category表进行关联，取名i，并且a表的categoryid字段等于category表的id字段
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    //想要的字段
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    //查询
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->count();

            }
            //第二个(id输入 第一个日期输入)
            elseif($data['contract_id'] && $info1['start']  && $info1['end']  && $info2['start'] == '' && $info2['end'] == '' && $info3['start'] == '' && $info3['end'] == '' )
                 {
                $res = Db::name('green_contractledger')
                    ->alias("a")
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->count();
            }
        //第二个(id输入 第2个日期输入)
            elseif($data['contract_id'] && $info1['start'] ==''  && $info1['end'] ==''  && $info2['start']  && $info2['end']  && $info3['start'] == '' && $info3['end'] == '' )
                 {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->count();
            }
                      //第二个(id输入 第3个日期输入)
            elseif($data['contract_id'] && $info1['start'] ==''  && $info1['end'] =='' && $info2['start'] == '' && $info2['end'] == '' && $info3['start']  && $info3['end']  )
                 {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->count();
            }

            //第三个(id输入 1、2输入)
            elseif($data['contract_id'] && $info1['start'] && $info1['end']   && $info2['start']  && $info2['end'] && $info3['start'] =='' && $info3['end'] == ''  )
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                     ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->count();
            }

            //第三个(id输入 1、3输入)
            elseif($data['contract_id'] && $info1['start'] && $info1['end']   && $info2['start'] =='' && $info2['end'] =='' && $info3['start']  && $info3['end']  )
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                     ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);

                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->count();
            }
            //第三个(id输入 2、3输入)
            elseif($data['contract_id'] && $info1['start'] =='' && $info1['end']  =='' && $info2['start'] && $info2['end'] && $info3['start']  && $info3['end']  )
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                     ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);

                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->count();
            }
             //第四个(id输入 三个日期都输入)
            elseif($data['contract_id'] && $info1['start'] && $info1['end']  && $info2['start']  && $info2['end']  && $info3['start']  && $info3['end']  )
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where(['a.contract_id'=>$data['contract_id']])
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->count();
            }

            //第一个(id没输入 三个日期都没输入)
        elseif($data['contract_id'] =='' && $info1['start'] =='' && $info1['end'] == ''  && $info2['start'] == '' && $info2['end'] == '' && $info3['start'] == '' && $info3['end'] == '')
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->count();
            }
            //第二个(id没输入 第一个日期输入)
            elseif($data['contract_id'] =='' && $info1['start']  && $info1['end']  && $info2['start'] == '' && $info2['end'] == '' && $info3['start'] == '' && $info3['end'] == '' )
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->count();
            }
            //第二个(id没输入 第2个日期输入)
            elseif($data['contract_id'] == '' && $info1['start'] ==''  && $info1['end'] ==''  && $info2['start']  && $info2['end']  && $info3['start'] == '' && $info3['end'] == '' )
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->count();
            }
            //第二个(id没输入 第3个日期输入)
            elseif($data['contract_id'] =='' && $info1['start'] ==''  && $info1['end'] =='' && $info2['start'] == '' && $info2['end'] == '' && $info3['start']  && $info3['end']  )
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->count();
            }
            //第三个(id没输入 1、2输入)
            elseif($data['contract_id'] ==''&& $info1['start'] && $info1['end']   && $info2['start']  && $info2['end'] && $info3['start'] =='' && $info3['end'] == ''  )
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                     ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);

                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->count();
            }
            //第三个(id没输入 1、3输入)
            elseif($data['contract_id'] =='' && $info1['start'] && $info1['end']   && $info2['start'] =='' && $info2['end'] =='' && $info3['start']  && $info3['end']  )
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                     ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);

                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->count();
            }
             //第三个(id没输入 2、3输入)
            elseif($data['contract_id'] == ''&& $info1['start'] =='' && $info1['end']  =='' && $info2['start']  && $info2['end'] && $info3['start']  && $info3['end']  )
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                     ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);

                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->count();
            }
             //第四个(id没输入 三个日期都输入)
            elseif($data['contract_id'] ==''&& $info1['start'] && $info1['end']  && $info2['start']  && $info2['end']  && $info3['start']  && $info3['end']  )
            {
                $res = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count = Db::name('green_contractledger')
                    ->alias("a") 
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->count();
            }
            else
            {
                $res = Db::name('green_contractledger')
                    ->alias("a")
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->paginate($data['pagenumber'],false,["query"=>$data]);

                $count = Db::name('green_contractledger')
                    ->alias("a") //取一个别名
                    ->join('green_confirm i', 'a.contract_id = i.contract_id')
                    ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount,a.contract_receivables,a.contract_accepted,a.contract_invoiced_unpaid')
                    ->where($info)
                    ->where('`contract_receivables`'.$info4['contract_receivables'].' && '.'`contract_accepted`'.$info4['contract_accepted'].' && '.'`contract_invoiced_unpaid`'.$info4['contract_invoiced_unpaid'])
                    ->where('contractledger_signtime','BETWEEN',[$info1['start'],$info1['end']])
                    ->where('invoice_date','BETWEEN',[$info2['start'],$info2['end']])
                    ->where('payment_date','BETWEEN',[$info3['start'],$info3['end']])
                    ->count();
            }
        $this -> view -> assign('orderList', $res);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', $data['pagenumber']);
        $confirm = true;
        $this -> view -> assign('confirm',$confirm);
        return $this -> view -> fetch('taizhangguanli');
    }
    //合同台账模糊筛选
    public function ContractLedgerSelectAll(Request $request){
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("ledger");
        if ($limit ==0) {
            return $this -> view -> fetch('noPower');
        }
        $this -> view -> assign('limit', $limit);
        $data=$request->param();
        $res = Db::name('green_contractledger')
            ->alias("a") //取一个别名
            //与category表进行关联，取名i，并且a表的categoryid字段等于category表的id字段
            ->join('green_confirm i', 'a.contract_id = i.contract_id')
            ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount')
            ->whereor([
                'a.contract_id'=>['like','%'.$data['content'].'%'],
                'contractledger_signtime'=>['like', '%'.$data['content'].'%'],
                'contractledger_amount'=>['like', '%'.$data['content'].'%'],
                'contractledger_actual'=>['like','%'.$data['content'].'%'],
                'invoice_date'=>['like', '%'.$data['content'].'%'],
                'invoice_amount'=>['like', '%'.$data['content'].'%'],
                'payment_date'=>['like', '%'.$data['content'].'%'],
                'payment_amount'=>['like', '%'.$data['content'].'%'],
            ])
            ->paginate($data['pagenumber'],false,["query"=>$data]);
        $count = Db::name('green_contractledger')
            ->alias("a") //取一个别名
            //与category表进行关联，取名i，并且a表的categoryid字段等于category表的id字段
            ->join('green_confirm i', 'a.contract_id = i.contract_id')
            ->field('a.contract_id,a.contractledger_signtime,a.contractledger_amount,a.contractledger_actual,i.invoice_date,i.invoice_amount,i.payment_date,i.payment_amount')
            ->whereor([
                'a.contract_id'=>['like','%'.$data['content'].'%'],
                'contractledger_signtime'=>['like', '%'.$data['content'].'%'],
                'contractledger_amount'=>['like', '%'.$data['content'].'%'],
                'contractledger_actual'=>['like','%'.$data['content'].'%'],
                'invoice_date'=>['like', '%'.$data['content'].'%'],
                'invoice_amount'=>['like', '%'.$data['content'].'%'],
                'payment_date'=>['like', '%'.$data['content'].'%'],
                'payment_amount'=>['like', '%'.$data['content'].'%'],
            ])
            ->count();
        $this -> view -> assign('orderList', $res);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', $data['pagenumber']);

        $confirm = true;
        $this -> view -> assign('confirm',$confirm);
        return $this -> view -> fetch('taizhangguanli');
    }
    // 新建合同台账
    public function newledger(Request $request){
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('ledger');
        if($limit!=2)
            {
                return $this->view->fetch('noPower');
            }
        else
            {
                return $this->view->fetch('newledger');
            }
    }
     // 招投标项目
     public function zhaotoubiao(Request $request)
   {
    $sid=Session::get('staff_id');
    $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('bid');
    if ($limit == 0) {
        return $this -> view -> fetch('noPower');
        exit;
    }

        $data = $request -> param();
        $count = Db::table('green_bid')->count();
        $list = Db::table('green_bid')->order("bid_date desc")->paginate(10); 

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', 10);
        return $this -> view -> fetch('zhaotoubiao');
    }
    //招投标项目详情
    public function zhaotoubiao_details(Request $request){
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("bid");
        $this -> view -> assign('limit', $limit);
        //获取到要编辑的工程号
        $toubiao_id = $request -> param('id');
        $data=['toubiao_id'=>$toubiao_id];

        //根据ID和手机号进行查询
        $result =Db::table('green_bid')->where($data)->select();
        $result1 =Db::table('green_bidcompensation')->where($data)->select();

        $result2 =Db::table('green_biddeposite')->where($data)->select();
        $result3 =GreenBidphase::get($data);
        // $result4 =GreenBidtype::get($data);
        $this->view->assign('BidList',$result[0]);

        //给当前编辑模板赋值
        $this->view->assign('deposite',$result2);
        $this->view->assign('Compensation',$result1);
        $this->view->assign('phase',$result3);
        // $this->view->assign('project_info4',$result4);

        // $this -> view -> assign('bid_infoes', $info);
        //渲染编辑模板
        return $this->view->fetch('zhaotoubiao_details');
    }

    // 招投标表筛选
    public function BidSelect(Request $requset)
    {
        $data = $requset -> param();
        if ($data["type"] == "无") {
            $data["type"] = '';
        }
        if ($data["bid_progress"] == "无") {
            $data["bid_progress"] = '';
        }
        if ($data["bid_type"] == "无") {
            $data["bid_type"] = '';
        }
        if($data['bid_progress']&&$data['bid_type'])
            $info=['bid_progress'=>$data['bid_progress'],'bid_type'=>$data['bid_type']];
        else if($data['bid_progress']&&!$data['bid_type'])
            $info=['bid_progress'=>$data['bid_progress']];
        else if(!$data['bid_progress']&&$data['bid_type'])
            $info=['bid_type'=>$data['bid_type']];
        else
            $info=[];
        $res[0]=['toubiao_id'=>'','bid_content'=>'','bid_deposite'=>'','bid_compensation'=>'','bid_progress'=>'','bid_type'=>'','bid_pretrial_date'=>'','bid_date'=>''];
        $id=[];$idfo=[];
        // 已开票未收款筛选
        if($data['type']=='已开票未收款'){
            // 补偿费搜索
            $id1=Db::table('green_biddeposite')
                ->whereNotNull('deposite_invoice_date')
                ->whereNull('deposite_payment_date')
                ->field('toubiao_id')
                ->select();
                // 保证金搜索
            $id2=Db::table('green_bidcompensation')
                ->whereNotNull('compensation_invoice_date')
                ->whereNull('compensation_payment_date')
                ->field('toubiao_id')
                ->select();
            $i=0;$j=0;
            foreach ($id1 as $k=>$v)
            {
                if(in_array($v,$id)){}
                else
                {
                    $id[$i++]=$v;
                }
            }
            foreach ($id2 as $k=>$v)
            {
                if(in_array($v,$id)){}
                else
                {
                    $id[$i++]=$v;
                }
            }
            foreach ($id as $k=>$v){
                $idfo[$j++]=$v['toubiao_id'];
            }
        }
        else if($data['type']=='未开票未收款'){
            // 补偿费搜索
            $id1=Db::table('green_biddeposite')
                ->whereNull('deposite_invoice_date')
                ->whereNull('deposite_payment_date')
                ->field('toubiao_id')
                ->select();
            // 保证金搜索
            $id2=Db::table('green_bidcompensation')
                ->whereNull('compensation_invoice_date')
                ->whereNull('compensation_invoice_date')
                ->field('toubiao_id')
                ->select();
            $i=0;$j=0;
            foreach ($id1 as $k=>$v)
            {
                if(in_array($v,$id)){}
                else
                {
                    $id[$i++]=$v;
                }
            }
            foreach ($id2 as $k=>$v)
            {
                if(in_array($v,$id)){}
                else
                {
                    $id[$i++]=$v;
                }
            }
            foreach ($id as $k=>$v){
                $idfo[$j++]=$v['toubiao_id'];
            }
        }
        else{
            // 既不是“已开票未收款”，也不是“未开票未收款”
            // 补偿费搜索
            $id1=Db::table('green_biddeposite')
                ->field('toubiao_id')
                ->select();
            // 保证金搜索
            $id2=Db::table('green_bidcompensation')
                ->field('toubiao_id')
                ->select();
            $i=0;$j=0;
            foreach ($id1 as $k=>$v)
            {
                if(in_array($v,$id)){}
                else
                {
                    $id[$i++]=$v;
                }
            }
            foreach ($id2 as $k=>$v)
            {
                if(in_array($v,$id)){}
                else
                {
                    $id[$i++]=$v;
                }
            }
            foreach ($id as $k=>$v){
                $idfo[$j++]=$v['toubiao_id'];
            }
        }

        if($data['start1']&&$data['end1']&&$data['start2']==''&&$data['end2']=='')
        {
            //资格预审时间
            $res=Db::table('green_bid')
                ->where($info)
                ->where('toubiao_id','IN',$idfo)
                ->where('bid_pretrial_date','BETWEEN',[$data['start1'],$data['end1']])
                ->order("bid_date desc")
               ->paginate($data['pagenumber'],false,["query"=>$data]);
            $count=Db::table('green_bid')
                ->where($info)
                ->where('toubiao_id','IN',$idfo)
                ->where('bid_pretrial_date','BETWEEN',[$data['start1'],$data['end1']])
                ->count();
        }
        else if($data['start1']==''&&$data['end1']==''&&$data['start2']&&$data['end2'])
        {
            //交标时间
            $res= Db::table('green_bid')
                ->where($info)
                ->where('toubiao_id','IN',$idfo)
                ->where('bid_date', 'BETWEEN', [$data['start2'], $data['end2']])
                ->order("bid_date desc")
                ->paginate($data['pagenumber'], false, ["query" => $data]);
            $count= Db::table('green_bid')
                ->where($info)
                ->where('toubiao_id','IN',$idfo)
                ->where('bid_date', 'BETWEEN', [$data['start2'], $data['end2']])
                ->count();
        }
        else if($data['start1']&&$data['end1']&&$data['start2']&&$data['end2'])
        {
            //资格预审时间+招标时间
            $res= Db::table('green_bid')
                ->where($info)
                ->where('toubiao_id','IN',$idfo)
                ->where('bid_pretrial_date', 'BETWEEN', [$data['start1'], $data['end1']])
                ->where('bid_date', 'BETWEEN', [$data['start2'], $data['end2']])
                ->order("bid_date desc")
                ->paginate($data['pagenumber'], false, ["query" => $data]);
            $count= Db::table('green_bid')
                ->where($info)
                ->where('toubiao_id','IN',$idfo)
                ->where('bid_pretrial_date', 'BETWEEN', [$data['start1'], $data['end1']])
                ->where('bid_date', 'BETWEEN', [$data['start2'], $data['end2']])
                ->count();
        }
        else
        {
            $res= Db::table('green_bid')
                ->where($info)
                ->where('toubiao_id','IN',$idfo)
                ->order("bid_date desc")
                ->paginate($data['pagenumber'], false, ["query" => $data]);
            $count= Db::table('green_bid')
                ->where($info)
                ->where('toubiao_id','IN',$idfo)
                ->count();
        }

        $this -> view -> assign('orderList', $res);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', $data['pagenumber']);
        return $this -> view -> fetch('zhaotoubiao');
    }
    //招投标模糊筛选（进展阶段，投标类型）
    public function BidSelectAll(Request $request){
        $data=$request->param();
        $res=Db::table('green_bid')
            ->whereor([
                'toubiao_id'=>['like','%'.$data['content'].'%'],
                'bid_progress'=>['like','%'.$data['content'].'%'],
                'bid_type'=>['like', '%'.$data['content'].'%'],
            ])
            ->order("bid_date desc")
            ->paginate($data['pagenumber'],false,["query"=>$data]);
        $count=Db::table('green_bid')
        ->whereor([
            'toubiao_id'=>['like','%'.$data['content'].'%'],
            'bid_progress'=>['like','%'.$data['content'].'%'],
            'bid_type'=>['like', '%'.$data['content'].'%'],
        ])
        ->count();
        $this -> view -> assign('orderList', $res);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', $data['pagenumber']);
        return $this -> view -> fetch('zhaotoubiao');
    }
    //新建招投标项目
    public function BidAdd(Request $request){
        $data=$request->param();
        if (GreenBid::get(['toubiao_id'=> $data['toubiao_id']])) {
        //如果在表中查询到该用户名
        $status = 3;
        $message1 = '该招投标项目已存在,请重新输入~~';
        return ['status'=>$status, 'message'=>$message1];
        }
        else{
        if ($data['bidphase_phase7']){
            $data['bid_progress']='其他';
        }
        elseif ($data['bidphase_phase6']){
            $data['bid_progress']='已归档';
        }
        elseif($data['bidphase_phase5']){
            $data['bid_progress']='已结算';
        }
        elseif ($data['bidphase_phase4']){
            $data['bid_progress']='合同签订';
        }
        elseif ($data['bidphase_phase3']){
            $data['bid_progress']='公示完成';
        }
        elseif ($data['bidphase_phase2']){
            $data['bid_progress']='投标完成';
        }
        elseif ($data['bidphase_phase1']){
            $data['bid_progress']='合同签订';
        }
        else
        {$data['bid_progress']=null;}

        // 获取投标类型 "其他"数据
        if($data["bid_type"]=="其他")
            $data["bid_type"] = $data["bid_types"];
        // 获取投标类型 "其他"数据end
        $res=Db::table('green_bid')
            ->insert([
                'toubiao_id'=>$data['toubiao_id'],
                'project_name'=>$data['project_name'],
                'bid_content'=>$data['bid_content'],
                'biaolan_price'=>$data['biaolan_price'],
                'notice'=>$data['notice'],
                // 'project_constructor'=>$data['project_constructor'],
                // 'project_agent'=>$data['project_agent'],
                'bid_pretrial_date'=>$data['bid_pretrial_date'],
                'bid_ispretrial'=>$data['bid_ispretrial'],
                'houshen_date'=>$data['houshen_date'],
                'question_date'=>$data['question_date'],
                'bid_date'=>$data['bid_date'],
                'bid_space'=>$data['bid_space'],
                'bid_document'=>$data['bid_document'],
                'bid_isbid'=>$data['bid_isbid'],
                'bid_master'=>$data['bid_master'],
                'join_person'=>$data['join_person'],
                'bid_progress'=>$data['bid_progress'],
                'bid_type'=>$data['bid_type'],
                'toubiao_above'=>$data['toubiao_above'],
                'toubiao_under'=>$data['toubiao_under'],
                'toubiao_amount'=>$data['toubiao_amount'],
                'toubiao_address'=>$data['toubiao_address'],
                'toubiao_other'=>$data['toubiao_other'],
                // 'bid_deposite'=>null,
                // 'bid_compensation'=>null,
                'bid_fabaoren'=>$data['bid_fabaoren'],
                'bid_contractor_mobile'=>$data['bid_contractor_mobile'],
                'bid_dailiren'=>$data['bid_dailiren'],
                'bid_agent_mobile'=>$data['bid_agent_mobile'],
                'bid_remarks'=>$data['bid_remarks'],
            ]);
        $compensation = explode('^', $data['compensation']);
        for ($i=0; $i < count($compensation); $i++) {
            $contents = explode('*', $compensation[$i]);
            // if(!$contents[2]){$contents[2]=null;}
            // if(!$contents[3]){$contents[3]=null;}
            $res2=Db::table('green_bidcompensation')
                ->insert([
                    'toubiao_id'=>$data['toubiao_id'],
                    'compensation_price'=>$contents[0],
                    'compensation_invoice_date'=>$contents[1],
                    'compensation_invoice_amount'=>$contents[2],
                    'compensation_payment_date'=>$contents[3],
                    'compensation_payment_amount'=>$contents[4],
                ]);
        }
        $deposite = explode('^', $data['deposite']);
        for ($i=0; $i < count($deposite); $i++) {
            $contents = explode('*', $deposite[$i]);
            // if(!$contents[2]){$contents[2]=null;}
            // if(!$contents[3]){$contents[3]=null;}
            $res3=Db::table('green_biddeposite')
                ->insert([
                    'toubiao_id'=>$data['toubiao_id'],
                    'deposite_invoice_id'=>$contents[0],
                    'deposite_invoice_price'=>$contents[1],
                    'deposite_invoice_object'=>$contents[2],
                    'deposite_invoice_date'=>$contents[3],
                    'deposite_invoice_amount'=>$contents[4],
                    'deposite_payment_date'=>$contents[5],
                    'deposite_payment_amount'=>$contents[6],
                ]);
        }
            if($data['bidphase_phase1']==''){$data['bidphase_phase1']=null;}
            if($data['bidphase_phase2']==''){$data['bidphase_phase2']=null;}
            if($data['bidphase_phase3']==''){$data['bidphase_phase3']=null;}
            if($data['bidphase_phase4']==''){$data['bidphase_phase4']=null;}
            if($data['bidphase_phase5']==''){$data['bidphase_phase5']=null;}
            if($data['bidphase_phase6']==''){$data['bidphase_phase6']=null;}
            if($data['bidphase_phase7']==''){$data['bidphase_phase7']=null;}
        $res4=Db::table('green_bidphase')
            ->insert([
                'toubiao_id'=>$data['toubiao_id'],
                'bidphase_phase1'=>$data['bidphase_phase1'],
                'bidphase_phase2'=>$data['bidphase_phase2'],
                'bidphase_phase3'=>$data['bidphase_phase3'],
                'bidphase_phase4'=>$data['bidphase_phase4'],
                'bidphase_phase5'=>$data['bidphase_phase5'],
                'bidphase_phase6'=>$data['bidphase_phase6'],
                'bidphase_phase7'=>$data['bidphase_phase7'],
            ]);

        if ($res== null||$res4 == null) {
            $status = 0;
            $message = '添加失败~~';
        }
        else{
            $status = 1;
            $message = '添加成功';
        }
        return json(['status'=>$status, 'message'=>$message]);

}
    }
  // 管理员列表
    public function adminlist()
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("adminstrator");
        $this -> view -> assign('limit', $limit);

        $count = Db::table('green_administrators')->where('admin','IN','超级管理员,普通管理员')->count();
        $list = Db::table('green_administrators')->where('admin','IN','超级管理员,普通管理员')->order("staff_id")->paginate(10);
        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', 10);
        //渲染管理员列表模板
        return $this -> view -> fetch('adminlist');
    }
    //管理员列表模糊筛选
public function adminselectall(Request $request)
{
    $sid=Session::get('staff_id');
    $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("adminstrator");
    $this -> view -> assign('limit', $limit);

    $data=$request->param();
    $content = $data["content"];
    $res = Db::table('green_administrators')->where(function($query) use ($content){
        $query->whereor([
            'staff_id'=>['like','%'.$content.'%'],
            'staff_name'=>['like', '%'.$content.'%'],
            'administrators_name'=>['like', '%'.$content.'%'],
        ]);
    })->where('admin','IN','超级管理员,普通管理员')
    ->order("staff_id")
    ->paginate($data['pagenumber'],false,["query"=>$data]);
    $count=Db::table('green_administrators')->where(function($query) use ($content){
        $query->whereor([
            'staff_id'=>['like','%'.$content.'%'],
            'staff_name'=>['like', '%'.$content.'%'],
            'administrators_name'=>['like', '%'.$content.'%'],
        ]);
    })->where('admin','IN','超级管理员,普通管理员')
    ->count();
    $this -> view -> assign('orderList', $res);
    $this -> view -> assign('count', $count);
    $this -> view -> assign('pagenumber', $data['pagenumber']);
    return $this -> view -> fetch('adminlist');
}
  //新增管理员
    public function addadmin(Request $requset)
    {
        $data = $requset -> param();
        $check = Db::table('green_administrators')->where("administrators_name",$data['username'])->select();
        if ($check) {
            return ['status'=>2, 'message'=>'用户名已存在'];
        }
        $temp=[2,2,0,0,2,2,2,2,2,0,0,0];
    $result = Db::table('green_administrators')
        ->insert([
            'staff_name'=>$data['name'],
            'administrators_name'=>$data['username'],
            'administrators_password'=>md5($data['repass']),
            'admin'=>'普通管理员',
            'enable'=>1,
            'project_view'=>$temp[0],
            'contract_view'=>$temp[1],
            'ledger'=>$temp[2],
            'bid'=>$temp[3],
            'adminstrator'=>$temp[4],
            'staff'=>$temp[5],
            'jurisdiction'=>$temp[6],
            'customer'=>$temp[7],
            'project_value'=>$temp[8],
            'department_value'=>$temp[9],
            'staff_value'=>$temp[10],
            'invoice_new'=>$temp[11],
            'update_time'=>'00:00:00',
            'administrators_lastTime'=>time(),
            'admin_status'=>0,
        ]);

        if ($result) {
            return ['status'=>1, 'message'=>'添加成功'];
        } else {
            return ['status'=>0, 'message'=>'添加失败,请检查'];
        } 
    }
    //启用管理员更改
    public function changeAble(Request $requset)
    {
        $data = $requset -> param();
        // 默认权限
        $yuan=  [1,1,1,1,0,1,0,2,2,2,0,0];
        $sheng= [2,2,0,0,2,2,2,2,2,0,0,0];
        $cai=   [1,1,2,2,0,1,0,0,1,2,2,2];
        $ban=   [2,1,0,0,0,2,0,2,1,0,0,2];
        $rest=  [1,0,0,0,0,1,0,0,1,0,0,0];
        $temp=  [2,2,0,0,2,2,2,2,2,0,0,0];
        $admin = Db::table('green_administrators')->where("staff_name",$data["staff_name"])->value("admin");
        if($admin == "超级管理员")
        return ['status'=>0, 'message'=>'更新失败，无修改超级管理员权限'];
        $staff_department = Db::table('green_staff')->where("staff_name",$data["staff_name"])->value("staff_department");
            // 启用为管理员
        if($data["enable"]==1)
        {
                // 直接更新表
            $result1 = Db::table('green_administrators')
            ->where("staff_name",$data["staff_name"])
        ->update([
            'admin'=>'普通管理员',
            'enable'=>1,
            'project_view'=>$temp[0],
            'contract_view'=>$temp[1],
            'ledger'=>$temp[2],
            'bid'=>$temp[3],
            'adminstrator'=>$temp[4],
            'staff'=>$temp[5],
            'jurisdiction'=>$temp[6],
            'customer'=>$temp[7],
            'project_value'=>$temp[8],
            'department_value'=>$temp[9],
            'staff_value'=>$temp[10],
            'invoice_new'=>$temp[11],
        ]);
        }
        else{
            // 停用管理员身份，恢复员工身份
            if($staff_department){
                switch ($staff_department)
            {
                case '院长室':
                   $temp=$yuan;
                    break;
                case '生产经营部':
                    $temp=$sheng;
                    break;
                case '财务部':
                    $temp=$cai;
                    break;
                case '办公室':
                    $temp=$ban;
                    break;
                default:
                    $temp=$rest;
            }
            $result1 = Db::table('green_administrators')
            ->where("staff_name",$data["staff_name"])
                ->update([
                    'admin'=>0,
                    'enable'=>$data['enable'],
                    'project_view'=>$temp[0],
                    'contract_view'=>$temp[1],
                    'ledger'=>$temp[2],
                    'bid'=>$temp[3],
                    'adminstrator'=>$temp[4],
                    'staff'=>$temp[5],
                    'jurisdiction'=>$temp[6],
                    'customer'=>$temp[7],
                    'project_value'=>$temp[8],
                    'department_value'=>$temp[9],
                    'staff_value'=>$temp[10],
                    'invoice_new'=>$temp[11],
                    'update_time'=>'00:00:00',
                    'administrators_lastTime'=>time(),
                    'admin_status'=>0,
                ]);
            }
            else{
                $temp = $rest;
                $result1 = Db::table('green_administrators')
                ->where("staff_name",$data["staff_name"])
                ->update([
                    'admin'=>0,
                    'enable'=>$data['enable'],
                    'project_view'=>$temp[0],
                    'contract_view'=>$temp[1],
                    'ledger'=>$temp[2],
                    'bid'=>$temp[3],
                    'adminstrator'=>$temp[4],
                    'staff'=>$temp[5],
                    'jurisdiction'=>$temp[6],
                    'customer'=>$temp[7],
                    'project_value'=>$temp[8],
                    'department_value'=>$temp[9],
                    'staff_value'=>$temp[10],
                    'invoice_new'=>$temp[11],
                    'update_time'=>'00:00:00',
                    'administrators_lastTime'=>time(),
                    'admin_status'=>0,
                ]);
            }
        }
        $result2 =  Db::table('green_staff')->where("staff_name",$data["staff_name"])->update(['enable'=>$data["enable"]]);
        if ($result1) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        } 
    }
    //启用员工更改
    public function changeStaffAble(Request $requset)
    {
        $data = $requset -> param();
        $staff_name = Db::table('green_staff')->where(['staff_id'=>$data['id']])->value("staff_name");
        $result1 = Db::table('green_administrators')
            ->where([
                'staff_name'=>$staff_name
            ])
            ->update([
                'staff_enable'=>$data['staff_enable']
            ]);
        $result2 = Db::table('green_staff')
            ->where([
                'staff_id'=>$data['id']
            ])
            ->update([
                'staff_enable'=>$data['staff_enable']
            ]);
        if ($result1 && $result2 ) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        } 
    }
        // 工程产值
     public function gongchengchanzhi()
   {
        $sid=Session::get('staff_id');
        $contract_amount_limit=Db::table('green_administrators')->where('staff_id',$sid)->value("contract_amount_limit");
        $this -> view -> assign('contract_amount_limit', $contract_amount_limit); 
    
        $count = Db::table('green_project_totalvalue')->count();
        $list = Db::table('green_project_totalvalue')
            ->order("project_id desc")
            ->select();
        $sum=Db::table('green_project_totalvalue')->sum('total_department');

       $curpage = input('page') ? input('page') : 1;//当前第x页，有效值为：1,2,3,4,5...
       $listRow = 10;//每页10行记录
       $dataTo = array();
       $dataTo = array_chunk($list, $listRow);

       $showdata = array();
       if ($dataTo) {
           $showdata = $dataTo[$curpage - 1];
       } else {
           $showdata = null;
       }
       $p = Bootstrap::make($showdata, $listRow, $curpage, count($list), false, [
           'var_page' => 'page',
           'path' => '',//这里根据需要修改url
           'query' =>  Request::instance()->param(),//此处参数可以保留当前数据集的查询条件
           'fragment' => '',
       ]);
       $p->appends($_GET);
        $this->assign('orderList', $p);
        $this->assign('plistpage', $p->render());
        $this -> view -> assign('count', $count);
        $this -> view -> assign('sum', round($sum,2));
        $this -> view -> assign('pagenumber', 10);
        //渲染管理员列表模板
        return $this -> view -> fetch('gongchengchanzhi');
    }
//工程产值表筛选(project_id,start,end)
        public function gongchengchanzhiSelect(Request $request)
    {
        $sid=Session::get('staff_id');
        $contract_amount_limit=Db::table('green_administrators')->where('staff_id',$sid)->value("contract_amount_limit");
        $this -> view -> assign('contract_amount_limit', $contract_amount_limit);
        $data = $request -> param();
        if($data['project_id']==''&$data['start']==''&$data['end']==''){
            $count = Db::table('green_project_totalvalue')->count();
            if ($data['pagenumber'] =="全部") {
                $data['pagenumber'] = $count;
            }
            $list = Db::table('green_project_totalvalue')->order("project_id desc")->select();
            $sum=Db::table('green_project_totalvalue')->sum('total_department');
        }else if($data['project_id']!=''&$data['start']==''&$data['end']==''){
            $count = Db::table('green_project_totalvalue')->where(['project_id'=>$data['project_id']])->count();
            if ($data['pagenumber'] =="全部") {
                $data['pagenumber'] = $count;
            }
            $list = Db::table('green_project_totalvalue')->where(['project_id'=>$data['project_id']])->order("project_id desc")->select();
            $sum=Db::table('green_project_totalvalue')->where(['project_id'=>$data['project_id']])->sum('total_department');
        }else if($data['project_id']==''&$data['start']!=''&$data['end']!=''){
            $project_id=Db::table('green_project_totalvalue')->field('project_id')->select();
            $k=0;
            $j=0;
            for($i=0;$i<count($project_id);$i++)
            {
                $res[$k]=Db::table('green_projectvalue'.$project_id[$i]['project_id'])
                    ->where('drawing_time','BETWEEN',[$data['start'],$data['end']])
                    ->select();
                $k+=count($res[$k]);
            }
            $list=[];
            $sum=0;
            foreach ($res as $key=>$value)
            {
                foreach ($value as $k=>$v)
                {
                    $list[$j++]=$v;
                    $sum+=$v['value_subtotal'];
                }
            }
            $count = $j;
            if ($data['pagenumber'] =="全部") {
                $data['pagenumber'] = $count;
            }

//            $sum=Db::table('green_project_totalvalue')->where('drawing_time','BETWEEN',[$data['start'],$data['end']])->sum('total_department');
        }else if($data['project_id']!=''&$data['start']!=''&$data['end']!=''){
            $count = Db::table('green_projectvalue'.$data['project_id'])->where('drawing_time','between',[$data['start'],$data['end']])->count();
            if ($data['pagenumber'] =="全部") {
                $data['pagenumber'] = $count;
            }
            $list = Db::table('green_projectvalue'.$data['project_id'])->where('drawing_time','between',[$data['start'],$data['end']])->order("project_id desc")->select();
//                ->paginate($data['pagenumber'],false,["query"=>$data]);
            $sum=Db::table('green_projectvalue'.$data['project_id'])->where('drawing_time','between',[$data['start'],$data['end']])->sum('value_subtotal');
        }
        
            $curpage = input('page') ? input('page') : 1;//当前第x页，有效值为：1,2,3,4,5...
            $listRow = $data['pagenumber'];//每页10行记录
            $dataTo = array();
            $dataTo = array_chunk($list, $listRow);
            $showdata = array();
            if ($dataTo) {
                $showdata = $dataTo[$curpage - 1];
            } else {
                $showdata = null;
            }
            $p = Bootstrap::make($showdata, $listRow, $curpage, count($list), false, [
                'var_page' => 'page',
                'path' => '',//这里根据需要修改url
                'query' =>  Request::instance()->param(),//此处参数可以保留当前数据集的查询条件
                'fragment' => '',
            ]);
            $p->appends($_GET);
            $this->assign('orderList', $p);
            $this->assign('plistpage', $p->render());
        $this -> view -> assign('count',$count);
        $this -> view -> assign('pagenumber',$data["pagenumber"]);
        $this -> view -> assign('sum',round($sum,2));
        return $this -> view -> fetch('gongchengchanzhi');
    }

    //工程产值模糊筛选（工程号，工程名称）
    public function ProjectValueSelectAll(Request $request){
        $sid=Session::get('staff_id');
        $contract_amount_limit=Db::table('green_administrators')->where('staff_id',$sid)->value("contract_amount_limit");
        $this -> view -> assign('contract_amount_limit', $contract_amount_limit);
        $data=$request->param();
        
        $count=Db::table('green_project_totalvalue')
            ->whereor([
                'project_id'=>['like','%'.$data['content'].'%'],
                'project_name'=>['like', '%'.$data['content'].'%'],
                'project_subcontractor'=>['like', '%'.$data['content'].'%'],
            ])
            ->count();
        if ($data['pagenumber'] =="全部") {
                $data['pagenumber'] = $count;
            }
        $res=Db::table('green_project_totalvalue')
            ->whereor([
                'project_id'=>['like','%'.$data['content'].'%'],
                'project_name'=>['like', '%'.$data['content'].'%'],
                'project_subcontractor'=>['like', '%'.$data['content'].'%'],
            ])
            ->order("project_id desc")
            ->select();
        $sum=Db::table('green_project_totalvalue')
            ->whereor([
                'project_id'=>['like','%'.$data['content'].'%'],
                'project_name'=>['like', '%'.$data['content'].'%'],
                'project_subcontractor'=>['like', '%'.$data['content'].'%'],
            ])
            ->sum('total_department');

        $curpage = input('page') ? input('page') : 1;//当前第x页，有效值为：1,2,3,4,5...
        $listRow = $data['pagenumber'];//每页10行记录
        $dataTo = array();
        $dataTo = array_chunk($res, $listRow);
        $showdata = array();
        if ($dataTo) {
            $showdata = $dataTo[$curpage - 1];
        } else {
            $showdata = null;
        }
        $p = Bootstrap::make($showdata, $listRow, $curpage, count($res), false, [
            'var_page' => 'page',
            'path' => '',//这里根据需要修改url
            'query' =>  Request::instance()->param(),//此处参数可以保留当前数据集的查询条件
            'fragment' => '',
        ]);
        $p->appends($_GET);
        $this->assign('orderList', $p);
        $this->assign('plistpage', $p->render());

        $this -> view -> assign('count', $count);
        $this -> view -> assign('sum', round($sum,2));
        $this -> view -> assign('pagenumber', $data["pagenumber"]);
        return $this -> view -> fetch('gongchengchanzhi');
    }
    //渲染工程产值详情界面
    public function projectValue_details(Request $request)
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("project_value");
        $this -> view -> assign('limit', $limit);
        //获取到要编辑的工程号
        $data = $request -> param();
        $project_name = Db::table('green_project')->where('project_id',$data["project_id"])->value("project_name");
        if($data['start']==''||$data['end']==''){
            $result=Db::table('green_projectvalue'.$data['project_id'])
//                ->where('drawing_time','BETWEEN',[$data['start'],$data['end']])
                ->select();
        }else{
            $result=Db::table('green_projectvalue'.$data['project_id'])
                ->where('drawing_time','BETWEEN',[$data['start'],$data['end']])
                ->select();
        }
        $this->view->assign('content',$result);
        $this->view->assign('project_name',$project_name);
        //渲染编辑模板
        return $this->view->fetch('projectValue_details');
    }

    //合同详情界面
    public function contract_details(Request $request)
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("contract_view");
        $this -> view -> assign('limit', $limit);
        //获取到要编辑的合同号
        $base = $request -> param();
        $id =$base["id"];
        $data=['id'=>$id];
        $data2 =["contract_id"=>$base["contract_id"],"project_id"=>$base["project_id"]];
        $res=Db::table('green_contract')->where($data)->select();
      
        $res1=Db::table('green_contractunitprice')->where($data2)->select();
        $res2=Db::table('green_contractphase')->where(["contract_id"=>$base["contract_id"]])->select(); 
        $res3=Db::table('green_confirm')->where(["contract_id"=>$base["contract_id"]])->select();

        $this->view->assign(['ContractList'=>$res[0],'Unitprice'=>$res1,'phase'=>$res2[0],'confirm'=>$res3]);
        //渲染编辑模板
        return $this->view->fetch('contract_details');
    }
    //新建台账获取合同数据
    public function get_contract_details(Request $request)
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("contract_view");
        $this -> view -> assign('limit', $limit);
        //获取到要编辑的合同号
        $data = $request -> param();
        $res=Db::table('green_contract')->where("contract_id",$data["value"])->select(); 
        $result = 0;
        if ($res) {
            $result = 1;
            // 获取生产数据
            return ["result"=>$result,"project_name"=>$res[0]["project_name"],"contract_signtime"=>$res[0]["contract_signtime"],"contract_agent"=>$res[0]["contract_agent"],"contract_amount"=>$res[0]["contract_amount"],"contract_compute"=>$res[0]["contract_compute"]];
        }
        return ["result"=>$result];
        //渲染编辑模板
    }
// 合同详细，新增设计单价行
public function designPriceAdd(Request $request)
{
    $data=$request->param();
    $res=Db::table('green_contractunitprice')
        ->insert([
            'contract_id'=>$data['contract_id'],
            'project_id'=>$data['project_id'],
            'contract_content'=>$data['contract_content'],
            'contract_unitprice'=>$data['contract_unitprice'],
            'contract_floatingrate'=>$data['contract_floatingrate'],
            'contract_remarks'=>$data['contract_remarks'],
        ]);
    if(null!=$res){
        return json(['status'=>1,'message'=>'添加成功！']);
    }
    else{
        return json(['status'=>0,'message'=>'添加失败，请检查！']);
    }
}
   // 院产值渲染接口
    public function yuanchanzhi()
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('department_value');
        if ($limit == 0) {
            return $this -> view -> fetch('noPower');
            exit;
        }
        $count = Db::table('green_departmentvalue')->count();
        $list = Db::table('green_departmentvalue')->paginate(10);
       //  $list = Db::name('green_departmentvalue')
       //      ->alias("a") //取一个别名
       //      //与category表进行关联，取名i，并且a表的categoryid字段等于category表的id字段
       //      ->join('green_staff i', 'a.staff_name=i.staff_name')
       //      //想要的字段
       //      ->field('a.staff_department,a.staff_name,round(sum(total_personalvalue),2) as total_personalvalue,a.draw_date,a.id')
       //      ->group('a.staff_name')
       //      ->paginate(10);
       // // ->select();
        $sum=Db::table('green_departmentvalue')->sum('total_personalvalue');
        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('sum', round($sum,2));
        $this -> view -> assign('pagenumber', 10);
        return $this->view->fetch('yuanchanzhi');
    }
    //渲染院产值详情界面
    public function departmentValue_details(Request $request){
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("department_value");
        $this -> view -> assign('limit', $limit);
        //获取到要编辑的工程号
        $data = $request -> param();
        $staff_name=Db::table('green_departmentvalue')->where('id',$data['id'])->value('staff_name');
        if($data['start']==''||$data['end']=='')
        {
            $res=Db::table('green_departmentvalue')
                ->order("total_personalvalue desc")
                ->where('staff_name',$staff_name)
                ->select();
        }
        else
        {
            $res=Db::table('green_departmentvalue')
                ->order("total_personalvalue desc")
                ->where('staff_name',$staff_name)
                ->where('draw_date','BETWEEN',[$data['start'],$data['end']])
                ->select();
        }
        
        $this->view->assign('content',$res);
        return $this->view->fetch('departmentValue_details');
    }

    //院产值条件筛选(staff_department,start,end)
    public function DepartmentvalueSelect(Request $request)
    {
        $data = $request -> param();
        if($data['staff_department']==''&$data['start']==''&$data['end']==''){
            $count = Db::table('green_departmentvalue')
                ->alias("a") 
                ->join('green_staff i', 'a.staff_name=i.staff_name')
                ->field('a.staff_department,a.staff_name,round(sum(total_personalvalue),2) as total_personalvalue,a.draw_date,a.id')
                ->group('a.staff_name')
                ->count();
            if ($data['pagenumber'] == "全部") {
                $data['pagenumber'] = $count;
            }
            $list = Db::table('green_departmentvalue')->order("total_personalvalue desc")
                ->alias("a") 
                ->join('green_staff i', 'a.staff_name=i.staff_name')
                ->field('a.staff_department,a.staff_name,round(sum(total_personalvalue),2) as total_personalvalue,a.draw_date,a.id')
                ->group('a.staff_name')
                ->paginate($data['pagenumber'],false,["query"=>$data]);
            $sum=Db::table('green_departmentvalue')
            ->sum('total_personalvalue');
        }else if($data['staff_department']!=''&$data['start']==''&$data['end']==''){
            $count = Db::table('green_departmentvalue')
            ->alias("a") 
            ->join('green_staff i', 'a.staff_name=i.staff_name')
            ->field('a.staff_department,a.staff_name,round(sum(total_personalvalue),2) as total_personalvalue,a.draw_date,a.id')
            ->group('a.staff_name')
            ->where(['a.staff_department'=>$data['staff_department']])
            ->count();
            if ($data['pagenumber'] == "全部") {
                $data['pagenumber'] = $count;
            }
            $list = Db::table('green_departmentvalue')
            ->alias("a") 
            ->join('green_staff i', 'a.staff_name=i.staff_name')
            ->where(['a.staff_department'=>$data['staff_department']])
            ->field('a.staff_department,a.staff_name,round(sum(total_personalvalue),2) as total_personalvalue,a.draw_date,a.id')
            ->group('a.staff_name')
            ->order("total_personalvalue desc")
            ->paginate($data['pagenumber'],false,["query"=>$data]);
            $sum=Db::table('green_departmentvalue')->where(['staff_department'=>$data['staff_department']])->sum('total_personalvalue');
        }else if($data['staff_department']==''&$data['start']!=''&$data['end']!=''){
            $count = Db::table('green_departmentvalue')
                ->alias("a") 
                ->join('green_staff i', 'a.staff_name=i.staff_name')
                ->field('a.staff_department,a.staff_name,round(sum(total_personalvalue),2) as total_personalvalue,a.draw_date,a.id')
                ->group('a.staff_name')
                ->where('draw_date','BETWEEN',[$data['start'],$data['end']])
                ->count();
            if ($data['pagenumber'] == "全部") {
                $data['pagenumber'] = $count;
            }
            $list = Db::table('green_departmentvalue')
                ->alias("a") 
                ->join('green_staff i', 'a.staff_name=i.staff_name')
                ->field('a.staff_department,a.staff_name,round(sum(total_personalvalue),2) as total_personalvalue,a.draw_date,a.id')
                ->group('a.staff_name')
                ->where('draw_date','BETWEEN',[$data['start'],$data['end']])
                ->order("total_personalvalue desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);
            $sum=Db::table('green_departmentvalue')
                ->where('draw_date','BETWEEN',[$data['start'],$data['end']])
                ->sum('total_personalvalue');
        }else if($data['staff_department']!=''&$data['start']!=''&$data['end']!=''){
            $count = Db::table('green_departmentvalue')
                ->alias("a") 
                ->join('green_staff i', 'a.staff_name=i.staff_name')
                ->field('a.staff_department,a.staff_name,round(sum(total_personalvalue),2) as total_personalvalue,a.draw_date,a.id')
                ->group('a.staff_name')
                ->where(['a.staff_department'=>$data['staff_department']])
                ->where('draw_date','between',[$data['start'],$data['end']])
                ->count();
            if ($data['pagenumber'] == "全部") {
                $data['pagenumber'] = $count;
            }
            $list = Db::table('green_departmentvalue')
                ->alias("a") 
                ->join('green_staff i', 'a.staff_name=i.staff_name')
                ->field('a.staff_department,a.staff_name,round(sum(total_personalvalue),2) as total_personalvalue,a.draw_date,a.id')
                ->group('a.staff_name')
                ->where(['a.staff_department'=>$data['staff_department']])
                ->where('draw_date','between',[$data['start'],$data['end']])
                ->order("total_personalvalue desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);
            $sum=Db::table('green_departmentvalue')->where(['staff_department'=>$data['staff_department']])->where('draw_date','between',[$data['start'],$data['end']])->sum('total_personalvalue');
        }
       // return json($list);

         $this -> view -> assign('orderList', $list);
         $this -> view -> assign('count', $count);
         $this -> view -> assign('pagenumber',$data['pagenumber']);
         $this -> view -> assign('sum', round($sum,2));
         //渲染管理员列表模板
         return $this -> view -> fetch('yuanchanzhi');
    }

    //院产值模糊筛选接口
    public function DepartmentvalueSelectAll(Request $request){
        $data=$request->param();
        $count=Db::table('green_departmentvalue')
        ->alias("a") 
        ->join('green_staff i', 'a.staff_name=i.staff_name')
        ->field('a.staff_department,a.staff_name,round(sum(total_personalvalue),2) as total_personalvalue,a.draw_date,a.id')
        ->group('a.staff_name')
        ->whereor(['a.staff_department'=>['like','%'.$data['content'].'%'],
                'draw_date'=>['like','%'.$data['content'].'%']])
        ->count();
        if ($data['pagenumber'] == "全部") {
                $data['pagenumber'] = $count;
            }
        $res=Db::table('green_departmentvalue')
            ->alias("a") 
            ->join('green_staff i', 'a.staff_name=i.staff_name')
            ->field('a.staff_department,a.staff_name,round(sum(total_personalvalue),2) as total_personalvalue,a.draw_date,a.id')
            ->group('a.staff_name')
            ->whereor(['a.staff_department'=>['like','%'.$data['content'].'%'],
                'draw_date'=>['like','%'.$data['content'].'%']])
            ->order("total_personalvalue desc")
            ->paginate($data['pagenumber'],false,["query"=>$data]);
        
        $sum=Db::table('green_departmentvalue')
            ->whereor(['staff_department'=>['like','%'.$data['content'].'%'],
                'draw_date'=>['like','%'.$data['content'].'%']])
            ->sum('total_personalvalue');
        $this -> view -> assign('orderList', $res);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber',$data['pagenumber']);
        $this -> view -> assign('sum', round($sum,2));
        return $this -> view -> fetch('yuanchanzhi');
    }
    // 个人产值
     public function gerenchanzhi(Request $request)
   { 
        $count = Db::table('green_personalvalue')->count();
        $list = Db::table('green_personalvalue')
            ->group('staff_name')
            ->field('id,staff_department,staff_name,round(output_value,2) as output_value')
            ->order("output_value desc")
            ->paginate(10); 
        $sum = Db::table('green_personalvalue')->sum('output_value'); 
        $this -> view -> assign('sum', round($sum,2));
        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', 10);
        return $this -> view -> fetch('gerenchanzhi');
    }
    // 个人产值详情
    public function personalvalue_details(Request $request)
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("staff_value");
        $this -> view -> assign('limit', $limit);
        $data= $request -> param();
        if($data['start']==''||$data['end']==''){
            $res=Db::table('green_personalvalue'.$data['staff_name'])
                // ->where('id',$data['id'])
                ->select();
            $sum=Db::table('green_personalvalue'.$data['staff_name'])
                // ->where('id',$data['id'])
                ->sum('output_value');
        }
        else{
            $res=Db::table('green_personalvalue'.$data['staff_name'])
                // ->where('id',$data['id'])
                ->where('draw_date','BETWEEN',[$data['start'],$data['end']])
                ->select();
            $sum=Db::table('green_personalvalue'.$data['staff_name'])
                // ->where('id',$data['id'])
                ->where('draw_date','BETWEEN',[$data['start'],$data['end']])
                ->sum('output_value');
        }
        $this -> view -> assign('content', $res);
        $this -> view -> assign('id',$data['id'] );
        $this -> view -> assign('sum', round($sum,2));
        //渲染管理员列表模板
        return $this -> view -> fetch('personalvalue_details');
    }
    //个人产值条件筛选接口
    public function PersonalvalueSelect(Request $request){
        $data = $request -> param();
        $info=[];
        $res=[];
        $sum=0;
        foreach ($data as $key => $value)
        {  
            if($value&&$key!="pagenumber"&&$key!="page"&&$key!="start"&&$key!="end")
            {
                $info[$key]=$value;
            }
        }
        foreach ($data as $key => $value){
            if($data['start']&&$data['end'])
                {
                    $count=Db::table('green_personalvalue')
                        ->where($info)
                        ->where('draw_date','BETWEEN',[$data['start'],$data['end']])
                        // ->field('id,staff_department,staff_name')
                        ->count();
                    if ($data['pagenumber'] == "全部") {
                        $data['pagenumber'] = $count;
                    }
                    $res=Db::table('green_personalvalue')
                        ->where($info)
                        ->where('draw_date','BETWEEN',[$data['start'],$data['end']])
                        // ->field('id,staff_department,staff_name,output_value,draw_date')
                        ->order("output_value desc")
                        ->paginate($data['pagenumber'],false,["query"=>$data]);
                    

                $sum=Db::table('green_personalvalue')
                    ->where($info)
                    ->where('draw_date','BETWEEN',[$data['start'],$data['end']])
                    ->sum('output_value');
                }
            else
                {
                $count=Db::table('green_personalvalue')
                    ->where($info)
                    // ->field('staff_department,staff_name')
                    ->count();
                if ($data['pagenumber'] == "全部") {
                    $data['pagenumber'] = $count;
                }
                $res=Db::table('green_personalvalue')
                    ->where($info)
                    // ->field('id,staff_department,staff_name,output_value,draw_date')
                    ->order("output_value desc")
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                                $sum=Db::table('green_personalvalue')
                    ->where($info)
                    ->field('output_value')
                    ->sum('output_value');
                }
        }
        // return json($sum);
            $this -> view -> assign('orderList', $res);
            $this -> view -> assign('count', $count);
            $this -> view -> assign('pagenumber', $data["pagenumber"]);
            $this -> view -> assign('sum', round($sum,2));
         //渲染管理员列表模板
         return $this -> view -> fetch('gerenchanzhi');
    }

    //个人产值模糊筛选接口
    public function PersonalvalueSelectAll(Request $request){
        $data=$request->param();
        $count=Db::table('green_personalvalue')
            ->whereor(['staff_department'=>['like','%'.$data['content'].'%'],'staff_name'=>['like','%'.$data['content'].'%'],'draw_date'=>['like','%'.$data['content'].'%']])
            ->field('id,staff_department,staff_name,draw_date,output_value')->count();
            
        if ($data['pagenumber'] == "全部") {
            $data['pagenumber'] = $count;
        }
        $res=Db::table('green_personalvalue')
            ->whereor(['staff_department'=>['like','%'.$data['content'].'%'],'staff_name'=>['like','%'.$data['content'].'%'],'draw_date'=>['like','%'.$data['content'].'%']])
            ->field('id,staff_department,staff_name,draw_date,output_value')
            // ->field('round(SUM(output_value),2) as output_value')
            ->order("output_value desc")
            ->paginate($data['pagenumber'],false,["query"=>$data]);

        $sum=Db::table('green_personalvalue')
            ->whereor(['staff_department'=>['like','%'.$data['content'].'%'],'staff_name'=>['like','%'.$data['content'].'%'],'draw_date'=>['like','%'.$data['content'].'%']])->sum('output_value');
          
        $this -> view -> assign('orderList', $res);
        $this -> view -> assign('count',$count);
        $this -> view -> assign('pagenumber',$data['pagenumber']);
        $this -> view -> assign('sum', round($sum,2));
        //渲染管理员列表模板
        return $this -> view -> fetch('gerenchanzhi');
    }


    //新建合同
    public function contractAdd(Request $request)
    {
        $data=$request->param();
        if (GreenContract::get(['contract_id'=> $data['contract_id']])&&GreenContract::get(['project_id'=> $data['project_id']])) {
            //如果在表中查询到该用户名
            $status = 0;
            $message1 = '该合同已存在,请重新输入~~';
            return ['status'=>$status, 'message'=>$message1];
        }
        else{
            $status = 0;
            $message = '添加失败';
            if ($data['contract_type']=='其他') {
                $data['contract_type'] = $data['contract_type_self'];
            }
            else if ($data['contract_type']=='undefined') {
                $data['contract_type'] ='';
            }
            $res1=Db::table('green_contract')
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
                    'contract_compute'=>$data['contract_compute'],
                    'contract_design'=>$data['contract_design'],
                    'contract_content'=>$data['contract_content'],
                    'contract_reward'=>$data['contract_reward'],
                    'contract_operator'=>$data['contract_operator'],
                    'contract_projectleader'=>$data['contract_projectleader'],
                    'contract_approver'=>$data['contract_approver'],
                    'project_totalarea'=>$data['project_totalarea'],
                    'contract_remarks'=>$data['contract_remarks'],
                ]);
            if (!$res1) {
                $status = 0;
                $message = '新建失败~~';
            }
            else{
                $status = 1;
                $message = '新建成功';
            }
            $unitprice = explode('^', $data['contract_unitprice']);
            for ($i=1; $i < count($unitprice); $i++) {
                $contents = explode('*', $unitprice[$i]);
                $res2=Db::table('green_contractunitprice')
                    ->insert([
                        'contract_id'=>$data['contract_id'],
                        'project_id'=>$data['project_id'],
                        'contract_content'=>$contents[0],
                        'contract_unitprice'=>$contents[1],
                        'contract_floatingrate'=>$contents[2],
                    ]);
                if ($res2 === null) {
                    $status = 0;
                    $message = '添加失败~~';
                }
                else{$status = 1;
                    $message = '添加成功';
                }
            }
            $res3=Db::table('green_contractphase')
                ->insert([
                    'contract_id'=>$data['contract_id'],
                    'project_id'=>$data['project_id'],
                    'contract_phase1'=>$data['contract_phase1'],
                    'contract_phase2'=>$data['contract_phase2'],
                    'contract_phase3'=>$data['contract_phase3'],
                    'contract_phase4'=>$data['contract_phase4'],
                    'contract_phase5'=>$data['contract_phase5'],
                    'contract_phase6'=>$data['contract_phase6'],
                ]);
            if ($res3 === null) {
                $status = 0;
                $message = '添加失败~~';
            }
            else{
                $status = 1;
                $message = '添加成功';
            }
            return ['status'=>$status, 'message'=>$message];
        }
    }

// 客户总览
    public function kehu(Request $request)
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('customer');
        if ($limit ==0) {
            return $this -> view -> fetch('noPower');
            exit;
        }
        $data = $request -> param();

        $count = Db::table('green_customer')->where($data)->count();
        $list = Db::table('green_customer')->where($data)->order("project_id desc")->paginate(10);  

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', 10);

        //渲染管理员列表模板
        return $this -> view -> fetch('kehu');
    }
      //客户统计模糊筛选
    public function CustomerSelectAll(Request $request){
        $data=$request->param();
        $res=Db::table('green_customer')
            ->whereor([
                'project_id'=>['like','%'.$data['content'].'%'],
                'project_name'=>['like', '%'.$data['content'].'%'],
                'customer_company'=>['like','%'.$data['content'].'%'],
                'customer_name'=>['like','%'.$data['content'].'%'],
                'customer_phone'=>['like','%'.$data['content'].'%'],
                'customer_mobile'=>['like','%'.$data['content'].'%'],
                'customer_wechat'=>['like','%'.$data['content'].'%'],
                'customer_QQ'=>['like','%'.$data['content'].'%'],
                'customer_email'=>['like','%'.$data['content'].'%'],
            ])
            ->order("project_id desc")
            ->paginate($data['pagenumber'],false,["query"=>$data]);
        $count=Db::table('green_customer')
            ->whereor([
                'project_id'=>['like','%'.$data['content'].'%'],
                'project_name'=>['like', '%'.$data['content'].'%'],
                'customer_company'=>['like','%'.$data['content'].'%'],
                'customer_name'=>['like','%'.$data['content'].'%'],
                'customer_phone'=>['like','%'.$data['content'].'%'],
                'customer_mobile'=>['like','%'.$data['content'].'%'],
                'customer_wechat'=>['like','%'.$data['content'].'%'],
                'customer_QQ'=>['like','%'.$data['content'].'%'],
                'customer_email'=>['like','%'.$data['content'].'%'],
            ])
            ->count();
            $this -> view -> assign('count', $count);
            $this -> view -> assign('orderList', $res);
            $this -> view -> assign('pagenumber', $data["pagenumber"]);
        return $this -> view -> fetch('kehu');
    }
    //客户表筛选
    public function CustomerSelect(Request $request)
    {
        $data = $request -> param();
        $info=[];
        foreach ($data as $key => $value){
            if($value && $key !="pagenumber"&& $key !="page"){
                $info[$key]=$value;
            }
        }
        $count = Db::table('green_customer')->where($info)->count();
        $list = Db::table('green_customer')->where($info)->order("project_id desc")->paginate($data['pagenumber'],false,["query"=>$data]); 
            $this -> view -> assign('count', $count);
            $this -> view -> assign('orderList', $list);
            $this -> view -> assign('pagenumber', $data["pagenumber"]);
        return $this -> view -> fetch('kehu');
    }
    //新建客户
public function CustomerAdd(Request $request){
    $data=$request->param();
    if (GreenCustomer::get(['project_id'=> $data['project_id'],'customer_name'=>$data['customer_name']])) {
    //如果在表中查询到该用户名
    $status = 0;
    $message1 = '该客户已存在,请重新输入~~';
    return ['status'=>$status, 'message'=>$message1];
}
else{
    $res=Db::table('green_customer')
        ->insert([
            'project_id'=>$data['project_id'],
            'project_name'=>$data['project_name'],
            'customer_company'=>$data['customer_company'],
            'customer_department'=>$data['customer_department'],
            'customer_division'=>$data['customer_division'],
            'customer_name'=>$data['customer_name'],
            'customer_position'=>$data['customer_position'],
            'customer_phone'=>$data['customer_phone'],
            'customer_mobile'=>$data['customer_mobile'],
            'customer_wechat'=>$data['customer_wechat'],
            'customer_QQ'=>$data['customer_QQ'],
            'customer_email'=>$data['customer_email'],
            'customer_remarks'=>$data['customer_remarks'],
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
}
    //渲染客户编辑页面
    public function customerEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();

        $result = Db::table('green_customer')
            ->where([
                'project_id'=>$data['project_id'],
                'customer_name'=>$data['customer_name']
            ])->select();

        $this -> view -> assign('orderList', $result[0]);
        return $this -> view -> fetch('customer_edit');
    }
        //客户信息编辑提交接口
    public function customerEditComplete(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $res = Db::table('green_customer')
            ->where(['project_id'=>$data['project_id'],'customer_name'=>$data['customer_name']])
            ->update([ 
                'project_name'=>$data['project_name'],
                'customer_company'=>$data['customer_company'],
                'customer_department'=>$data['customer_department'],
                'customer_division'=>$data['customer_division'],
                'customer_position'=>$data['customer_position'],
                'customer_phone'=>$data['customer_phone'],
                'customer_mobile'=>$data['customer_mobile'],
                'customer_wechat'=>$data['customer_wechat'],
                'customer_QQ'=>$data['customer_QQ'],
                'customer_email'=>$data['customer_email'],
                'customer_remarks'=>$data['customer_remarks']
            ]);
        if ($res == 1) {
           return json(['status'=>1, 'message'=>"成功"]);
        }
        else{
            return json(['status'=>0, 'message'=>"修改信息失败！"]);
        }
        // $this -> view -> assign('orderList', $result[0]);
        
    }
// 删除接口
    //删除工程
    public function  ProjectDel(Request $request){
        $data=$request->param();
        $res=[];$res1=[];$res2=[];$res3=[];$res4=[];$res5=[];$res6=[];$res7=[];$res8=[];$res9=[];$res10=[];
        if(Db::table('green_project')->where('project_id',$data['project_id'])->select()){
            $res=Db::table('green_project')->where('project_id',$data['project_id'])->delete();}
        if(Db::table('green_project')->where('project_id',$data['project_id'])->select()){
            $res1=Db::table('green_projectconstruction')->where('project_id',$data['project_id'])->delete();}
        if(Db::query('show tables like "projectdesigner'.$data['project_id'].'"')){
            $res2=Db::query("DROP TABLE `greenbuild`.`projectdesigner".$data['project_id']."`");}
        if(Db::table('green_projectdraw')->where('project_id',$data['project_id'])->select()){
            $res3=Db::table('green_projectdraw')->where('project_id',$data['project_id'])->delete();}
        // if(Db::table('green_projectvalue')->where('project_id',$data['project_id'])->select()){
        //     $res4=Db::table('green_projectvalue')->where('project_id',$data['project_id'])->delete();}
        if(Db::table('green_projectdrawplan')->where('project_id',$data['project_id'])->select()){
            $res5=Db::table('green_projectdrawplan')->where('project_id',$data['project_id'])->delete();}
        if(Db::table('green_projectphase')->where('project_id',$data['project_id'])->select()){
            $res6=Db::table('green_projectphase')->where('project_id',$data['project_id'])->delete();}
        if(Db::table('green_projectvaluemajor')->where('project_id',$data['project_id'])->select()){
            $res7=Db::table('green_projectvaluemajor')->where('project_id',$data['project_id'])->delete();}
        // if(Db::table('green_project_totalvalue')->where('project_id',$data['project_id'])->select()){
        //     $res8=Db::table('green_project_totalvalue')->where('project_id',$data['project_id'])->delete();}
        if(Db::table('green_drawplan_designer')->where('project_id',$data['project_id'])->select()){
            $res9=Db::table('green_drawplan_designer')->where('project_id',$data['project_id'])->delete();}
        // if(Db::table('green_projectvalue')->where('project_id',$data['project_id'])->select()){
        //     $res10=Db::table('green_projectvalue')->where('project_id',$data['project_id'])->delete();}
        if ($res === null||$res1 === null||$res3 === null||$res5 === null||$res6 === null||$res7 === null||$res8 ===null||$res9 === null) {
            $status = 0;
            $message = '删除失败~~';
        }
        else{
            $status = 1;
            $message = '删除成功';
        }
        return ['status'=>$status, 'message'=>$message];
    }
    //删除合同
    public function  ContractDel(Request $request){
        $data=$request->param();
        $res=Db::table('green_contract')->where('id',$data['id'])->delete();
        $res1=Db::table('green_contractaccountphase')->where('contract_id',$data['contract_id'])->delete();
        $res2=Db::table('green_contractunitprice')->where('contract_id',$data['contract_id'])->delete();
        // $res3=Db::table('green_contractconstruction')->where('contract_id',$data['contract_id'])->delete();
        $res4=Db::table('green_contractledger')->where('contract_id',$data['contract_id'])->delete();
        $res5=Db::table('green_contractsettlement')->where('contract_id',$data['contract_id'])->delete();
        $res6=Db::table('green_confirm')->where('contract_id',$data['contract_id'])->delete();
        if (!$res&&!$res1&&!$res2&&!$res4&&!$res5&&!$res6) {
            $status = 0;
            $message = '删除失败~~';
        }
        else{
            $status = 1;
            $message = '删除成功';
        }
        return json(['status'=>$status, 'message'=>$message]);
    }
    //删除台账
    public function  LedgerDel(Request $request){
        $data=$request->param();
        $res=Db::table('green_contractledger')->where('contract_id',$data['contract_id'])->delete();
        $res1=Db::table('green_contractsettlement')->where('contract_id',$data['contract_id'])->delete();
        $res2=Db::table('green_confirm')->where('contract_id',$data['contract_id'])->delete();

        if ($res == null||$res1 == null||$res2==null) {
            $status = 0;
            $message = '删除失败~~';
        }
        else{
            $status = 1;
            $message = '删除成功';
        }
        return json(['status'=>$status, 'message'=>$message]);
    }
    //删除招投标
    public function  BidDel(Request $request){
        $data=$request->param();
        $res=Db::table('green_bid')->where('toubiao_id',$data['toubiao_id'])->delete();
        $res1=Db::table('green_bidcompensation')->where('toubiao_id',$data['toubiao_id'])->delete();
        $res2=Db::table('green_biddeposite')->where('toubiao_id',$data['toubiao_id'])->delete();
        $res3=Db::table('green_bidphase')->where('toubiao_id',$data['toubiao_id'])->delete();

        if ($res == null||$res1 == null||$res2==null||$res3==null) {
            $status = 0;
            $message = '删除失败~~';
        }
        else{
            $status = 1;
            $message = '删除成功';
        }
        return json(['status'=>$status, 'message'=>$message]);
    }
    //删除补偿费一条
    public function  CompensationDel(Request $request){
        $data=$request->param();
        $res=Db::table('green_bidcompensation')->where(['id'=>$data['id']])->delete();
        if ($res == null) {
            $status = 0;
            $message = '删除失败~~';
        }
        else{
            $status = 1;
            $message = '删除成功';
        }
        return json(['status'=>$status, 'message'=>$message]);
    }
    //删除保证金一条
    public function  DepositeDel(Request $request){
        $data=$request->param();
        $res=Db::table('green_biddeposite')->where(['id'=>$data['id']])->delete();
        // $res1=Db::table('green_biddeposite')
        //     ->where('project_id',$data['project_id'])
        //     ->sum('deposite_payment_amount');
        // Db::table('green_bid')
        //     ->where('project_id',$data['project_id'])
        //     ->update(['bid_deposite'=>$res1]);
        if ($res == null) {
            $status = 0;
            $message = '删除失败~~';
        }
        else{
            $status = 1;
            $message = '删除成功';
        }
        return json(['status'=>$status, 'message'=>$message]);
    }
    //删除工程产值一条
    public function ProjectValueDel(Request $request){
        $data=$request->param();
        $res=Db::table('green_projectvalue'.$data['project_id'])->where(['id'=>$data['id']])->delete();
        $designe_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('designe_value');
        $proofreading_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('proofreading_value');
        $audit_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('audit_value');
        $work_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('work_value');
        $project_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('project_value');
        $other_expenses=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('other_expenses');
        $value_subtotal=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('value_subtotal');
        $design_area=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('design_area');
        // $ground_floor_area=Db::table('green_projectvalue'.$data['project_id'])
        //     ->where('ground_floor'=>'1'])
        //     ->sum('design_area');
        // $underground_building_area=Db::table('green_projectvalue'.$data['project_id'])
        //     ->where('ground_floor'=>'0'])
        //     ->sum('design_area');
        // $total_building_area=$ground_floor_area+$underground_building_area;

        Db::table('green_project_totalvalue')
            ->where('project_id',$data['project_id'])
            ->update([
                'subject_contract_amount'=>$design_area,
                'design_value'=>$designe_value,
                'check_value'=>$proofreading_value,
                'worktype_value'=>$work_value,
                'examine_value'=>$audit_value,
                'project_value'=>$project_value,
                'other_expenses'=>$other_expenses,
                'design_area'=>$proofreading_value,
                // 'ground_floor_area'=>$ground_floor_area,
                // 'underground_building_area'=>$underground_building_area,
                // 'total_building_area'=>$total_building_area,
                'total_department'=>$value_subtotal
            ]);
        if ($res == null) {
            $status = 0;
            $message = '删除失败~~';
        }
        else{
            $status = 1;
            $message = '删除成功';
        }
        return ['status'=>$status, 'message'=>$message];
    }
    //删除客户
    public function CustomerDel(Request $request){
        $data=$request->param();
        $res=Db::table('green_customer')->where(['project_id'=>$data['project_id'],'customer_name'=>$data['customer_name']])->delete();
        if ($res == null) {
            $status = 0;
            $message = '删除失败~~';
        }
        else{
            $status = 1;
            $message = '删除成功';
        }
        return json(['status'=>$status, 'message'=>$message]);
    }
 //删除员工
    public function  StaffDel(Request $request){
        $data=$request->param();
        $res=Db::table('green_staff')->where(['staff_id'=>$data['staff_id']])->delete();
        $r=Db::table('green_staff')->where(['staff_id'=>$data['staff_id']])->field('staff_name')->select();
        $res2=Db::table('green_administrators')->where(['staff_name'=>$r])->delete();
        if ($res === null||$res2 ===null) {
            $status = 0;
            $message = '删除失败~~';
        }
        else{
            $status = 1;
            $message = '删除成功';
        }
        return json(['status'=>$status, 'message'=>$message]);
    }
    //删除管理员
    public function  AdminDel(Request $request){
        $data=$request->param();
        $res=Db::table('green_administrators')->where(['staff_name'=>$data['staff_id']])->delete();
        if ($res === null) {
            $status = 0;
            $message = '删除失败~~';
        }
        else{
            $status = 1;
            $message = '删除成功';
        }
        return json(['status'=>$status, 'message'=>$message]);
    }
    // 删除接口end

//新增保证金
    public function compensationAdd(Request $request){
        $data=$request->param();
        foreach ($data as $k=>$v)
        {
            if($v==''){$data[$k]=NULL;}
        }
        $res=Db::table('green_bidcompensation')
            ->insert([
                'toubiao_id'=>$data['toubiao_id'],
                'compensation_invoice_date'=>$data['compensation_invoice_date'],
                'compensation_invoice_amount'=>$data['compensation_invoice_amount'],
                'compensation_payment_date'=>$data['compensation_payment_date'],
                'compensation_payment_amount'=>$data['compensation_payment_amount'],
            ]);
        $res1=Db::table('green_bidcompensation')
            ->where('toubiao_id',$data['toubiao_id'])
            ->sum('compensation_payment_amount');
        Db::table('green_bid')
            ->where('toubiao_id',$data['toubiao_id'])
            ->update(['bid_compensation'=>$res1]);
        if ($res === null) {
            $status = 0;
            $message = '添加失败~~';
        }
        else{
            $status = 1;
            $message = '添加成功';
        }
        return json(['status'=>$status, 'message'=>$message]);
    }

//新增补偿费
    public function depositeAdd(Request $request){
        $data=$request->param();

        foreach ($data as $k=>$v)
        {
            if($v==''){$data[$k]=NULL;}
        }
        $res=Db::table('green_biddeposite')
            ->insert([
                'toubiao_id'=>$data['toubiao_id'],
                'deposite_invoice_id'=>$data['deposite_invoice_id'],
                'deposite_invoice_price'=>$data['deposite_invoice_price'],
                'deposite_invoice_object'=>$data['deposite_invoice_object'],
                'deposite_invoice_date'=>$data['invoice_date'],
                'deposite_invoice_amount'=>$data['invoice_amount'],
                'deposite_payment_date'=>$data['payment_date'],
                'deposite_payment_amount'=>$data['payment_amount'],
            ]);
        $res1=Db::table('green_biddeposite')
            ->where('toubiao_id',$data['toubiao_id'])
            ->sum('deposite_payment_amount');
        Db::table('green_bid')
            ->where('toubiao_id',$data['toubiao_id'])
            ->update(['bid_deposite'=>$res1]);
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

    //姓名模糊筛选
    public function getName(Request $request){
        $data=$request->param();
        $res=Db::table('green_staff')
            ->whereor([
                'staff_name'=>['like',$data['content'].'%'],
            ])
            ->field('staff_name')
            ->select();
        return json($res);
    }

      
    public function test(Request $request){
        $data=$request->param();
        $res2=[1];
        if(Db::query('show tables like "projectdesigner'.$data['project_id'].'"')){
            $res2=Db::query("DROP TABLE `greenbuild`.`projectdesigner".$data['project_id']."`");}
        return json($res2);
    }
     //渲染出图登记界面
    public function drawplan()
    {  
        $result =Db::table('green_projectdrawplan')
        ->order("project_id desc")
        ->paginate(10);
        $count =Db::table('green_projectdrawplan')
            ->count();
        // $record =GreenProject::select();
        //获取记录数量
        $this -> view -> assign('orderList', $result);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber',10);
        return $this->view->fetch('drawplan');
    }
//出图登记条件筛选
    public function drawplanSelect(Request $request)
    {
        $data = $request -> param();
        $info=[];
        $monomer=[];
        foreach ($data as $key => $value){
            if($value && $key !="pagenumber"&& $key !="page"&& $key !='sort1'&& $key !='sort2'&& $key !='sort3'&& $key !='sort4'){
                $info[$key]=$value;
            }
        }
        $sort = "";
        $isSort = 0;
        if ($data['sort1']) {
            $sort = $sort.$data['sort1'];
            $isSort = 1;
        }
        if ($data['sort2']) {
            $sort = $sort. ",".$data['sort2'];
            $isSort = 1;
        }
        if ($data['sort3']) {
            $sort = $sort. ",".$data['sort3'];
            $isSort = 1;
        }
        if ($data['sort4']) {
            $sort = $sort. ",".$data['sort4'];
            $isSort = 1;
        }
        if ($isSort) {
            $sort = $sort . " desc";
        }
            $res =Db::table('green_projectdrawplan')
                ->where($info)
                ->where('monomer_name',"like","%".$data['monomer_name']."%")
                ->order($sort)
               // ->select();
                ->paginate($data['pagenumber'],false,["query"=>$data]);
        $this -> view -> assign('count', count($res));
        $this -> view -> assign('orderList', $res);
        $this -> view -> assign('pagenumber', $data["pagenumber"]);
        return $this -> view -> fetch('drawplan');
    }

      //出图登记模糊筛选
    public function drawplanSelectAll(Request $request){
        $data=$request->param();
        $res=Db::table('green_projectdrawplan')
            ->whereor([
                'project_id'=>['like','%'.$data['content'].'%'],
                'project_name'=>['like', '%'.$data['content'].'%'],
                'project_contractor'=>['like','%'.$data['content'].'%'],
                'project_agent'=>['like','%'.$data['content'].'%'],
                'monomer_name'=>['like','%'.$data['content'].'%'],
                'drawplan_major'=>['like','%'.$data['content'].'%'],
                'drawplan_phase'=>['like','%'.$data['content'].'%'],
                'figure_number'=>['like','%'.$data['content'].'%'],
                'drawplan_number'=>['like','%'.$data['content'].'%'],
            ])
            ->order("project_id desc")
            ->paginate($data['pagenumber'],false,["query"=>$data]);
        $count=Db::table('green_projectdrawplan')
             ->whereor([
                'project_id'=>['like','%'.$data['content'].'%'],
                'project_name'=>['like', '%'.$data['content'].'%'],
                'project_contractor'=>['like','%'.$data['content'].'%'],
                'project_agent'=>['like','%'.$data['content'].'%'],
                'monomer_name'=>['like','%'.$data['content'].'%'],
                'drawplan_major'=>['like','%'.$data['content'].'%'],
                'drawplan_phase'=>['like','%'.$data['content'].'%'],
                'figure_number'=>['like','%'.$data['content'].'%'],
                'drawplan_number'=>['like','%'.$data['content'].'%'],
            ])
            ->count();
            $this -> view -> assign('count', $count);
            $this -> view -> assign('orderList', $res);
            $this -> view -> assign('pagenumber', $data["pagenumber"]);
        return $this -> view -> fetch('drawplan');
    }

    public function newdrawplan(){
        $list = Db::table('green_drawing_fee')->select();
        $this -> view -> assign('orderList', $list);
        return $this->view->fetch('newdrawplan');
    }
    public function insertData(Request $request){
        $data=$request->param();
        $res = GreenProject::get(['project_id'=>$data["value"]]);
        if($res){
            return json(["status"=>1,"message"=>"已查找到相关数据","project_name"=>$res["project_name"],"project_contractor"=>$res["project_contractor"],"project_agent"=>$res["project_agent"]]);
        }
        return json(["status"=>0,"message"=>"未查找到相关数据"]);
    }
    public function insertDrawPlanData(Request $request){
        $data=$request->param();
        $res = GreenProjectdrawplan::get(['project_id'=>$data["value"]]);
        if($res){
            return json(["status"=>1,"message"=>"已查找到相关数据","drawplan_survey"=>$res["drawplan_survey"]]);
        }
        return json(["status"=>0,"message"=>"未查找到相关数据"]);
    }
    public function insertDataWithItem(Request $request){
        $data=$request->param();
        $res = GreenProject::get(['project_id'=>$data["value"]]);

        $res1 =Db::table('green_projectvalue'.$data["value"])->field('entry_name')->select();
        GreenProject::get(['project_id'=>$data["value"]]);
        if($res){
            return json(["status"=>1,"message"=>"已查找到相关数据","project_name"=>$res["project_name"],"project_contractor"=>$res["project_contractor"],"project_agent"=>$res["project_agent"],"project_remark"=>$res["project_remark"],"names"=>$res1]);
        }
        return json(["status"=>0,"message"=>"未查找到相关数据"]);
    }
    // 新增出图计划项目
public function drawplanAdd(Request $request)
    {
        $data=$request->param();
        foreach ($data as $key => $value){
            if($value=='')
                {$data[$key]=null;}
        }
        if (!$data['project_id']) {
            return ['status'=>0, 'message'=>"缺少工程号"];
        }
        else if(!$data['project_name']){
            return ['status'=>0, 'message'=>"缺少工程名称"];
        }
        else if(!$data['project_contractor']){
            return ['status'=>0, 'message'=>"缺少发包人"];
        }
        else if(!$data['project_agent']){
            return ['status'=>0, 'message'=>"缺少代建方"];
        }
        else if(!$data['entry_name']){
            return ['status'=>0, 'message'=>"缺少单体名称"];
        }
        else if(!$data['drawplan_phase']){
            return ['status'=>0, 'message'=>"缺少阶段"];
        }
        else if(!$data['drawing_time']){
            return ['status'=>0, 'message'=>"缺少出图日期"];
        }
        else if(!$data['figure_number']){
            return ['status'=>0, 'message'=>"缺少图号"];
        }
        else if(!$data['drawplan_major']){
            return ['status'=>0, 'message'=>"缺少专业"];
        }
      
        $res1=Db::table('green_drawplan_designer')
            ->insertGetId([
                'project_id'=>$data['project_id'],
                'drawplan_project1'=>$data['drawplan_project1'],
                'drawplan_project2'=>$data['drawplan_project2'],
                'project_remarks'=>$data['project_remarks'],
                'drawplan_type1'=>$data['drawplan_type1'],
                'drawplan_type2'=>$data['drawplan_type2'],
                'type_remarks'=>$data['type_remarks'],
                'drawplan_designer1'=>$data['drawplan_designer1'],
                'drawplan_designer2'=>$data['drawplan_designer2'],
                'designer_remarks'=>$data['designer_remarks'],
                'drawplan_drafting1'=>$data['drawplan_drafting1'],
                'drawplan_drafting2'=>$data['drawplan_drafting2'],
                'drafting_remarks'=>$data['drafting_remarks'],
                'drawplan_check1'=>$data['drawplan_check1'],
                'drawplan_check2'=>$data['drawplan_check2'],
                'check_remarks'=>$data['check_remarks'],
                'drawplan_verify1'=>$data['drawplan_verify1'],
                'drawplan_verify2'=>$data['drawplan_verify2'],
                'verify_remarks'=>$data['verify_remarks'],
                'drawplan_authorize1'=>$data['drawplan_authorize1'],
                'drawplan_authorize2'=>$data['drawplan_authorize2'],
                'authorize_remarks'=>$data['authorize_remarks'],
            ]);
            $res2=Db::table('green_economic_indicators')
            ->insertGetId([
                'project_id'=>$data['project_id'],
                'entry_name'=>$data['entry_name'],
                'aboveground_area'=>$data['aboveground_area'],
                'underground_area'=>$data['underground_area'],
                'civil_air_defense'=>$data['civil_air_defense'],
                'layer_number'=>$data['layer_number'],
                'height'=>$data['height'],
                'category'=>$data['category'],
                'notes'=>$data['notes'],
            ]);
            // 合并参与人员
            if($data['drawplan_project2'])$data['drawplan_project2'] = $data['drawplan_project2'].';';
            if($data['drawplan_type2'])$data['drawplan_type2'] = $data['drawplan_type2'].';';
            if($data['drawplan_drafting2'])$data['drawplan_drafting2'] = $data['drawplan_drafting2'].';';
            if($data['drawplan_check2'])$data['drawplan_check2'] = $data['drawplan_check2'].';';
            if($data['drawplan_verify2'])$data['drawplan_verify2'] = $data['drawplan_verify2'].';';
            if($data['drawplan_authorize2'])$data['drawplan_authorize2'] = $data['drawplan_authorize2'].';';
            $temp = $data['drawplan_project2'].$data['drawplan_type2'].$data['drawplan_designer2'].$data['drawplan_drafting2'].$data['drawplan_check2'].$data['drawplan_verify2'].$data['drawplan_authorize2'];
            $res3=Db::table('green_projectdrawplan')
            ->insertGetId([
                'project_id'=>$data['project_id'],
                'project_name'=>$data['project_name'],
                'project_contractor'=>$data['project_contractor'],
                'project_agent'=>$data['project_agent'],
                'monomer_name'=>$data['entry_name'],
                'drawplan_major'=>$data['drawplan_major'],
                'drawplan_phase'=>$data['drawplan_phase'],
                'figure_number'=>$data['figure_number'],
                'drawplan_survey'=>$data['drawplan_survey'],
                'drawplan_number'=>$data['drawplan_number'],
                'drawplan_member'=>$temp,
                'drawplan_date'=>$data['drawing_time'],
                'drawplan_remarks'=>$data['drawplan_remarks'],
            ]);
            //将出图，整合到工程产值小表中
            $res4=Db::table('green_projectvalue'.$data['project_id'])
            ->insertGetId([
                'project_id'=>$data['project_id'],
                'project_name'=>$data['project_name'],
                'entry_name'=>$data['entry_name'],
                'project_subcontractor'=>$data['project_contractor'],
                'design_area'=>$data['design_area'],
                'contract_amount'=>$data['contract_amount'],
                'stage_proportions'=>$data['stage_proportions'],
                'difficulty_system'=>$data['difficulty_system'],
                'distribution_ratio'=>$data['distribution_ratio'],
                'residual_coefficient'=>$data['residual_coefficient'],
                'drawplan_major'=>$data['drawplan_major'],
                'designer'=>$data['designer'],
                'design_price'=>$data['design_price'],
                'design_value'=>$data['design_value'],
                'proofreader'=>$data['proofreader'],
                'proofreading_price'=>$data['proofreading_price'],
                'proofreading_value'=>$data['proofreading_value'],
                'auditor'=>$data['auditor'],
                'audit_price'=>$data['audit_price'],
                'audit_value'=>$data['audit_value'],
                'work_boss'=>$data['work_boss'],
                'work_basenumber'=>$data['work_basenumber'],
                'work_value'=>$data['work_value'],
                'project_boss'=>$data['project_boss'],
                'project_basenumber'=>$data['project_basenumber'],
                'project_value'=>$data['project_value'],
                'other_expenses'=>$data['other_expenses'],
                'value_subtotal'=>$data['value_subtotal'],
                'department'=>$data['department'],
                'drawing_time'=>$data['drawing_time'],
                'remarks'=>$data['remarks'],
            ]);
            // 更新total工程产值大表
        $design_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('design_value');
        $proofreading_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('proofreading_value');
        $audit_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('audit_value');
        $work_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('work_value');
        $project_value=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('project_value');
        $other_expenses=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('other_expenses');
        $value_subtotal=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('value_subtotal');
        $design_area=Db::table('green_projectvalue'.$data['project_id'])
            ->sum('design_area');

        $res5 =Db::table('green_project_totalvalue')
            ->where('project_id',$data['project_id'])
            ->update([
                'subject_contract_amount'=>$design_area,
                'design_value'=>$design_value,
                'check_value'=>$proofreading_value,
                'worktype_value'=>$work_value,
                'examine_value'=>$audit_value,
                'project_value'=>$project_value,
                'other_expenses'=>$other_expenses,
                'design_area'=>$proofreading_value,
                'total_department'=>$value_subtotal
            ]);
        // 更新total工程产值大表end
            // 检查是否有对应员工的个人产值表
            $personalList = [$data["designer"],$data["proofreader"],$data["auditor"],$data["work_boss"],$data["project_boss"]];
            $personalValue = [$data["design_value"],$data["proofreading_value"],$data["audit_value"],$data["work_value"],$data["project_value"]];
            for ($i=0; $i < 5; $i++) { 
                if (!$personalList[$i]) {
                    continue;
                }
                // 检查个人小表是否存在，若不存在则当即插入
                 $sql="CREATE TABLE IF NOT EXISTS`greenbuild`.`green_personalvalue".$personalList[$i]."` ( 
                        `id` INT NOT NULL AUTO_INCREMENT COMMENT '自增ID' , 
                        `staff_department` VARCHAR(20) NOT NULL COMMENT '部门' , 
                        `staff_name` VARCHAR(11) NOT NULL COMMENT '人员名字' , 
                        `draw_date` date NULL COMMENT '出图时间' , 
                        `project_id` VARCHAR(30) NULL COMMENT '工程号' , 
                        `project_name` VARCHAR(200)NOT NULL COMMENT '工程名称' , 
                        `entry_name` VARCHAR(200) NOT NULL COMMENT '单体名称' , 
                        `output_value` float(10,2) NULL COMMENT '产值（元）' , 
                        `staff_remarks` VARCHAR(200) NULL COMMENT '备注' ,
                        PRIMARY KEY (`id`)) ENGINE = InnoDB COMMENT = '".$personalList[$i]."个人产值';";
                Db::execute($sql);
                $staff_department = Db::table('green_staff')->where('staff_name',$personalList[$i])->value("staff_department");
                if(!$staff_department){
                    $staff_department = "暂无部门";
                }
                Db::table('green_personalvalue'.$personalList[$i])
                ->insert([
                'staff_department'=>$staff_department,
                'staff_name'=>$personalList[$i],
                'draw_date'=>$data['drawing_time'],
                'project_id'=>$data['project_id'],
                'project_name'=>$data['project_name'],
                'entry_name'=>$data['entry_name'],
                'output_value'=>$personalValue[$i],
                'staff_remarks'=>$data['remarks'],
                ]);
            // 重新加载total个人产值表中的产值数据
            if(!Db::table('green_personalvalue')->where("staff_name",$personalList[$i])->select()){
                Db::table('green_personalvalue')
                ->insert([
                'staff_department'=>$staff_department,
                'staff_name'=>$personalList[$i],
                'draw_date'=>$data['drawing_time'],
                'project_id'=>$data['project_id'],
                'project_name'=>$data['project_name'],
                'entry_name'=>$data['entry_name'],
                'output_value'=>$personalValue[$i],
                'staff_remarks'=>$data['remarks'],
                ]);
            }
            else{
                // 个人产值大表中已存在个人产值数据
                $output_value=Db::table('green_personalvalue'.$personalList[$i])
                    ->sum('output_value');
                Db::table('green_personalvalue')
               ->where('staff_name',$personalList[$i])
               ->update([
                'output_value'=>$output_value
                ]);
            }
            // 重新加载院产值表中的产值数据
            if(!Db::table('green_departmentvalue')->where("staff_name",$personalList[$i])->select()){
                Db::table('green_departmentvalue')
                ->insert([
                'staff_department'=>$staff_department,
                'staff_name'=>$personalList[$i],
                'draw_date'=>$data['drawing_time'],
                'total_personalvalue'=>$personalValue[$i],
                'reward_coefficient'=>1,
                'special_allowance'=>0,
                'yearend_personalvalue'=>$personalValue[$i],
                'departmentvalue_remarks'=>$data['remarks'],
                ]);
            }
            else{
                // 个人产值大表中已存在个人产值数据
                $output_value=Db::table('green_personalvalue'.$personalList[$i])->sum('output_value');
                $reward_coefficient = Db::table('green_departmentvalue')->where("staff_name",$personalList[$i])->value('reward_coefficient');
                $special_allowance = Db::table('green_departmentvalue')->where("staff_name",$personalList[$i])->value('special_allowance');
                $yearend_personalvalue = $output_value*$reward_coefficient+$special_allowance;
                Db::table('green_departmentvalue')->where('staff_name',$personalList[$i])
                ->update([
                'total_personalvalue'=>$output_value,
                'yearend_personalvalue'=>$yearend_personalvalue,
                ]);
            }
            
            }
        if (!$res3&&!$res4) {
            return ['status'=>0, 'message'=>'添加失败~~'];
        }
        return ['status'=>1, 'message'=>"添加成功~~"];
        // }
    }

    //渲染出图登记详情界面
    public function drawplan_details(Request $request)
    {
        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("project_view");
        $this -> view -> assign('limit', $limit);
        //获取到要编辑的工程号
        $project_id = $request -> param('project_id');
        $data=['project_id'=>$project_id];
        
        //根据ID和手机号进行查询
        $result =GreenProject::get($data);
        $result1=Db::table('green_projectdrawplan')->where($data)->select();
        $result2=Db::table('green_drawplan_designer')->where($data)->select();
        $result3=Db::table('green_economic_indicators')->where($data)->select();
// exit;
        $this->view->assign('jibenxinxi',$result);
        $this->view->assign('content',$result1[0]);
        $this->view->assign('drawplan_designer',$result2);
        $this->view->assign('economic_indicators',$result3[0]);
        //渲染编辑模板
  
            return $this->view->fetch('drawplan_details');
    }
    //出图登记编辑修改
    public function drawplanEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result = Db::table('green_projectdrawplan')
            ->where([
                'project_id'=>$data['id']
            ])
            ->update([
                $data['column']=>$data['content'],
            ]);

        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //出图计划设计人员编辑修改
    public function ecoedit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $result=Db::table('green_economic_indicators')
        ->where('project_id',$data['id'])
        ->update([
            $data['column']=>$data['content'],
        ]);

        if (null!=$result) {
            return ['status'=>1,'message'=>'更新成功'];
        } else {
            return ['status'=>0,'message'=>'更新失败,请检查'];
        }
    }
    //出图计划设计人员编辑修改
    public function drawplanDesignerEdit(Request $request)
    {
        //获取数据
        $data = $request -> param();
        if ($data['column']=='1') {
            $result = Db::table('green_drawplan_designer')
            ->where([
                'project_id'=>$data['project_id']
            ])
            ->update([
                'drawplan_project1'=>$data['content1'],
                'drawplan_type1'=>$data['content2'],
                'drawplan_designer1'=>$data['content3'],
                'drawplan_drafting1'=>$data['content4'],
                'drawplan_check1'=>$data['content5'],
                'drawplan_verify1'=>$data['content6'],
                'drawplan_authorize1'=>$data['content7'],
            ]);
        }
        elseif ($data['column']=='2') {
            $result = Db::table('green_drawplan_designer')
            ->where([
                'project_id'=>$data['project_id']
            ])
            ->update([
                'drawplan_project2'=>$data['content1'],
                'drawplan_type2'=>$data['content2'],
                'drawplan_designer2'=>$data['content3'],
                'drawplan_drafting2'=>$data['content4'],
                'drawplan_check2'=>$data['content5'],
                'drawplan_verify2'=>$data['content6'],
                'drawplan_authorize2'=>$data['content7'],
            ]);
        }
        elseif ($data['column']=='3') {
            $result = Db::table('green_drawplan_designer')
            ->where([
                'project_id'=>$data['project_id']
            ])
            ->update([
                'project_remarks'=>$data['content1'],
                'type_remarks'=>$data['content2'],
                'designer_remarks'=>$data['content3'],
                'drafting_remarks'=>$data['content4'],
                'check_remarks'=>$data['content5'],
                'verify_remarks'=>$data['content6'],
                'authorize_remarks'=>$data['content7'],
            ]);
        }
        
            // return json(['status'=>1,'message'=>'更新成功']);
    }
    //图文制作费明细 渲染接口
    public function fee()
    {
        $count = Db::table('green_drawing_fee')->count();
        $list = Db::table('green_drawing_fee')->order("id")->select();

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        return $this->view->fetch('fee');
    }
        //新增architecture_fee_new页面
    public function architecture_fee_new(){
        return $this->view->fetch("architecture_fee_new");
    }
    //建筑专业设计单价明细 渲染接口
    public function architecture_fee()
    {
        $count = Db::table('green_architecture_fee')->count();
        $list = Db::table('green_architecture_fee')->order("id")->select();

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        return $this->view->fetch('architecture_fee');
    }
         //新增structure_fee_new页面
    public function structure_fee_new(){
        return $this->view->fetch("structure_fee_new");
    }
    //结构专业设计单价明细 渲染接口
    public function structure_fee()
    {
        $count = Db::table('green_structure_fee')->count();
        $list = Db::table('green_structure_fee')->order("id")->select();

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        return $this->view->fetch('structure_fee');
    }
    //设备专业设计单价明细 渲染接口
    public function equipment_fee()
    {
        $count = Db::table('green_equipment_fee')->count();
        $list = Db::table('green_equipment_fee')->order("id")->select();

        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('count', $count);
        return $this->view->fetch('equipment_fee');
    }

    //图文制作费编辑接口
    public function feeEdit(Request $request)
    {
        $data=$request->param('data');
        $data=explode("[",$data);
        $data1=[];
        $data2=[];
        $data3=[];
        $i=0;
        foreach ($data as $key => $value) {
            if($key!=0&&$key!=1&&$key!=2)
            {
                $data1[$i++]=explode("]", $value);
            }
        }
        $i=0;
        foreach ($data1 as $key => $value) {
            $data2[$i++]=explode(",", $value[0]);
        }
        for($i=0;$i<count($data2);$i++)
        {
            for($j=0;$j<count($data2[0]);$j++){
            $data2[$i][$j]=str_replace("'", "",$data2[$i][$j]);
            }
        }
        $timer = 0;
        for($i=0;$i<count($data2);$i++)
        {
            $res=Db::table('green_drawing_fee')
            ->where('id',$data2[$i][0])
            ->update([
                'drawing_size'=>$data2[$i][1],
                'drawing_blueprint'=>$data2[$i][2],
                'drawing_sulphuric_acid_diagram'=>$data2[$i][3],
                'drawing_text_production'=>$data2[$i][4],
            ]);
            $timer = $timer +$res;
        }
        if ($timer>0) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
        
    }
     //图文制作费新增接口
    public function feeAdd(Request $request)
    {
        $data=$request->param();
        $res=Db::table('green_drawing_fee')
            ->insert([
                'drawing_size'=>$data['drawing_size'],
                'drawing_blueprint'=>$data['drawing_blueprint'],
                'drawing_sulphuric_acid_diagram'=>$data['drawing_sulphuric_acid_diagram'],
                'drawing_text_production'=>$data['drawing_text_production'],
            ]);
        if ($res) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }

    //新增fee_new页面
    public function fee_new(){
        return $this->view->fetch("fee_new");
    }

    //图文制作费删除接口
    public function feeDel(Request $request)
    {
        $data=$request->param('id');
        $res=Db::table('green_drawing_fee')
            ->where('id',$data)
            ->delete();
        if ($res) {
            return ['status'=>1, 'message'=>'删除成功'];
        } else {
            return ['status'=>0, 'message'=>'删除失败,请检查'];
        }
    }

    //建筑专业编辑接口
    public function architectureEdit(Request $request)
    {
        $data=$request->param('data');
        $data=explode("[",$data);
        $data1=[];
        $data2=[];
        $data3=[];
        $i=0;
        foreach ($data as $key => $value) {
            if($key!=0&&$key!=1&&$key!=2)
            {
                $data1[$i++]=explode("]", $value);
            }
        }
        $i=0;
        foreach ($data1 as $key => $value) {
            $data2[$i++]=explode(",", $value[0]);
        }
        for($i=0;$i<count($data2);$i++)
        {
            for($j=0;$j<count($data2[0]);$j++){
            $data2[$i][$j]=str_replace("'", "",$data2[$i][$j]);
            }
        }
        $timer = 0;
        for($i=0;$i<count($data2);$i++)
        {
            $res=Db::table('green_architecture_fee')
            ->where('id',$data2[$i][0])
            ->update([
                'project_classification'=>$data2[$i][1],
                'design_unit_price'=>$data2[$i][2],
                'unitprice_remarks'=>$data2[$i][3],
            ]);
            $timer = $timer+$res;
        }
        if ($timer>0) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //建筑专业新增接口
    public function architectureAdd(Request $request)
    {
        $data=$request->param();
        $res=Db::table('green_architecture_fee')
            ->insert([
                'project_classification'=>$data['project_classification'],
                'design_unit_price'=>$data['design_unit_price'],
                'unitprice_remarks'=>$data['unitprice_remarks'],
            ]);
        if ($res) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //建筑专业删除接口
    public function architectureDel(Request $request)
    {
        $data=$request->param('id');
        $res=Db::table('green_architecture_fee')
            ->where('id',$data)
            ->delete();
        if ($res) {
            return ['status'=>1, 'message'=>'删除成功'];
        } else {
            return ['status'=>0, 'message'=>'删除失败,请检查'];
        }
    }
    //结构专业编辑接口
    public function structureEdit(Request $request)
    {
        $data=$request->param('data');
        $data=explode("[",$data);
        $data1=[];
        $data2=[];
        $data3=[];
        $i=0;
        foreach ($data as $key => $value) {
            if($key!=0&&$key!=1&&$key!=2)
            {
                $data1[$i++]=explode("]", $value);
            }
        }
        $i=0;
        foreach ($data1 as $key => $value) {
            $data2[$i++]=explode(",", $value[0]);
        }
        for($i=0;$i<count($data2);$i++)
        {
            for($j=0;$j<count($data2[0]);$j++){
            $data2[$i][$j]=str_replace("'", "",$data2[$i][$j]);
            }
        }
        $timer = 0;
        for($i=0;$i<count($data2);$i++)
        {
            $res=Db::table('green_structure_fee')
            ->where('id',$data2[$i][0])
            ->update([
                'project_classification'=>$data2[$i][1],
                'design_unit_price'=>$data2[$i][2],
                'unitprice_remarks'=>$data2[$i][3],
            ]);
            $timer = $timer+$res;
        }
        if ($timer>0) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //结构专业新增接口
    public function structureAdd(Request $request)
    {
        $data=$request->param();
        // echo $data;
        $res=Db::table('green_structure_fee')
            ->insert([
                'project_classification'=>$data['project_classification'],
                'design_unit_price'=>$data['design_unit_price'],
                'unitprice_remarks'=>$data['unitprice_remarks'],
            ]);
        if ($res) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //结构专业删除接口
    public function structureDel(Request $request)
    {
        $data=$request->param('id');
        $res=Db::table('green_structure_fee')
            ->where('id',$data)
            ->delete();
        if ($res) {
            return ['status'=>1, 'message'=>'删除成功'];
        } else {
            return ['status'=>0, 'message'=>'删除失败,请检查'];
        }
    }
    //设备专业编辑接口
    public function equipmentEdit(Request $request)
    {
        $data=$request->param('data');
        $data=explode("[",$data);
        $data1=[];
        $data2=[];
        $data3=[];
        $i=0;
        foreach ($data as $key => $value) {
            if($key!=0&&$key!=1&&$key!=2)
            {
                $data1[$i++]=explode("]", $value);
            }
        }
        $i=0;
        foreach ($data1 as $key => $value) {
            $data2[$i++]=explode(",", $value[0]);
        }
        for($i=0;$i<count($data2);$i++)
        {
            for($j=0;$j<count($data2[0]);$j++){
            $data2[$i][$j]=str_replace("'", "",$data2[$i][$j]);
            }
        }
        $timer = 0;
        for($i=0;$i<count($data2);$i++)
        {
            $res=Db::table('green_equipment_fee')
            ->where('id',$data2[$i][0])
            ->update([
                'project_classification'=>$data2[$i][1],
                'strong_electricity'=>$data2[$i][2],
                'water'=>$data2[$i][3],
                'weak_current'=>$data2[$i][4],
                'HVAC'=>$data2[$i][5],
                'unitprice_remarks'=>$data2[$i][6],
            ]);
            $timer = $timer +$res;
        }
        if ($timer>0) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //设备专业新增接口
    public function equipmentAdd(Request $request)
    {
        $data=$request->param();
        $res=Db::table('green_equipment_fee')
            ->insert([
                'project_classification'=>$data['project_classification'],
                'strong_electricity'=>$data['strong_electricity'],
                'water'=>$data['water'],
                'weak_current'=>$data['weak_current'],
                'HVAC'=>$data['HVAC'],
                'unitprice_remarks'=>$data['unitprice_remarks'],
            ]);
        if ($res) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }
    //设备专业删除接口
    public function equipmentDel(Request $request)
    {
        $data=$request->param('id');
        $res=Db::table('green_equipment_fee')
            ->where('id',$data)
            ->delete();
        if ($res) {
            return ['status'=>1, 'message'=>'删除成功'];
        } else {
            return ['status'=>0, 'message'=>'删除失败,请检查'];
        }
    }
    //开票条件筛选接口（工程号，开票类型，开票时间）
    public function invoiceSelect(Request $requset)
    {

        $sid=Session::get('staff_id');
        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value('bid');
        $this -> view -> assign('limit', $limit);
        if($limit!=2)
        {
            return $this->view->fetch('noPower');
        }
        else
            {
        $data=$requset->param();
        $info=[];
        if(null!=$data['toubiao_id']){$info['toubiao_id']=$data['toubiao_id'];}
        
        if($data['type']=='保证金')
        {
            if($data['start']&&$data['end']&&null==$data['start1']&&null==$data['end1'])
            {
                $list = Db::table('green_biddeposite')
                ->where($info)
                ->where('deposite_invoice_date','BETWEEN',[$data['start'],$data['end']])
                ->order("deposite_invoice_date desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);
                $list1 = Db::table('green_bidcompensation')
                ->order("compensation_invoice_date desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count=Db::table('green_biddeposite')
                ->where($info)
                ->where('deposite_invoice_date','BETWEEN',[$data['start'],$data['end']])
                ->count()+Db::table('green_bidcompensation')->count();
            }
            else if($data['start1']&&$data['end1']&&null==$data['start']&&null==$data['end'])
            {
                $list = Db::table('green_biddeposite')
                ->where($info)
                ->where('deposite_payment_date','BETWEEN',[$data['start1'],$data['end1']])
                ->order("deposite_invoice_date desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);

                $list1 = Db::table('green_bidcompensation')
                ->order("compensation_invoice_date desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);

                $count=Db::table('green_biddeposite')
                ->where($info)
                ->where('deposite_payment_date','BETWEEN',[$data['start1'],$data['end1']])
                ->count()+Db::table('green_bidcompensation')->count();
            }
            else if($data['start1']&&$data['end1']&&$data['start']&&$data['end'])
            {
                $list = Db::table('green_biddeposite')
                ->where($info)
                ->where('deposite_invoice_date','BETWEEN',[$data['start'],$data['end']])
                ->where('deposite_payment_date','BETWEEN',[$data['start1'],$data['end1']])
                ->order("deposite_invoice_date desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);

                $list1 = Db::table('green_bidcompensation')
                ->order("compensation_invoice_date desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);

                $count=Db::table('green_biddeposite')
                ->where($info)
                ->where('deposite_invoice_date','BETWEEN',[$data['start'],$data['end']])
                ->where('deposite_payment_date','BETWEEN',[$data['start1'],$data['end1']])
                ->count()+Db::table('green_bidcompensation')->count();
            }
            else
            {
                $list = Db::table('green_biddeposite')
                    ->where($info)
                    ->order("deposite_invoice_date desc")
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $list1 = Db::table('green_bidcompensation')
                    ->order("compensation_invoice_date desc")
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count=Db::table('green_biddeposite')
                    ->where($info)
                    ->count()+Db::table('green_bidcompensation')->count();
            }

            
        }
        if($data['type']=='补偿费')
        {
//            $list = Db::table('green_biddeposite')
//            ->order('deposite_invoice_date desc')
//            ->paginate($data['pagenumber'],false,["query"=>$data]);

            if($data['start']&&$data['end']&&null==$data['start1']&&null==$data['end1'])
            {
                $list1 = Db::table('green_bidcompensation')
                    ->where($info)
                    ->where('compensation_invoice_date','BETWEEN',[$data['start'],$data['end']])
                    ->order("compensation_invoice_date desc")
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $list = Db::table('green_biddeposite')
                    ->order('deposite_invoice_date desc')
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count=Db::table('green_biddeposite')
                        ->count()+
                        Db::table('green_bidcompensation')
                        ->where($info)
                        ->where('compensation_invoice_date','BETWEEN',[$data['start'],$data['end']])->count();
            }
            else if($data['start1']&&$data['end1']&&null==$data['start']&&null==$data['end'])
            {
                $list1 = Db::table('green_bidcompensation')
                ->where($info)
                ->where('deposite_payment_date','BETWEEN',[$data['start1'],$data['end1']])
                ->order("compensation_invoice_date desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);

                $list = Db::table('green_biddeposite')
                ->order("deposite_invoice_date desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);

                $count=Db::table('green_bidcompensation')
                ->where($info)
                ->where('deposite_payment_date','BETWEEN',[$data['start1'],$data['end1']])
                ->count()+Db::table('green_biddeposite')->count();
            }
            else if($data['start1']&&$data['end1']&&$data['start']&&$data['end'])
            {
                $list1 = Db::table('green_bidcompensation')
                ->where($info)
                ->where('deposite_invoice_date','BETWEEN',[$data['start'],$data['end']])
                ->where('deposite_payment_date','BETWEEN',[$data['start1'],$data['end1']])
                ->order("compensation_invoice_date desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);

                $list = Db::table('green_biddeposite')
                ->order("deposite_invoice_date desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);

                $count=Db::table('green_bidcompensation')
                ->where($info)
                ->where('deposite_invoice_date','BETWEEN',[$data['start'],$data['end']])
                ->where('deposite_payment_date','BETWEEN',[$data['start1'],$data['end1']])
                ->count()+Db::table('green_biddeposite')->count();
            }
            else
            {
                $list = Db::table('green_biddeposite')
                    ->order("deposite_invoice_date desc")
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $list1 = Db::table('green_bidcompensation')
                    ->where($info)
                    ->order("compensation_invoice_date desc")
                    ->paginate($data['pagenumber'],false,["query"=>$data]);
                $count=Db::table('green_biddeposite')
                        ->where($info)
                        ->count()+Db::table('green_bidcompensation')->count();
            }
        }

            $this -> view -> assign('orderList', $list);
            $this -> view -> assign('orderList1', $list1);
            $this -> view -> assign('count', $count);
            $this -> view -> assign('pagenumber', $data['pagenumber']);
        return $this -> view -> fetch('invoiceHistory');
        }
    }
    //开票模糊筛选接口（工程号）
    public function invoiceSelectAll(Request $request)
    {
        $data=$request->param();
        $list=Db::table('green_biddeposite')
            ->whereor([
                'toubiao_id'=>['like','%'.$data['content'].'%'],
            ])
            ->order("deposite_invoice_date desc")
            ->paginate($data['pagenumber'],false,["query"=>$data]);
        $list1=Db::table('green_bidcompensation')
            ->whereor([
                'toubiao_id'=>['like','%'.$data['content'].'%'],
            ])
            ->order("compensation_invoice_date desc")
            ->paginate($data['pagenumber'],false,["query"=>$data]);
        $count=Db::table('green_bidcompensation')
            ->whereor([
                'toubiao_id'=>['like','%'.$data['content'].'%'],
            ])->count()+Db::table('green_biddeposite')
                ->whereor([
                    'toubiao_id'=>['like','%'.$data['content'].'%'],
                ])->count();
        $this -> view -> assign('orderList', $list);
        $this -> view -> assign('orderList1', $list1);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', $data['pagenumber']);
        return $this -> view -> fetch('invoiceHistory');
    }
//开票删除
    public function kaipiaoshanchu(Request $request)
    {
        $data=$request->param();
         if($data['type']=='保证金'){
             if(Db::table('green_biddeposite')->where('project_id',$data['project_id'])->select()){
            $res=Db::table('green_biddeposite')->where('project_id',$data['project_id'])->delete();
                 $res1=Db::table('green_biddeposite')
                     ->where('project_id',$data['project_id'])
                     ->sum('deposite_payment_amount');
                 Db::table('green_bid')
                     ->where('project_id',$data['project_id'])
                     ->update(['bid_deposite'=>$res1]);}
         }
       if($data['type']=='补偿费'){
         if(Db::table('green_biddeposite')->where('project_id',$data['project_id'])->select()){
            $res=Db::table('green_biddeposite')->where('project_id',$data['project_id'])->delete();
             $res1=Db::table('green_bidcompensation')
                 ->where('project_id',$data['project_id'])
                 ->sum('compensation_payment_amount');
             Db::table('green_bid')
                 ->where('project_id',$data['project_id'])
                 ->update(['bid_compensation'=>$res1]);}
       }
        if ($res === null){
            $status = 0;
            $message = '删除失败~~';
        }
        else{
            $status = 1;
            $message = '删除成功';
        }
        return ['status'=>$status, 'message'=>$message];
    }
    // 加晒记录概览渲染
    public function slideshow()
    {
//        $sid=Session::get('staff_id');
//        $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("tosum");
//        $this -> view -> assign('limit', $limit);
        $count = Db::table('green_tosum')->count();
        $result = Db::table('green_tosum')->order("sum_date desc")->paginate(10);
        $this -> view -> assign('orderList', $result);
        $this -> view -> assign('count', $count);
        $this -> view -> assign('pagenumber', 10);
        //渲染管理员列表模板
        return $this -> view -> fetch('slideshow');
    }
//新增加晒记录_页面渲染
    public function newslideshow()
    {
        $list = Db::table('green_drawing_fee')->select();
        $this -> view -> assign('orderList', $list);
        return $this->view->fetch('newslideshow');
    }
// 新增加晒记录，功能函数
    public function addslideshow(Request $request){
         $data=$request->param();
        $sql="CREATE TABLE IF NOT EXISTS`greenbuild`.`green_tosum".$data['project_id']."` ( 
                            `id` INT NOT NULL AUTO_INCREMENT COMMENT '自增ID' , 
                            `stampflag` VARCHAR(20) NOT NULL COMMENT '明细时间戳' , 
                            `project_id` VARCHAR(50) NOT NULL COMMENT '工程号' , 
                            `project_name` VARCHAR(200) NOT NULL COMMENT '工程名称' , 
                            `entry_name` VARCHAR(200) NOT NULL COMMENT '单体名称' , 
                            `project_contractor` VARCHAR(300) NULL COMMENT '发包人' , 
                            `project_agent` VARCHAR(300) NULL COMMENT '代建方' , 
                            `sum_fee` VARCHAR(50) NULL COMMENT '加晒费总额' , 
                            `sum_settled` VARCHAR(50) NULL COMMENT '已结算加晒费' , 
                            `sum_free` VARCHAR(11) NULL COMMENT '免费金额' , 
                            `project_sort` VARCHAR(20) NULL COMMENT '加晒图分类' ,  
                            `sum_receivable` VARCHAR(11) NULL COMMENT '应收金额' ,
                            `sum_copies` int(11) NULL COMMENT '图纸数量' ,
                            `sum_norms` VARCHAR(140) NULL COMMENT '图纸规格' , 
                            `sum_number` VARCHAR(11) NULL COMMENT '图纸份数' ,
                            `sum_untiprice` FLOAT(10,2) NULL COMMENT '单份合计' , 
                            `sum_totalprice` FLOAT(10,2) NULL COMMENT '小计' ,  
                            `sum_date` DATE NULL COMMENT '加晒时间' , 
                            `sum_remarks` VARCHAR(200) NULL COMMENT '备注' , 
                            PRIMARY KEY (`id`)) ENGINE = InnoDB COMMENT = '".$data['project_id']."加晒记录';";
            Db::execute($sql);
            $stampflag = time();

         if ($data["flag"]==0) {
                //规格加晒，增加多条记录
            $list =explode("*", $data["entry_name"]);
            for ($i=1; $i <  sizeof($list); $i++) { 
                // 插入数据进入分支表
               $res=Db::table('green_tosum'.$data["project_id"])->insert(['project_id'=>$data['project_id'],
                    'stampflag'=>$stampflag,
                    'project_name'=>$data['project_name'],
                    'project_contractor'=>$data['project_contractor'],
                    'project_agent'=>$data['project_agent'],
                    'project_sort'=>$data['sort'],
                    'sum_date'=>$data['date'],
                    'sum_settled'=>$data['sum_settled'],
                    'sum_number'=>$data['sum_number'],
                    'entry_name'=>$list[$i]]);
               // 插入数据进入总表
                $res1=Db::table('green_tosum')->insert(['project_id'=>$data['project_id'],
                    'stampflag'=>$stampflag,
                    'project_name'=>$data['project_name'],
                    'project_contractor'=>$data['project_contractor'],
                    'project_agent'=>$data['project_agent'],
                    'project_sort'=>$data['sort'],
                    'sum_date'=>$data['date'],
                    'sum_settled'=>$data['sum_settled'],
                    'sum_number'=>$data['sum_number'],
                    'entry_name'=>$list[$i]]);
            }
         }
         else{
            //自定义加晒，只增加单条记录
            $names = Db::table('green_drawing_fee')->field('drawing_size')->select();
            //定义总额为 $sum_fee,单份合计为 sum_norms,图纸规格为 sum_norms,小计为 sum_totalprice，图纸数量为 $sum_copies
            $sum_fee = 0;
            $sum_totalprice = 0;

            $sum_norms = "";
            $sum_copies =0;
            $sum_untiprice =0;
            for($i = 0;$i<sizeof($names);$i++)
            {
                // 蓝图
                if ($data["blueprint_".$names[$i]["drawing_size"]]) {
                     $sum_copies += number_format($data["blueprint_".$names[$i]["drawing_size"]], 2);
                     $price = number_format($data["blueprint_".$names[$i]["drawing_size"]], 2)*number_format($data["price_blueprint_".$names[$i]["drawing_size"]], 2);
                    $sum_untiprice += number_format($price,2);
                    if (!strlen($sum_norms))
                       $sum_norms =$sum_norms.'蓝'.$names[$i]["drawing_size"]."*".number_format($data["blueprint_".$names[$i]["drawing_size"]]);
                    else
                        $sum_norms =$sum_norms.'、蓝'.$names[$i]["drawing_size"]."*".number_format($data["blueprint_".$names[$i]["drawing_size"]]);
                    
                }
                // 硫酸图
                if ($data["sulphuric_acid_diagram_".$names[$i]["drawing_size"]]) {
                     $sum_copies += number_format($data["sulphuric_acid_diagram_".$names[$i]["drawing_size"]], 2);
                     $price = number_format($data["sulphuric_acid_diagram_".$names[$i]["drawing_size"]], 2)*number_format($data["price_sulphuric_acid_diagram_".$names[$i]["drawing_size"]], 2);
                    $sum_untiprice += number_format($price,2);
                    if (!strlen($sum_norms))
                       $sum_norms =$sum_norms.'硫'.$names[$i]["drawing_size"]."*".number_format($data["sulphuric_acid_diagram_".$names[$i]["drawing_size"]]);
                    else
                        $sum_norms =$sum_norms.'、硫'.$names[$i]["drawing_size"]."*".number_format($data["sulphuric_acid_diagram_".$names[$i]["drawing_size"]]);
                }
                // 文本制作数量
                if ($data["text_production_".$names[$i]["drawing_size"]]) {
                     $sum_copies += number_format($data["text_production_".$names[$i]["drawing_size"]], 2);
                     $price = number_format($data["text_production_".$names[$i]["drawing_size"]], 2)*number_format($data["price_text_production_".$names[$i]["drawing_size"]], 2);
                    $sum_untiprice += number_format($price,2);
                     if (!strlen($sum_norms))
                       $sum_norms =$sum_norms.'文'.$names[$i]["drawing_size"]."*".number_format($data["text_production_".$names[$i]["drawing_size"]]);
                    else
                        $sum_norms =$sum_norms.'、文'.$names[$i]["drawing_size"]."*".number_format($data["text_production_".$names[$i]["drawing_size"]]);
                }
            }
            $sum_totalprice = $sum_untiprice*number_format($data['sum_number']);
             // 插入数据进入分支表
               $res=Db::table('green_tosum'.$data["project_id"])->insert(['project_id'=>$data['project_id'],
                    'stampflag'=>$stampflag,
                    'project_name'=>$data['project_name'],
                    'project_contractor'=>$data['project_contractor'],
                    'project_agent'=>$data['project_agent'],
                    'project_sort'=>$data['sort'],
                    'sum_date'=>$data['date'],
                    'sum_settled'=>$data['sum_settled'],
                    'sum_number'=>$data['sum_number'],
                    'entry_name'=>$data['one_entry_name'],
                    'sum_copies'=>$sum_copies,
                    'sum_untiprice'=>$sum_untiprice,
                    'sum_totalprice'=>$sum_totalprice,
                    'sum_norms'=>$sum_norms,
                    'sum_fee'=>$data["sum"]]);
               // 插入数据进入总表
                $res1=Db::table('green_tosum')->insert(['project_id'=>$data['project_id'],
                    'stampflag'=>$stampflag,
                    'project_name'=>$data['project_name'],
                    'project_contractor'=>$data['project_contractor'],
                    'project_agent'=>$data['project_agent'],
                    'project_sort'=>$data['sort'],
                    'sum_date'=>$data['date'],
                    'sum_settled'=>$data['sum_settled'],
                    'sum_number'=>$data['sum_number'],
                    'entry_name'=>$data['one_entry_name'],
                    'sum_fee'=>$data["sum"]]);

         }
         return ['res'=>$res,'message'=>"message"];
    }
//加晒记录详情渲染
public function slideshow_details(Request $request)
{
   $sid=Session::get('staff_id');
   // $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("tosum");
   $this -> view -> assign('limit', 2);
    $data=$request->param();
    $res=Db::table('green_tosum'.$data['project_id'])->where('stampflag',$data["stampflag"])->select();
    $this->view->assign('content',$res[0]);
    return $this->view->fetch('slideshow_details');
}
    //加晒记录模糊筛选接口
public function tosumSelectAll(Request $request){
    $data=$request->param();
    $res=Db::table('green_tosum')
        ->whereor([
            'project_id'=>['like','%'.$data['content'].'%'],
            'project_name'=>['like', '%'.$data['content'].'%'],
            'project_contractor'=>['like','%'.$data['content'].'%'],
            'project_agent'=>['like','%'.$data['content'].'%'],
            'sum_fee'=>['like','%'.$data['content'].'%'],
            'sum_settled'=>['like','%'.$data['content'].'%'],
            'sum_date'=>['like','%'.$data['content'].'%'],
            'sum_receivable'=>['like','%'.$data['content'].'%'],
            'sum_free'=>['like','%'.$data['content'].'%'],
        ])
        ->order("sum_date desc")
        ->paginate($data['pagenumber'],false,["query"=>$data]);
        $this -> view -> assign('orderList', $res);
        $this -> view -> assign('count', count($res));
        $this -> view -> assign('pagenumber', $data['pagenumber']);
        //渲染管理员列表模板
        return $this -> view -> fetch('slideshow');
    }
//加晒记录筛选接口
public function tosumSelect(Request $request)
{
    // $sid=Session::get('staff_id');
    // $limit=Db::table('green_administrators')->where('staff_id',$sid)->value("tosum");
    // if ($limit ==0) {
    //     return $this -> view -> fetch('noPower');
    // }
    // $this -> view -> assign('limit', $limit);
    $data = $request -> param();
    $info=[];
    $res=[];
    foreach ($data as $key => $value)
    {
        if($value&&$key!="pagenumber"&&$key!="page"&&$key!="start"&&$key!="end")
        {
            $info[$key]=$value;
        }
    }
    foreach ($data as $key => $value){
        if($data['start']&&$data['end'])
        {
            $count=Db::table('green_tosum')
                ->where($info)
                ->where('sum_date','BETWEEN',[$data['start'],$data['end']])
                ->count();
            if ($data['pagenumber'] == "全部") {
                $data['pagenumber'] = $count;
            }
            $res=Db::table('green_tosum')
                ->where($info)
                ->where('sum_date','BETWEEN',[$data['start'],$data['end']])
                ->order("sum_date desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);
        }
        else
        {
            $count=Db::table('green_tosum')
                ->where($info)
                ->count();
            if ($data['pagenumber'] == "全部") {
                $data['pagenumber'] = $count;
            }
            $res=Db::table('green_tosum')
                ->where($info)
                ->order("sum_date desc")
                ->paginate($data['pagenumber'],false,["query"=>$data]);
        }
    }
    $this -> view -> assign('orderList', $res);
    $this -> view -> assign('count', $count);
    $this -> view -> assign('pagenumber', $data["pagenumber"]);
    //渲染管理员列表模板
    return $this -> view -> fetch('slideshow');
}
    //加晒记录编辑单个个接口
    public function tosum_Edit(Request $request)
    {
        $data=$request->param();
        $res=Db::table('green_tosum')
            ->where('id',$data['id'])
            ->update([
                $data['content']=>$data['project_id'],
            ]);
        if(null!=$res){
            $status=1;
            $message='修改成功!';
        }
        else{
            $status=0;
            $message='修改失败，请检查！';
        }
        return ['status'=>$status,'message'=>$message];
    }


}