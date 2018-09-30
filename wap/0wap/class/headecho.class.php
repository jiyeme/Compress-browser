<?php
#输出网页MIME和文件头的类
class headecho
{
static function gotologin($url='',$del=false)
{
global $PAGE;
if($url=='')
 $url=$_SERVER['REQUEST_URI'];
self::location("read.php?bid=$PAGE[bid]&cid=user&pid=login&u=".urlencode($url));
$del && exit;
}
static function mime()
{
global $PAGE;
if($PAGE['mime']===NULL)
  $PAGE['mime']=DEFAULT_PAGE_MIME;
if($PAGE['charset']===NULL)
  $PAGE['charset']=DEFAULT_PAGE_CHARSET;
$mime=$PAGE['mime'];
if($PAGE['charset'])
  $mime.='; charset='.$PAGE['charset'];
header('Content-Type: '.$mime);
}
static function xmlhead()
{
global $PAGE;
return '<?xml version="1.0" encoding="'.$PAGE['charset'].'" ?>';
}
static function pagehead()
{
global $PAGE;
if($PAGE['bid']===NULL)
  $PAGE['bid']=DEFAULT_PAGE_UBB;
include CLASS_DIR.'/pagehead/'.$PAGE['bid'].'.inc.php';
return $pagehead;
}
static function get()
{
return self::xmlhead().self::pagehead();
}
static function put()
{
self::mime();
echo self::xmlhead(),self::pagehead();
}
static function getpagemime($bid)
{
global $PAGE;
$ma='application/vnd.wap.xhtml+xml';
$mb='application/xhtml+xml';
$mc='text/html';
$md='text/vnd.wap.wml';
$me='application/vnd.wap.wmlc';
$ac=$_SERVER['HTTP_ACCEPT'];
if($bid=='xhtml')
{
if(strpos($ac,$ma)!==false)
 $mime=$ma;
elseif(strpos($ac,$mb)!==false)
 $mime=$mb;
elseif(strpos($ac,$mc)!==false)
 $mime=$mc;
elseif(strpos($ac,$md)!==false or strpos($ac,$me)!==false)
{$mime=$md;
$PAGE['bid']='wml';}
else
 $mime=$mc;
}
else
{
if(strpos($ac,$md)!==false or strpos($ac,$me)!==false)
 $mime=$md;
else
{
$PAGE['bid']='xhtml';
if(strpos($ac,$ma)!==false)
 $mime=$ma;
elseif(strpos($ac,$mb)!==false)
 $mime=$mb;
elseif(strpos($ac,$mc)!==false)
 $mime=$mc;
else
{$PAGE['bid']='wml';
$mime=$md;}
}
}
return $mime;
}
static function getpagecss($bid)
{
global $PAGE;
include CLASS_DIR.'/pagehead/css/'.$bid.'.inc.php';
return $pagecss;
}
static function gz_start()
{
global $PAGE;
if(strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip')!==false)
 $PAGE['gzip']=true;
include FUNC_DIR.'/page_gzip.func.php';
ob_start('page_gzip');
}
static function gz_stop()
{
global $PAGE;
$PAGE['gzip']=false;
ob_end_clean();
}
static function refresh($time=3,$url='')
{
global $PAGE;
if($url=='')
 $url=str_replace('&','&amp;',self::getnewurl());
if($PAGE['bid']=='xhtml')
 {
 $meta="$time; url=$url";
$PAGE['meta'].='<meta http-equiv="refresh" content="'.$meta.'"/>';
 }
else
 {
$PAGE['ontimer']=' ontimer="'.$url.'"><timer value="'.$time.'0"/';
 }
}
static function getnewurl()
{
if(strpos($_SERVER['REQUEST_URI'],'?')===false)
 $add='?';
else
 $add='&';
return preg_replace('[\?&]new=[0-9\.]*','',$_SERVER['REQUEST_URI']).$add.'new='.microtime(true);
}
static function location($url,$del=false,$addsid=true)
{
if($addsid)
{
global $PAGE;
 if(
$PAGE['u_sid']&&
substr($url,0,9)==='read.php?'&&
strpos($url,'&sid=')===false
 )
  $url.=$PAGE['u_sid'];
}
header('Location: '.str_replace('&amp;','&',$url));
$del && exit;
}
#headecho类结束
}
?>