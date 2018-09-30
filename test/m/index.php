<?php 
require_once('PHPMailer/class.phpmailer.php'); //导入PHPMAILER类
//require_once('src/SMTP.php');
$mail = new PHPMailer(); //创建实例
$mail -> CharSet='utf-8'; //设置字符集
$mail -> SetLanguage('ch','PHPMailer/language/');  //设置语言类型和语言文件所在目录          
$mail -> IsSMTP(); //使用SMTP方式发送
$mail -> SMTPAuth = true; //设置服务器是否需要SMTP身份验证  
$mail -> Host = 'yl.jysafe.cn'; //SMTP 主机地址  
$mail -> Port = '25'; //SMTP 主机端口
$mail -> From = 'me@yl.jysafe.cn'; //发件人EMAIL地址
$mail -> FromName = 'me@yl.jysafe.cn'; //发件人在SMTP主机中的用户名  
$mail -> Username = 'me@yl.jysafe.cn'; //发件人的姓名  
$mail -> Password = 'CQC.cqc.0130'; //发件人在SMTP主机中的密码  
$mail -> Subject = '测试邮件的标题'; //邮件主题  
$mail -> AltBody = 'text/html'; //设置在邮件正文不支持HTML时的备用显示
$mail -> Body = '测试邮件的内容';//邮件内容做成
$mail -> IsHTML(true);  //是否是HTML邮件
$mail -> AddAddress('1690127128@qq.com','jysafe'); //收件人的地址和姓名  
$mail -> AddReplyTo('1690127128@qq.com','jysafe'); //收件人回复时回复给的地址和姓名
$mail -> AddAttachment('/home/jysafec1/public_html/yl/temp/disk_forever_k6gnzhn/1537759569_99949_unknown','t.mrp');//附件的路径和附件名称
if(!$mail -> Send()) //发送邮件  
var_dump($mail -> ErrorInfo);  //查看发送的错误信息 
///home/jysafec1/public_html/yl/temp/disk_forever_k6gnzhn/1537784055_94377_unknown