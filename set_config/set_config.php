<?php
/*
 *
 *	设置文件
 *
 *	2011-8-31 @ jiuwap.cn
 *
 */

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

#网盘永久存储目录(如果修改,请注意转移用户文件),末尾必须加/
$b_set['dfforever'] = DIR.'temp/disk_forever_hhbm256/';

#网盘临时存储目录(您可以随时删除这些文件),末尾必须加/
$b_set['dftemp'] = DIR.'temp/disk_temp_hhbm256/';

#临时中转下载目录(文件),可随时删除,末尾必须加/
$b_set['rfile'] = DIR.'temp/down_file_hhbm256/';

#临时中转下载目录(文件),可随时删除,末尾必须加/
$b_set['rini'] = DIR.'temp/down_ini_hhbm256/';

#用户缓存目录,末尾必须加/
$b_set['utemp'] = DIR.'temp/cache_hhbm256/';

#密钥
$b_set['key1'] = 'Pr4eIJB';
$b_set['key2'] = 'cckulXl';
$b_set['key3'] = 'rWRSaIQ';
$b_set['key4'] = 'zZeFKi6nPORs5ybYv3mp9SdxCH=TGlowMgILBDk7Jq/tr81NW20ja4fcU+hVAEuXQ';

//上述4个目录和4个密钥请严格保密,否则可能威胁服务器安全！！

#压流页面标题前缀提示,类似“[压流]手机腾讯网”
$b_set['title_str'] = '[压流]'; //可以为空


//邮箱SMTP
$b_set['mail']['smtp'] = 'yl.jysafe.cn';

//用户名
$b_set['mail']['user'] = 'me@yl.jysafe.cn';

//密码
$b_set['mail']['pass'] = 'CQC.cqc.0130';

//发信人地址
$b_set['mail']['from'] = 'me@yl.jysafe.cn';

//超级密码
$b_set['rootpassword'] = 'jysafe';

/*
安装于2018-10-5 16:12:21
*/