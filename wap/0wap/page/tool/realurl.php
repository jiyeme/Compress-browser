[html=真实链接提取器]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]]
<input name="url" value="<?php
if($url=$_POST['url'])
{
$url=str_replace('&amp;','&',$url);
if(!strpos($url,'://'))
 $url='http://'.$url;
$h=new httplib;
$h->open($url,10,10);
$h->send();
echo $h->url();
}
?>
"/>[br]
[submit]提交[/submit]
[/form]
[time]
[/html]