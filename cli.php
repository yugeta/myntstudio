<?php

date_default_timezone_set('Asia/Tokyo');

//CLIの場合argvをREQUESTに変換する。
if(!isset($_SERVER['SCRIPT_URI']) && isset($argv)){
    for($i=0,$c=count($argv);$i<$c;$i++){
        if(!$argv[$i]){continue;}
        //各クエリの分解
        $q = explode("=",$argv[$i]);
        if(count($q)<2){continue;}
        if($q[0]!=''){
            //requestに格納
            $key = $q[0];
            $val = join("=",array_slice($q,1));
            $_REQUEST[$key]=$val;
        }
    }
}

new CLI();
class CLI{

    public $data_path = "data/contents/domain/";
    //public $current_reflexive = 0;
    public $max_reflexive = 1;

    //public $ext_target = "pdf";

    function __construct(){

        if(!preg_match("@^http@",$_REQUEST['url'])){die("Error: ".$_REQUEST['url']);return;}

        $this->getPage($_REQUEST['url'],0);
    }

    function getPage($url,$reflexive){
        $pageFormat = $this->checkFormat($url);
        if($pageFormat){return;}

        //path情報
        $page_url_info = $this->getUrlInfo($url);

        $date = date("Ymd");

        //データフォルダ作成

        if(!is_dir($this->data_path)){
            mkdir($this->data_path,0777,true);
        }

        $source = file_get_contents($url);

        preg_match_all("@<a (.*?)href.*?=['|\"| *?](.*?)['|\"](.*?)>(.*?)</a>@i",$source,$href);


        // currentPage
        unset($res);
        exec("grep '".$date.",".$url.","."' ".$this->data_path.$page_url_info['domain'], $res);
        if(!count($res)){
            preg_match("@<title>(.*?)</title>@i",$source,$title);
            $line = $date.",".$url.",".$this->checkTagImg($title[1],$page_url_info).",";
            file_put_contents($this->data_path.$page_url_info['domain'], $line."\n", FILE_APPEND);
            echo $line."\n";
        }

        for($i=0;$i<count($href[2]);$i++){
            //リンク
            $link = $this->getLink($href[2][$i],$page_url_info);

            if(!$link){continue;}

            $format = $this->checkFormat($link);
            $info = $this->getUrlInfo($link);

            //echo $reflexive." : ".$link."\n";



            // [1:ymd , 2:url , 3:pdf]
            if($format){
                $line = $date.",".$url.",".$this->checkTagImg($href[4][$i],$page_url_info).",".$link.",";
                file_put_contents($this->data_path.$info['domain'], $line."\n", FILE_APPEND);
                echo $line."\n";
                continue;
            }
            else{
                $line = $date.",".$link.",".$this->checkTagImg($href[4][$i],$page_url_info).",";
            }

            unset($res);
            //exec("grep -x '".$line."' ".$this->data_path.$info['domain'], $res);
            exec("grep '".$date.",".$link.","."' ".$this->data_path.$info['domain'], $res);
            if(count($res)>0){continue;}

            file_put_contents($this->data_path.$info['domain'], $line."\n", FILE_APPEND);
            echo $line."\n";

            //再帰処理
            if($reflexive < $this->max_reflexive && $info['domain']==$page_url_info['domain']){
                $this->getPage($link, $reflexive+1);
            }
        }
    }


    function getUrlInfo($url=null){

        //query
        $url_sp = explode("?",$url);
        if(!isset($url_sp[1])){$url_sp[1]="";}
        $query = $url_sp[1];

        //protocol
        $protocol = $this->getProtocol($url_sp[0]);

        //domain
        $domain = $this->getDomain($url_sp[0]);

        //path
        //$path0 = dirname($url);
        $path_sp = explode("/",dirname($url));
        //die(dirname($url));
        //$path = join("/",array_slice($path_sp,3));
        $path = join("/",array_slice($path_sp,3,count($path_sp)));

        //url
        if($domain){
            $uri = $url;
        }
        else{
            $uri = $protocol."://".$domain."/".$url;
        }

        // return array(
        //     "data"=>parse_url($url),
        //     "scheme"=>parse_url($url, PHP_URL_SCHEME),
        //     "url_user"=>parse_url($url, PHP_URL_USER),
        //     "url_pass"=>parse_url($url, PHP_URL_PASS),
        //     "host"=>parse_url($url, PHP_URL_HOST),
        //     "port"=>parse_url($url, PHP_URL_PORT),
        //     "path"=>parse_url($url, PHP_URL_PATH),
        //     "query"=>parse_url($url, PHP_URL_QUERY),
        //     "fragment"=>parse_url($url, PHP_URL_FRAGMENT)
        // );
        parse_str($url_sp[1],$query_data);
        $dirname = dirname($url_sp[0].PHP_EOL);
        if($dirname && !preg_match("@\/$@",$dirname)){
            $dirname .= "/";
        }
        return array(
            "uri"=>$uri,
            "url"=>$url_sp[0],
            "query"=>$url_sp[1],
            "quary_data"=>$query_data,
            "protocol"=>$protocol,
            "domain"=>$domain,
            "path"=>$path,
            "dirname"=>$dirname,
            "basename"=>basename($url_sp[0].PHP_EOL),
            "pathinfo"=>pathinfo($url_sp[0].PHP_EOL),
            "encode"=>urlencode($uri)
        );
    }
    function getProtocol($url=null){
        if(preg_match("@^https://@",$url)){
            return "https";
        }
        else{
            return "http";
        }
    }
    function getDomain($url=null){
        if(!$url){return;}

        // full-path
        if(preg_match("@^http.*?://@",$url)){
            $sp = explode("/",$url);
            return $sp[2];
        }

    }

    // ^https:// | http://
    function checkScheme($url){
        if(preg_match("@^https://@",$url)){
            return true;
        }
        else if(preg_match("@^http://@",$url)){
            return true;
        }
        else{
            return false;
        }
    }

    // a/b.html -> http://exp.com/a/b.html
    function setUrl($url,$page_url_info){
        if(preg_match("@^\.@",$url)){
            return $this->setAbsolute($url,$page_url_info);
        }
        else if(preg_match("@^javascript@i",$url)){
            return "";
        }
        else if(preg_match("@^\/@i",$url)){
            return $page_url_info['protocol']."://".$page_url_info['domain'].$url;
        }
        else{
            return $page_url_info['dirname'].$url;
        }
    }

    // ../../ -> absolutePath
    function setAbsolute($url,$page_url_info){



        $sp1 = explode("/",$url);
        $sp2 = explode("/",$page_url_info['dirname']);

        if(preg_match("@\/$@",$page_url_info['dirname'])){
            $sp2 = array_slice($sp2, 0, count($sp2)-1);
        }

        for($i=0;$i<count($sp1);$i++){
            if($sp1[$i]==".."){
                if(count($sp2)<=3){continue;}
                $sp2 = array_slice($sp2, 0, count($sp2)-1);
            }
            else if($sp1[$i]=="."){

            }
            else{
                array_push($sp2,$sp1[$i]);
            }
        }
        return join("/",$sp2);
        //return $page_url_info['dirname']." : ".$url." : ".join("/",$sp2);
    }

    function getLink($url,$page_url_info){
        if($this->checkScheme($url)){
            return $url;
        }
        else{
            return $this->setUrl($url,$page_url_info);
        }
    }

    function checkFormat($url){
        if(preg_match("@\.pdf$@i",$url)){
            return "pdf";
        }
        else if(preg_match("@\.gif$@i",$url)){
            return "gif";
        }
        else if(preg_match("@\.[jpg|jpeg]$@i",$url)){
            return "jpg";
        }
        else if(preg_match("@\.png$@i",$url)){
            return "png";
        }
        else if(preg_match("@\.avi$@i",$url)){
            return "avi";
        }
        else if(preg_match("@\.mp3$@i",$url)){
            return "mp3";
        }
        else if(preg_match("@\.bmp$@i",$url)){
            return "bmp";
        }
    }

    function checkTagImg($str,$page_url_info){
        preg_match("@^<img .*?src.*?=['|\"| *?](.*?)['|\"].*?>$@i",$str,$img);
        if(count($img)){
            return $this->setAbsolute($img[1],$page_url_info);
        }
        else{
            return $str;
        }
    }

}
