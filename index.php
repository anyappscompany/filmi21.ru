<?php

include_once("settings.php");
include_once("db.php");
include_once("functions.php");

$result = mysqli_query($db, "SELECT id FROM cache");
$total_records = mysqli_num_rows($result);

$home = true;



$recent_searches_str = "";
$recent_searches_arr = array();
$result2 = mysqli_query($db, "SELECT * FROM cache ORDER BY id DESC LIMIT 5");
while($line = mysqli_fetch_array($result2)){
    $recent_searches_arr[]= "<a class='recent-searches-link' href='http://".$_SERVER['SERVER_NAME']."/".urlencode ($line['kw'])."'>".$line['kw']."</a>";
}
$recent_searches_str = implode(" | ", $recent_searches_arr);

//$page_template = str_replace("[TOP-RANDOM-RECORDS]", implode("&nbsp;", $top_random_records), $page_template);
//$page_template = str_replace("[BOTTOM-RANDOM-RECORDS]", implode("&nbsp;", $bottom_random_records), $page_template);
$page_template = file_get_contents("template/template.html");



if(is_page()){
  include_once('page.php');
}else
if(is_archive_page()){
  include_once('archive_page.php');
}else
if(is_details_page()){
  include_once('details_page.php');
}else
if(is_about_page()){
  include_once('pages/about.php');
}else
if(is_contacts_page()){
  include_once('pages/contacts.php');
}else
if(is_useragreement_page()){
  include_once('pages/useragreement.php');
}else
if(is_articles_list_page()){
  include_once('pages/articles.php');
}else
if(is_authorization_page()){
  include_once('pages/authorization.php');
}else
if(is_full_article()){
  include_once('full_article.php');
}else
if(is_my_feed_page()){
  include_once('pages/myfeed.php');
}else
if(is_liked_page()){
  include_once('pages/liked.php');
}else
if(is_sitemap()){
  include_once('sm.php');
}else
if(is_privacy_policy_page()){
  include_once('pages/privacypolicy.php');
}else
if(is_home()){
    include_once('home.php');
}else{
    $incl = TRUE;
    header("Content-Type: text/html; charset=utf-8");
    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found\r\n");
    include_once('404.php');
    exit();
}


$page_template = str_replace("[UNIQUE]", md5(uniqid(rand(),1)), $page_template);
$page_template = str_replace("[COPYRIGHT]", $copyright_text, $page_template);
$page_template = str_replace("[LANG]", $lang, $page_template);
$page_template = str_replace("[SITE-TITLE]", $site_title, $page_template);

$page_template = str_replace("[PLACEHOLDER]", $placeholder, $page_template);

$page_template = str_replace("[GLOBAL-SITE-TITLE]", $global_site_title, $page_template);
$page_template = str_replace("[GLOBAL-SITE-DESCRIPTION]", $global_site_description, $page_template);
$page_template = str_replace("[SERVER-NAME]", $_SERVER['SERVER_NAME'], $page_template);

$page_template = str_replace("[CONTACTS]", $contaxt_link_text, $page_template);
$page_template = str_replace("[SITEMAP-LINK]", $sitemap_link_text, $page_template);
  //mysql_close($db);

echo $page_template;


// генерация карты сайта
//$cur_cache_time = strtotime($row['cachetime']);
$curtime = time();
$sitemap_last_generation_time_file = "sitemap_last_generation_time.txt";

$sitemap_last_generation_time = 0;
if(file_exists ($sitemap_last_generation_time_file)){
    $sitemap_last_generation_time = file_get_contents($sitemap_last_generation_time_file);
}
//echo "<".$curtime."-".$sitemap_last_generation_time.">";

if(($curtime-$sitemap_last_generation_time) > $sitemap_generation_period){
    // сгенерить карту
    // обновить файл текущим временеим
    try{
        include_once("sitemap_generation.php");
        file_put_contents($sitemap_last_generation_time_file, $curtime);
        file_put_contents("robots.txt", "User-agent: *".PHP_EOL."Sitemap: httр://".$_SERVER['SERVER_NAME']."/sitemap.xml");
    }catch (Exception $e){
        //
    }

}

?>