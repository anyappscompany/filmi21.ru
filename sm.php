<?php
include_once("settings.php");
include_once("db.php");
include_once("functions.php");

$map_page = 1;
$total = 1;
$sitemap_cont = "";





$num = 50;
// Извлекаем из URL текущую страницу
$page = $_GET['page'];
// Определяем общее число сообщений в базе данных
$result = mysqli_query($db, "SELECT * FROM cache");
$posts = mysqli_fetch_row($result);
//$num_rows = mysql_num_rows($result);
// Находим общее число страниц
$total_records = mysqli_num_rows($result);

$total = intval(($total_records-1)/$num)+1;
// Определяем начало сообщений для текущей страницы
$page = intval($page);
// Если значение $page меньше единицы или отрицательно
// переходим на первую страницу
// А если слишком большое, то переходим на последнюю
if(empty($page) or $page < 0) $page = 1;
  if($page > $total) $page = $total;
// Вычисляем начиная к какого номера
// следует выводить сообщения
$start = $page * $num - $num;
// Выбираем $num сообщений начиная с номера $start
$result = mysqli_query($db, "SELECT * FROM cache LIMIT $start, $num");
// В цикле переносим результаты запроса в массив $postrow
while ( $postrow[] = mysqli_fetch_array($result));
/***********************************/
$sitemap_cont .= "";
for($i = 0; $i < $num; $i++)
{
    if(strlen($postrow[$i]['kw'])>0){
$sitemap_cont .= "<a href='http://".$_SERVER['SERVER_NAME']."/video/".$postrow[$i]['kw']."' class='smkw'>".$postrow[$i]['kw']."</a><br />";
}
}
$sitemap_cont .= "";





// Проверяем нужны ли стрелки назад
if ($page != 1) $pervpage = '<a href="http://'.$_SERVER['SERVER_NAME'].'/sitemap.php?page=1"><<</a>
                               <a href="http://'.$_SERVER['SERVER_NAME'].'/sitemap.php?page='. ($page - 1) .'"><</a> ';
// Проверяем нужны ли стрелки вперед
if ($page != $total) $nextpage = ' <a href="http://'.$_SERVER['SERVER_NAME'].'/sitemap.php?page='. ($page + 1) .'">></a>
                                   <a href="http://'.$_SERVER['SERVER_NAME'].'/sitemap.php?page=' .$total. '">>></a>';

// Находим две ближайшие станицы с обоих краев, если они есть
if($page - 2 > 0) $page2left = ' <a href="http://'.$_SERVER['SERVER_NAME'].'/sitemap.php?page='. ($page - 2) .'">'. ($page - 2) .'</a> | ';
if($page - 1 > 0) $page1left = '<a href="http://'.$_SERVER['SERVER_NAME'].'/sitemap.php?page='. ($page - 1) .'">'. ($page - 1) .'</a> | ';
if($page + 2 <= $total) $page2right = ' | <a href="http://'.$_SERVER['SERVER_NAME'].'/sitemap.php?page='. ($page + 2) .'">'. ($page + 2) .'</a>';
if($page + 1 <= $total) $page1right = ' | <a href="http://'.$_SERVER['SERVER_NAME'].'/sitemap.php?page='. ($page + 1) .'">'. ($page + 1) .'</a>';

// Вывод меню
$sitemap_cont .= "<hr />".$pervpage.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$nextpage;


$page_template = str_replace("[META]", '<meta name="robots" content="noindex,follow" />', $page_template);
$page_template = str_replace("[SERVER-NAME]", $_SERVER['SERVER_NAME'], $page_template);
$page_template = str_replace("[PAGINATION]", "", $page_template);
$page_template = str_replace("[BOTTOM-ROW]", "", $page_template);
$page_template = str_replace("[CONTENT]", $sitemap_cont, $page_template);
$page_template = str_replace("[H-TITLE]", $sitemap_page_title." ".$page, $page_template);
$page_template = str_replace("[TITLE]", $sitemap_page_title." ".$page, $page_template);




?>