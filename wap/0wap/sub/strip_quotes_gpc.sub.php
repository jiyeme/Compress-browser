<?php
/*过程：把GET,POST,COOKIE中引号被加上的反斜线去掉，并关闭在执行中的引号转义*/
ini_set('magic_quotes_runtime',0);
if(ini_get('magic_quotes_gpc'))
 define('STRIP_QUOTES_FUNC','stripslashes');
elseif(ini_get('magic_quotes_sybase'))
 define('STRIP_QUOTES_FUNC','strip2quote');
else
 return;
array_multimap(STRIP_QUOTES_FUNC,$_GET);
array_multimap(STRIP_QUOTES_FUNC,$_POST);
array_multimap(STRIP_QUOTES_FUNC,$_COOKIE);
array_multimap(STRIP_QUOTES_FUNC,$_REQUEST);
/*array_multimap(STRIP_QUOTES_FUNC,$_FILES); //手册说$_FILES不会被转义，所以注释掉了*/
function strip2quote($str)
{
return str_replace("''","'",$str);
}
function array_multimap($func,&$array)
{
foreach($array as &$val)
 {
  if(is_array($val))
   array_multimap($func,$val);
  else
   $val=$func($val);
 }
}
?>