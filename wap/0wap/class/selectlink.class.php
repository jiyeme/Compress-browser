<?php
class selectlink
{
var $baseurl;
var $selected;
var $option; //选项

//初始化
//参数分别是 链接基址 当前被选中的项的id
function __construct($baseurl,$selected)
{
$this->baseurl=$baseurl;
$this->selected=$selected;
}

//添加
//支持数组（给$id赋数组，省略后俩参数）。数组的结构是array(id,name,url, id,name,url, …)
function add($id,$name=null,$url=null)
{
 if(is_array($id))
 {
  $cnt=count($id);
  for($i=0;$i<$cnt;$i+=3)
  {
   $this->option[$id[$i]]=array($id[$i+1],$id[$i+2]);
  }
 }
 else
  $this->option[$id]=array($name,$url);
}

//显示
function show($分隔符=null)
{
$cnt=count($this->option);
$i=1;
foreach($this->option as $id=>$val)
{
if($id==$this->selected)
 echo $val[0];
else
 echo '<a href="',$this->baseurl,$val[1],'">',$val[0],'</a>';
if($i<$cnt)
 echo $分隔符;
}
}
//class end
}
?>