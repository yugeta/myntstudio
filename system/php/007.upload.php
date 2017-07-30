<?php

class MYNT_UPLOAD{

	public $dir_picture = "data/picture/";

	function setPost(){

		if($_REQUEST["mode"] === "picture"){
			$this->setUpload_picture();
		}

	}

	public function setUpload_picture(){
		// print_r($_FILES);return;

		// make-dir
		$this->setPictureDir();

		// upload-file
		$this->setUploadFiles();


	}

	public function setPictureDir(){
		$path = $this->dir_picture;
		if(!is_dir($path)){
			mkdir($path,0777,true);
		}
	}

	public function setUploadFiles(){
		if(!isset($_FILES["data"]["name"]) || !count($_FILES["data"]["name"])){return;}

		$currentName = date("YmdHis");

		for($i=0,$c=count($_FILES["data"]["tmp_name"]); $i<$c; $i++){
			// $baseFile = $_FILES["data"]["tmp_name"][$i];
			// $sentFile = $this->dir_picture .$_FILES["data"]["name"][$i];
			// move_uploaded_file($baseFile , $sentFile);
			$currentFileName = $currentName."-".$i;
			$this->setImageDataFile($currentFileName , $_FILES["data"]["name"][$i] , $_FILES["data"]["tmp_name"][$i]);
			$this->setImageInfoFile($currentFileName , $_FILES["data"]["name"][$i] , $_FILES["data"]["size"][$i]);
		}
		echo "finished.(".date("YmdHis").")";
		exit();
	}

	public function setImageDataFile($currentName , $fileName , $tmpData){
		$fileInfo = pathinfo($fileName);
		$baseFile = $tmpData;
		$sentFile = $this->dir_picture .$currentName .".". $fileInfo["extension"];
		move_uploaded_file($baseFile , $sentFile);
	}
	public function setImageInfoFile($currentName , $fileName , $size){
		$fileInfo = pathinfo($fileName);
		$data = array();
		$data["currentName"] = $currentName;
		$data["fileName"]    = $fileName;
		$data["extension"]   = $fileInfo["extension"];
		$data["size"]        = $size;
		$data["entry"]       = date("YmdHis");
		$data["accessIP"]    = $_SERVER["REMOTE_ADDR"];
		$jsonStr = json_encode($data);
		$jsonStr = preg_replace_callback('/\\\\u([0-9a-zA-Z]{4})/', function ($matches) {return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');},$jsonStr);
		file_put_contents($this->dir_picture .$currentName.".info" , $jsonStr);
	}

	public function viewImages(){
		$URL = new MYNT_URL;
		$currentUrl = $URL->getDir();
		$flg=0;
		if(isset($_REQUEST["lastImage"]) && $_REQUEST["lastImage"]){
			$flg=1;
		}
		$files = scandir($this->dir_picture);
		for($i=0,$c=count($files); $i<$c; $i++){
			if($files[$i] === "." || $files[$i] === ".."){continue;}
			if(!preg_match('/^(.+?)(\.info)$/', $files[$i], $m)){continue;}
			$info = json_decode(file_get_contents($this->dir_picture. $m[1].".info") , true);
			// print_r($m);

			//last-image-check
			if($flg === 1 && $_REQUEST["lastImage"] === $m[1]){$flg = 0; continue;}
			if($flg === 1){continue;}

			$path = $this->dir_picture.$info["fileName"];

			// echo "<div>".$i.":".$path."</div>".PHP_EOL;
			echo "<div class='pictures'>".PHP_EOL;
			echo "<div class='pictures_td'>".PHP_EOL;
			echo "<img src='".$currentUrl.$this->dir_picture.$m[1].".".$info["extension"]."' alt='".$info["alt"]."' data-id='".$m[1]."'>".PHP_EOL;
			echo "</div>".PHP_EOL;
			echo "</div>".PHP_EOL;
		}
		exit();
		// return "aaa";
	}
	public function getImages(){
		$URL = new MYNT_URL;
		$currentUrl = $URL->getDir();
		$flg=0;
		if(isset($_REQUEST["lastImage"]) && $_REQUEST["lastImage"]){
			$flg=1;
		}
		$files = scandir($this->dir_picture);
		$html = array();
		for($i=0,$c=count($files); $i<$c; $i++){
			if($files[$i] === "." || $files[$i] === ".."){continue;}
			if(!preg_match('/^(.+?)(\.info)$/', $files[$i], $m)){continue;}
			$info = json_decode(file_get_contents($this->dir_picture. $m[1].".info") , true);
			// print_r($m);

			//last-image-check
			if($flg === 1 && $_REQUEST["lastImage"] === $m[1]){$flg = 0; continue;}
			if($flg === 1){continue;}

			$path = $this->dir_picture.$info["fileName"];
			$html[] = "<div class='pictures'>";
			$html[] = "<div class='pictures_td'>";
			$html[] = "<img src='".$currentUrl.$this->dir_picture.$m[1].".".$info["extension"]."' alt='".$info["alt"]."' data-id='".$m[1]."' data-ext='".$info["extension"]."'>";
			$html[] = "</div>";
			$html[] = "</div>";
		}
		return join(PHP_EOL,$html);
	}


	public function removeImageFile(){
		if(!isset($_REQUEST["id"]) || !isset($_REQUEST["ext"])){return;}
		$file_img  = "data/picture/".$_REQUEST["id"].".".$_REQUEST["ext"];
		$file_info = "data/picture/".$_REQUEST["id"].".info";
		if(is_file($file_img) && is_file($file_info)){
			unlink($file_img);
			unlink($file_info);
			echo "removed";
		}
		exit();
	}

}

// new MYNT_UPLOAD();
