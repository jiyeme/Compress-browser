<?php
return;
//if(!$USER['islogin'])
// return false;
if(!isset($AD['set']))
{
$ad_s=new session($USER['uid'],'set',0,array('ad'));
$AD['set']=$ad_s['ad'] or $AD['set']=array('top'=>true,'bottom'=>true,'other'=>true);
unset($ad_s);
}
if(!isset($AD['site']))
 $AD['site']='other';
//相应位置广告关闭
if(!$AD['set'][$AD['site']])
{
 unset($AD['site']);
 return;
}
else
 unset($AD['site']);
//统计
if(!isset($AD['count']))
{
$ad_s=session::conn();
$ad_s=$ad_s->query("select count(*) from session where sid=0 and zu='ad'");
if($ad_s)
 {
 $ad_s=$ad_s->fetch();
 $ad_s=$ad_s[0];
 }
$AD['count']=$ad_s-1;
unset($ad_s);
}
if($AD['count']<0)
 return;
$ad_r=mt_rand(0,$AD['count']);
$ad_s=new session(0,'ad',0,"limit $ad_r,1");
foreach($ad_s as $ad_i => $ad_r)
{
if($ad_r['html'])
 echo $ad_r['html'];
else
 echo '<br/>',$ad_r['type'],'<a href="/wap/0wap/?cid=common&amp;pid=ad_go&amp;aid=',$ad_i,'">',$ad_r['title'],'</a>';
}
unset($ad_i,$ad_r,$ad_s);
?>