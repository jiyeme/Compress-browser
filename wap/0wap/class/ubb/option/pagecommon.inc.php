<?php
global $PAGE;
$bds=array(
'\[(%*)?([bcps]id)\]','<%\1=$PAGE<("\\2")>%>',
'\[(%*)?([hu]).sid\]','<%\\1=$PAGE<("\\2_sid")>%>',
'\[(%*)?h\.([bcpus])\]','[h=\\2id]<%\\1=$PAGE<("\\2id")>%>[/h]',
'\[h\.([bcpus])=(.*)\]','[h=\\1id]\\2[/h]',
'\[(%*)?u\.([bcpus])\]','\\2id=<%\\1=$PAGE<("\\2id")>%>',
'\[u\.([bcpus])=(.*)\]','\\1id=\\2',
'\[input=([a-zA-Z0-9]*),([0-9]*)\](.*)?\[/input\]','<input type="text" name="\\1" value="\\3" size="\\2"/>',
'\[input=([a-zA-Z0-9]*)\](.*)?\[/input\]','<input type="text" name="\\1" value="\\2"/>',
'\[input=([0-9]*),([a-zA-Z0-9]*)\](.*)?\[/input\]','<input type="text" name="\\2" value="\\3" maxlength="\\1"/>',
'\[input=([0-9]*),([a-zA-Z0-9]*),([0-9*])\](.*)?\[/input\]','<input type="text" name="\\2" value="\\4" size="\\3" maxleh="\\1"/>',
'\[sel(ect)?=([a-zA-Z0-9]*)\]','<select name="\\2">',
'\[/sel(ect)?\]','</select>',
'\[op(tion)?\](.*)?\[/op(tion)?\]','<option value="\\2">\\2</option>',
'\[op(tion)?=(.*)?\](.*)?\[/op(tion)?\]','<option value="\\2">\\3</option>',
/*'\[(%*)?read=,(.*),\](.*)?\[/read\]','<a href="read.php?bid='.$PAGE['bid'].'&amp;cid=\\2&amp;pid='.DEFAULT_PAGE_NAME.'<%\\1=$PAGE<("u_sid")>%>">\\3</a>',*/
'\[(%*)?read=,(.*),(.*)\](.*)?\[/read\]','<a href="read.php?bid='.$PAGE['bid'].'&amp;cid=\\2&amp;pid=\\3<%\\1=$PAGE<("u_sid")>%>">\\4</a>',
'\[(%*)?read=(.*),(.*),(.*)\](.*)?\[/read\]','<a href="read.php?bid=\\2&amp;cid=\\3&amp;pid=\\4<%\\1=$PAGE<("u_sid")>%>">\\5</a>',
'\[(%)?time\]','<%\\1echo date("Y-m-d H:i")%>',
'\[(%)?time=(.*)\]','<%\\1echo date("\\2")%>',
'\[(%)?getuser\]','<%\\1$USER["notshow"]=true;
include ubb::page("'.$PAGE['bid'].'","common","head")%>',
'\[(%)?foot\]','<%\\1include ubb::page("'.$PAGE['bid'].'","common","foot")%>',
'\[(%)?head\]','<%\\1include ubb::page("'.$PAGE['bid'].'","common","head")%>',
'\[(%)?include=,(.*),(.*)\]','<%\\1include ubb::path("'.$PAGE['bid'].'","\\2","\\3")%>',
'\[(%)?include=(.*),(.*),(.*)\]','<%\\1include ubb::path("\\2","\\3","\\4")%>',
'\[xor\]',chr(94),
'\[or\]',chr(124),
);
?>