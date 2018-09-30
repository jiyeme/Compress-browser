<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$eid=str::word($_REQUEST['eid']);
$s=new session($USER['uid'],'text',24*3600,array($eid),false);
if(!isset($s[$eid]))
{ ?>
[html=出错啦！]
数据库说：“你等等，我找一下……[br]抱歉，没有找到耶。可能是我不小心弄丢了，对不起。[br]你可以去[read=,edit,newtext]新建一份文本文档[/read]，或者去[read=,index,]首页[/read]逛逛。祝你娱快，并希望我们不要再见（你懂的）。 - -”[hr]
[time][foot][/html]
<?php
exit;
}
$e=$s[$eid];
$ueid="&amp;eid=$eid";
?>
[html=<%=code::html($e<('title')>)%>-编辑器工具]
[head]
『[read=,fat32,bin_dir&amp;xx=dir&amp;u=<%=urlencode('read.php?[%%u.b]&cid=edit&pid=text_tobin&eid='.$eid)%>]下载[/read][or][read=,edit,set<%=$ueid%>]设置[/read][or][read=,edit,edit<%=$ueid%>]编辑[/read]』[br]
文件：<%=$e['title']%>[br]
字数：<%=mb_strlen($e['value'],'utf-8')%>字[br]
行数：<%=mb_substr_count($e['value'],"\n")+1%>行[hr]
『过滤器』[br][read=,edit,text_ff&amp;ff=xmltotext<%=$ueid%>&amp;u=<%=urlencode($_SERVER<('REQUEST_URI')>)%>]网页转纯文本[/read]
[hr]
『另存为』[br][read=,tool,text_toqzone<%=$ueid%>&amp;u=<%=urlencode($_SERVER<('REQUEST_URI')>)%>]发到QQ空间[/read][br][read=,edit,text_toother<%=$ueid%>&amp;type=sky]sky(冒泡书城/凯阅电子书)[/read][hr]
『简繁转换』[br][read=,edit,tool_gb2bg&amp;d=bg2gb&amp;eid=<%=$eid%>]繁转简[/read] [or] [read=,edit,tool_gb2bg&amp;d=gb2bg&amp;eid=<%=$eid%>]简转繁[/read]
[br](可能有部分字对应不准确，欢迎[url=/wap/?id=liuyan]反馈[/url])[br][read=,edit,tool_code&amp;d=urlencode<%=$ueid%>]URL编码[/read] [or] [read=,edit,tool_code&amp;d=urldecode<%=$ueid%>]URL解码[/read][hr]
『[read=,edit,tool_found&amp;eid=<%=$eid%>]查找[/read] [or] [read=,edit,tool_replace&amp;eid=<%=$eid%>]替换[/read]』[hr]
『复制工具』[br][read=,edit,tool_copy&amp;d=text&amp;eid=<%=$eid%>]文字[/read] [or] [read=,edit,tool_copy&amp;d=url&amp;eid=<%=$eid%>]链接[/read] [or] [read=,edit,tool_copy&amp;d=html<%=$ueid%>]代码[/read][hr]
更多工具添加中，敬请期待[xor]_[xor][hr]
[read=,edit,edit&amp;eid=<%=$eid%>]继续编辑[/read]-[read=,edit,copybk]剪切板[/read][br][url=<%=code::html($e<('url')>)%>]来源页[/url]-[read=,index,]首页[/read][br][time]
[foot][/html]