<?php
set_time_limit(3600);
//error_reporting(E_ALL);
$uid=$_GET['uid'];
$doid=$_GET['doid'];
$s=new session($uid,'book_cai',3600*24);
$info=$s[$doid];
if(!$info)
{
echo '失败！任务已过期。';
return false;
}
unset($s[$doid]);
$include=PAGE_CDIR.'/cai/'.$info['type'].'.sub.php';
 #$s[$doid]=$info;
$db=db::conn('book');
$d=str::word($info['d']);
$rs=$db->query("select zjs from ".DB_A."title where zbm='$d' limit 1");
if(!$rs or !$rs=$rs->fetch(db::ass))

{
echo '错误，小说不存在！';
exit;
}
include $include;
function addzj($zip,$tit,$tnr)
{
global $db,$rs,$d,$uid;
$zj[':tit']=$tit;
$zj[':tnr']=$tnr;
$zj[':zip']=$zip;
$zj[':zbm']=$d;
$zj[':uvm']=1;
$zj[':uid']=$uid;
$sql='insert into '.DB_A.'book(tit,tnr,zip,zbm,uvm,uid) values(:tit, :tnr, :zip, :zbm, :uvm, :uid)';
$rsii=$db->prepare($sql);
if($rsii->execute($zj) && $rs['zjs']<$zip)
 $db->exec("update title set zjs=$zip where zbm='$d'");}
?>