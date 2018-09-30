<?php
$PAGE['gzip']=false;
header('Content-type: application/x-down-url');

$dir=str::word($_REQUEST['dir']);
$eid=str::word($_REQUEST['eid']);
if($dir=='')
{
 [%getuser]
 $s=new session($USER['uid'],'my.cmmm',0,array($eid));
}
else
 $s=new session(0,'cmmm.'.$dir,0,array($eid));
$s=$s[$eid];
echo '[';
$la=count($s['url']);
foreach($s['url'] as $i=>$u)
{
if(!preg_match('![xor][a-zA-Z0-9_\\-]*:!',$u['url']))
 {
$u['url']=preg_replace('![xor].*(file\..[[xor]\[\]]*\.[a-z]{3}).*$!','http://\\1',$u['url']);
 }
echo "('",$u['url'],"', '",
($dir ? "h".($i+1).".jpg" : $u['name']),
"', 0, 0, 0, 0, 'cmmm')";
if($i+1<$la)
 echo ', ';
}
echo ']';
?>