# lvjianERP
ERP系统
```
安装流程：
```
1.拉取所有代码到服务器内
2.导入数据库，数据库为greenbuild.sql文件
3.配置相关数据(服务器地址、关联数据库)
	/greenbuild/conf/config.php app_host修改为你的服务器ip地址或域名，或localhost
	/greenbuild/conf/database.php database 修改为数据库名，username 修改为你的数据库用户名，password 修改为你的数据库密码
4.可直接运行， app_host、greenbuild/index/login.html 进入页面
5.admin账号密码：admin 密码：123456

```
目录列表：
```
1.工程概况
	--查看工程
2.出图登记
	--出图登记
	--加晒记录
3.产值管理
	--工程产值
	--院产值
	--个人产值
4.合同管理
	--查看合同
	--台账管理
5.招投标管理
	--招投标项目
	--开票记录
6.人事管理
	--管理员列表
	--员工管理
7.客户信息
	--客户总览
8.单价管理
	--图文制作费
	--建筑专业设计单价
	--结构专业设计单价
	--设备专业设计单价