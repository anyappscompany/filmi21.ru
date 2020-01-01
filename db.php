<?php

/*$db = mysql_connect("localhost","root","");
mysql_select_db("shop_search", $db);
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $db);
$shops = array();
$result = mysql_query("SELECT * FROM shops", $db);
while ($line = mysql_fetch_array($result)) {
    $shops[$line['shop_id']] = array($line['shop_name'], $line['shop_id'], $line['shop_logo'], $line['shop_url'], $line['shop_description']);
}

mysql_close($db);*/

$dbnamedb = "user1_filmi21";
$userdb = "user1_user1";
$passdb = "EVrWrd6P";

$db = mysqli_connect("localhost",$userdb,$passdb,$dbnamedb);

mysqli_select_db($db, $dbnamedb);
mysqli_query($db, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");


?>
