<?php
use GDText\Box;
use GDText\Color;

  $cdb = mysqli_connect($b_set['db']['server'],$b_set['db']['user'],$b_set['db']['pass'],$b_set['db']['table']);
mysqli_set_charset($cdb,"utf8");
class Traum_captcha {

    public function Chemical() {
        global $cdb;
        $id = rand(1,10186);
        $results =  mysqli_query($cdb ,"SELECT * FROM `captcha_vcode` WHERE `id` =$id");
        $results = mysqli_fetch_array($results);
        //exit (json_encode($results));
        $url = "https://www.chemicalbook.com/CAS/GIF/".$results["cas"].".gif";
        //ob_end_clean();
        //exit($results['anwser']);
        Header("HTTP/1.1 303 See Other");
        Header("Location:$url");
        $_SESSION['Checknum'] = $results["anwser"];
        //exit($_SESSION['Checknum']);
    }
    public function History() {
        global $cdb;
        $id = rand(1,30925);
        $results1 = mysqli_query($cdb ,"SELECT * FROM `captcha_event` WHERE `id` =$id");
        $results1 = mysqli_fetch_array($results1);
        $type = $results1['y'];
        $year = $results1['d'];
        $riqi = $results1['i'];
        $info = $results1['p'];
        if ($type != "0") {
            $id = rand(1,30000);
            $results1 = mysqli_query($cdb ,"SELECT * FROM `captcha_event` WHERE `id` =$id");
            $results1 = mysqli_fetch_array($results1);
            $type = $results1['y'];
            $year = $results1['d'];
            $riqi = $results1['i'];
            $info = $results1['p'];
        }
        switch ($type) {
            case '0':
                $t = "大事件发生";
                break;
            case '1':
                $t = "人物出生";
                break;
            case '2':
                $t = "人物逝世";
                break;

            default:

                break;
        }
        $year = str_replace("前", "-", $year);
        $year = str_replace("年", "", $year);
        $month = explode("月",$riqi);
        $m = sprintf("%02d", $month[0]);
        $d = sprintf("%02d", str_replace("日","",$month[1]));
        $ttt = $t.":\n".$info;
        $width = (strlen($info) >= 189) ? 500 : 250 ;
        $im = imagecreatetruecolor(500, $width);
        $backgroundColor = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $backgroundColor);
        $box = new Box($im);
        $box->setFontFace('/home/jysafec1/public_html/browser/inc/class/captcha/assets/fonts/fz.otf');
        //受freetype版本限制，字体文件不能过大
        $box->setFontColor(new Color(0, 0, 0));
        $box->setTextShadow(new Color(0, 0, 0, 50), 0, 0);
        $box->setFontSize(28);
        $box->setLineHeight(1.5);
        $box->setBox(20, 20, 460, 460);
        $box->setTextAlign('left', 'top');
        $box->draw($ttt
        );
        $_SESSION['Checknum'] = $year.$m.$d;
        header("Content-type: image/png;");
        header("cache-control:no-cache,must-revalidate");
        imagepng($im);
        imagedestroy($im);
    }

    public function Matrix() {
        global $cdb;
        $image = imagecreatefrompng("/home/jysafec1/public_html/browser/inc/class/captcha/assets/img/bg.png");
        $black = imagecolorallocate($image, 0, 0, 0);
        $id = rand(1,22523);
        $size = 22;
        $font = '/home/jysafec1/public_html/browser/inc/class/captcha/assets/fonts/fz.otf';
        $text = "1";
        //exit("aaa");
        $results1 = mysqli_query($cdb ,"SELECT * FROM `captcha_matrix` WHERE `id` =$id");
        $results1 = mysqli_fetch_array($results1);
        //exit (json_encode($results1));
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
        //ob_end_clean();
        header("cache-control:no-cache,must-revalidate");
        $_SESSION['Checknum'] = $anwser;
        header('content-type: image/png');
        imagepng($image);
        imagedestroy($image);
    }


}