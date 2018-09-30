<?php
global $PAGE;
$bds=array(
'\[div=(.*)\]','<div class="\\1">',
'\[/div\]','</div>',
'\[whr\]','',
'\[iswml\].*\[/iswml\]','',
'\[anchor=.*\].*\[/anchor\]','',
'\[pst=[a-zA-Z0-9]*\]','',
'\[/?isxhtml\]','',
'\[hr\]','<hr/>',
'\[html=(.*)\]','<?php headecho::put(); ?>
<title>\\1</title>
</head>
<body>
<a id="top" href="#bottom" accesskey="6"></a>',
'\[/html\]','<a id="bottom" href="#top" accesskey="3"></a>
</body></html>',
'\[form=file,(.*)\]','<form method="post" enctype="multipart/form-data" action="\\1">',
'\[form=([a-zA-Z]*),(.*)\]','<form accept-charset="utf-8" action="\\2" method="\\1">',
'\[/form\]','</form>',
'\[file=([a-zA-Z0-9]*)\].*\[/file\]','<input type="file" name="\\1"/>',
'\[submit=([a-zA-Z0-9]*)\](.*)?\[/submit\]','<input type="submit" name="\\1" value="\\2"/>',
'\[submit\](.*)?\[/submit\]','<input type="submit" value="\\1"/>',
'\[h(idden)?=([a-zA-Z0-9]*)\](.*)?\[/h(idden)?\]','<input type="hidden" name="\\2" value="\\3"/>',
'\[text=([a-zA-Z0-9_]+)\]','<textarea name="\\1">',
'\[/text\]','</textarea>',
'\[text=([0-9]+),([0-9]+),([a-zA-Z0-9_]+)\]','<textarea name="\\3" cols="\\1" rows="\\2">',
'\[tp\]','<div class="tp">',
'\[/tp\]','</div>',
);
?>