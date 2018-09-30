[html=科学计算器]
[sid]/[cid].[pid].[bid][hr]
请输入算式：
[form=post,read.php?cid=math&amp;pid=js]
[input=ss]<%=$ss=$_REQUEST<('ss')>%>[/input]
[br][submit]计算[/submit][anchor=post,计算,read.php?bid=wml&amp;cid=math&amp;pid=js][pst=ss][/anchor]
[/form]
<?php
if($ss!='')
{
 echo '[hr]',code::html($ss),'[br]=',$yes=math_js::go($ss);
if(!$yes&&$yes!==0)
 echo ' 算式错误！';
}
?>[hr][time][/html]