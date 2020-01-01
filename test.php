<?php

echo getHtmlPage("Малолетка");

function getHtmlPage($query){
    $yandex_page = "";

    $proxy_json = file_get_contents("http://89.47.165.45/proxies/proxies.php");
    $proxies = json_decode($proxy_json);
    $total_proxies = count($proxies);

    for($i=0; $i<20; $i++){
    $rand = rand(0, ($total_proxies-1));

    //echo "Используется прокси: ".$proxies[$rand]->ip.":".$proxies[$rand]->port."<br />";


    $ch = curl_init("https://yandex.ru/video/search?text=".urlencode($query)."&duration=long");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.54 Safari/537.36');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    //curl_setopt($ch, CURLOPT_PROXY, $proxy_user);
    //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy_password);
    curl_setopt($ch, CURLOPT_PROXY, $proxies[$rand]->ip.":".$proxies[$rand]->port);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER ,false);

    $yandex_page = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            //echo $yandex_page; die;
    //$pos = strpos($yandex_page, "websearch-button__text");
    if($http_status == "200"){
        return $yandex_page;
        break;
    }

    curl_close ($ch);

    }
    return $yandex_page;
}

?>