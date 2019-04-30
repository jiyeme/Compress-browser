<?php
/*
 * 使用http接口请求数据
 * 可以在$url按格式指定协议与端口 https://domain.com:443/path?querystring
 * 返回array
 * $method = 'PROXY'时,$post=" GET url  HTTP/1.0\r\n",也就是代理请求的方法自己设置
 */
function request($url = '', $post = null /* 数组*/, $method = 'GET', $header = null, $timeout = 20 /* ms */) {
    if (empty($url)) return array('error' => 'url必须指定');
    $url = parse_url($url);
    $method = strtoupper(trim($method));
    $method = empty($method) ? 'GET' : $method;
    $scheme = strtolower($url['scheme']);
    $host = $url['host'];
    $path = $url['path'];
    empty($path) and ($path = '/');
    $query = $url['query'];
    $port = isset($url['port']) ? (int)$url['port'] : ('https' == $scheme ? 443 : 80);
    $protocol = 'https' == $scheme ? 'ssl://' : '';
    echo $protocol.$host.$port;
    if (!$res = fsockopen($protocol.$host, (int)$port, $errno, $errstr, (int)$timeout)) {
        return array('error' => mb_convert_encoding($errstr, 'UTF-8', 'UTF-8,GB2312'), 'errorno' => $errno);
    } else {
        $crlf = "\r\n";
        $commonHeader = $method == 'PROXY' ? array() : array(
            'Host' => $host
            ,'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; rv:16.0) Gecko/20100101 Firefox/16.0'
            ,'Content-Type' => 'POST' == $method ? 'application/x-www-form-urlencoded' : 'text/html; charsert=UTF-8'
            ,'Connection' => 'Close'
        );                
        is_array($header) and ($commonHeader = array_merge($commonHeader, $header));
        
        foreach ($commonHeader as $key => & $val) {
            $val = str_replace(array("\n", "\r", ':'), '', $val);
            $key = str_replace(array("\n", "\r", ':'), '', $key);
            $val = "{$key}: {$val}{$crlf}";
        }     
        
        if ($method == 'PROXY') {
            $post = trim(str_replace(array("\n", "\r"), '', $post)).$crlf;
            
            if (empty($post)) return array('error' => '使用代理时,必须指定代理请求方法($post参数)');
        } else if (!is_array($post)) {
            $post = array();
        }
        
        switch ($method) {
            case 'POST':                        
                $post = http_build_query($post);
                $query = empty($query) ? '' : '?'.$query;
                $commonHeader[] = 'Content-Length: '.strlen($post).$crlf;
                $post = empty($post) ? '' : $crlf.$post.$crlf;
                $commonHeader = implode('', $commonHeader);
                $request = "{$method} {$path}{$query} HTTP/1.1{$crlf}"
                            ."{$commonHeader}"
                            .$post
                            .$crlf;//表示提交结束了
                break;
            case 'PROXY'://代理
                $commonHeader = implode('', $commonHeader);
                $request =  $post
                            .$commonHeader
                            .$crlf;//表示提交结束了                
                break;
            case 'GET':
            default:
                empty($query) ? ($query = array()) : parse_str($query, $query);
                $query = array_merge($query, $post);
                $query = http_build_query($query);
                $commonHeader = implode('', $commonHeader);
                $query = empty($query) ? '' : '?'.$query;
                $request =  "{$method} {$path}{$query} HTTP/1.1{$crlf}"
                            ."{$commonHeader}"
                            .$crlf;//表示提交结束了
        }

        fwrite($res, $request);
        $reponse = '';
        
        while (!feof($res)) {
            $reponse .= fgets($res, 128);
        }
        
        fclose($res);
        $pos = strpos($reponse, $crlf . $crlf);//查找第一个分隔                
        if($pos === false) return array('reponse' => $reponse);
        $header = substr($reponse, 0, $pos);
        $body = substr($reponse, $pos + 2 * strlen($crlf));       
        
        //exit($header);
         //重定向
 if(preg_match("/^HTTP\/\d.\d 301 Moved Permanently/is",$header)){
     //exit('我进来了');
  if(preg_match("/Location:(.*?)\r\n/is",$header,$murl)){
      //exit("我进来了".$murl[1]);
      $url=trim($murl[1]);
      return request($url);
  }
 }

 if(preg_match("/^HTTP\/\d.\d 302 /is",$header)){
     //exit('我进来了');
  if(preg_match("/Location:(.*?\/\/.+?)\//is",$header,$murl)){
      //exit("我进来了".$murl[1]);
      $url=trim($murl[1]);
      return request($url);
  }
 }
 //读取内容
/* if(preg_match("/^HTTP///d./d 200 OK/is",$body)){
  preg_match("/Content-Type:(.*?)/r/n/is",$body,$murl);
  $contentType=trim($murl[1]);
  $Content=explode("/r/n/r/n",$Content,2);
  $Content=$Content[1];
 }*/
 
        return array('body' => $body, 'header' => $header);
    }
}
print_r(request('https://www.wodemo.com'));