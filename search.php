<?php
set_time_limit(0);

include_once("settings.php");
include_once("db.php");

$video_query = mb_strtolower(mb_convert_encoding(urldecode($_GET['q']), 'UTF-8', 'auto'), "UTF-8");

if (preg_match("/http/iu", $video_query)) {
    die;
}

$md5_string = md5($video_query);

$first_path_part_to_cache = $md5_string[0].$md5_string[1].$md5_string[2];
$second_path_part_to_cache = $md5_string[3].$md5_string[4].$md5_string[5];
$cache_full_path = 'cache/'.$first_path_part_to_cache.'/'.$second_path_part_to_cache.'/'.$md5_string;

$dir_exist = false;
if(is_dir($cache_full_path)){
    $dir_exist = true;
}

if($dir_exist){
    if (file_exists($cache_full_path."/json.json")) {
        $file_date_time = date ("Y-m-d H:i:s", filemtime($cache_full_path."/json.json"));
        $current_date_time = date('Y-m-d H:i:s');
        $time_difference = strtotime($current_date_time) - strtotime($file_date_time);

        if($time_difference>=$cache_time_limit){
            $html = getHtmlPage($video_query);
        }else{
            //echo file_get_contents($cache_full_path."/json.json");

            if(isset($_GET['json']) && $_GET['json'] == "1"){
                echo file_get_contents($cache_full_path."/json.json");
                die;
            }

            echo "http://".$_SERVER['SERVER_NAME']."/video/".urlencode($video_query);
            die;
        }
    }else{
        die;
    }

}else{
    $html = getHtmlPage($video_query);
}

preg_match_all('#data-video="(?<vobject>.*?)" data-cid#', $html, $vobjects);

if(count($vobjects['vobject'])>0){
    mkdir('cache/'.$first_path_part_to_cache, 0777);
    chmod('cache/'.$first_path_part_to_cache, 0777);

    mkdir('cache/'.$first_path_part_to_cache.'/'.$second_path_part_to_cache, 0777);
    chmod('cache/'.$first_path_part_to_cache.'/'.$second_path_part_to_cache, 0777);

    mkdir('cache/'.$first_path_part_to_cache.'/'.$second_path_part_to_cache.'/'.$md5_string, 0777);
    chmod('cache/'.$first_path_part_to_cache.'/'.$second_path_part_to_cache.'/'.$md5_string, 0777);

    mkdir('cache/'.$first_path_part_to_cache.'/'.$second_path_part_to_cache.'/'.$md5_string."/relatedphotos", 0777);
    chmod('cache/'.$first_path_part_to_cache.'/'.$second_path_part_to_cache.'/'.$md5_string."/relatedphotos", 0777);

    $for_json = array();
    $for_json['totalvideos'] = count($vobjects['vobject']);
    $for_json['query'] = $video_query;
    $for_json['url'] = $md5_string;
    $for_json['jsonpath'] = $cache_full_path;

    $lines = array();
    for($t=0; $t<count($vobjects['vobject']); $t++){
        $line = array();
        $obj = str_replace("&quot;", "\"", $vobjects['vobject'][$t]);
        $arr = json_decode($obj);

        preg_match_all("#src=\"(?<frameurl>.*?)\"#", $arr->player->autoplayHtml, $framesrcs);

        //file_put_contents($cache_full_path."/".$md5_string."_".$t, file_get_contents("http:".$arr->thumbUrl));


        $line['frameurl'] = "http:".$framesrcs['frameurl'][0];
        //$line['thumburl'] = $cache_full_path."/".$md5_string."_".$t;
        $line['thumburl'] = "http:".$arr->thumbUrl;

        $line['title'] = strip_tags ($arr->formatted->title);
        $line['text'] = strip_tags ($arr->formatted->text);
        $line['duration'] = strip_tags ($arr->formatted->duration);
        $line['url'] = $arr->url;
        $line['greenhost'] = $arr->greenHost;
        array_push($lines, $line);
    }
    $for_json['videos'] = $lines;

    /*RELATED*/
    preg_match_all('/<div class="related-serp__item-title"(?<relatedpart>.*?)<\/div><\/a>/uis', $html, $relatedparts);
    preg_match_all('/related-serp__image_full" src="(?<relatedphoto>.*?)"/uis', $html, $relatedphotos);
    $related = array();

    for($k=0; $k<count($relatedparts[0]); $k++){
        $related_arr = array();
        $related_arr['kw'] = trim(preg_replace('/\s+/', ' ', preg_replace('/<[^>]*>/', ' ', $relatedparts[0][$k])));

        //file_put_contents($cache_full_path."/relatedphotos/".$md5_string."_".$k, file_get_contents("http:".$relatedphotos[1][$k]));
        //$related_arr['photo'] = $cache_full_path."/relatedphotos/".$md5_string."_".$k;
        $related_arr['photo'] = "http:".$relatedphotos[1][$k];

        array_push($related, $related_arr);

        //$related[] = trim(preg_replace('/\s+/', ' ', preg_replace('/<[^>]*>/', ' ', $rel))); //strip_tags($rel);
    }
    /*foreach($relatedparts[0] as $rel){
        $related[] = trim(preg_replace('/\s+/', ' ', preg_replace('/<[^>]*>/', ' ', $rel))); //strip_tags($rel);
    }*/
    $for_json['related'] = $related;
    $for_json['totalrelatedvideos'] = count($relatedparts[0]);

    $json =  json_encode($for_json);
    file_put_contents($cache_full_path."/json.json", $json);

    mysqli_query($db, "INSERT INTO cache (kw, url, createtime) VALUES ('".mysqli_real_escape_string($db, urldecode($video_query))."', '".$md5_string."', '".date('Y-m-d H:i:s')."')");

    if(isset($_GET['json']) && $_GET['json'] == "1"){
                echo file_get_contents($cache_full_path."/json.json");
                die;
    }

    echo "http://".$_SERVER['SERVER_NAME']."/video/".urlencode($video_query);
    die;

    }else{
        die;
}






function getHtmlPage($query){
    $count_rep = 0;
    start:

    if($count_rep>=5) return "";
    $yandex_page = "";

    $proxy_json = file_get_contents("http://89.47.165.45/proxies/proxies.php");
    $proxies = json_decode($proxy_json);
    $total_proxies = count($proxies);

    if($total_proxies<=10){
        $count_rep++;
        file_get_contents("http://89.47.165.45/proxies/update2.php");
        goto start;
    }

    for($i=0; $i<50; $i++){
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

    file_get_contents("http://89.47.165.45/proxies/delproxy.php?ip=".$proxies[$rand]->ip);

    }
    return $yandex_page;
}


?>
