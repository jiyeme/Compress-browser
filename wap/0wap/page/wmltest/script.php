<?php
header('Content-type: text/vnd.wap.wmlscript');
echo ut2un('extern function go_test()
{
var mymsg;
Dialogs.alert("中文测试 Hello! Welcome to WML lab of hu60.cn");
mymsg=Dialogs.prompt("Please input a string",0);
Dialogs.confirm("You inputed: "+mymsg+" ,did not you?","Yes.","Oh! No!");
Dialogs.alert("Thank you, bye~~");
return true;
}');
function ut2un($t)
{return mb_convert_encoding($t,'gb2312','utf-8');}
?>