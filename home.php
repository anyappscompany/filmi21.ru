<?php
include_once("settings.php");
include_once("db.php");
include_once("functions.php");

$content = '<div class="row py-3">

<div class="col-md-3"><a href="http://filmi21.ru/video/%D0%B2%D0%BB%D0%B0%D1%81%D1%82%D0%B5%D0%BB%D0%B8%D0%BD%D1%8B%20%D1%85%D0%B0%D0%BE%D1%81%D0%B0%20(2019)">
                    <div class="card mb-4 box-shadow home-video" style="background: url(template/homeimages/vlastelinihaosa.jpg) 100% 100% no-repeat;background-size: cover;">
                        <div class="card-body">
                            <h5 class="home-video-title">
                                Властелины хаоса (2019)</h5>
                        </div>
                    </div></a>
                </div>

                <div class="col-md-3"><a href="http://filmi21.ru/video/%D0%B0%D0%BB%D0%B0%D0%B4%D0%B4%D0%B8%D0%BD%20(2019)">
                    <div class="card mb-4 box-shadow home-video" style="background: url(template/homeimages/aladdin.jpg) 100% 100% no-repeat;background-size: cover;">
                        <div class="card-body">
                            <h5 class="home-video-title">
                                Аладдин (2019)</h5>
                        </div>
                    </div></a>
                </div>

                <div class="col-md-3"><a href="http://filmi21.ru/video/%D0%BA%D0%B0%D0%BA%20%D0%BF%D1%80%D0%B8%D1%80%D1%83%D1%87%D0%B8%D1%82%D1%8C%20%D0%B4%D1%80%D0%B0%D0%BA%D0%BE%D0%BD%D0%B0%203%20(2019)">
                    <div class="card mb-4 box-shadow home-video" style="background: url(template/homeimages/kakpriruchitdrakona.jpg) 100% 100% no-repeat;background-size: cover;">
                        <div class="card-body">
                            <h5 class="home-video-title">
                                Как приручить дракона 3 (2019)</h5>
                        </div>
                    </div></a>
                </div>

                <div class="col-md-3"><a href="http://filmi21.ru/video/%D0%BA%D1%83%D1%80%D1%81%D0%BA%20(2019)">
                    <div class="card mb-4 box-shadow home-video" style="background: url(template/homeimages/kursk.jpg) 100% 100% no-repeat;background-size: cover;">
                        <div class="card-body">
                            <h5 class="home-video-title">
                                Курск (2019)</h5>
                        </div>
                    </div></a>
                </div>

                <div class="col-md-12">
                <div class="float-left mr-2" style="max-width:300px">';

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
$content .= $fast_links;

$content .= '</div>
                <p>Сегодня, Вам больше не нужен телевизор, чтобы наслаждаться просмотром любимых телепередач, фильмов, сериалов. Интернет наполнен множеством бесплатных веб-сайтов, которые позволяют просматривать кино онлайн. Разработанные нами алгоритмы сканируют лучшие сайты на наличие видео и предоставляют вам информацию в удобном формате.</p>
                <p>У нас Вы сможете найти в основном новые фильмы 2019 года, а также и широкий спектр полнометражных, документальных, иностранных фильмов 20-го века.<p/>
                <p>В общем, если вы являетесь поклонником сетевого бесплатного телевидения, то этот веб-сайт то, что вы искали.</p>
                </div>
            </div>';

$page_template = str_replace("[META]", '', $page_template);
$page_template = str_replace("[SERVER-NAME]", $_SERVER['SERVER_NAME'], $page_template);
$page_template = str_replace("[PAGINATION]", "", $page_template);
$page_template = str_replace("[CONTENT]", $content, $page_template);
$page_template = str_replace("[H-TITLE]", "<h4>".$home_h_text."</h4>", $page_template);
$page_template = str_replace("[TITLE]", $global_site_title." - ".$_SERVER['SERVER_NAME'], $page_template);
?>