<?php

if(isset($_REQUEST["mode"]) && $_REQUEST["mode"]=="encode"){
	// die("aa");
	//echo json_encode($_REQUEST["str"],JSON_PRETTY_PRINT);
	$data = json_encode($_REQUEST["str"]);
	$data = preg_replace_callback('/\\\\u([0-9a-zA-Z]{4})/', function ($matches) {return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');},$data);
	echo "<textarea>".$data."</textarea>";
	exit();
}
else{

}

 ?>


<html>
<head>

</head>
<body>

<form method="post">
	<input type="hidden" name="mode" value="encode">
<textarea name="str"><?php echo file_get_contents($_REQUEST['file']); ?></textarea>
<input type="submit" value="json-encode">
</form>

</body>
</html>
