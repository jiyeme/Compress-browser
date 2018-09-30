<?php
ini_set('memory_limit','20M');
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$eid=$_REQUEST['eid'];
$s=new session ($USER['uid'],'text',30*24*3600,array($eid));
$sset=new session($USER['uid'],'set',0,array('edit'),false);
if(!isset($s[$eid]))
{
?>
[html=编辑器错误]
数据库说：“对不起，我找不到你要编辑的文字了。可能是因为你很久没用它，所以被我删除了。[br]
如果你愿意，你可以[read=,fat32,newtext&amp;u=<%=urlencode('read.php?[%%u.b]&[%%u.c]&[%%u.p]')%>]点击此处[/read]新建一段文字。[br]或者，去[read=,index,]首页[/read]逛逛。[br]祝你心情娱快，再见。”[hr][read=,bbs,]论坛[/read]-[read=,bbs,liuyan]留言[/read][br][time]
[hr][foot][/html]
<?php
exit;
}
$e=$s[$eid];
$strlen=mb_strlen($e['value'],'utf-8');
if(!isset($sset['edit']))
 $sset['edit']=$set=array(
'br'=>'',
'nbsp'=>'',
'text'=>false,
'duan'=>800,
'kuang'=>100,
'hanghao'=>true,
'less'=>false,
'qqmode'=>false,
);
else
 $set=$sset['edit'];
if($jc=$_POST['editcount']){
$off=$_POST['editoffset'];
$len=$_POST['editlength'];
for($i=0,$nr='';$i<$jc;$i++)
 {
$nri=$_POST["editv$i"];
if($set['qqmode'])
{
$leni=mb_strlen($nri,'utf-8');
if(mb_substr($nri,0,1,'utf-8')=='[xor]')
{$nri=mb_substr($nri,1,$leni,'utf-8');$leni--;}
if(mb_substr($nri,$leni-1,1,'utf-8')=='$')
 $nri=mb_substr($nri,0,$leni-1,'utf-8');
}
$nr.=$nri; 
 }
if($set['br']!='')
 $nr=str_replace($set['br'],"\n",$nr);
if($set['nbsp']!='')
 $nr=str_replace($set['nbsp'],' ',$nr);
$e['offset']+=mb_strlen($e['value']=mb_substr($e['value'],0,$off,'utf-8').$nr.mb_substr($e['value'],$off+$len,$strlen,'utf-8'),'utf-8')-$strlen;
$s[$eid]=$e;
}
if($_REQUEST['editsubmit'])
 headecho::location($e['url'] ? $e['url'] : 'read.php?[%%u.b]&cid=fat32&pid=text_tool&eid='.$eid.$PAGE['u_sid'],true);
if($_REQUEST['edittool'])
 headecho::location("read.php?bid=$PAGE[bid]&cid=fat32&pid=text_tool&eid=$eid".$PAGE['u_sid'],true);
$goto=$_REQUEST['editgoto'];
if($_REQUEST['edittoduan'])
 $e['offset']=($goto-1)*$set['duan'];
elseif($_REQUEST['edittoword'])
 $e['offset']=$goto;
elseif($_REQUEST['edittohang'])
 $e['offset']=str::npos($e['value'],"\n",$goto-1,'utf-8');
elseif($_REQUEST['editnext'])
 $e['offset']+=$set['duan'];
elseif($_REQUEST['editbefore'])
 $e['offset']-=$set['duan'];
#超限处理
$strlen=mb_strlen($e['value'],'utf-8');
if($e['offset']>$strlen)
 $e['offset']=$strlen-$set['duan']/2;
if(!$e['offset'])
 $e['offset']=0;
if($e['offset']<0)
 $e['offset']=0;
$s[$eid]=$e;
$nr=$e['value'];
$off=$e['offset'];
$len=mb_strlen($nr,'utf-8');
$duan=floor($len/$set['duan'])+1;
$iduan=floor($off/$set['duan'])+1;
$hang=substr_count($nr,"\n")+1;
$ihang=substr_count(mb_substr($nr,0,$off,'utf-8'),"\n")+1;
$nr=mb_substr($nr,$off,$set['duan'],'utf-8');
$count=ceil($set['duan']/$set['kuang']);
?>
[html=<%=code::html($e<('title')>)%>-编辑]
<?php if($less=!$set['less']) { ?>
[form=post,read.php?[u.b]&amp;cid=edit&amp;pid=edit&amp;eid=<%=$eid%>[u.sid]]
不保存，并转到[br]
<?php } ?>
[read=,edit,edit&amp;eid=<%=$eid%>&amp;editnext=1[u.sid]]下段[/read]
[read=,edit,edit&amp;eid=<%=$eid%>&amp;editbefore=1[u.sid]]上段[/read]
<%="($iduan/$duan)"%>[br]
<?php if($less) { ?>
[input=editgoto,2][/input]
[submit=edittoword]跳字[/submit][anchor=post,跳字,read.php?[u.b]&amp;cid=edit&amp;pid=edit&amp;eid=<%=$eid%>[u.sid]][pst=editgoto][post=edittoword]1[/post][/anchor](<%="$off/$len"%>)
[submit=edittoduan]跳段[/submit][anchor=post,跳段,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;eid=<%=$eid%>[u.sid]][pst=editgoto][post=edittoduan]1[/post][/anchor](<%="$iduan/$duan"%>)
[submit=edittohang]跳行[/submit][anchor=post,跳行,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;eid=<%=$eid%>[u.sid]][pst=editgoto][post=edittohang]1[/post][/anchor](<%="$ihang/$hang"%>)
<?php } ?>
[br][url=<%=code::html($_SERVER<('REQUEST_URI')>)%>]刷新[/url]-[url=<%=$e<('url')> ? code::html($e<('url')>) : 'read.php?bid='.$PAGE<('bid')>.'&amp;cid=fat32&amp;pid=text_tool&amp;eid='.$eid.$PAGE<('u_sid')>%>]返回来源页[/url][br]
[read=,edit,set&amp;eid=<%=$eid%>]设置[/read]-[read=,fat32,text_tool&amp;eid=<%=$eid%>]编辑器工具[/read]
<?php
if($less)
 echo '[/form]';
?>
[hr]
[form=post,read.php?[u.b]&amp;cid=edit&amp;pid=edit&amp;eid=<%=$eid%>[u.sid]]
<?php
for($ii=0,$ioff=0,$iihang=$ihang,$iioff=$off;$ii<$count;$ii++,$ioff+=$set['kuang'])
{
if($set['hanghao'])
 echo "($iihang,$iioff)";
$iioff+=mb_strlen($inr=mb_substr($nr,$ioff,$set['kuang'],'utf-8'),'utf-8');
$iihang+=substr_count($inr,"\n");
if($set['br']!='')
 $inr=str_replace("\n",$set['br'],$inr);
if($set['nbsp']!='')
 $inr=str_replace(' ',$set['nbsp'],$inr);
$inr=code::html($inr,false,!$set['qqmode']);
if($set['qqmode'])
 $inr='[xor]'.$inr.'$';
[isxhtml]
if($set['text'])
 echo '<textarea name="editv',$ii,'">',$inr,'</textarea>';
else
[/isxhtml]
 echo '<input type="text" name="editv',$ii,'" value="',$inr,'"/>';
[iswml]$input_var.="<postfield name=\"editv$ii\" value=\"\$(editv$ii)\"/>";[/iswml]
echo '[br]';
}
[iswml]
$info_var='[post=editcount]'.$count.'[/post][post=editlength]'.$len.'[/post][post=editoffset]'.$off.'[/post]';
[/iswml]
if($less)
 echo '[hr]保存，并转到[br]';
else
 echo '[hr]';
[isxhtml]
echo '[h=editcount]',$count,'[/h][h=editlength]',$set['duan'],'[/h][h=editoffset]',$off,'[/h]';
[/isxhtml]
?>
[submit=editnext]下段[/submit][anchor=post,下段,<%=$info_u='read.php?[%%u.b]&amp;[%%u.c]&amp;[%%u.p]&amp;eid='.$eid.'[%%u.sid]'%>][post=editnext]1[/post]<%=$info_var,$input_var%>[/anchor]
[submit=editbefore]上段[/submit][anchor=post,上段,<%=$info_u%>][post=editbefore]1[/post]<%=$info_var,$input_var%>[/anchor](<%="$iduan/$duan"%>)[br]
<?php if($less) { ?>
<input type="text" name="[iswml]wml[/iswml]editgoto" size="2"/>
[submit=edittoword]跳字[/submit][anchor=post,跳字,<%=$info_u%>][post=edittoword]1[/post][post=editgoto]$(wmleditgoto)[/post]<%=$info_var,$input_var%>[/anchor](<%="$off/$len"%>)
[submit=edittoduan]跳段[/submit][anchor=post,跳段,<%=$info_u%>][post=edittoduan]1[/post][post=editgoto]$(wmleditgoto)[/post]<%=$info_var,$input_var%>[/anchor](<%="$iduan/$duan"%>)
[submit=edittohang]跳行[/submit][anchor=post,跳行,<%=$info_u%>][post=edittohang]1[/post][post=editgoto]$(wmleditgoto)[/post]<%=$info_var,$input_var%>[/anchor](<%="$ihang/$hang"%>)[br]
<?php } ?>
[submit=editsubmit]提交到来源页[/submit][anchor=post,提交到来源页,<%=$info_u%>][post=editsubmit]1[/post]<%=$info_var,$input_var%>[/anchor]
[submit=edittool]编辑器工具[/submit][anchor=post,编辑器工具,<%=$info_u%>][post=edittool]1[/post]<%=$info_var,$input_var%>[/anchor]
[submit]刷新[/submit][anchor=post,刷新,<%=$info_u%>]<%=$info_var,$input_var%>[/anchor]
[/form]
[hr][time][foot]
[/html]