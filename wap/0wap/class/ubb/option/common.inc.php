<?php
$bds=array(
'\[url=(.*)\](.*)\[/url\]','<a href="\\1">\\2</a>',
'\[br\]','<br/>',
'\[tab\]','&nbsp;&nbsp;&nbsp;&nbsp;',
'\[url\](.*)\[/url\]','<a href="\\1">\\1</a>',
'\[img\](.*)\[/img\]','<img src="\\1"/>',
'\[img=(.*)\](.*)\[/img\]','<img src="\\1" alt="\\2"/>',
'\[(/?[bip])\]','<\\1>',
);
?>