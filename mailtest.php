<?php

$account_id = "geta1972@gmail.com";

$d    = date("YmdHis");
$md5  = md5($d);
$data = $d.",".$account_id.",".md5($account_pw).",".md5($d);

$sub    = "Confirmation of registration";
$msg    = "..\r\n"."?".$md5."\r\n";
$header = "From:test@hoge.com"."\r\n";

mb_send_mail($account_id , $sub , $msg , $header);

echo "finish";
