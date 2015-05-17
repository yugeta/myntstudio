<?php
/**
 *　webサイトのクロール処理
 */
// date_default_timezone_set('Asia/Tokyo');

new getSite();

class getSite{

    //軌道処理
    function __construct(){
        $url = "http://www.yahoo.co.jp/";
        $source = $this->getUrl($url);
        echo $source;
    }

    function getUrl($url){
        if(!$url){return;}
        return file_get_contents($url);
    }
}
