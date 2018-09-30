<?php
/***
http类，用来实现http/ftp通讯/下载
采用fopen和PHP的stream（流）函数打造，特别适合采集和大文件下载，支持断点续传
***/
class http
{
var $url, $ishttp, #地址、是否为HTTP
    $fp, $context, #流资源、流控制上下文
    $header, $file, #HTTP请求头和文件上传数组
    $post, $cookie, #POST和COOKIE数组
    $ua, $errmsg, #浏览器UA数组、错误信息文本
    $sent; #是否已成功send()
function __construct($use_ua=true)
{
if($use_ua)
 $this->ua($use_ua);
}
function open($url, $timeout=30, $jumplimit=10)
{
$this->close();
$xor=chr(94);
$or=chr(124);
if(!preg_match("!{$xor}(ht){$or}(f)tps?://!i", $url))
 {
 $this->errmsg='网址错误：网址格式不正确';
 return false;
 }
if(strtolower(substr($url,0,1))=='h')
 $this->ishttp=true;
else
 $this->ishttp=false;
$this->context['timeout']=$timeout;
if($this->ishttp)
 {
$context['max_redirects']=$jumplimit;
 }
$this->url=$url;
return true;
}
function ua($n=null,$v=null)
{
if($n===true)
 {
$this->ua['Accept']='text/vnd.wap.wml,text/html, application/vnd.wap.xhtml+xml,application/xhtml+xml, image/jpeg;q=0.5,image/png;q=0.5;image/gif;q=0.5;image/*;q=0.6, *.*;q=0.6';
/*$this->ua['Accept-Encoding']='gzip, deflate, compress;q=0.9';*/
$this->ua['Accept-Charset']='utf-8, gb2312;q=0.7,*;q=0.7';
$this->ua['Accept-Language']='zh-CN, zh;q=0.9,en;q=0.8';
$this->ua['User-Agent']='Mozilla/5.0 ( SymbianOS/9.3; U; Series60/3.2 NokiaE75-1/110.48.125 Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebkit/413 ( KHTML, like Gecko ) Safari/413';
$this->ua['Q_UA']='SKYQB12_GA/120012&MTKMTT_3/120012&SKY&152020&6225&00000&4019&V3';
$this->ua['Q_GUID']='6736d89a4e0576906865febe4120bae1';
$this->ua['Q_AUTH']='5772eb80db9d4cb81b06961662e3a3389db93df03119e2c0';
 }
elseif($n===null)
 return $this->ua;
else
 {
if(is_array($n))
 $this->ua=$n;
elseif($v!==null)
 $this->ua[$n]=$v;
else
 return $this->ua[$n];
 }
}
function send()
{
if($this->fp)
 fclose($this->fp);
if($this->ishttp)
 {
$this->context['header']='';
if($this->ua)
  {
foreach($this->ua as $n=>$v)
   {
$this->context['header'].="{$n}: {$v}\r\n";
   }
  }
if($this->header)
  {
foreach($this->header as $n=>$v)
   {
$this->context['header'].="{$n}: {$v}\r\n";
   }
$this->header=null;
  }
if($this->cookie)  {
$this->context['header'].='COOKIE: '.str_replace('&','; ',http_build_query($this->cookie))."\r\n";
$this->cookie=null;  }
if($this->post or $this->file)
  {
$this->context['method']='POST';
$this->context['header'].='CONTENT-TYPE: '.($this->file ? 'none' : 'application/x-www-form-urlencoded')."\r\n";
if($this->file)
   {
$this->context['content']=null;
$this->file=$this->post=null;
   }
else
   {
$this->context['header'].='Content-Length: '.strlen($this->context['content']=http_build_query($this->post))."\r\n";
$this->post=null;
   }
  }
else
  {
$this->context['method']='GET';
  }
$this->context['header'].="Connection: Close\r\n";
 }
$context=stream_context_create(array( ($this->ishttp ? 'http' : 'ftp') =>$this->context));
$this->context=null;
if($this->fp=fopen($this->url,'r',false,$context))
  {
unset($context);
$this->sent=true;
return true;
  }
else
  {
$this->errmsg="网址打开失败：连接失败";
$context=$this->fp=null;
return false;
  }
}
function post($n,$v=null)
{
if($this->sent or !$this->ishttp)
 return false;
if(is_array($n))
 $this->post=array_merge($this->post, $n);
else
 $this->post[$n]=$v;
}
function cute_header()
{
if(!$this->ishttp or !$this->sent)
 return false;
$header=stream_get_meta_data($this->fp);
$header=$header['wrapper_data'];
$i=0;
foreach($header as $n)
 {
$x=strpos($n,': ');
if(!$x)
  {
$i++;
$this->header[$i]['STATUS']=$n;
  }
else
 $this->header[$i][strtoupper(substr($n,0,$x))]=substr($n,$x+2);
 }
}
function header($n=null,$v=null)
{
if(!$this->ishttp)
 return false;
if($this->sent)
 {
if(!$this->header)
 $this->cute_header();
if($n===null)
  {
if($v===true)
 return $this->header;
elseif($v)
 return $this->header[$v];
else
   {
return $this->header[count($this->header)];
   }
  }
else
  {
if($v)
 return $this->header[$v][$n];
else
 return $this->header[count($this->header)][$n];
  }
 }
else
 {
if(is_array($n))
 $this->header=array_merge($this->header,$n);
else
 $this->header[$n]=$v; }
}
function cute_cookie()
{
if(!$this->ishttp or !$this->sent)
 return false;

}
function cookie($n=null, $v=null)
{
if(!$this->ishttp)
 return false;
if($this->sent)
 {
if(!$this->cookie)
 $this->cate_cookie();
if($n===null)
 return $this->cookie;
else
 return $this->cookie[$n];
 }
else
 {
if(is_array($n))
  {
foreach($n as $name=>$val)
{$this->cookie($name,$val);}
return;
  }
else
  {
if(is_array($v))
   {
if($v['expires'] && $v['expires']<time())
 return false;
else
 $v=$v['value'];
   }
$this->cookie[$n]=$v;
  }
 }
}
function referer($url=null)
{
if(!$this->ishttp)
 return false;
if($this->sent)
 {
$header=$this->header(null,true);
if($url===null)
 $url=count($header)-2;
$referer=$header[$url]['LOCATION'];
if($referer=='')
 return $this->url;
else
 return $referer;
 }
else
 $this->header['Referer']=$url;
}
function url($url=null)
{
return $this->url;
}
function file()
{
return;
}
function response($size=null,$gzdecode=false,$timeout=30)
{
if(!$this->fp)
 return false;
$content='';
$tm=time();
for($len=0,$length=8192; (!$size or $len<$size) && !feof($this->fp); )
 {
if($size && $size-$len<8192)
 $length=$size-$len;
$nr=fread($this->fp,$length);
$len+=strlen($nr);
$content.=$nr;
if($tm+$timeout<time())
 break;
 }
unset($nr);
if($gzdecode && $this->$ishttp && $this->header('CONTENT-ENCODING'))
 {
if(!function_exists('gzdecode'))
 include FUNC_DIR.'/gzdecode.func.php';
$content=gzdecode($content);
 }
return $content;
}
function tofile($fpath,$size=null,$mode='w',$timeout=540)
{
if(!$this->sent && !$this->fp)
 return false;
if(!$fp=fopen($fpath,$mode))
 return false;
$tm=time();
for($len=0,$length=8192; (!$size or $len<$size)&&!feof($this->fp);)
 {
if($size && $size-$len<8192)
 $length=$size-$len;
$len+=fwrite($fp,fread($this->fp,$length));
if($tm+$timeout<time())
 break;
 }
fflush($fp);
fclose($fp);
return $len;
}
function range($size)
{
if($this->sent)
 {
for($len=0,$length=8192; $len<$size && !feof($this->fp);)
  {
if($size-$len<8192)
 $length=$size-$len;
$len+=strlen(fread($this->fp,$length));
  }
 }
else
 {
if($this->ishttp)
 $this->header['Range']='bytes='.$size.'-';
else
 $this->context['resume_pos']=$size;
 }
}
function isrange()
{
if(!$this->sent)
 return false;
if($this->ishttp)
 return $this->header('CONTENT-RANGE');
else
 return true;
}
function close()
{
if($this->fp)
 {
fclose($this->fp);
$this->fp=null;
 }
if($this->header)
 $this->header=null;
if($this->cookie)
 $this->cookie=null;
}
function __destruct()
{
$this->close();
}
#class end
}
?>