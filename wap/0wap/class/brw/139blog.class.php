<?php
class brw_139blog
{
var $url;
var $h;
var $cmdxml;
function __construct($url)
 {
$this->url=preg_replace(array('!&amp;!','!&do=.*&!U','!&tpl=.*&!U'),'&',$url).'&tpl=wml20&do=';
$this->h=new httplib;
return true;
 }
function puttext($content,$title='[139博客浏览器]',$status='1',$category='10',$tags='')
{
$url=$this->url.'Text.editPost';
$h=$this->h;
$h->open($url,5,0);
$this->mixip();
$h->post(array(
 'title'=>$title,
 'content'=>$content,
 'category'=>$category,
 'status'=>$status,
 'tags'=>$tags
 ));
if(!$h->send())

 return false;
else
 return $this->cmdxml=$h->response();
}
function getcmd()
{
$url=$this->url.'Text.view&n=1&ps=1&p=1';
$h=$this->h;
if(!$this->cmdxml)
{
$h->open($url,5,0);
$this->mixip();
$h->send();
$cmdxml=$h->response();
}
else
 $cmdxml=$this->cmdxml;
//var_dump('cmd代码页：',($cmdxml));die;
$xml=xml::toarray(xml::去空白($cmdxml));
$ok=false;
$cmd=$durl=null;
foreach($xml as $i=>$v)
{
if(!$ok)
 {
 if($v['tag']=='FORM'&&$v['type']=='close')
  $ok=true;
 }
elseif($v['tag']=='DIV'&&$v['type']=='cdata'&&$v['level']==3&&preg_match('!'.chr(94).'\\s\\d+楼:(.*)\\s$!s',$v['value'],$cmdarr))
 {
 $cmd=$cmdarr[1];
 $v=&$xml[$i+1];
 if($v['tag']=='A'&&$v['value']=='删除')
  $durl=url::realpath($v['attributes']['HREF'],$url);
 break;
 }
}
if($durl!==null)
 {
 $h->open($durl,5,0);
 $this->mixip();
 $h->send();
$this->cmdxml=$h->response();
 }
else
 $this->cmdxml=null;
return $cmd;
}
function mixip($return=false)
 {
sleep(1);
/*static $jc=0;
echo $jc,'<hr>';
if($jc && !$return)
 ;//sleep(5);
else
 return $jc;
$jc++;
$ip=mt_rand(173,191).'.'.mt_rand(0,255).'.'.mt_rand(0,255).'.'.mt_rand(0,255);
$this->h->header('X_FORWARDED_FOR',$ip);*/
 }
}
?>