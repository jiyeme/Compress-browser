[html=用户注册]
<?php
$u=$_GET['u'];
if(!$_POST['go'])
{ ?>
[form=post,read.php?id=reg&amp;do=do&amp;u=<?php echo urlencode($u); ?>]
用户名(可以是中文、字母或数字):[br][input=name][/input][br]
密码:[br][input=pass][/input][br]
联系方式(QQ、E-mail等):[br][input=lianx][/input][br]
个性签名(100字以内):[text=qianm][/text][br]
[submit=go]我要注册[/submit]
<?php
}
else{
include 'db/db.php';
include 'db/u2gb.php';
include 'user/login.php';
$p=&$_POST;
if(!preg_match('/[ex][\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u',$p[name]))
{echo '用户名含有系统不支持的字符或为空！[/html]';return true;}
login($name,$pass,$uid,$sid,$err);
if($err!=2) {echo '用户已存在！[/html]';return true;}
$db=new dbclass('db/user.db3');
$sql=u2gb("insert into user(name,pass,qianm,lianx,sid,mtime) values('".yhtp($p[name])."','".md5($p[pass])."','".yhtp($p[qianm])."','".yhtp($p[lianx])."','".setsid($p[name],$p[pass],$db)."',".(3600*24*30).")");
if($db->query($sql))
{login($a,$b,$c,$d,$e);
echo "注册成功！[br]<a href=\"$u\">返回来源页</a>";}
else echo "注册失败！";
}
echo '[br][url=index.php]首页[/url]-[url='.$u.']返回[/url]';
?>
[hr][time][br][ad]
[/html]