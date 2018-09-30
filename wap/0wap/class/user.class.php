<?php
/*user类，与用户登陆相关*/
class user
{
static $db;
static function 用户名合法吗($str)
{
return str::全是中文吗($str,'a-zA-Z0-9_\\-');
}
static function conn(){
if(!self::$db)
  self::$db=db::conn('user');
return self::$db;
}
static function setsid($uid,$name,$pass,$tm)
{
self::conn();
do{
$sid=str_shuffle(url::b64e(md5(md5($name,true).md5(microtime(),true).md5($pass,true),true))).url::b64e(pack('V',$uid));
}while(!self::$db->exec("update user set sid='$sid', mtime=".($tm+DEFAULT_LOGIN_TIMEOUT)." where uid=$uid"));
return $sid;
}
static function login(&$ipname=0,&$ippass=0,&$ipuid=0,&$ipsid=0,&$iperror=0){
self::conn();
if(!$ipname)
 {
$name=$_REQUEST['name'];
if($ipname!==0)
  $ipname=$name;
 }
else $name=$ipname;
if(!$ippass)
 { $pass=$_REQUEST['pass'];
if($ippass!==0)
  $ippass=$pass;
 }
else $pass=$ippass;
if($name==='' or $pass==='')

 {
if($iperror!==0)
  $iperror=new error(4,'用户名或密码为空');
return false;
 }
$info=self::$db->prepare('select uid,pass,sid,mtime from user where name=? limit 1');
$info->execute(array($name));
if(!$info=$info->fetch(db::ass))
 {
if($iperror!==0)
  $iperror=new error(2,'用户不存在');
return false;
 }
$pass2=$info['pass'];
$mtime=$info['mtime'];
$sid=$info['sid'];
$uid=$info['uid'];
if($pass2!=md5($pass))
 {
if($iperror!==0)
 $iperror=new error(3,'密码错误');
return false;
 }
$tm=time();
if($mtime<$tm)
{
$sid=self::setsid($uid,$name,$pass,$tm);
}
setcookie('sid',$sid,$tm+DEFAULT_LOGIN_TIMEOUT);
if($iperror!==0)
 $iperror=new error(1,'登陆成功');
if($ipuid!==0)
 $ipuid=$uid;
if($ipsid!==0)
 $ipsid=$sid;
return true;
}
static function islogin(&$ipname=0,&$ippass=0,&$ipuid=0,&$ipsid=0,&$iperror=0,&$ipuserinfo=true)
{
global $PAGE;
self::conn();
if($ipsid)
 $sid=$ipsid;
elseif(!$sid=$PAGE['sid'])
{
if($iperror!==0)
 $iperror=new error(4,'用户身份信息为空');
return false;
}
elseif($ipsid!==0)
 $ipsid=$sid;
$info=self::$db->prepare('select uid,name,pass,mtime,lianx,qianm,setinfo,extra from user where sid=? limit 1');
$info->execute(array($sid));
if(!$info=$info->fetch(db::ass))
 {
if($iperror!==0)
 $iperror=new error(2,'用户身份信息错误');
return false;
 }
#$info=$info->fetch(db::ass);
if($ipuserinfo!==true)
{
foreach($info as $a=>$b) 
$ipuserinfo[$a]=$b;
}
if($ipname!==0)
 $ipname=$info['name'];
$mtime=$info['mtime'];
if($ipuid!==0)
 $ipuid=$info['uid'];
$tm=time();
if($mtime<$tm)
 {
if($iperror!==0)

 $iperror=new error(5,'用户身份过期');
return false;
 }
if($ippass!==0)
 $ippass=$info['pass'];
if($iperror!==0)
 $iperror=new error(1,'用户已登陆');
return true;
}
//根据uid取得用户信息
static function getinfobyuid($uid)
{
static $rs;
if(!$rs)
 {
self::conn();
$rs=self::$db->prepare('select * from '.DB_A.'user where uid=? limit 1');
if(!$rs)
 return false;
 }
$rs->execute(array($uid));
return $rs->fetch(db::ass);
}
//根据name取得用户信息
static function getinfobyname($name)
{
static $rs;
if(!$rs)
 {
self::conn();
$rs=self::$db->prepare('select * from '.DB_A.'user where name=? limit 1');
if(!$rs)
 return false;
 }
$rs->execute(array($name));
return $rs->fetch(db::ass);
}
#user类结束#
}
?>
