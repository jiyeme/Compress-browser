<?php
class someinput
{
static $set;
static function set($set,$size=null,$next='<br/>')
{
}
function someinput($nr,$id,$rs=true)
{
if($rs===true)
 $rs=array('meism'=>100,
'newsm'=>2,
'addsm'=>1,
'isbr'=>true,
'br'=>'[br]',
'istext'=>false);
if($rs['meism']<1)
  $rs['meism']=100;
$len=mb_strlen($nr,'utf-8');
if($nr!='') $rs['newsm']=0;
if($rs['isbr'])
  echo '<input type="hidden" name="'.$id.'isbr" value="1" />
<input type="hidden" name="'.$id.'br" value="'.bmtxt2($rs['br']).'" />';
for($off=0, $i=0; $off<$len or $rs['newsm']>0 or $rs['addsm']>0; $off+=$rs['meism'], $i++)
{
if($off>$len)
  {$rs['newsm']--; $rs['addsm']--;}
$text=bmtxt2(mb_substr($nr,$off,$rs['meism'],'utf-8'));
if($rs['isbr'])
  $text=str_replace("\n",'[br]',$text);
if($rs['istext'])
  echo "<textarea name=\"$id$i\" rows=\"2\">$text</textarea>";


else
  echo "<input name=\"$id$i\" value=\"$text\" />";
echo "<br/>\n";
}
echo "<input type=\"hidden\" name=\"$id"."count\" value=\"$i\" />";
}
function resomeinput($id)
{
$count=$_POST[$id.'count'];
$text='';
for($i=0; $i<=$count; $i++)
 {
$text.=$_POST[$id.$i];
 }
if($_POST[$id.'isbr'])
  $text=str_replace($_POST[$id.'br'],"\n",$text);
if(!$_POST[$id])
  $_POST[$id]=$text;
return $text;
}
?>