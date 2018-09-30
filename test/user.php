<?php
foreach (getallheaders() as $name => $value) { 
echo "$name: $value\n"; 
$links = "$name: $value\r\n";

$file = 'log.txt';//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个

 $content = "\r\n".$links;


file_put_contents($file, $content,FILE_APPEND);

} 