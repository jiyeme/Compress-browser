<?php
if($ssh['page']=='form')
{
?>
你正在登陆手机起点网[br]
用户名:[input=user][/input][br]
密码:[input=pass][/input][br]
验证码<img src="read.php?cid=user&amp;pid=ssh_img&amp;type=qidian_cn&amp;t=<%=time()%>" alt="请打开图片显示"/>:[input=yan,3][/input][br]
[submit=go]登陆[/submit]
<?php
}
else
{
$h=new httplib;
$h->open('http://qidian.cn/user/login_sd.do',10,0);
$h->cookie($ssh['cookies']);
#var_dump($ssh);
$h->post('m','login');
$h->post('mtd','getLogin_SD');
$h->post('uid',$_POST['user']);
$h->post('password',$_POST['pass']);
$h->post('ekey',$_POST['anquand']);
$h->post('validateNumber_page',$_POST['yan']);
$h->send();
$ssh['cookies']=$h->cookie();
echo '登录状态（此处内容与本站无关）：<br/>',$nr=strip_tags($h->response());
if(strpos($nr,'正返回来源页面')!==false)
 $ssh['ok']=true;
else
 $ssh['ok']=false;
}
?>