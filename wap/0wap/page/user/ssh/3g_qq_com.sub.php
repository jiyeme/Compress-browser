<?php
if($ssh['page']=='form')
{ ?>
你正在登陆3GQQ[br]QQ号:[input=user][/input][br]
登陆书签:[input=pass][/input][br]
[submit=go]提交[/submit][br]
提示：在3g.qq.com登陆后，随便复制一个链接粘贴到“登陆书签”那儿都可以。
<?php }
else
{
$query=url::getqueryarray(preg_replace('!'.'^.*\?!','',$_POST['pass']));
$sid=$query['sid'];
$qq=$_POST['user'];
if($qq && $sid)
{
$ssh['cookies']=array('qq'=>$qq,'sid'=>$sid);
$ssh['ok']=true;
echo 'QQ '.$qq.' 的登陆信息已保存，但没有进行任何验证（这意味着过期的或错误的登陆信息也会被保存）';
}
else
 echo 'QQ号和地址中的sid都不能为空！';
}
?>