<?php
$lim = 45000;

$result1 = mysqli_query($db, "SELECT kw FROM cache");





/*$dbnamedb = "user1_filmi21";
$userdb = "user1_user1";
$passdb = "EVrWrd6P";
$db = mysqli_connect("localhost",$userdb,$passdb,$dbnamedb);
mysqli_select_db($db, $dbnamedb);
mysqli_query($db, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

$result1 = mysqli_query($db, "SELECT kw FROM cache");
$total_records = mysqli_num_rows($result);*/






$filesnum = intval(($total_records - 1) / $lim) + 1;

// главный
$sm = "";
$sm .= '<?xml version="1.0" encoding="UTF-8"?>';
$sm .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

for($i=0;$i<$filesnum;$i++){
  $sm .= PHP_EOL.'<sitemap><loc>http://'.$_SERVER['SERVER_NAME'].'/sitemap'.$i.'.xml.gz</loc></sitemap>';
}

$sm .= PHP_EOL.'</sitemapindex>';
$file_name = 'sitemap.xml';
$one_file = fopen($file_name, "w");
fwrite($one_file, $sm);
fclose($one_file);

// подкарты
$map_start = "";
$map_start .= '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'.PHP_EOL;
$map_start .= '<url><loc>http://'.$_SERVER['SERVER_NAME'].'/</loc><lastmod>'.date("Y-m-d\TH:i:s+02:00").'</lastmod><changefreq>hourly</changefreq><priority>1.00</priority></url>'.PHP_EOL;
$map_end = '</urlset>';
$map_body = '';
$fn=0;
for ($i=0;$i<$total_records;$i++)
{
  $row = mysqli_fetch_array($result1);
  $map_body .= '<url><loc>http://'.$_SERVER['SERVER_NAME'].'/video/'.urlencode($row["kw"]).'</loc><changefreq>hourly</changefreq><priority>0.50</priority></url>'.PHP_EOL;
  if(($i%$lim)==0&&$i>0){
    //
    $file_name = 'sitemap'.$fn.'.xml';
$one_file = fopen($file_name,"w");
fwrite($one_file,$map_start.$map_body.$map_end);
fclose($one_file);

$fp = gzopen ($file_name.".gz", 'w9');
gzwrite ($fp, file_get_contents($file_name));
gzclose($fp);

  $fn++;
  $map_body = "";
  }
}

// запись в файл
if(strlen($map_body)>0){
$file_name = 'sitemap'.$fn.'.xml';
$one_file = fopen($file_name,"w");
fwrite($one_file,$map_start.$map_body.$map_end);
fclose($one_file);

$fp = gzopen ($file_name.".gz", 'w9');
gzwrite ($fp, file_get_contents($file_name));
gzclose($fp);
}


?>