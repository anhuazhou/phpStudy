<?php
header("content-type:text/html;charset=utf-8");
date_default_timezone_set('PRC');
if(!isset($_COOKIE["visit"])){
    setcookie("visit",date("y-m-d H:i:s"));
    echo "这是你第一次访问";
}else{
    setcookie("visit",date("y-m-d H:i:s"),time()+60);
    echo "你上次访问时间：".$_COOKIE["visit"];
    echo "<br>";
}
echo "当前时间：".date("y-m-d H:i:s");



?>