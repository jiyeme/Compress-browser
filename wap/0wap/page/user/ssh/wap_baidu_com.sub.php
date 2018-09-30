<?php
$query=url::getqueryarray(preg_replace('!'.'^.*\?!','',$url=$_POST['pass']));
$ssid=$query['ssid'];
if($ssid)
{
$ssh['cookies']=array(
 'ssid'=>$ssid,
 'url'=>$url,
);
$ssh['ok']=true;
echo '百度登陆信息已保存，但没有进行任何验证（这意味着过期的或错误的登陆信息也会被保存）';
}
else
 echo '在地址中没有发现ssid，这真的是百度的自动登陆书签吗？';
?>