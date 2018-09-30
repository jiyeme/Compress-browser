<?php
class brw_main
{
var $brw;
var $h;
var $html;
var $info;
function __construct($type,$url,&$info)
 {
$this->h=new httplib;
$this->brw=new $type($url);
$this->info=&$info;
return true;
 }
function gethtml($url,$post=null,$cookie=null)
{
if(!preg_match('!'.chr(94).'[a-zA-Z_][a-zA-Z0-9_]*://!',$url))
 $url='http://'.$url;
$h=$this->h;
$h->open($url,10,5);
$post!==null && $h->post($post);
$cookie!==null && $h->cookie($cookie);
if(!$h->send())
 return false;
return $this->html=$h->response();
}
function gettext()
{
return array('[内容编辑中……]',$this->html);
}
function puttext($text,$title='[内容编辑中……]')
{
$brw=$this->brw;
return $brw->puttext($text,$title);
}
function exec()
 {
$cmd=$this->brw->getcmd();
if($cmd===null)
 return false;
elseif($cmd[0]=='#')
{
if($cmd[1]=='#')
 return null;
$cmd=url::realpath($this->info['link'][(int)substr($cmd,1)],$this->info['url']);
}
if(!$cmd)
 return false;
elseif(!preg_match('!'.chr(94).'[a-zA-Z_][a-zA-Z0-9_]*://!',$cmd))
  $cmd='http://'.$cmd;
$this->info['link'][0]=$this->info['url'];
$html=brw_xml::totext($this->gethtml($cmd),$this->info['link'],$title);
$this->info['url']=$this->h->url();
return $this->puttext(str_replace('http://','h t t p : / / ',$html)."\n\n出处：".preg_replace('!(.)!','\\1 ',$cmd),'[转]'.$title);
 }
}
?>