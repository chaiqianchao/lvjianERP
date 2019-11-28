<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


// [ 应用入口文件 ]

//定义配置目录
define('CONF_PATH',__DIR__.'/conf/');
// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';
//绑定store模块
    define('BIND_MODULE','store');
    //绑定Index控制器
    define('BIND_CONTROLLER','Index');
//显示错误信息
error_reporting(E_ALL);
ini_set('display_errors','on');
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');