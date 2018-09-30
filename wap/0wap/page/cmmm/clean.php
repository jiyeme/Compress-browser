[html=自动垃圾清理]
<?php
set_time_limit(600);
ini_set('memory_limit','40M');
$USER['uid']=0;
if(limit::访问('cmmm_clean',600))
 die('请十分钟后再来访问。[/html]');
$db=session::conn();
$cnt=$db->query("select count(*) as cnt from session where zu='cmmm'");
$cnt=$cnt->fetch(db::ass);
$cnt=$cnt['cnt'];
$deljc=0;
$dcljc=0;
$ejc=0;
$cjc=0;
for($ii=0;$ii<=$cnt;$ii+=10)
{
$s=new session(0,'cmmm',0,"order by id desc limit {$ii},10");
foreach($s as $n=>$v)
{
$cjc++;
$e=new session(0,'cmmm.'.$n,0,'order by id desc');
$jc=count($e);
foreach($e as $m=>$u)
 {
$ejc++;
if(!trim($u['title']))
 $urljc=-1;else
 $urljc=count($u['url']);
foreach($u['url'] as $x)
  {
if(!preg_match('!10086\.cn!i',$x['url']))
 $urljc--;
  }
if($urljc<=0)
  {
unset($e[$m]);
$jc--;
$deljc++;
  }
 }
if(0==$jc)
 {
unset($s[$n]);
$dcljc++;
 }
}
}
echo "已清理{$deljc}个空资源（共{$ejc}），{$dcljc}个空分类（共{$cjc}）";
?>
[/html]