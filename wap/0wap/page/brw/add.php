[html=网页中转-添加]
[head]
<?php
if(!$USER['islogin'])
 headecho::gotologin('',true);
$db=session::conn();
$zu=floor($_POST['zu']);
$url=$_POST['url'];
if($zu<1 or $zu>5)
 die('你得输入正确的组哦，从1到5。[/html]');
/*if(session::zucount(0,'brw.'.$zu)>=5)
 die('该组人满了。[/html]');*/
$uid=floor($USER['uid']);
if($db->exec("delete from session where sid=0 and zu>'brw' and zu<'brx' and name='$uid'"))
 echo '之前的中转任务已删除[br]';
elseif($_POST['go']=='移除我的任务')
 echo '没有任务了哦';
if($url && $_POST['go']=='加入')
{
if(session::zucount(0,'brw.'.$zu)>5)
 die('抱歉，该组人满了。[/html]');
$brw=new brw_main('brw_139blog',$url,$info);
$brw->puttext('正在打字，手机速度好慢啊……');
$s=new session(0,'brw.'.$zu,3600*5,array($uid));
$s[$uid]=array('blogurl'=>$url);
echo '你的中转任务已添加[br]';
}
?>
[hr][time]
[/html]