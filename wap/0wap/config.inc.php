<?php
/*php运行时配置*/
error_reporting(E_ALL &~ E_NOTICE &~ E_WARNING); #错误提示等级，E_ALL全部开启、0关闭
ini_set('display_errors',1); #在页面上显示错误，1开启、0关闭
ini_set('memory_limit','64M'); #设置程序最大内存占用
date_default_timezone_set('PRC'); #默认时区设置，PRC是中华人民共和国
set_time_limit(60); #设置脚本超时时间是60秒
ignore_user_abort(1); #用户断开连接后脚本不自动停止
define('STRIP_QUOTES_GPC',1); #使用过程中如果发现任何引号异常情况（包括引号被自动加上了反斜线 \" \' ，或者单引号自动变成两个单引号 '' ，请把它设为1）
/*默认显示设置*/
define('DEFAULT_PAGE_MIME','html/text'); #默认网页mime（不要修改）
define('DEFAULT_PAGE_UBB','xhtml'); #默认页面类型（xhtml或wml）
define('DEFAULT_PAGE_CHARSET','utf-8'); #默认页面编码，不要改
define('DEFAULT_PAGE_NAME','index'); #默认首页文件名
define('PAGE_GZIP',9); #网页gzip级别，1~9，越大压缩比越高，但服务器压力也越大。0为关闭
define('DEFAULT_LOGIN_TIMEOUT',3600*24*90); #默认登陆超时时间：三个月
/*数据库设置*/define('DB_TYPE','sqlite'); #数据库类型。更换数据库的话可能还要修改class/db.class.php类
define('DB_ADDR','.db3'); #数据库文件扩展名，修改它还要同时修改db目录的数据库文件扩展名。
define('DB_A',''); #数据库表名前缀，方便转数据库到Mysql时使用（自己在Mysql建表。在有的脚本里面SQL语句可能漏写了DB_A，需要手动修改。另外，你需要在Mysql建一个id integer primary autoincrement列）
/*程序目录的绝对路径，可以修改，但必须同步移动相关文件夹的位置*/
#本程序所在目录的绝对路径
define('ROOT_DIR',dirname(__FILE__));
#类文件夹的绝对路径
define('CLASS_DIR',ROOT_DIR.'/class');
#函数文件夹的绝对路径
define('FUNC_DIR',ROOT_DIR.'/func');
#页面文件目录绝对路径
define('PAGE_DIR',ROOT_DIR.'/page');
#数据库目录
define('DB_DIR',ROOT_DIR.'/../db');
#临时文件目录
define('TEMP_DIR',ROOT_DIR.'/temp');
#页面缓存文件绝对路径
define('PAGECACHE_DIR',TEMP_DIR.'/pagecache');
#用户文件存放目录绝对路径
define('USERFILE_DIR',ROOT_DIR.'/userfile');
#用户文件目录相对路径
define('USERFILE_RDIR','userfile');
#ubb规则存放目录
define('UBB_DIR',CLASS_DIR.'/ubb');
#过程文件存放目录
define('SUB_DIR',ROOT_DIR.'/sub');
/*引入自动加载类的函数，无需修改*/
include FUNC_DIR.'/autoload.func.php';
#注册自动加载类的函数，可以注册多个#
spl_autoload_register('autoload_file');
spl_autoload_register('autoload_libfile');
/**处理GET、POST、COOKIE被加上的反斜线**/
if(STRIP_QUOTES_GPC)
 include SUB_DIR.'/strip_quotes_gpc.sub.php';
?>