<?php
/**
QQ浏览器书签操作类
**/
class qb_mark
{
const maxjc=10; #最大书签数
var $jc, #书签总数
    $fp; #书签句柄
function create($fname)
{
if(!$this->fp=fopen($fname,'w+'))
 return false;
fwrite($this->fp, '~q.'.pack('H*','010000000088010000'));
$this->jc=0;
return true;
}
function add($name,$url,$size)
{
if($this->jc >= self::maxjc)
 return false;
$this->jc++;
fseek($this->fp,4);
fwrite($this->fp,chr($this->jc));
fseek($this->fp,0,SEEK_END);
fwrite($this->fp,str_pad(mb_convert_encoding($name,'UTF-16LE','UTF-8'),128,chr(0)).str_pad($url,256,chr(0)).pack('VH*',$size,'00010000'));
return true;
}
function __destruct()
{
if(!$this->fp)
 fclose($this->fp);
}
#class end#
}
?>