<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CUrl
 * @param 주소 $url
 * @param 파라미터 $data
 * @return mixed
 */
function curl_get($url, $header=false, $timeout = 15)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
    //https 옵션
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($ch, CURLOPT_SSLVERSION, TRUE);
    if($header) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $g = curl_exec($ch);
    curl_close($ch);
    return $g;
}
/* End of file */
