<?php
$mix = explode("/", mb_strtolower(mb_convert_encoding(urldecode($_SERVER['REQUEST_URI']), 'UTF-8', 'auto'), "UTF-8"));

if(count($mix)<3 || $mix[1]!="video"){
    header("Content-Type: text/html; charset=utf-8");
    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found\r\n");
    include_once('404.php');
    exit();
}





//print_r($mix);
$query_parts = array();
for($s=2;$s<count($mix);$s++){
    $query_parts[] = $mix[$s];
}
$video_query = trim(implode("/", $query_parts));

//$result = mysql_query("SELECT COUNT(*) FROM cache WHERE kw='".$video_query."'");
$result = mysqli_query($db, "SELECT id FROM cache");
$total_records = mysqli_num_rows($result);
if($total_records<=0){
    header("Content-Type: text/html; charset=utf-8");
    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found\r\n");
    include_once('404.php');
    exit();
}


//$video_query = mb_strtolower(urldecode($mix[2]), 'UTF-8');
$md5_string = md5($video_query);                //echo $md5_string;
$first_path_part_to_cache = $md5_string[0].$md5_string[1].$md5_string[2];
$second_path_part_to_cache = $md5_string[3].$md5_string[4].$md5_string[5];
$cache_full_path = 'cache/'.$first_path_part_to_cache.'/'.$second_path_part_to_cache.'/'.$md5_string;

//$result = mysql_query("SELECT * FROM cache WHERE kw='".mysql_real_escape_string(urldecode($mix[2]))."'");
//$num_rows = mysql_num_rows($result);
//$row=mysql_fetch_assoc($result);
$content = "";

$content .= '<div class="row text-center" id="fast-links"><div class="col-md-12 mt-3 mb-2">';
/*random links*/
$fast_links = "";

$random_items_id = array();
$random_items_query = "";
while(true){
    $rnd = rand(1, intval($total_records));
    array_push($random_items_id, $rnd);
    $random_items_id = array_unique($random_items_id);
    if(count($random_items_id)==8 || $total_records<8) break;
}
$random_items_id = array_values($random_items_id);

for($i=0; $i<count($random_items_id); $i++){
    $random_items_query .= "select id, kw from cache where id=".$random_items_id[$i];
    if($i==(count($random_items_id)-1)) break;
    $random_items_query .= " union ";
}
$result = mysqli_query($db, $random_items_query);
while ($row = mysqli_fetch_assoc($result)) {
        $fast_links .= ' <a class="fast-link" href="http://'.$_SERVER['SERVER_NAME'].'/video/'.$row['kw'].'"><i class="fas fa-dragon"></i> '.$row['kw']."</a>";
}
/*random links*/
$content .= $fast_link_text."".$fast_links;
$content .= '</div></div>';

$content .= '<div class="row text-center" id="query-text">
                <div class="col-md-12">
                    <h1 id="h1-video-name">'.$video_query.'</h1>
                </div>
            </div>';
//if($num_rows == 0){
    /*header("Content-Type: text/html; charset=utf-8");
    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found\r\n");
    include_once('404.php');
    exit();*/
    $json_result= file_get_contents("http://".$_SERVER['SERVER_NAME']."/search.php?q=".urlencode($video_query)."&json=1");
    $search_result_arr = json_decode($json_result);

    if($search_result_arr->totalvideos>0){
        $related_list = "";

        if(count($search_result_arr->related)>0){
            $content .= "<div id=\"page-content\" class=\"row\"><div class=\"col-md-8\"><div class=\"row py-1\">";
        }else{
            $content .= "<div id=\"page-content\" class=\"row py-1\">";
        }

        $items = $search_result_arr->videos;
        $count = 0;
        foreach($items as $it){
            /*$content .= '<div class="col-md-4 rounded box-shadow">
                    <h3 class="film-title">'.$it->title.'</h3>
                    <div onclick="modal_init(\''.urlencode($it->frameurl).'\', \''.urlencode($it->title).'\');" style="background-image: url(http://'.$_SERVER['SERVER_NAME'].'/'.$it->thumburl.');" class="video-thumb">        <p><small class="text-muted text-duration">'.$it->duration.'</small></p>
</div>
                    <p class="film-description">'.$it->text.'<br /><!--noindex--><a rel="nofollow noopener" target="_blank" class="green-host-link" href="'.$it->url.'">'.$it->greenhost.'</a><!--/noindex--></p>

                </div>';*/
                $content .= '<div class="col-md-4 rounded box-shadow">
                    <h3 class="film-title">'.$it->title.'</h3>
                    <div onclick="modal_init(\''.urlencode($it->frameurl).'\', \''.urlencode($it->title).'\');" style="background-image: url('.$it->thumburl.');" class="video-thumb">        <p><small class="text-muted text-duration">'.$it->duration.'</small></p>
</div>
                    <p class="film-description">'.$it->text.'<br /><!--noindex--><a rel="nofollow noopener" target="_blank" class="green-host-link" href="'.$it->url.'">'.$it->greenhost.'</a><!--/noindex--></p>

                </div>';
        /*$content .= '<div class="col-sm-6 col-md-2">
                        <div class="thumbnail" title="'.$it[0].': '.$it[5].'">
                        <img onclick="modal_init(\''.$it[6].'\', \''.str_replace(" ", "-", $row['kw'])."-".$count.'.'.$it[4].'\')" data-toggle="modal" data-target="#full-view" onerror="imageerrorloading(this)" src="'.$it[6].'" alt="'.$it[0].': '.$it[5].'" title="'.$it[0].': '.$it[5].'" class="hidden-lg hidden-md hidden-sm visible-xs">
                        <div onclick="modal_init(\''.$it[6].'\', \''.str_replace(" ", "-", $row['kw'])."-".$count.'.'.$it[4].'\')" data-toggle="modal" data-target="#full-view" class="thumbnail-div hidden-xs visible-sm visible-md visible-lg" style="background: url('.$it[7].') center; background-size: cover; "></div>
                        <div class="caption">
                            <div class="file-resolution">'.$file_resolution_text.': '.$it[1].'X'.$it[2].'</div>
                            <div class="file-size">'.$file_size.': '.$it[3].'</div>
                            <div class="file-type">'.$file_type.': '.$it[4].'</div>
                            <div class="file-name">'.$file_name.': <span class="file-name-span">'.str_replace(" ", "-", $row['kw'])."-".$count.'.'.$it[4].'</span></div>
                            <!--<noindex>--> <a target="_blank" rel="nofollow" class="download" href="http://'.$_SERVER['SERVER_NAME'].'/download.php?file='.urlencode($it[6]).'&type='.$it[4].'&name='.urlencode(mb_strtolower (str_replace(" ", "-", str_replace(" ", "-", $row['kw'])."-".$count), "UTF-8")).'">'.$download_text.'</a> <!--</noindex>-->
                        </div>
                    </div>
                </div>';
        $count++;*/
        }
        /*related*/
        //$related_list .= '<ul class="list-group list-group-flush">';
        for($r=0; $r<count($search_result_arr->related); $r++){        if($r>=$max_related_videos) break;
            //$related_list .= '<li class="list-group-item related-list-item"><a href="http://'.$_SERVER['SERVER_NAME'].'/video/'.$search_result_arr->related[$r].'">'.$search_result_arr->related[$r].'</a></li>';

// если закачка похожих фото включена $related_list .= '<div onclick="start_search(\''.urlencode($search_result_arr->related[$r]->kw).'\'); return false;" class="col-md-12 related-block mb-1"><img class="float-left mr-2" src="'.$search_result_arr->related[$r]->photo.'"/><p>'.$search_result_arr->related[$r]->kw.'</p></div>';
            $related_list .= '<div onclick="start_search(\''.urlencode($search_result_arr->related[$r]->kw).'\'); return false;" class="col-md-12 related-block mb-1"><img class="float-left mr-2" src="'.$search_result_arr->related[$r]->photo.'"/><p>'.$search_result_arr->related[$r]->kw.'</p></div>';
            //$related_list .= '<div class="col-md-12 related-block mb-1"><a href="http://'.$_SERVER['SERVER_NAME'].'/video/'.urlencode($search_result_arr->related[$r]->kw).'"><img class="float-left mr-2" src="'.$search_result_arr->related[$r]->photo.'"/><p>'.$search_result_arr->related[$r]->kw.'</p></a></div>';
        }
        //$related_list .= '</ul>';
        if(count($search_result_arr->related)>0){
            $content .= "</div></div><div class=\"col-md-4\">"."<div class=\"row\">".$related_list."</div>"."</div></div>";
        }else{
            $content .= "</div>";
        }

    // вставка в базу

    }else{
        // видео не найдены
    }
//}


$page_template = str_replace("[TITLE]", $before_title.mb_ucwords(urldecode($video_query)).$after_title, $page_template);
$page_template = str_replace("[H1]", "<h1>".$search_result_arr->query."</h1>", $page_template);
$page_template = str_replace("[META]", '', $page_template);
$page_template = str_replace("[TITLE]", "TITLE", $page_template);
$page_template = str_replace("[SERVER-NAME]", $_SERVER['SERVER_NAME'], $page_template);
$page_template = str_replace("[PAGINATION]", "", $page_template);
$page_template = str_replace("[CONTENT]", $content, $page_template);
$page_template = str_replace("[CLOSE-IFRAME-BTN]", $close_iframe_btn, $page_template);
?>