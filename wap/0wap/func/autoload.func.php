<?php
#加载类文件
function autoload_file($classname)
{include CLASS_DIR.'/'.$classname.'.class.php';}
#加载类库中的类文件
function autoload_libfile($classname)
{include CLASS_DIR.'/'.str_replace('_','/',$classname).'.class.php';}
#autoload系列函数结束
?>