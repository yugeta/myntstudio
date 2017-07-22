<?php

class PLUGIN_LOGIN{

	// Authorize
	function checkAuth(){
		if(isset($_SESSION["login_id"]) && $_SESSION["login_id"]!==""){
			return true;
		}
		else{
			return false;
		}
	}

	//Logout
	function checkLogout(){

		//セッション情報を削除
		foreach($_SESSION as $key=>$val){
			unset($_SESSION[$key]);
		}

		//リダイレクト
		$URL = new MYNT_URL();
		header("Location: ".$URL->getDir()."?p=login");

		exit();
	}

	//Login-check
	function checkLogin(){
		if($this->setLogin($_REQUEST["login_id"] , $_REQUEST["login_pw"])){
			$_SESSION["login_id"] = $_REQUEST["login_id"];
		}
		$URL = new MYNT_URL;
		header("Location: ".$URL->getDir());
	}

	function setLogin($id="",$pw=""){

		if($id==="" || $pw===""){return;}

		//-----
		//DB検索
		//-----

		$data = false;

		//mysql
		if($GLOBALS['config']['define']['database_type']=='mysql'){
			$data = $this->checkLogin_mysql($id,$pw);
			if(!$data){return;}
		}
		//mongodb
		else if($GLOBALS['config']['define']['database_type']=='mongodb'){
			$data = $this->checkLogin_mongodb($id,$pw);
			if(!$data){return;}
		}
		//couched
		else if($GLOBALS['config']['define']['database_type']=='couchdb'){
			$data = $this->checkLogin_couchdb($$id,$pw);
			if(!$data){return;}
		}
		//file (data/)
		else{
			$data = $this->checkLogin_file($id,$pw);
			// $data = $this->checkLogin_file($id,$pw);
			// if(!$data){return;}
		}

		if($data === true){
			$_SESSION["login_id"] = $id;
		}

		return $data;
	}

	/*----------
	 ログイン DataBase Check
	----------*/

	function checkLogin_mysql($id,$pw){

	}
	function checkLogin_mongodb($id,$pw){

	}
	function checkLogin_couchdb($id,$pw){

	}
	function checkLogin_file($id="",$pw=""){

		if($id==="" || $pw===""){
			return;
		}

		// $regist = new SYSTEM_REGIST();
		$passwdFile = "data/system/users.json";

		if(!file_exists($passwdFile)){return;}

		//ユーザーデータ読み込み
		$data_users = explode("\n",file_get_contents($passwdFile));

		//unset($pw_data,$buf);

		//データ内のライン処理
		// [ 0:unique-id 1:delete-flg 2:service 3:user-id 4:password]
		$loginFlg = false;
		for($i=count($data_users)-1;$i>=0;$i--){
			$data_users[$i] = str_replace("\r","",$data_users[$i]);
			if(!$data_users[$i]){continue;}

			//ラインの文字列を分解
			$json = json_decode($data_users[$i],true);

			//アカウント判別
			if($json["id"]===$id){

				if($json["flg"]==="1"){
					break;
				}

				//論理削除フラグフラグ->on
				if($json["md5"] === md5($pw)){
					$loginFlg = true;
					break;
				}

				//パスワード保持->通常ログイン
				break;
			}
		}
		return $loginFlg;
	}

	//セッションデータ保持
	function setSessionData($id=""){

		if(!$id){return;}

		$account = new ACCOUNT();
		$_SESSION['pass_data'] = $account->getPassData($id);
		$_SESSION['user_data'] = $account->getUserData($id);

		//$_SESSION['nm']   = $data['nm'];
		//$_SESSION['mail'] = $data['mail'];

		//$_SESSION['user_data'] = $user_data;


		/*
		$_SESSION['no']   = $data['no'];
		$_SESSION['id']   = $data['id'];
		$_SESSION['name'] = $data['name'];
		$_SESSION['mail'] = $data['mail'];
		$_SESSION['service'] = $data['service'];
		$_SESSION['auth'] = $data['auth'];
		$_SESSION['img']  = $data['img'];
		*/
	}
	function delSessionData(){
		unset($_SESSION['no']);
		unset($_SESSION['id']);
		unset($_SESSION['name']);
		unset($_SESSION['mail']);
		unset($_SESSION['service']);
		unset($_SESSION['auth']);
		unset($_SESSION['img']);
	}

	function viewPageLogin(){
		$page = new PAGE;
		$source = "";
		if(!isset($_SESSION["login_id"]) || !$_SESSION["login_id"]){
			$source = $page->getPageSource("data/page/source/login.dat");
		}
		else if(isset($_REQUEST["p"]) && $_REQUEST["p"]){
			$source = $page->getPageSource("data/page/source/".$_REQUEST["p"].".dat");
		}
		else{
			$source = $page->getPageSource("data/page/source/top.dat");
		}
		return $page->changePageNewLine($source);
	}

}
