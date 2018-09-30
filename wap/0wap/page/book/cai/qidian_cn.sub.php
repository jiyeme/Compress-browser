<?php
$qi=$info['qi'];
$gong=$info['gong'];
$url=$info['url'];
$h=new httplib;
$ck=new session($info['uid'],'cookies',30*3600*24);
$ck=$ck['qidian_cn'];
for($jc=0;!$gong||$jc<$gong;$jc++)
{
$h->open(str_replace('&amp;','&',$url),10,5);
foreach($ck as $ckn=>$ckv)
{$h->cookie($ckn,$ckv['value']);}
$h->send();
$url2=$h->url();
$str=$h->response();
$str=preg_replace(array("/[\r\n]/","!<br ?/?>!"),array('',"\n"),$str);
$xml=xml_parser_create('utf-8');
xml_parse_into_struct($xml,$str,$arr);
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
if($title=='' && $nr['tag']=='CARD')
  $title=$nr['attributes']['TITLE'];
elseif($url=='' && $nr['tag']=='A' && $nr['value']=='下章')
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
$content=$text;
 }
}
if($ok=$title&&$content)
 {
if(mb_strlen($content,'utf-8')<200)
 if(strpos($str,'充值') or strpos($str,'登陆') or strpos($str,'图片'))
  $ok=false;
$ok&&addzj($qi+$jc,$title,$content);
 }
if($url)
 echo $url=str_replace('..',dirname(dirname($url2)),$url);
else
 break;
sleep(1);
}
?>