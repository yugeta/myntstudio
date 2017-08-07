<?php

class MYNT_URL{

	//port + domain [http://hoge.com:8800/]
	//現在のポートの取得（80 , 443 , その他）
	function getSite(){
		//通常のhttp処理
		if($_SERVER['SERVER_PORT']==80){
			$site = 'http://'.$_SERVER['HTTP_HOST'];
		}
		//httpsページ処理
		else if($_SERVER['SERVER_PORT']==443){
			$site = 'https://'.$_SERVER['HTTP_HOST'];
		}
		//その他ペート処理
		else{
			$site = 'http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'];
		}

		return $site;
	}

	//現在ページのサービスroot階層のパスを返す
	function getDir(){

		$uri = $this->getSite();
		$req = explode('?',$_SERVER['REQUEST_URI']);

		return $uri.dirname($req[0]." ")."/";
	}

	//現在のクエリ無しパスを返す
	function getUrl(){
		$uri = $this->getSite();
		$req = explode('?',$_SERVER['REQUEST_URI']);
		$uri.= $req[0];
		return $uri;
	}

	//フルパスを返す
	function getUri(){
		$uri = $this->getSite();
		if($_SERVER['REQUEST_URI']){
			$uri.= $_SERVER['REQUEST_URI'];
		}
		else{
			$uri = $this->getUrl.(($_SERVER['QUERY_STRING'])?"?".$_SERVER['QUERY_STRING']:"");
		}
		return $uri;
	}

	//基本ドメインを返す
	function getDomain(){
		return $_SERVER['HTTP_HOST'];
	}

	//リダイレクト処理
	function setUrl($url){
		if(!$url){return;}
		header("Location: ".$url);
	}

	//
	function getDesignRoot(){
		return $this->getDir()."design/".$GLOBALS["config"]["design"]["target"]."/";
	}
	function getLibraryRoot(){
		return $this->getDir()."library/";
	}
	function getPluginRoot(){
		return $this->getDir()."plugin/";
	}
	function getDataRoot(){
		return $this->getDir()."data/";
	}
	function getSystemRoot(){
		return $this->getDir()."system/";
	}

	public function getLocalDir(){
		$req = explode('?',$_SERVER['REQUEST_URI']);
		return dirname($req[0]." ")."/";
	}
	public function getLocalFilename(){
		return $_SERVER['SCRIPT_NAME'];
	}

}
