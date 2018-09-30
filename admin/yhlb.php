<?php
$id=isset($_GET['id'])?$_GET['id']:NULL;
$m=isset($_GET['m'])?$_GET['m']:NULL;
$yes=isset($_GET['yes'])?$_GET['yes']:NULL;
if($id==null) {
echo "<title>用户列表</title><div>用户列表</div>";
$pagesize=10;
$r=mysql_query("select count(*) from `browser_users`",$db);
while($row=mysql_fetch_row($r)) {
$all=$row[0];
}
if($all>0 &&$all>$pagesize) {
$num=ceil($all/$pagesize);
}else{
$num=1;
}
$page=isset($_GET['page'])?$_GET['page']:1;
$sy=$pagesize*($page-1);
$sql="select * from `browser_users` limit $sy,$pagesize";
$rs=mysql_query($sql,$db);
while($row=mysql_fetch_assoc($rs)){
echo "<a href={$_SERVER['PHP_SELF']}?do=yhlb&id={$row['id']}>ID:{$row['id']} 用户:{$row['name']}</a><br/>";
}
if($page>1) {
$lastpage=$page-1;
echo "<a href={$_SERVER['PHP_SELF']}?do=yhlb&page={$lastpage}>上页</a>";
}
if($page<$num) {
$nextpage=$page+1;
echo "<a href={$_SERVER['PHP_SELF']}?do=yhlb&page={$nextpage}>下页</a>";
}
if($num>2&&$page!=$num) {
echo "<a href={$_SERVER['PHP_SELF']}?do=yhlb&page={$num}>尾页</a>";
}
echo "<br/>一共{$all}个用户,总{$num}页<br/>";
}elseif($id!=null){
if($m==null) {
$r=mysql_query("select * from `browser_users` where id=$id");
while($row=mysql_fetch_assoc($r)){
$xinxi=$row;
}
echo "<title>{$xinxi['name']}的用户信息</title><div>用户信息:</div>id:{$xinxi['id']}<br/>帐号:{$xinxi['name']}<br/>密码:{$xinxi['pass']}<br/>浏览:{$xinxi['num_look']}次<br/>网页:".html_size($xinxi['num_size_html'])."<br/>图片:".html_size($xinxi['num_size_pic'])."<br/><a href={$_SERVER['PHP_SELF']}?do=yhlb&id={$xinxi['id']}&m=delete>删除用户</a>";
}
if($m==delete){
if($yes==null) {
echo "<title>删除用户</title>你是否要删除ID {$id}<br/><a href={$_SERVER['PHP_SELF']}?do=yhlb&id={$id}&m=delete&yes=yes>是</a> <a href={$_SERVER['PHP_SELF']}?do=yhlb&id={$id}&m=delete&yes=no>否</a>";
}elseif($yes==yes) {
$delete=mysql_query("delete from `browser_users` where id=$id",$db);
if($delete) {
echo "用户已删除";
}elseif(!$delete){
echo "删除用户错误";
}
}elseif($yes==no) {
header("location:{$_SERVER['PHP_SELF']}?do=yhlb&id={$id}");
}
}

}