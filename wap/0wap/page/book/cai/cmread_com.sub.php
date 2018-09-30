<?php
function 取cid($url)
{
$url=url::getqueryarray($url,true);
return $url['cid'];
}
$qi=$info['qi'];
$gong=$info['gong'];
$url=$info['url'];
$h=new httplib;
for($jc=0;!$gong||$jc<$gong;$jc++)
{
$url=str_replace('&amp;','&',$url);
if(!$cid=取cid($url))
 break;
$url="http://wap.cmread.com/iread/wml/l/setWordCnt.jsp?bid=348697998&cid={$cid}&t=0";
$h->open($url,10,5);
$h->send();
$url2=$h->url();
$str=$h->response();
$str=xml::去空白($str);
$arr=xml::toarray($str);
if(!is_array($arr))
 break;
$title='';
$content='';
$cnt=array();
$url='';
$all=count($arr);
for($ii=0;$ii<$all;$ii++)
{
$nr=$arr[$ii];
if($url=='' && $nr['tag']=='A' && $nr['value']=='下章')
  $url=$nr['attributes']['HREF'];
elseif($nr['type']=='cdata')
  $cnt[]=$nr['value'];
}
$maxlen=0;
foreach($cnt as $text)
{
$len=strlen($text);
if($len>$maxlen)
 {
$maxlen=$len;
$content=trim($text);
 }
}
$content=substr($content,0,strrpos($content,"\n"));
echo $title=substr($content,6,strpos($content,"\n")-6);
if($ok=$title&&$content)
 {
if(mb_strlen($content,'utf-8')<200)
 if(strpos($str,'充值') or strpos($str,'登陆') or strpos($str,'完结'))
  $ok=false;
$ok&&addzj($qi+$jc,$title,$content);
 }
/*if($url)
 echo $url=str_replace('..',dirname(dirname($url2)),$url);
else*/
echo $url;
if(!$url or strpos($url,'readbook.jsp')===false)
 break;
sleep(1);
}
?>