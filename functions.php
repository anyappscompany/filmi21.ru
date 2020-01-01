<?php
function is_page(){
    $mix = explode("/", $_SERVER['REQUEST_URI']);
    if(preg_match("/\/video\//", urldecode($_SERVER['REQUEST_URI']))){
        return true;
    }else{
        return false;
    }
}

function is_archive_page(){
    if(preg_match("/\/page\/[0-9]+/", $_SERVER['REQUEST_URI'])){
        return true;
    }else{
        return false;
    }
}

function is_full_article(){
    if(preg_match("/^\/art\/[a-z0-9-]+\.html$/", $_SERVER['REQUEST_URI'])){
        return true;
    }else{
        return false;
    }
}

function is_authorization_page(){
    if(preg_match("/^\/authorization.php/", $_SERVER['REQUEST_URI'])){
        return true;
    }else{
        return false;
    }
}

function is_sitemap(){
    if(preg_match("/^\/sitemap.php/", $_SERVER['REQUEST_URI'])){
        return true;
    }else{
        return false;
    }
}

function is_details_page(){
    if(preg_match("/^\/details\/[\p{L}0-9\\s]+-[0-9]+$/", $_SERVER['REQUEST_URI'])){
        return true;
    }else{
        return false;
    }
}

function is_home(){
    if($_SERVER['REQUEST_URI'] == '/'){
        return true;
    }else{
        return false;
    }
}

function is_about_page(){          //echo $_SERVER['REQUEST_URI']; die;
    if(preg_match("/^\/about\.php$/", $_SERVER['REQUEST_URI'])){
        return true;
    }else{
        return false;
    }
}
                 //
function is_contacts_page(){
    if(preg_match("/^\/contacts\.php$/", $_SERVER['REQUEST_URI'])){
        return true;
    }else{
        return false;
    }
}
function is_my_feed_page(){
    if(preg_match("/^\/myfeed\.php$/", $_SERVER['REQUEST_URI'])){
        return true;
    }else{
        return false;
    }
}
//useragreement.php
function is_useragreement_page(){
    if(preg_match("/^\/useragreement\.php$/", $_SERVER['REQUEST_URI'])){
        return true;
    }else{
        return false;
    }
}

function is_liked_page(){
    if(preg_match("/^\/liked\.php$/", $_SERVER['REQUEST_URI'])){
        return true;
    }else{
        return false;
    }
}

function is_privacy_policy_page(){
    if(preg_match("/^\/privacypolicy\.php$/", $_SERVER['REQUEST_URI'])){
        return true;
    }else{
        return false;
    }
}

function is_articles_list_page(){
    if(preg_match("/^\/articles\.php$/", $_SERVER['REQUEST_URI'])){
        return true;
    }else{
        return false;
    }
}

function mb_ucwords($str) {
$str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
return ($str);
}

function get_web_page( $url )
{
  $uagent = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14";

  $ch = curl_init( $url );

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу
  curl_setopt($ch, CURLOPT_HEADER, 0);           // не возвращает заголовки
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // переходит по редиректам
  //curl_setopt($ch, CURLOPT_ENCODING, "");        // обрабатывает все кодировки
  curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // таймаут соединения
  curl_setopt($ch, CURLOPT_TIMEOUT, 120);        // таймаут ответа
  //curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // останавливаться после 10-ого редиректа
  //curl_setopt($ch, CURLOPT_COOKIE, "SRCHD=AF=NOFORM; SRCHUID=V=2&_EDGE_V=1;&ADLT=OFF&UTC=120; _ITAB=STAB=FAV; _IFAV=COUNT=0&SEEALL=NaN;");
  //curl_setopt($ch, CURLOPT_COOKIE, "SRCHUID=V=2&dmnchg=1; SRCHD=AF=NOFORM;_EDGE_V=1;DPR=1&ADLT=OFF&_ITAB=STAB=FAV; _IFAV=COUNT=0&SEEALL=NaN;dmnchg=1");

  $content = curl_exec( $ch );
  curl_close( $ch );
  //return $header;
  return $content;
}

function words_limit($input_text, $limit = 50, $end_str = '') {
    $input_text = strip_tags($input_text);
    $words = explode(' ', $input_text); // создаём из строки массив слов
    if ($limit < 1 || sizeof($words) <= $limit) { // если лимит указан не верно или количество слов меньше лимита, то возвращаем исходную строку
        return $input_text;
    }
    $words = array_slice($words, 0, $limit); // укорачиваем массив до нужной длины
    $out = implode(' ', $words);
    return $out.$end_str; //возвращаем строку + символ/строка завершения
}
?>