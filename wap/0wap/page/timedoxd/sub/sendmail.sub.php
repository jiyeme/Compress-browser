<?php
$url=$of['url'];
include '../db/u2gb.php';
$n=gb2u($of['name']);
$f="../mailtmp/$of[fname].gz";
$mail=new PHPMailer();
$mail->IsSMTP();
$mail->SetLanguage('zh_cn','language/');
$mail->Host=$of['smtp'];
$mail->SMTPAuth=$of['user'];
$mail->Username=$of['user'];
$mail->Password=$of['pass'];
$mail->From=$of['mail'];
$mail->FromName='qq.com';
$mail->CharSet="utf-8";
$mail->Encoding="base64";
$mail->AddAddress($of['tomail']);
$mail->AddAttachment($f,$n,'base64');
$mail->IsHTML=false;
$size=echosize(filesize($f));
$mail->Subject="[文件分享]".$n."($size)";
$mail->Body="你的好友 ".preg_replace('!'.'^(.['.'^@]*)(@.*)?$!','\\1',$of[user])." 通过QQ文件中转站与你分享了一个文件。
:$n
文件大小:$size
你可以在附件中下载到它
======
本邮件由 QQ文件中转站 自动发出，请不要回复
http://mail.qq.com";


ob_start();
if($mail->Send())
$zzn=6;
else
{
$error=", error='".u2gb(yhtp(ob_get_contents()))."'";
$zzn=7;
}
?>