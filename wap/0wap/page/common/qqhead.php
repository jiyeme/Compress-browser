<?php
return;
echo '[br]';
$qq_time=time();
$qq_i=new session($USER['uid'],'qq',0,array('head','head_open','head_timeout','login_timeout','login_s'));
#if(!$qq_i['head_open'])
# return;
$qq_head_timeout=60;
$qq_login_timeout=600;
$qq_login_s=$qq_i['login_s'] or $qq_login_s=10;
$qq_head=$qq_i['head'];
if($qq_i['head_timeout']<$qq_time)
{
$qq_s=new session($USER['uid'],'cookies',3600*24*30,array('3g_qq_com'));
if(!$qq_u=$qq_s['3g_qq_com'])
 return;
$qq_h=new httplib;
if($qq_i['login_timeout']<$qq_time)
{
$qq_url='http://pt.3g.qq.com/s?aid=nLogin3gqqbysid&myqq='.$qq_u['qq'].'&3gqqsid='.$qq_u['sid'].'&g_ut=1';
$qq_h->open($qq_url,2,2);
$qq_h->send();
if(!strpos($qq_h->response(),'成功'))
{
echo '[QQ风向标登陆失败]';
return;
}
$qq_url="http://q.3g.qq.com/g/s?sid=$qq_u[sid]";
$qq_h->open($qq_url,2,2);
$qq_h->post('s',$qq_login_s);
$qq_h->post('aid','chgStatus');
$qq_h->send();
if(!strpos($qq_h->response(),'成功'))
{
echo '[QQ风向标设置登陆状态失败]';
return;
}
echo '[已登陆]';
$qq_i['login_timeout']=$qq_time+$qq_login_timeout;
}
$qq_url='http://q.3g.qq.com/g/s?aid=nqqGroup&sid='.$qq_u['sid'].'&myqq='.$qq_u['qq'].'&g_ut=1';
$qq_h->open($qq_url,2,2);
$qq_h->send();
$qq_head=$qq_h->response();
$qq_i['head']=$qq_head=substr($qq_head,$qq_pst=strpos($qq_head,'<p>')+4,strpos($qq_head,'</p>')-$qq_pst);
if(!strpos($qq_head,'QQ'))
{
echo '[QQ风向标获取数据失败]';
return;
}
echo '[已刷新]';
$qq_i['head_timeout']=$qq_time+$qq_head_timeout;
}
echo $qq_head ? $qq_head : '[QQ风向标打开失败]';
unset($qq_i,$qq_s,$qq_h);
?>