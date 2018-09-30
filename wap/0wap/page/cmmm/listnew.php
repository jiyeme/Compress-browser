<?php
[%getuser]
?>
[html=最新免流资源]
[head]
<?php
$set=new session($USER['uid'],'set',0,array('page'));
$set=$set['page'];
$hang=$set['hang'] or $hang=20;
$db=session::conn();
$rs=$db->query("select count(*) as cnt from session where zu>'cmmm' and zu<'cmmn'");
$rs=$rs->fetch(db::ass);
$cnt=$rs['cnt'];
$p=$_GET['p'] or $p=1;
$la=ceil($cnt/$hang);
if($p<0 or $p>$la)
 $p=$la;
$qi=($p-1)*$hang;
$rs=$db->query("select name,zu,nr from session where zu>'cmmm' and zu<'cmmn' order by id desc limit $qi,$hang");
$selfuu=urlencode($_SERVER['REQUEST_URI']);
if($cnt>0)

{
echo '共',$cnt,'个资源[br][br]';
$u=$_REQUEST['u'];
$jc=$qi;
foreach($rs->fetchall(db::ass) as $val)
{
$eid=$val['name'];
$dir=substr($val['zu'],5);
$val=unserialize($val['nr']);
$fname=$val['title'];
$jc++;
echo $jc,'. [%%read=,cmmm,info&amp;dir=',$dir,'&amp;eid=',$eid,']',code::html($fname),'[/read][br]';
}
if($p<$la)
 echo '[%read=,cmmm,listnew&amp;p=',$p+1,']下页[/read]';
if($p>1)
 echo ' [%read=,cmmm,listnew&amp;p=',$p-1,']上页[/read]';
echo " $p/",$la,'页';
#如果.有文件#结束
}
else
 echo '数据库说：欢迎使用虎绿林免流资源发布系统，你可以去[%read=,cmmm,newbin&amp;dir=',$dir,']新建一免流个资源[/read]。爪机有你更精彩！';
?>
[hr]返回[read=,cmmm,]分类[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]