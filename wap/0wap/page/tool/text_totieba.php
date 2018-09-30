<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$u=$_REQUEST['u'];
$eid=str::word($_REQUEST['eid']);
$s=new session($USER['uid'],'text',3600*5*24,array($eid),false);
$input=new session($USER['uid'],'input',3600*24*5,array('tieba_kw'));
if(!$e=$s[$eid])
{ ?>
[html=出错啦！]
数据库：抱歉，我找不到你的文本，可能过期了。[hr]返回[url=<%=code::html($u)%>]来源页[/url] [read=,index,]首页[/read][br][time][foot]
[/html]
<?php
exit;
}
if(!$_POST['go'])
{
?>
[html=<%=$title=code::html($e<('title')>)%>-发到百度贴吧]
[head]
正在把文章“<%=$title%>”发布到百度贴吧。如果你第一次使用它，请先[read=,user,ssh&amp;u=<%=urlencode($_SERVER<('REQUEST_URI')>)%>]远程登陆百度贴吧[/read][hr]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;eid=<%=$eid%>]
贴吧:[input=kw]<%=code::html($input['tieba_kw'])%>[/input]吧[br]
标题:[input=ti]<%=$title%>[/input][br]
[submit=go]发表[/submit][read=,edit,edit&amp;eid=<%=$eid%>]我再改改[/read]
[/form]
<?php
}
else
{
?>
[html=发到百度贴吧-状态]
已经把文章提交到了百度贴吧，至于是否成功……请看下文：[hr]
<?php
$tieba=new session($USER['uid'],'cookies',0,array('wap_baidu_com'),false);
$tieba=$tieba['wap_baidu_com'];
$kw=$_POST['kw'];
$url="http://wapp.baidu.com/f?kw=".urlencode($kw)."&ssid=$tieba[ssid]&bd_page_type=1";
$h=new httplib;
$h->open($tieba['url'],5,5);
$h->send();
$cok=$h->cookie();
sleep(1);
$h->open($url,5,5);
$h->cookie($cok);
$input['tieba_kw']=$kw;
$ti=xml::空格转换($_POST['ti']);
$co=xml::空格转换($e['value']);
$h->send();
echo $h->url();
echo code::html($xml=$h->response());
$xmlp=xml_parser_create('utf-8');
xml_parse_into_struct($xmlp,$xml,$arr);
$form=0;
foreach($arr as $n)
{
if($n['tag']=='FORM')
 $form++;
if($form==3)
 {
if($n['tag']=='FORM')
 $url2='http://tieba.baidu.com'.$n['attributes']['ACTION'];
if($n['tag']=='INPUT' && $n['attributes']['TYPE']=='hidden')
 $post[$n['attributes']['NAME']]=$n['attributes']['VALUE'];
 }
elseif($form>3)
 break;
}
$post['ti']=$ti;
$post['co']=$co;
$post['sub1']='发表贴子';
$post['word1']='';
echo $url2;print_r($cok);print_r($post);
$h->open($url2,10,5);
$h->referer($url);
$h->cookie($cok);
$h->post($post);
sleep(1);
$h->send();
echo $h->url();
sleep(1);
$xml=$h->response();
echo $xml ? code::html($xml) : '不知成功还是失败，';
echo '[url='.code::html($url).']去'.$kw.'吧看看吧[/url]';
}
?>
[hr]返回[url=<%=code::html($u)%>]来源页[/url] [read=,fat32,text_list]我的文本[/read] [read=,index,]首页[/read][br]
[time][foot]
[/html]