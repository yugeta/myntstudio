<?php

/**
* redirect
*/
$urls = explode("?",$_SERVER["REQUEST_URI"]);
if($urls[0] !== $_SERVER["DOCUMENT_URI"]){
	$url = $_SERVER["DOCUMENT_URI"];
	if(isset($urls[1])){$url .= "?".$urls[1];}
	header("Location: ".$url);
}
