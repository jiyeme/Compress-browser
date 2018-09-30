<?php
[%getuser]
if(!$USER['islogin'])
 headecho::location('',true);
$s=new session($USER['uid'],'set',0,array('edit'));
$set=$s['edit'];
$eid=str::word($_REQUEST['eid']);
if(!$_POST['go'])
{
?>
[html=编辑器设置]
[head]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;eid=<%=$eid%>]
[read=,help,edit_set]帮助中心_编辑器设置[/read][br]
输入框类型:[sel=text][op=0]单行输入框[/op][op=1]多行输入框[/op][/sel][br]
分段字数:[input=duan,3]<%=$set['duan']?$set['duan']:800%>[/input][br]
单框字数:[input=kuang,3]<%=$set['kuang']?$set['kuang']:100%>[/input][br]
换行转换:[input=br,3]<%=code::html($set['br'])%>[/input][br](留空则不转换。QQ空间的换行符是 [br<%='/'%>] ，百度贴吧的是 &amp;#10; ，其他地方的一般为 [b<%='r'%>] )[br]
Q浏模式:[sel=qqmode][op=0]关闭[/op][op=1]开启[/op][/sel][br](QQ浏览器有一个设计失误：它会忽略位于输入框最前面和最后面的空格，很容易会导致程序/网页代码出现错误。[br]开启Q浏模式后将在每个输入框内容的开始和结尾处添加字符 [xor] 和 <%=$dr=code::html('$')%> （插入内容时请插在两者之间），并且在WML版不再把<%=$dr%>（这里只有一个美元符哦）转义为<%=$dr,$dr%>（Q浏WML版美元符BUG）。当保存时开头的 [xor] 和结尾的 <%=$dr%>会被自动删去。还有，Q浏不支持换行，请务必设置换行转换。另外，超过500字提交时内容会被Q浏截断)
[br]显示行号:[sel=hanghao][op=1]开启[/op][op=0]关闭[/op][/sel][br]
(行号是为了方便代码编写工作，如果你不需要，可以关闭它)[br]
精简风格:[sel=less][op=0]关[/op][op=1]开[/op][/sel][br](开启精简风格将隐藏跳字跳行工具栏)[br]
[submit=go]提交[/submit][anchor=post,提交,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;eid=<%=$eid%>][post=go]1[/post][pst=text][pst=duan][pst=kuang][pst=br][pst=nbsp][pst=hanghao][pst=qqmode][/anchor]
[/form]
[hr]返回[read=,edit,edit&amp;eid=<%=$eid%>]编辑器[/read] [read=,edit,copybk]剪切板[/read] [read=,index,]首页[/read][br][time][foot]
[/html]
<?php
}
else
{
$s['edit']=array(
'text'=>floor($_POST['text']),
'duan'=> (($duan=floor($_POST['duan']))>0 ? $duan : 800),
'kuang'=> (($kuang=floor($_POST['kuang']))>0 ? $kuang : 100),
'br'=>$_POST['br'],
'qqmode'=>floor($_POST['qqmode']),
'nbsp'=>$_POST['nbsp'],
'hanghao'=>floor($_POST['hanghao']),
'less'=>floor($_POST['less']),
);
if($eid)
 $url='read.php?[%%u.b]&cid=edit&pid=edit&eid='.$eid;
else
 $url='read.php?[%%u.b]&cid=edit&pid=copybk';
headecho::location($url,true);
}
?>