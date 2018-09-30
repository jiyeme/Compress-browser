<?php
/*error类，用于处理和显示错误*/
class error
{
public $code,$msg,
   $file,$line;
function __construct($code=0,$msg='',$file='',$line=0)
{
$this->code=$code;
$this->msg=$msg;
$this->file=$file;
$this->line=$line;
}
function __toString()
{
$msg="出错啦！<br/>\n";
if($this->code)
 $msg.="错误代码: $this->code<br/>\n";
if($this->msg)
 $msg.="错误信息: $this->msg<br/>\n";
if($this->file)
 $msg.="错误文件: $this->file<br/>\n";
if($this->line)
 $msg.="错误行号: $this->line";
return $msg;
}
#error类结束#
}
?>