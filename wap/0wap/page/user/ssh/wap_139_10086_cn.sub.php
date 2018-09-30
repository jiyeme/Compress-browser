<?php
if($ssh['page']=='form')
{
?>
你正在登陆139说客相册[br]
请粘贴你的相片上传页地址:[br][input=url][/input][br]
[submit=go]登陆[/submit][br]登陆提示：mrpQQ浏览器直接从网页上复制的地址经常不完整，建议在“菜单＞打开＞历史”里面复制。
<?php
}
else
{
$ssh['ok']=false;
$url=preg_replace('!http://[a-z0-9_\\-\\.]+(:[0-9]+)?!i','',str_replace('&amp;','&',$_POST['url']));
$s=strpos($url,'jsessionid=');
$len=strpos($url,'?');
if($s===false or $len===false)
{
echo '登陆地址错误，没有发现jsessionid[br]',code::html($url);
return;
}
echo 'jsessionid=', $cok['jsessionid']=substr($url,$s+11,$len-$s-11), '[br]';
$arr=url::getqueryarray(substr($url,$len+1));
if(!$cok['m']=$arr['m'])
{
echo '登陆地址错误，没有发现m参数[br]请检查你是否误入了旧版说客（返回139首页，在顶部有一行“找人 新说客”，点“新说客”进入，然后进“应用”＞“相册”＞“上传”＞“手机在线上传”，复制地址并重新粘贴到这）[br]在139登陆时，“自动为我登陆”要选“是”。建议关闭Cookies。因为不这样做容易导致没有m参数。[br]',code::html($url);
return;
}
echo 'm=',$cok['m'],'[br]';
if(!$cok['src']=$arr['src'])
{
echo '登陆地址错误，没有发现src参数，你登陆了吗？？[br]',code::html($url);
return;
}
echo 'src=',$cok['src'],'[br]';
if(!$cok['cid']=$arr['cid'])
{
echo '登陆地址错误，没有发现cid（相册id）[br]这真的是139相片上传页的地址吗？[br]',code::html($url);
return;
}
echo 'cid=',$cok['cid'];
$cok['go']=$url;
$ssh['cookies']=$cok;
$ssh['ok']=true;
}
?>