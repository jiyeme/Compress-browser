<?php
global $PAGE;
$bds=array(
'\[div=.*\]','',
'\[/div\]','',
'\[whr\]','<br/>----------<br/>',
'\[isxhtml\].*\[/isxhtml\]','',
'\[/?form(=.*)?\]','',
'\[submit(=.*)?\].*\[/submit\]','',
'\[/?iswml\]','',
'\[h(idden)?=.*\].*\[/h(idden)?\]','',
'\[html=(.*)\]','<?php headecho::put(); ?>

<card id="main" title="\\1"<%=$PAGE["ontimer"]%>>',
'\[/html\]','</card></wml>',
'<hr */?>','[hr]',
'\[hr\]','<br/>--------<br/>',
'\[/anchor\]','</go></anchor>',
'\[anchor=([a-zA-Z]*),(.*),(.*)\]','<anchor>\\2<go method="\\1" accept-charset="utf-8" href="\\3">',
'\[post=([a-zA-Z0-9]*)\](.*)\[/post\]','<postfield name="\\1" value="\\2"/>',
'\[pst=([a-zA-Z0-9]*)\]','<postfield name="\\1" value="$(\\1)"/>',
'\[text=([a-zA-Z0-9]*)\]','<input type="text" name="\\1" value="',
'\[/text\]','"/>',
'\[text=([0-9]+),([0-9]+),([a-zA-Z0-9]*)\]','<input type="text" name="\\3" size="\\1" value"',
'\[/?tp\]','',

' accesskey="[0-9\*#]"', '',
'\[file=.*\](.*)\[/file\]','[\\1]',
);
?>