[html=URL可用性检验]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]]
请输入URL:[input=url][/input][br]
[submit]开始检验[/submit]
[/form]
<?php
if($url=$_POST['url'])
{
$h=new httplib;
if(!$h->open($url))
 echo '错误：网址格式不正确！[br]';
elseif(!$h->send())
 echo '错误：网址打开失败！[br]';
elseif(!$xml=$h->response())
 echo '错误：网页打开后是空白！';
else
 echo str_replace(array('<','>','&',"\n"),array('&lt;','&gt;','&amp;','<br/>'),xml::totext($xml));
}
?>
[hr]如果你看到了“登陆”字样，就说明地址不正确。[hr]建议的提取方法：接入点设为CMNET，Cookies设为拒绝，然后访问[url]http://wap.139.10086.cn:90/portal.do[/url][hr]如果你用你提取地址的浏览器访问上面的链接后，发现已经登陆上了（CMWAP就是这样的，免登陆，但是这样的地址是不能用的，如果不用你的手机去访问，就会被要求重新登陆），那么请切换接入点、关闭Cookies或清除Cookies再试。如果你无论如何都无法跳转到登陆页面（注：只有自己输入用户名和密码之后提取到的地址才是有效的），那你就只能用电脑或者[url=/wap.php]压流浏览器[/url]提取地址了。
[hr][time]
[/html]