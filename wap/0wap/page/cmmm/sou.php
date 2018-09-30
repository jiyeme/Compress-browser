[html=资源搜索]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p][u.sid]]
[input=w]<?php echo code::html($w=trim(preg_replace('!\\s+!U',' ',$_REQUEST['w']))); ?>[/input][br]
[submit]搜索[/submit]
[/form]
<?php
if($w!='')
{
echo '[hr]';
$md5=md5($w);
$db=session::conn();
//$db->exec("delete from session where zu='temp.cmmm' and timeout<".time());
$ss=new session(0,'temp.cmmm',600,array($md5));
if($info=$ss[$md5])
{
echo '你看到的是搜索结果的缓存(有效期十分钟)[br]';
$name=$info[0];
$count=$info[1];
unset($info);
}
else
{
$w=explode(' ',$w);
$c=count($w);
$sql="select id,name,zu,nr from session where zu>'cmmm' and zu<'cmmn' and nr like ? order by id desc limit 1000";
$db=session::conn();
//$tmp=new session(0,'cmmm.sou.tmp',600,'');
$rs=$db->prepare($sql);
for($i=$c;$i>0;$i--)
 {
$wi=$w[$c-$i];
$rs->execute(array('%'.$wi.'%'));
$nr=$rs->fetchall(db::ass);
foreach($nr as $nri)
  {
$info=unserialize($nri['nr']);
$zu=substr($nri['zu'],5);
$name[$nri['id']]=array($zu,$nri['name'],$info['title']);
$count[$nri['id']]+=$i;
  }
 }
arsort($count);
$ss[$md5]=array($name,$count);
}
$i=0;
$p=$_REQUEST['p'] or $p=1;
$qi=$p*20-19;
$zhi=$qi+20;
$cnt=count($count);
foreach($count as $id=>$temp)
 {$i++;
if($i<$qi) continue;
if($i>=$zhi) break;
echo $i.'. <a href="read.php?[%u.b]&amp;[%u.c]&amp;pid=info&amp;dir='.$name[$id][0].'&amp;eid='.$name[$id][1].'">'.$name[$id][2].'</a>[br]';
 }
unset($name,$count);
$la=ceil($cnt/20);
$g='<a href="read.php?[%%u.b]&amp;[%%u.c]&amp;[%%u.p]&amp;w='.urlencode($_REQUEST['w']).'&amp;go=go&amp;p=';
if($p<$la)
 echo $g,$p+1,'">下页</a> ';
if($p>1)
 echo $g,$p-1,'">上页</a> ';
echo $p,'/',$la,'页,共',$cnt,'条';
}
?>
[hr][time][foot]
[/html]