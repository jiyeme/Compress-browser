[html=网页不存在]
<?php
$ur=code::html(str_replace('/0wap/','/',str_replace('cid=','id=',$_SERVER['REQUEST_URI'])));
echo '<a href="'.$ur.'">进入旧版</a> <a href="'.str_replace('&amp;id=','&amp;id=lktxt&amp;p=1&amp;d=',$ur).'">小说地址</a><br/>';
$url=$_REQUEST['cid'];
if(preg_match($urbds='![a-zA-Z0-9\._\-]{1,}\.[a-zA-Z]{2,}!',$url) or preg_match($urbds,$url=$_REQUEST['pid']))
echo '直接访问[url=http://'.$url.']'.$url.'[/url][hr]';
?>
抱歉，没有找到你要访问的页面。[br]出现这个问题的原因可能是：[br]1.你输入了错误的地址，请检查并重新输入。[br]2.你访问的页面还没来得及建立，或者正在修改中。[br]3.管理员一不小心打错了网址，[url=/wap/?id=liuyan]点此报错[/url][br]4.你正在使用冒泡浏览器，对于斯凯弄出的一大堆BUG我们感到很无力。[br]5.你正在使用CMWAP接入点浏览，而移动网关对网页进行了错误的转换。请切换到CMNET接入点并刷新之前的页面再进。[br]最后，再次表示很抱歉。[hr]
[read=,index,index]返回首页[/read]-[url=/wap/]旧版首页[/url]
[br][time]
[/html]