<?php
/*
 *
 *	设置文件
 *
 *	2011-8-31 @ jiuwap.cn
 *
 */


/* ********************************************************* */
//        功能开关   true开启     false关闭

#网盘
$b_set['switch']['disk'] = true;

#中转下载
$b_set['switch']['agantdown'] = true;

#上传功能
$b_set['switch']['upload'] = true;

#快捷登陆
$b_set['switch']['quicklogin'] = true;

#同步功能
$b_set['switch']['synch'] = true;

#自定义DNS功能
$b_set['switch']['dns'] = true;

#COOKIES管理功能
$b_set['switch']['cookiemanage'] = true;

#HTTP代理功能
$b_set['switch']['httpagent'] = true;

#捕获并模拟QQ浏览器
$b_set['switch']['qqua'] = true;

//MYSQL服务地址
$b_set['db']['server'] = 'localhost';

//用户名
$b_set['db']['user'] = 'jysafec1_llq';

//密码
$b_set['db']['pass'] = 'jtGaSQ_eu2oe';

//数据库名
$b_set['db']['table'] = 'jysafec1_sql';

#浏览器名字
$b_set['webtitle'] = '祭夜浏览器';

#网盘名字
$b_set['disktitle'] = '祭夜网盘';

#备案号
$b_set['icp'] = '';

#中转上传最大文件大小,单位B
$b_set['tupload'] = '10485760';

#中转下载最大文件大小,单位B
$b_set['tdown'] = '10485760';

#邮件发送最大文件大小,单位B
$b_set['tmail'] = '10485760';

#网盘本地上传最大文件大小,单位B
$b_set['dlocal'] = '10485760';

#网盘远程上传最大文件大小,单位B
$b_set['dhttp'] = '10485760';

#网盘中转上传最大文件大小,单位B
$b_set['thttp'] = '10485760';

#网盘初始大小,单位B
$b_set['dinit'] = '52428800';

#密钥
$b_set['key1'] = 'RGRm6md';
$b_set['key2'] = 'r8c7QcP';
$b_set['key3'] = 'clEZuuf';
$b_set['key4'] = 'rPQZ8gBYDMUxnfAi=hjKa0qo5Tc/JVI+Nu26m3pzFekXs4LvOGStW1RC9Hb7dyElw';

//上述4个目录和4个密钥请严格保密,否则可能威胁服务器安全！！

#压流页面标题前缀提示,类似“[压流]手机腾讯网”
$b_set['title_str'] = '[压流]'; //可以为空

//邮箱SMTP
$b_set['mail']['smtp'] = 'yl.jysafe.cn';

//邮箱SMTP端口
$b_set['mail']['port'] = '587';

//用户名
$b_set['mail']['user'] = 'me@yl.jysafe.cn';

//密码
$b_set['mail']['pass'] = 'CQC.cqc.0130';

//发信人地址
$b_set['mail']['from'] = 'me@yl.jysafe.cn';

//超级密码
$b_set['rootpassword'] = 'jysafe';

//服务器类型
$b_set['server_method'] = '';//目前可以为 空白、ace、sae


$b_set['server_sae_storage'] = 'jiuwap';//仅当服务类型为sae时有效

$b_set['server_php_mamcache_server'] = '';//仅当服务类型为php时有效，若为空则不用memcache服务

//新浪
$b_set['quicklogin']['sina']['akey'] = '';
$b_set['quicklogin']['sina']['skey'] = '';


//QQ
$b_set['quicklogin']['qq']['appid'] = '101546697';
$b_set['quicklogin']['qq']['appkey'] = '70de6dec1a9a821376bde44c36d3957a';
