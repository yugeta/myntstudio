<?php

class MYNT_LOGIN extends MYNT{
	/*==========
	　初期設定
	==========*/

	//カラムマスター
	// public $session_array = array("flg","service","auth","id","name","password","mail","img");

	/*==========
	　認証処理
	==========*/

	// //authorize [return : boolean]
	// function auth($mode=""){
	//
	// 	$openid  = new OPENID();
	// 	$account = new ACCOUNT();
	// 	$url     = new MYNT_URL();
	//
	// 	//ログイン処理
	// 	if($mode=='login'){
	// 		$this->setLogin($_REQUEST['id'],$_REQUEST['pw']);
	// 	}
	// 	//ログアウト
	// 	else if($mode=='logout'){
	// 		$this->delSessionData();
	// 		$this->setLogout($_REQUEST['id'],$_REQUEST['pw']);
	//
	// 	}
	// 	//アカウント登録
	// 	else if($mode=='regist'){
	//
	// 		$account->new_regist($_REQUEST['action'],"",$_REQUEST['data']);
	// 	}
	// 	//Open-ID認証
	// 	else if($mode=='openid'){
	//
	// 		//認証サイトからの返信
	// 		if($_REQUEST['action']){
	//
	// 			//セッションID無し（期限切れ等）
	// 			if(!$_REQUEST['session_id'] || $_REQUEST['session_id'] != session_id()){
	// 				//リダイレクト処理
	// 				header("Location: ".$url->getUrl());
	// 			}
	//
	// 			//認証成功（管理専用※返答値確認）
	// 			if($_REQUEST['check']){
	// 				$keys = array_keys($_REQUEST);
	// 				$a="";
	// 				for($i=0;$i<count($keys);$i++){
	// 					$a.= "<h4 style='color:red;'>".$keys[$i]."</h4>\n".$_REQUEST[$keys[$i]]."\n";
	// 				}
	// 				//$b = "--\n".file_get_contents($_REQUEST['openid_claimed_id'])."&"."\n--\n";
	// 				$b = "";
	//
	// 				$GLOBALS['view']['html'] = "<pre>".$a.$b."</pre>";
	// 				//echo "OK<br>\n";
	// 				$template = new template();
	// 				echo $template->file2HTML(_SYSTEM."/"._COMMON."/tpl/common.html");
	// 				exit();
	// 			}
	// 			//認証成功->ログイン
	// 			else{
	//
	// 				//openidのIDを取得
	// 				$id   = $openid->getReturnData($_REQUEST['service'],"id");
	// 				$mail = $openid->getReturnData($_REQUEST['service'],"mail");
	// 				//die($_REQUEST['service']." / ".$id." / ".$mail);
	//
	// 				//登録済みチェック
	// 				if(!$account->checkAccountID($_REQUEST['service'],$id,"")){
	// 					//未登録の場合（新規登録 id:アカウント pw:null openid:[google,facebook,twitter]）
	// 					$account->setAccount(array("0",$_REQUEST['service'],"",$id,"","",$mail));
	// 				}
	// 				//セッション情報の登録(SESSION-IDが登録されていると、認証済みと判断される。)
	// 				$_SESSION['id'] = $id;
	// 				$_SESSION['service'] = $_REQUEST['service'];
	// 				$_SESSION['mail'] = $mail;
	//
	// 				//cookie-time処理
	// 				if($_REQUEST['cookie_time']){
	// 					$CookieInfo = session_get_cookie_params();
	// 					setcookie( session_name(), session_id(), time() + $_REQUEST['cookie_time'] , $CookieInfo['path'] );
	// 				}
	//
	// 				//リダイレクト処理
	// 				header("Location: ".$url->getUrl());
	// 			}
	//
	// 		}
	// 		//認証サイトへ遷移
	// 		else{
	// 			$openid->services($_REQUEST['service'],$_REQUEST['check']);
	// 		}
	// 	}
	// 	//認証済み
	// 	else if(isset($_SESSION['id']) && $_SESSION['id'] && $_COOKIE['PHPSESSID']){
	//
	// 		if(!$_REQUEST['p'] && $GLOBALS['sys']['config']['default_plugin']){
	// 			$_REQUEST['p'] =  $GLOBALS['sys']['config']['default_plugin'];
	// 		}
	//
	// 	}
	// 	//未認証（ログイン前）
	// 	else{
	// 		$_REQUEST['m'] = "login";
	// 	}
	// }


	// /*==========
	// 　ログイン処理
	// ==========*/
	// function setLogin(){
	//
	// 	$url = new MYNT_URL;
	//
	// 	// Session-Start
	// 	session_name($GLOBALS["config"]["define"]["session_name"]);
	// 	session_start();
	//
	// 	$flg = false;
	//
	// 	// Log-in
	// 	if(isset($_REQUEST["login"]) && $_REQUEST["login"]==="login"){
	// 		if($this->checkLogin($_REQUEST["login_id"] , $_REQUEST["login_pw"])){
	// 			$_SESSION["login_id"] = $_REQUEST["login_id"];
	// 		}
	// 		else if(isset($_SESSION["login_id"])){
	// 			unset($_SESSION["login_id"]);
	// 		}
	// 		header("Location: ".$url->getDir());
	// 	}
	//
	// 	// Log-out
	// 	else if(isset($_REQUEST["login"]) && $_REQUEST["login"]==="logout"){
	// 		//unset($_SESSION["login_id"]);
	// 		$_SESSION = array();
	// 		session_destroy();
	// 		header("Location: ".$url->getDir()."admin.php");
	// 	}
	//
	// 	//auth
	// 	else if($this->checkAuth()){
	// 		$flg = true;
	// 	}
	// 	return $flg;
	// }

	// Authorize
	function checkAuth(){
		if(isset($_SESSION["login_id"]) && $_SESSION["login_id"]!==""){
			return true;
		}
		else{
			return false;
		}
	}
	// //ログイン処理※errorの場合は、対象文言を保存する。
	// function setLogin($id="",$pw=""){
	//
	// 	//未入力
	// 	if(!$id || !$pw){
	// 		//$GLOBALS['view']['message'] = "アカウントIDとパスワードを入力してください。";
	// 		// return array(
	// 		// 	"flg"=>false,
	// 		// 	"message"=>"IDとパスワードを入力してください。"
	// 		// );
	// 		$this->viewError("IDまたはPasswordが入力されていません。");
	// 		return false;
	// 	}
	//
	// 	//認証成功※open-idは無し
	// 	if($this->checkLogin($id,$pw)){
	// 		// return array(
	// 		// 	"flg"=>true,
	// 		// 	"action"=>"redirect"
	// 		// );
	// 		return true;
	// 	}
	//
	// 	//認証失敗
	// 	else{
	// 		//$GLOBALS['view']['message'] = "アカウントIDまたはパスワードが違います。";
	// 		// return array(
	// 		// 	"flg"=>false,
	// 		// 	"message"=>"IDまたはパスワードが違います。"
	// 		// );
	// 		$this->viewError("IDまたはPasswordが間違っています。");
	// 		return false;
	// 	}
	// }

	//Logout
	function checkLogout(){

		//セッション情報を削除
		foreach($_SESSION as $key=>$val){
			unset($_SESSION[$key]);
		}

		//リダイレクト
		$URL = new MYNT_URL();
		header("Location: ".$URL->getDir()."?html=login");

		exit();
	}

	//Login-check
	function checkLogin(){
		// if(isset($_SESSION["login_id"])){
		// 	// unset($_SESSION["login_id"]);
		// }
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
		//セッションデータ保持
		// $_SESSION["id"] = $id;

		// $this->setSessionData($id);

		//$_SESSION['nm'] = "test1";
		//$_SESSION['mail'] = "test2";
		//$this->setSessionData($id);

		//クッキー時間の書き換え
		/*
		if($_REQUEST['cookie_time']){
			$CookieInfo = session_get_cookie_params();
			setcookie( session_name(), session_id(), time() + $_REQUEST['cookie_time'] , $CookieInfo['path'] );
		}
		*/
		// //デフォルト１時間
		// if(!$_REQUEST['cookie_time']){
		// 	$_REQUEST['cookie_time'] = (60*60);
		// }
		// $CookieInfo = session_get_cookie_params();
		// setcookie( session_name(), session_id(), time() + $_REQUEST['cookie_time'] , $CookieInfo['path'] );
		//
		//
		//
		// //認証成功
		// return array(
		// 	"flg"=>true
		// );

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
		$page = new MYNT_PAGE;
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
