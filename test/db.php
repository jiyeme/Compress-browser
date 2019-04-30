<?php
$cdb=mysqli_connect("localhost","jysafec1_llq","jtGaSQ_eu2oe","jysafec1_sql"); 
if (mysqli_connect_errno($con)) 
{ 
    echo "连接 MySQL 失败: " . mysqli_connect_error(); 
} 
 Matrix();
function Matrix() {
        global $cdb;
        $image = imagecreatefrompng("/home/jysafec1/public_html/browser/inc/class/captcha/assets/img/bg.png");
        $black = imagecolorallocate($image, 0, 0, 0);
        $id = rand(1,22523);
        $size = 22;
        $font = '/home/jysafec1/public_html/browser/inc/class/captcha/assets/fonts/fz.otf';
        $text = "1";
        $results1 = mysqli_query($cdb ,"SELECT * FROM `captcha_matrix` WHERE `id` =$id");
        $results1 = mysqli_fetch_array($results1);
        $m1 = $results1['m1'];
        $m2 = $results1['m2'];
        $anwser = $results1['anwser'];
        $m1_ex = explode(";",$m1);
        $m2_ex = explode(";",$m2);
        $row = "";
        foreach ($m1_ex as $key => $value) {
            foreach (explode(" ",$value) as $key1 => $value1) {
                $row = $row.$value1."   ";
            }
            imagettftext($image, $size, 0, 45, 130+$key*40, $black, $font, $row);
            $row = "";
        }
        foreach ($m2_ex as $key => $value) {
            foreach (explode(" ",$value) as $key1 => $value1) {
                $row = $row.$value1."   ";
            }
            imagettftext($image, $size, 0, 307, 130+$key*40, $black, $font, $row);
            $row = "";
        }
        ob_end_clean();
        header("cache-control:no-cache,must-revalidate");
        $_SESSION['Checknum'] = $anwser;
        header('content-type: image/png');
        imagepng($image);
        imagedestroy($image);
    }

// 执行查询
/*mysqli_set_charset($con,"utf8");
$result=mysqli_query($con,"SELECT * FROM `captcha_matrix` WHERE `id` = 3");

$result= mysqli_fetch_array($result);
echo json_encode($result);
*/
mysqli_close($con);