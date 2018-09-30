<?php
class code
{
/*
类:code
code::html($str,$br=false) 转换html实体字符，$br=true后把换行转换为<br/>
进行编码转换
属性：
$in  原编码，默认为UTF-8
$out  目标编码，默认为gbk
  $gb2bg  是否启用简繁转换。默认为0（关闭），1：繁＞简，2：简＞繁，3：自动（当转到BIG5时自动从简到繁，从BIG5转到其他编码时从翻到简）。使用utf8_cn.class.php里的utf8_cn类实现转换，必须（只需）引入自动加载类（autoload.php）
  $func  使用函数，默认为iconv（设为0）。为1时使用mb_convert_encoding
  $option  错误处理方式。1:转换成类似的字符，如还转不了则丢掉。0:丢掉不能转换的字符。默认为0。$func=1时无效（它会默认把不能转换的字符转成问号 ?）
普通方法：
en($text)  从原编码转换成目标编码
de($text)  反向编码，从目标编码转回原编码
did($text,$in,$out) 使用之前的配置（$in,$out除外）自定义编码进行转码
set($in='utf-8',$out='gbk',$gb2bg=0,$func=0,$option=1)  设置以上列出的属性，括号内是默认值。使用空字符串''可跳过对应项设置。使用$ob->set();可置回默认
静态方法：
  coding($text,$in,$out,$gb2bg,$func,$option)  每一项都不可省略，实际的转码工作由它完成
*/
var $in='utf-8',$out='gbk',$gb2bg=0,$func=0,$option=0;
static function html($str,$br=false,$wmlOn=true,$input=false)
{
global $PAGE;
$str=htmlspecialchars($str,ENT_QUOTES,'utf-8');
if($br)
 $str=preg_replace("!\r?\n!",$input ? '&#10;' : '<br/>',$str);
if($wmlOn&&$PAGE['bid']=='wml')
 $str=str_replace('$','$$',$str);
return $str;
}
static function coding($text,$in,$out,$gb2bg,$func,$option)
{
if($gb2bg)
{
 if($gb2bg==3)
 {
if($in=='big5')
  $gb2bg=1;
elseif($out=='big5')
  $gb2bg=2;
 }
 if($in!='utf-8')
{
$text=self::coding($text,$in,'utf-8',0,$func,$option);
$in='utf-8';
}
 if($gb2bg==1)
  $text=utf8_cn::bg2gb($text);
 elseif($gb2bg==2)
  $text=utf8_cn::gb2bg($text);
}
if(!$func)
{
 if($option)
  $out.='//TRANSLIT';
$out.='//IGNORE';
$text=iconv($in,$out,$text);
}
else
{
$text=mb_convert_encoding($text,$out,$in);
}
return $text;
}
function did($text,$in,$out)
{
return self::coding($text,strtolower($in),strtolower($out),$this->gb2bg,$this->func,$this->option);
}
function en($text)
{
return $this->did($text,$this->in,$this->out);
}
function de($text)
{
return $this->did($text,$this->out,$this->in);
}
function set($in='utf-8',$out='gbk',$gb2bg=0,$func=0,$option=0)
{
if($in!=='') $this->in=strtolower($in);
if($out!=='') $this->out=strtolower($out);
if($gb2bg!=='') $this->gb2bg=$gb2bg;
if($func!=='') $this->func=$func;
if($option!=='') $this->option=$option;
}
#code类结束
}
?>