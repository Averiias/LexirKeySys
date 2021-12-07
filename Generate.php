<?php

include('./Config.php');

$Connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$USER_IP = $_SERVER['REMOTE_ADDR'];

$Sanitized_query = sprintf("SELECT * FROM `userdata` WHERE `USERIP`='%s'", $Connection->real_escape_string($USER_IP));


$Query = $Connection->query($Sanitized_query);
$Row = mysqli_fetch_row($Query);

if(isset($_GET['solve'])) {
    if($Row) {
        if($Row[2] == 0) {
            $Sanitized_query_2 = sprintf("UPDATE `userdata` SET `USERPASS`=1 WHERE `USERIP`='%s'", $Connection->real_escape_string($USER_IP));
            $Connection->query($Sanitized_query_2);
            header('refresh:0;url=https://'.USER_DOMAIN.'/index.html');
        }
    }
} else {
    if(!$Row) {
        $USER_KEY = md5(random_bytes(32));
        $Sanquery3 = sprintf("INSERT INTO `userdata`(`USERIP`, `USERKEY`, `USERPASS`) VALUES ('%s','%s',0)", $USER_IP, $USER_KEY);
        $Connection->query($Sanquery3);
        die(json_encode(["solve"=>USER_FINISH]));
    } else {
        if($Row[2] == 1) {
            die(json_encode(["key"=>$Row[1]]));
        } else {
        	die(json_encode(["solve"=>USER_FINISH]));
        }
    }
}
