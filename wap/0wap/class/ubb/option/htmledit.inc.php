<?php
function ubb4($t)
{
global $code;
include dirname(__FILE__)."/data/html.php";
$bds=array(
'\[html=(.*)\]',$html.'<title>\\1</title>
</head>
<body>',
'\[wml=(.*)\]',$wml.'</head>
<card id="main" title="\\1">',
'\[/html\]','</body>
</html>',
'\[/wml\]','</card>
</wml>',
'\[html\]',$html,
'\[wml\]',$wml,
'\[url=(.*)\](.*)\[/url\]','<a href="\\1">\\2</a>',
'\[br\]','<br/>',
'\[hr\]','<hr/>',
'\[tab\]','&nbsp;&nbsp;&nbsp;&nbsp;',
'\[url\](.*)\[/url\]','<a href="\\1">\\1</a>',
'\[img\](.*)\[/img\]','<img src="\\1"/>',
'\[img=(.*)\](.*)?\[/img\]','<img src="\\1" alt="\\2"/>',
'\[([bip])\]','<\\1>',
'\[((center)|(left)|(right))\]','<p align="\\1">',
'\[/([bip])\]','</\\1>',
'\[((center)|(left)|(right))\]','</p>',
'\[p=([a-z]+)\]','<p style="text-align:\\1;">',
'\[form=([a-zA-Z0-9]*),(.*)\]','<form action="\\2" method="\\1">',
'\[/form\]','</form>',
'\[anchor=([a-z]+),(.*),(.*)\]','<anchor>\\3<go method="\\1" action="\\2">',
'\[/anchor\]','</go></anchor>',
'\[post=([a-zA-Z0-9_]*)\](.*)\[/post\]','<postfield name="\\1" value="\\2"/>',
'\[submit=([a-zA-Z0-9]*),[0-9\*#]\](.*)\[/submit\]','<input type="submit" name="\\1" value="\\3" accesskey="\\2"/>',
'\[submit,[0-9\*#]\](.*)\[/submit\]','<input type="submit" value="\\2" accesskey="\\1"/>',
'\[input=([a-zA-Z0-9]*),([0-9]*)\](.*)?\[/input\]','<input name="\\1" value="\\3" size="\\2"/>',
'\[input=([a-zA-Z0-9]*)\](.*)?\[/input\]','<input name="\\1" value="\\2"/>',
'\[submit=([a-zA-Z0-9]*)\](.*)?\[/submit\]','<input type="submit" name="\\1" value="\\2"/>',
'\[submit\](.*)?\[/submit\]','<input type="submit" value="\\1"/>',
'\[sel(ect)?=([a-zA-Z0-9]*)\]','<select name="\\2">',
'\[/sel(ect)?\]','</select>',
'\[op(tion)?\](.*)?\[/op(tion)?\]','<option value="\\2">\\2</option>',
'\[op(tion)?=(.*)?\](.*)?\[/op(tion)?\]','<option value="\\2">\\3</option>',
'\[h(idden)?=([a-zA-Z0-9]*)\](.*)?\[/h(idden)?\]','<input type="hidden" name="\\2" value="\\3"/>',
'\[text=([a-zA-Z0-9_]+)\]','<textarea name="\\1">',
'\[/text\]','</textarea>',
'\[text=([0-9]+),([0-9]+),([a-zA-Z0-9_]+)\]','<textarea name="\\3" cols="\\1" rows="\\2">',
'\[ex\]',chr(94),
'\[or\]',chr(124),
);
$jc=count($bds);
for($a=0;$a<$jc;$a=$a+2)
{
$b=$a+1;
$t=preg_replace('!'.$bds[$a].'!uisU',$bds[$b],$t);
}
return $t;
}
?>