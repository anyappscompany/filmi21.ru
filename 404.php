<?php
if(!isset($incl)){
    header("Content-Type: text/html; charset=utf-8");
    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found\r\n");
    include_once('settings.php');

}
?>
<!DOCTYPE HTML>

<html>

<head>
  <title>404</title>
</head>

<body>
<center>
    <h1><?php echo $error_404_description; ?></h1>
    <p><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>"><?php echo $error_404_home; ?></a></p>
</center>
</body>

</html>

<?php
exit();
?>