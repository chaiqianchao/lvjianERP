<?php
namespace app\store\controller;

use think\Controller;
use think\Request;
use app\index\model\ArtourAdministrators;
use app\index\model\ArtourGame;
use app\index\model\ArtourUser;
use app\index\model\ArtourRecord;
use app\index\model\ArtourMall;
use app\index\model\ArtourSpots;
use app\index\model\ArtourWzu;
use app\index\model\ArtourAccess;
use app\index\model\ArtourOrder;
use think\Session;
use think\Db;
class Sample extends Controller
{

    protected function _initialize()
    {
        parent::_initialize();
        define('USER_ID', Session::get('user_id'));
    }


    //判断用户是否登陆,放在系统后台入口前面: index/index
    protected function isLogin()
    {
        if (is_null(USER_ID)) {
            $this -> error('用户未登陆,无权访问',url('index/login'));
        }
    }


    //防止用户重复登陆,放在登陆操作前面:index/login
    protected function alreadyLogin(){
        if (USER_ID) {
            $this -> error('用户已经登陆,请勿重复登陆',url('index/index'));
        }
    }



    //主页面
    public function index()
    {
        $this -> isLogin();  //判断用户是否登录
        $this -> view -> assign('title', 'AR智游后台管理系统');
        return $this->view->fetch();
    }

    //登陆界面
    public function login()
    {
        //防止重复登录登录
        $this -> alreadyLogin();
        $this -> view -> assign('title', '用户登录');
        $this -> view -> assign('keywords', 'AR智游');
        $this -> view -> assign('desc', '后台管理');
        $this -> view -> assign('copyRight', '视时空');
        return $this -> view -> fetch();
    }

    //验证登录
    public function checkLogin(Request $request)
    {
        //初始返回参数
        $status = 0; //验证失败标志
        $result = '验证失败'; //失败提示信息
        $data = $request -> param();

        //验证规则
        $rule = [
            'name|姓名' => 'require',
            'password|密码'=>'require',
            'captcha|验证码' => 'require|captcha'
        ];

        //自定义验证失败的提示信息
        $msg=[
            'name'=>['require'=>'用户名不能为空，请检查'],
            'password'=>['require'=>'密码不能为空，请检查'],
            'captcha'=>[
                'require'=>'验证码不能为空，请检查',
                'captcha'=>'验证码错误'
                ],
        ];

        //验证数据 $this->validate($data, $rule, $msg)
        $result = $this -> validate($data, $rule,$msg);

        //通过验证后,进行数据表查询
        //此处必须全等===才可以,因为验证不通过,$result保存错误信息字符串,返回非零
        if (true === $result) {

            $password = md5($data['password']);
            $ret = model('ArtourAdministrators')->get(['administrators_name' => $data['name'], 'administrators_password' => $password]);
            if (!$ret) {
                $result = '没有该用户,请检查';
            } else {
                $res=model('ArtourAdministrators')->get(['administrators_name' => $data['name'], 'administrators_password' => $password,'administrators_status'=>1]);
                if($res){
                    $result = '该用户已被停用';
                }else {
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

        return ['status'=>$status, 'message'=>$result, 'data'=>$data];
    }


    //退出登录
    public function logout()
    {
        //退出前先更新登录时间字段,下次登录时就知道上次登录时间了
        ArtourAdministrators::update(['administrators_lastTime'=>time()],['administrators_id'=> Session::get('user_id')]);
        Session::delete('user_id');
        Session::delete('user_name');
        Session::delete('user_password');
        Session::delete('user_role');
        Session::delete('user_lastTime');

        $this -> success('注销登陆,正在返回',url('index/login'));
    }


    //管理员列表
    public function  adminList()
    {
        $this -> isLogin();  //判断用户是否登录
        $this -> view -> assign('title', '管理员列表');
        $this -> view -> assign('keywords', 'AR智游后台管理系统');
        $this -> view -> assign('desc', '视时空');

        $this -> view -> count = ArtourAdministrators::count();

        //判断当前是不是admin用户
        //先通过session获取到用户登陆名
        $userName = Session::get('user_name');
        if ($userName == 'admin') {
            $list = ArtourAdministrators::paginate(10);  //admin用户可以查看所有记录,数据要经过模型获取器处理
        } else {
            //为了共用列表模板,使用了all(),其实这里用get()符合逻辑,但有时也要变通
            //非admin只能看自己信息,数据要经过模型获取器处理
            $list =Db::table('artour_administrators')->where('administrators_name',$userName)->paginate(10);
        }

        $this -> view -> assign('list', $list);
        //渲染管理员列表模板
        return $this -> view -> fetch('admin_list');
    }


    //管理员状态变更
    public function adminStatus(Request $request)
    {
        $user_id = $request -> param('id');
        $result = ArtourAdministrators::get($user_id);
        if($result->getData('administrators_status') == 1) {
            ArtourAdministrators::update(['administrators_status'=>0],['administrators_id'=>$user_id]);
            ArtourAdministrators::update(['administrators_updateTime'=>time()],['administrators_id'=>$user_id]);
        } else {
            ArtourAdministrators::update(['administrators_status'=>1],['administrators_id'=>$user_id]);
            ArtourAdministrators::update(['administrators_updateTime'=>time()],['administrators_id'=>$user_id]);
        }
    }

    //渲染编辑管理员界面
    public function adminEdit(Request $request)
    {
        $user_id = $request -> param('id');
        $data=['administrators_id'=>$user_id];
        $result = ArtourAdministrators::get($data);
        $this->view->assign('title','编辑管理员信息');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');
        $this->view->assign('user_info',$result->getData());
        return $this->view->fetch('admin_edit');
    }

    //更新数据操作
    public function editAdmin(Request $request)
    {
        //获取数据
        $data = $request -> param();
        //去掉表单中为空的数据,即没有修改的内容
        foreach ($data as $key => $value ){
            if (!empty($value)){
                $data[$key] = $value;
            }
        }

        $ad=new ArtourAdministrators();
        if(Session::get('user_name') == 'admin') {

            $result = $ad
                ->where('administrators_id', $data['id'])
                ->update([
                    'administrators_name' => $data['name'],
                    'administrators_password' => md5($data['password']),
                    'administrators_role' => $data['role'],
                    'administrators_status' => $data['status'],
                    'administrators_updateTime' => time(),
                ]);
        }else{
            $result = $ad
                ->where('administrators_id', $data['id'])
                ->update([
                    'administrators_name' => $data['name'],
                    'administrators_password' => md5($data['password']),
                    'administrators_updateTime' => time(),
                ]);
        }

        //如果是admin用户,更新当前session中用户信息user_role,供页面调用
        if (Session::get('user_name') == 'admin') {
            Session::set('user_role', $data['role']);
        }
        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }


    //删除操作
    public function deleteAdmin(Request $request)
    {
        $data = $request -> param();
        $aa=new ArtourAdministrators();
        $res = $aa->where([
            'administrators_id'=>$data['id'],
        ])->delete();

    }


    //添加操作的界面
    public function  adminAdd()
    {
        $this->view->assign('title','添加管理员');
        $this->view->assign('keywords','php.cn');
        $this->view->assign('desc','PHP中文网ThinkPHP5开发实战课程');
        return $this->view->fetch('admin_add');
    }

    //检测用户名是否可用
    public function checkUserName(Request $request)
    {
        $userName = trim($request -> param('name'));
        $status = 1;
        $message = '用户名可用';
        if (ArtourAdministrators::get(['administrators_name'=> $userName])) {
            //如果在表中查询到该用户名
            $status = 0;
            $message = '用户名重复,请重新输入~~';
        }
        return ['status'=>$status, 'message'=>$message];
    }


    //添加管理员
    public function addAdmin(Request $request)
    {
        $data = $request -> param();
        $status = 1;
        $message = '添加成功';

        $rule = [
            'name|用户名' => "require|min:3|max:10",
            'password|密码' => "require|min:3|max:10",
        ];

        $result = $this -> validate($data, $rule);

        if ($result === true) {
            $ad=new ArtourAdministrators();
            $user=$ad
                ->allowField(true)
                ->save([
                    'administrators_id' => $data['id'],
                    'administrators_name'=>$data['name'],
                    'administrators_password'=>md5($data['password']),
                    'administrators_role'=>$data['role'],
                    'administrators_status'=>$data['status'],
                ]);
            if ($user === null) {
                $status = 0;
                $message = '添加失败~~';
            }
        }
        return ['status'=>$status, 'message'=>$message];
    }




    //游戏列表
    public function  gameList()
    {
        $this -> isLogin();  //判断用户是否登录
        //获取所有游戏表数据
        $game = ArtourGame::paginate(10);
        //获取记录数量
        $count = ArtourGame::count();
        $this -> view -> assign('gameList', $game);
        $this -> view -> assign('count', $count);
        $user = new ArtourGame();
        $data = $user->column('');
        $arr=[];$num=[];
        foreach ($data as $key => $value) {  //把对象数据变为数组
            $arr['num'][] = $value;
        }
        foreach ($arr['num'][0] as $key => $value){
            $num[]=$key;
        }
        $this->view->assign('column', $num);
        
        
        //获取字段备注
        $data = Db::query('SHOW FULL COLUMNS FROM '.'artour_game');
        $note=[];
        $i=0;
        foreach ($data as $key => $value) {  //把对象数据变为数组
            $note[$i++]=$value['Comment'];
        }
//        dump($note);
        $this -> view -> assign('note', $note);
        return $this -> view -> fetch('game_list');
    }

    //游戏状态变更
    public function gameStatus(Request $request)
    {
        //获取当前的游戏ID
        $game_id = $request -> param('id');

        //查询
        $result = ArtourGame::get($game_id);

        //启用和禁用处理
        if($result->getData('game_status') == 1) {
            ArtourGame::update(['game_status'=>0],['game_id'=>$game_id]);
            ArtourGame::update(['game_updateTime'=>time()],['game_id'=>$user_id]);
        } else {
            ArtourGame::update(['game_status'=>1],['game_id'=>$game_id]);
            ArtourGame::update(['game_updateTime'=>time()],['game_id'=>$user_id]);
        }
    }

    //渲染游戏编辑界面
    public function gameEdit(Request $request)
    {
        //获取到要编辑的游戏ID
        $game_id = $request -> param('id');

        //根据ID进行查询
        $result = ArtourGame::get($game_id);

        //给当前页面seo变量赋值
        $this->view->assign('title','编辑游戏');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');

        //给当前编辑模板赋值
        $this->view->assign('game_info',$result);

        //渲染编辑模板
        return $this->view->fetch('game_edit');
    }

    //游戏编辑
    public function editGame(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $ag=new ArtourGame();
        $result = $ag
            ->where('game_id', $data['id'])
            ->update([
                'game_name' => $data['name'],
                'game_rule' => $data['rule'],
                'game_status' => $data['status'],
                'game_updateTime' => time(),
            ]);

        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }

//        $data = $request -> param();
//
//        //设置更新条件
//        $condition = ['game_id'=>$data['id']];
//
//        //更新当前记录
//        $result = ArtourGame::update($data,$condition);
//
//        //设置返回数据
//        $status = 0;
//        $message = '更新失败,请检查';
//
//        //检测更新结果,将结果返回给game_edit模板中的ajax提交回调处理
//        if (true == $result) {
//            $status = 1;
//            $message = '恭喜, 更新成功~~';
//        }
//        return ['status'=>$status, 'message'=>$message];
    }

    //渲染游戏添加界面
    public function gameAdd()
    {
        //给模板赋值seo变量
        $this->view->assign('title','添加游戏');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');

        //渲染添加模板
        return $this->view->fetch('game_add');
    }

    //添加游戏
    public function addGame(Request $request)
    {
        $id = $request -> param('id');
        $name = $request -> param('name');
        $rule = $request -> param('rule');
        $status = $request -> param('status');
        $status1 = 1;
        $message = '添加成功';

            $ag=new ArtourGame();
            $res=$ag
                ->allowField(true)
                ->save([
                    'game_id'=>$id,
                    'game_name'=>$name,
                    'game_rule'=>$rule,
                    'game_status'=>$status,
                ]);
            if ($res === null) {
                $status1 = 0;
                $message = '添加失败~~';
            }

        return ['status'=>$status1, 'message'=>$message];
    }

    //删除游戏操作
    public function deleteGame(Request $request)
    {
        $data = $request -> param();
        $ag=new ArtourGame();
        $res = $ag->where([
            'game_id'=>$data['id'],
        ])->delete();

    }





    //渲染用户界面
    public function userList(Request $request)
    {
        $this -> isLogin();  //判断用户是否登录
        $mobile = $request->param('mobile');
        if($mobile==null){
            //获取所有游戏纪录表数据
            $user =ArtourUser::paginate(10);
            //获取记录数量
            $count = ArtourUser::count();
        }else if($mobile!=null){
            $user =Db::table('artour_user')->where('user_mobile',$mobile)->paginate(10);
            //获取记录数量
            $count = Db::table('artour_user')->where('user_mobile',$mobile)->count();
        }
        $this->view->assign('userList',$user);
        $this->view->assign('count',$count);
        return $this->view->fetch('user_list');
    }

    //渲染编辑用户界面
    public function userEdit(Request $request)
    {
        //获取到要编辑的用户手机号
        $user_mobile = $request -> param('mobile');
        $data=['user_mobile'=>$user_mobile];

        //根据手机号进行查询
        $result = ArtourUser::get($data);

        //给当前页面seo变量赋值
        $this->view->assign('title','编辑用户信息');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');
        //给当前编辑模板赋值
        $this->view->assign('user_info',$result->getData());
        //渲染编辑模板
        return $this->view->fetch('user_edit');
    }

    //编辑数据操作
    public function editUser(Request $request)
    {
        //获取数据
        $data = $request -> param();
        //去掉表单中为空的数据,即没有修改的内容
        foreach ($data as $key => $value ){
            if (!empty($value)){
                $data[$key] = $value;
            }
        }
        $au=new ArtourUser();
        $result = $au
            ->where('user_mobile', $data['mobile'])
            ->update([
                'user_nickName' => $data['nickname'],
                'user_headPortrait' => $data['headPortrait'],
                'user_integral' => $data['integral'],
                'user_status' => $data['status'],
                'user_name' => $data['name'],
                'user_idCard' => $data['idCard'],
                'user_address' => $data['address'],
                'user_addressIndex' => $data['addressIndex'],
                'user_longitude' => $data['longitude'],
                'user_latitude' => $data['latitude'],
            ]);
        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }

    //用户状态变更
    public function userStatus(Request $request)
    {
        $user_id = $request -> param('id');
        $result = ArtourUser::get($user_id);
        if($result->getData('user_status') == 0) {
            ArtourUser::update(['user_status'=>1],['user_mobile'=>$user_id]);
        } else {
            ArtourUser::update(['user_status'=>0],['user_mobile'=>$user_id]);
        }
    }

    //用户删除
    public function deleteUser(Request $request)
    {
        $data = $request -> param();
        $au=new ArtourUser();
        $res = $au->where([
            'user_mobile'=>$data['mobile'],
        ])->delete();
    }

    //添加操作的界面
    public function  userAdd()
    {
        $this->view->assign('title','添加用户');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');
        return $this->view->fetch('user_add');
    }

    //检测手机号是否可用
    public function checkUserMobile(Request $request)
    {
        $userName = trim($request -> param('mobile'));
        $status = 1;
        $message='手机号可用';
        if (ArtourUser::get(['user_mobile'=> $userName])) {
            //如果在表中查询到该用户名
            $status = 0;
            $message = '该手机号已注册,请重新输入~~';
        }
        return ['status'=>$status, 'message'=>$message];

    }
    //添加操作
    public function addUser(Request $request)
    {
        $data = $request -> param();
        $idCard = $request -> param('idCard');
        $status = 1;
        $message = '添加成功';

        $rule = [
            'mobile|用户手机号' => "require|min:11|max:11",
            'password|密码' => "require|min:6|max:20",
        ];
        $result = $this -> validate($data, $rule);
        if ($result === true) {
            $ad=new ArtourUser();
            $user=$ad
                ->allowField(true)
                ->save([
                    'user_mobile'=>$data['mobile'],
                    'user_nickName'=>$data['nickname'],
                    'user_password'=>md5($data['password']),
                    'user_integral'=>$data['integral'],
                    'user_headPortrait'=>$data['headPortrait'],
                    'user_name'=>$data['name'],
                    'user_idCard'=>$idCard,
                    'user_status'=>$data['status'],
                    'user_address' => $data['address'],
                    'user_longitude' => $data['longitude'],
                    'user_latitude' => $data['latitude'],
                ]);
            if ($user === null) {
                $status = 0;
                $message = '添加失败~~';
            }
        }
        return ['status'=>$status, 'message'=>$message];
    }





    //游戏纪录列表
    public function recordList(Request $request)
    {
        $this -> isLogin();  //判断用户是否登录
        $mobile = $request->param('mobile');
        $id= $request->param('id');
        if($mobile==null&$id==null){
            //获取所有游戏纪录表数据
            $record =ArtourRecord::paginate(10);
            //获取记录数量
            $count = ArtourRecord::count();
        }else if($mobile==null&$id!=null){
            $record =Db::table('artour_record')->where('record_id',$id)->paginate(10);
            //获取记录数量
            $count = Db::table('artour_record')->where('record_id',$id)->count();
        }else if($mobile!=null&$id==null){
            $record =Db::table('artour_record')->where('record_mobile',$mobile)->paginate(10);
            //获取记录数量
            $count = Db::table('artour_record')->where('record_mobile',$mobile)->count();
        }else{
            $record =Db::table('artour_record')->where(['record_id'=>$id,'record_mobile'=>$mobile])->paginate(10);
            //获取记录数量
            $count =Db::table('artour_record')->where(['record_id'=>$id,'record_mobile'=>$mobile])->count();
        }

        $this -> view -> assign('recordList', $record);
        $this -> view -> assign('count', $count);
        return $this -> view -> fetch('record_list');
    }

    //渲染游戏编辑界面
    public function recordEdit(Request $request)
    {
        //获取到要编辑的游戏ID和手机号
        $record_id = $request -> param('id');
        $record_mobile = $request -> param('mobile');
        $data=['record_mobile'=>$record_mobile,'record_id'=>$record_id];

        //根据ID和手机号进行查询
        $result = ArtourRecord::get($data);

        //给当前页面seo变量赋值
        $this->view->assign('title','编辑游戏纪录');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');

        //给当前编辑模板赋值
        $this->view->assign('record_info',$result);

        //渲染编辑模板
        return $this->view->fetch('record_edit');
    }

    //游戏纪录编辑
    public function editRecord(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $ar=new ArtourRecord();
        $result = $ar
            ->where([
                'record_mobile'=>$data['mobile'],
                'record_id'=>$data['id'],
            ])
            ->update([
                'record_top'=>$data['top'],
                'record_update'=>time(),
            ]);

        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }

    }

    //删除操作
    public function deleteRecord(Request $request)
    {
        $data = $request -> param();
        $ar=new ArtourRecord();
        $res = $ar->where([
            'record_mobile'=>$data['mobile'],
            'record_id'=>$data['id'],
        ])->delete();
    }

    //渲染游戏纪录添加界面
    public function recordAdd()
    {
        //给模板赋值seo变量
        $this->view->assign('title','添加游戏纪录');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');

        //渲染添加模板
        return $this->view->fetch('record_add');
    }

    //添加游戏纪录
    public function addRecord(Request $request)
    {
        $data = $request -> param();
        $status = 1;
        $message = '添加成功';

        $ar=new ArtourRecord();
        $res=$ar
            ->allowField(true)
            ->save([
                'record_mobile'=>$data['mobile'],
                'record_id'=>$data['id'],
                'record_top'=>$data['top'],
            ]);
        if ($res === null) {
            $status = 0;
            $message = '添加失败~~';
        }

        return ['status'=>$status, 'message'=>$message];
    }




    //渲染商城界面
    public function mallList()
    {
        $this -> isLogin();  //判断用户是否登录
        $mall=ArtourMall::paginate(10);
        $count=ArtourMall::count();
        $this->view->assign('mallList',$mall);
        $this->view->assign('count',$count);
        return $this->view->fetch('mall_list');
    }

    //渲染编辑商品界面
    public function mallEdit(Request $request)
    {
        $mall_id = $request -> param('id');
        $data=['mall_id'=>$mall_id];

        //根据ID进行查询
        $result = ArtourMall::get($data);

        //给当前页面seo变量赋值
        $this->view->assign('title','编辑商品信息');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');
        //给当前编辑模板赋值
        $this->view->assign('mall_info',$result->getData());
        //渲染编辑模板
        return $this->view->fetch('mall_edit');
    }

    //更新数据操作
    public function editMall(Request $request)
    {
        //获取数据
        $data = $request -> param();
        //去掉表单中为空的数据,即没有修改的内容
        foreach ($data as $key => $value ){
            if (!empty($value)){
                $data[$key] = $value;
            }
        }
        $am=new ArtourMall();
        $result = $am
            ->where('mall_id', $data['id'])
            ->update([
                'mall_name'=>$data['name'],
                'mall_picture'=>$data['picture'],
                'mall_word'=>$data['word'],
                'mall_residualQuantity'=>$data['residualQuantity'],
                'mall_necessaryIntegral'=>$data['necessaryIntegral'],
                'mall_necessaryMoney'=>$data['necessaryMoney'],
                'mall_type'=>$data['type'],
            ]);
        if (true==$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }

    //删除商品
    public function deleteMall(Request $request)
    {
        $data = $request -> param();
        $am=new ArtourMall();
        $res = $am->where([
            'mall_id'=>$data['id'],
        ])->delete();
    }


    //添加操作的界面
    public function  mallAdd()
    {
        $this->view->assign('title','添加新商品');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');
        return $this->view->fetch('mall_add');
    }

    //检测商品是否可用
    public function checkMallId(Request $request)
    {
        $mallId= trim($request -> param('id'));
        $status = 1;
        $message='商品id可用';
        if (ArtourMall::get(['mall_id'=> $mallId])) {
            //如果在表中查询到该id
            $status = 0;
            $message = '该商品已存在,请重新输入~~';
        }
        return ['status'=>$status, 'message'=>$message];
    }

    //添加操作
    public function addMall(Request $request)
    {
        $data = $request -> param();
        $status = 1;
        $message = '添加成功';
        $am=new ArtourMall();
        $mall=$am
            ->allowField(true)
            ->save([
                'mall_id'=>$data['id'],
                'mall_name'=>$data['name'],
                'mall_picture'=>$data['picture'],
                'mall_word'=>$data['word'],
                'mall_residualQuantity'=>$data['residualQuantity'],
                'mall_necessaryIntegral'=>$data['necessaryIntegral'],
                'mall_necessaryMoney'=>$data['necessaryMoney'],
                'mall_type'=>$data['type'],
            ]);
        if ($mall === null) {
            $status = 0;
            $message = '添加失败~~';
        }
        return ['status'=>$status, 'message'=>$message];
    }




    //渲染景点表界面
    public function spotsList(Request $request)
    {
        $this -> isLogin();  //判断用户是否登录
        $name = $request->param('name');
        if($name==null){
            //获取所有游戏纪录表数据
            $spots =ArtourSpots::paginate(10);
            //获取记录数量
            $count = ArtourSpots::count();
        }else if($name!=null){
            $spots =Db::table('artour_spots')->where('spots_name',$name)->paginate(10);
            //获取记录数量
            $count = Db::table('artour_spots')->where('spots_name',$name)->count();
        }
        $this->view->assign('spotsList',$spots);
        $this->view->assign('count',$count);
        return $this->view->fetch('spots_list');
    }

    //渲染编辑景点界面
    public function spotsEdit(Request $request)
    {
        //获取到要编辑的编号
        $spots_number = $request -> param('number');
        $data=['spots_number'=>$spots_number];

        //根据ID和手机号进行查询
        $result = ArtourSpots::get($data);

        //给当前页面seo变量赋值
        $this->view->assign('title','编辑景点信息');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');
        //给当前编辑模板赋值
        $this->view->assign('spots_info',$result->getData());
        //渲染编辑模板
        return $this->view->fetch('spots_edit');
    }

    //更新数据操作
    public function editSpots(Request $request)
    {
        //获取数据
        $data = $request -> param();
        //去掉表单中为空的数据,即没有修改的内容
        foreach ($data as $key => $value ){
            if (!empty($value)){
                $data[$key] = $value;
            }
        }
        $as=new ArtourSpots();
        $result = $as
            ->where('spots_number', $data['number'])
            ->update([
                'spots_name'=>$data['name'],
                'spots_type'=>$data['type'],
                'spots_grade'=>$data['grade'],
                'spots_province'=>$data['province'],
                'spots_city'=>$data['city'],
                'spots_county'=>$data['county'],
                'spots_address'=>$data['address'],
                'spots_charge'=>$data['charge'],
                'spots_picture'=>$data['picture'],
                'spots_word'=>$data['word'],
                'spots_longitude'=>$data['longitude'],
                'spots_latitude'=>$data['latitude'],
                'spots_form'=>$data['form'],
                'spots_androidUrl'=>$data['androidUrl'],
                'spots_iosUrl'=>$data['iosUrl'],
            ]);
        if (true==$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }

    //删除景点
    public function deleteSpots(Request $request)
    {
        $data = $request -> param();
        $as=new ArtourSpots();
        $res = $as->where([
            'spots_number'=>$data['number'],
        ])->delete();
    }


    //添加操作的界面
    public function  spotsAdd()
    {
        $this->view->assign('title','添加新景点');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');
        return $this->view->fetch('spots_add');
    }

//    //检测用户名是否可用
//    public function checkSpots(Request $request)
//    {
//        $spots = trim($request -> param('number'));
//        $status = 1;
//        $message = '用户名可用';
//        if (ArtourAdministrators::get(['spots_number'=> $spots])) {
//            //如果在表中查询到该用户名
//            $status = 0;
//            $message = '景点id重复,请重新输入~~';
//        }
//        return ['status'=>$status, 'message'=>$message];
//
//    }

    //添加操作
    public function addSpots(Request $request)
    {
        $data = $request -> param();
        $status = 1;
        $message = '添加成功';
        $as=new ArtourSpots();
        $spots=$as
            ->allowField(true)
            ->save([
                'spots_number'=>$data['number'],
                'spots_name'=>$data['name'],
                'spots_type'=>$data['type'],
                'spots_grade'=>$data['grade'],
                'spots_province'=>$data['province'],
                'spots_city'=>$data['city'],
                'spots_county'=>$data['county'],
                'spots_address'=>$data['address'],
                'spots_charge'=>$data['charge'],
                'spots_picture'=>$data['picture'],
                'spots_word'=>$data['word'],
                'spots_longitude'=>$data['longitude'],
                'spots_latitude'=>$data['latitude'],
                'spots_form'=>$data['form'],
                'spots_androidUrl'=>$data['androidUrl'],
                'spots_iosUrl'=>$data['iosUrl'],
            ]);
        if ($spots === null) {
            $status = 0;
            $message = '添加失败~~';
        }
        return ['status'=>$status, 'message'=>$message];
    }




    //渲染温大景点表界面
    public function wzuList(Request $request)
    {
        $this -> isLogin();  //判断用户是否登录
        $name = $request->param('name');
        if($name==null){
            //获取所有温大表数据
            $wzu =ArtourWzu::paginate(10);
            //获取记录数量
            $count = ArtourWzu::count();
        }else if($name!=null){
            $wzu =Db::table('artour_wzu')->where('name',$name)->paginate(10);
            //获取记录数量
            $count = Db::table('artour_wzu')->where('name',$name)->count();
        }
        $this->view->assign('wzuList',$wzu);
        $this->view->assign('count',$count);
        return $this->view->fetch('wzu_list');
    }

    //渲染编辑管理员界面
    public function wzuEdit(Request $request)
    {
        //获取到要编辑的景点名
        $wzu_name = $request -> param('name');
        $data=['name'=>$wzu_name];

        //根据name和手机号进行查询
        $result = ArtourWzu::get($data);

        //给当前页面seo变量赋值
        $this->view->assign('title','编辑温大景点信息');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');
        //给当前编辑模板赋值
        $this->view->assign('wzu_info',$result->getData());
        //渲染编辑模板
        return $this->view->fetch('wzu_edit');
    }

    //更新数据操作
    public function editWzu(Request $request)
    {
        //获取数据
        $data = $request -> param();
        //去掉表单中为空的数据,即没有修改的内容
        foreach ($data as $key => $value ){
            if (!empty($value)){
                $data[$key] = $value;
            }
        }
        $aw=new ArtourWzu();
        $result = $aw
            ->where('name', $data['name'])
            ->update([
                'number'=>$data['number'],
                'picture'=>$data['picture'],
                'word'=>$data['word'],
                'navigation'=>$data['navigation'],
                'task'=>$data['task'],
                'longitude'=>$data['longitude'],
                'latitude'=>$data['latitude'],
            ]);
        if (true==$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }

    //删除温大景点
    public function deleteWzu(Request $request)
    {
        $name=$request->param('name');
        $au=new ArtourWzu();
        $res=$au
            ->where('name',$name)
            ->delete();
        if ($res===true) {
            return ['status'=>1, 'message'=>'删除成功'];
        } else {
            return ['status'=>0, 'message'=>'删除失败,请重试'];
        }
    }

    //添加操作的界面
    public function  wzuAdd()
    {
        $this->view->assign('title','添加新温大景点');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');
        return $this->view->fetch('wzu_add');
    }

    //检测景点名是否可用
    public function checkWzuName(Request $request)
    {
        $wzuName= trim($request -> param('name'));
        $status = 1;
        $message='温大景点name可用';
        if (ArtourWzu::get(['name'=> $wzuName])) {
            //如果在表中查询到该用户名
            $status = 0;
            $message = '该温大景点已存在,请重新输入~~';
        }
        return ['status'=>$status, 'message'=>$message];
    }

    //添加操作
    public function addWzu(Request $request)
    {
        $data = $request -> param();
        $status = 1;
        $message = '添加成功';
        $aw=new ArtourWzu();
        $wzu=$aw
            ->allowField(true)
            ->save([
                'name'=>$data['name'],
                'number'=>$data['number'],
                'picture'=>$data['picture'],
                'word'=>$data['word'],
                'navigation'=>$data['navigation'],
                'task'=>$data['task'],
                'longitude'=>$data['longitude'],
                'latitude'=>$data['latitude'],
            ]);
        if ($wzu === null) {
            $status = 0;
            $message = '添加失败~~';
        }
        return ['status'=>$status, 'message'=>$message];
    }




    //渲染订单表界面
    public function orderList(Request $request)
    {
        $this -> isLogin();  //判断用户是否登录
        $mobile = $request->param('mobile');
        $id = $request->param('id');
        if($mobile==null&$id==null){
            //获取所有游戏纪录表数据
            $record =ArtourOrder::paginate(10);
            //获取记录数量
            $count = ArtourOrder::count();
        }else if($mobile==null&$id!=null){
            $record =Db::table('artour_order')->where('order_id',$id)->paginate(10);
            //获取记录数量
            $count = Db::table('artour_order')->where('order_id',$id)->count();
        }else if($mobile!=null&$id==null){
            $record =Db::table('artour_order')->where('order_mobile',$mobile)->paginate(10);
            //获取记录数量
            $count = Db::table('artour_order')->where('order_mobile',$mobile)->count();
        }else{
            $record =Db::table('artour_order')->where(['order_id'=>$id,'order_mobile'=>$mobile])->paginate(10);
            //获取记录数量
            $count =Db::table('artour_order')->where(['order_id'=>$id,'order_mobile'=>$mobile])->count();
        }

        $this -> view -> assign('orderList', $record);
        $this -> view -> assign('count', $count);
        return $this -> view -> fetch('order_list');
    }

    //渲染编辑管理员界面
    public function orderEdit(Request $request)
    {
        //获取到要编辑的用户手机号
//        $record_id = $request -> param('id');
        $order_number = $request -> param('number');
        $data=['order_number'=>$order_number];

        //根据ID和手机号进行查询
        $result = ArtourOrder::get($data);

        //给当前页面seo变量赋值
        $this->view->assign('title','编辑订单信息');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');
        //给当前编辑模板赋值
        $this->view->assign('order_info',$result->getData());
        //渲染编辑模板
        return $this->view->fetch('order_edit');
    }

    //更新数据操作
    public function editOrder(Request $request)
    {
        //获取数据
        $data = $request -> param();
        //去掉表单中为空的数据,即没有修改的内容
        foreach ($data as $key => $value ){
            if (!empty($value)){
                $data[$key] = $value;
            }
        }
        $au=new ArtourOrder();
        $result = $au
            ->where('order_number', $data['number'])
            ->update([
                'order_id'=>$data['id'],
                'order_mobile'=>$data['mobile'],
                'order_money'=>$data['money'],
                'order_integral'=>$data['integral'],
                'order_cnee'=>$data['cnee'],
                'order_cneeMobile'=>$data['cneeMobile'],
                'order_address'=>$data['address'],
//                'order_date'=>$data['date'],
                'order_status'=>$data['status'],
            ]);
        if (true==$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
    }

    //删除
    public function deleteOrder(Request $request)
    {
        $data = $request -> param();
        $ao=new ArtourOrder();
        $res = $ao->where([
            'order_number'=>$data['number'],
        ])->delete();
    }


    //添加操作的界面
    public function  orderAdd()
    {
        $this->view->assign('title','添加新订单');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');
        return $this->view->fetch('order_add');
    }

    //添加操作
    public function addOrder(Request $request)
    {
        $data = $request -> param();
        $m=substr($data['mobile'],-4);
        $status = 1;
        $message = '添加成功';
        $am=new ArtourOrder();
        $order=$am
            ->allowField(true)
            ->save([
                'order_number'=>time().$m.rand(1000,9999),
                'order_id'=>$data['id'],
                'order_mobile'=>$data['mobile'],
                'order_cnee'=>$data['cnee'],
                'order_cneeMobile'=>$data['cneeMobile'],
                'order_address'=>$data['address'],
                'order_date'=>time(),
                'order_status'=>$data['status'],
            ]);
        if ($order === null) {
            $status = 0;
            $message = '添加失败~~';
        }
        return ['status'=>$status, 'message'=>$message];
    }




    public function charts1()
    {
        $this -> isLogin();  //判断用户是否登录
        return $this->view->fetch('charts_1');
    }
    public function charts2()
    {
        $this -> isLogin();  //判断用户是否登录
        return $this->view->fetch('charts_2');
    }
    public function charts3()
    {
        $this -> isLogin();  //判断用户是否登录
        return $this->view->fetch('charts_3');
    }

    public function getAccess()
    {
        $user = new ArtourAccess();
        $data = $user->column('access_number,access_date');
        //从数据库中获得数据
        $arr=[];
        foreach ($data as $key => $value) {  //把对象数据变为数组
//            array_push($arr,$value);
            $arr['date'][] = $key;
            $arr['num'][] = $value;
        }
        $this->assign("data", $arr); //把数据传到页面
        return json($arr);
    }

    public function getPosition()
    {
        $user = new ArtourSpots();
        $data = $user->column('spots_name,spots_longitude,spots_latitude');
        //从数据库中获得数据
//        dump($data);
        $map=[];
        foreach ($data as $key => $value) {  //把对象数据变为数组
            $map[$value['spots_name']]=[$value['spots_longitude'],$value['spots_latitude']];
        }
        $this->assign("data1", $map); //把数据传到页面
        return json($map);
    }

    public function getGrade()
    {
        $user = new ArtourSpots();
        $data2=$user->column('spots_name,spots_grade');
        $arr=[];
        $grade=[];
        foreach ($data2 as $key => $value) {  //把对象数据变为数组
            $arr['name']=$key;
            $arr['value']=$value;
            $grade[]=$arr;
        }
        $this->assign("data2", $grade);
        return json($grade);
    }

    public function getPosition2()
    {
        $user = new ArtourUser();
        $data = $user->column('user_mobile,user_longitude,user_latitude');
        //从数据库中获得数据
//        dump($data);
        $map=[];
        foreach ($data as $key => $value) {  //把对象数据变为数组
            $map[$value['user_mobile']]=[$value['user_longitude'],$value['user_latitude']];
        }
        $this->assign("data1", $map); //把数据传到页面
        return json($map);
    }

    //获得头像
    public function getHead()
    {
        $user = new ArtourUser();
        $data2=$user->column('user_mobile,user_headPortrait');
        $arr=[];
        $head=[];
        foreach ($data2 as $key => $value) {  //把对象数据变为数组
            $arr['name']=$key;
            $arr['value']=$value;
            $head[]=$arr;
        }
        $this->assign("data2", $head);
        return json($head);
    }
    

    //渲染编辑界面
    public function edit1(Request $request)
    {
        //获取到要编辑主键和字段
        $game_id = $request -> param('id');
        $edit = $request -> param('edit');
        $data=['game_id'=>$game_id];

        //根据ID和手机号进行查询
        $result = ArtourGame::get($data);
        $res=Db::table('artour_game')->where(['game_id'=>$game_id])->value($edit);

        //给当前页面seo变量赋值
        $this->view->assign('title','编辑');
        $this->view->assign('keywords','');
        $this->view->assign('desc','');

        //给当前编辑模板赋值
        $this->view->assign('info',$result);
        $this->view->assign('res',$res);
        $this->view->assign('edit',$edit);

        //渲染编辑模板
        return $this->view->fetch('edit');
    }

    //编辑
    public function edit2(Request $request)
    {
        //获取数据
        $data = $request -> param();
        $ar=new ArtourGame();
        $result = $ar
            ->where([
                'game_id'=>$data['id'],
            ])
            ->update([
                $data['ed']=>$data['edit'],
            ]);

        if (null!=$result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }

    }
}
