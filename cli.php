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
    function __construct(){

        //if(!preg_match("@^[http|https]:\/\/(.*?)\/(.*?)$@",$_REQUEST['url'])){die("Error: ".$_REQUEST['url']);return;}
        if(!preg_match("@^http@",$_REQUEST['url'])){die("Error: ".$_REQUEST['url']);return;}

        //path情報

        //$page_url_info = parse_url($_REQUEST['url']);

        $page_url_info = $this->getUrlInfo($_REQUEST['url']);
        // echo $_REQUEST['url']."\n";
        // echo "Domain: ".$href[2][$i]." : ".$this->getDomain($_REQUEST['url'])."\n";
        // echo "Root: ".$href[2][$i]." : ".dirname($_REQUEST['url'])."\n";
        // print_r($page_url_info);
        // exit();


        $source = file_get_contents($_REQUEST['url']);
        //$source = file_get_contents($_REQUEST['url'],FILE_USE_INCLUDE_PATH);
        //echo date("YmdHis").": ".$_REQUEST['url']."\n";
        //print_r($source);

        preg_match_all("/<a (.*?)href.*?=['|\"| *?](.*?)['|\"](.*?)>/i",$source,$href);


        //echo join("\n",$href);
        //print_r($href[2]);
        //print_r($href);

        for($i=0;$i<count($href[2]);$i++){
            //ドメイン抽出
            //echo "Domain: ".$href[2][$i]." : ".$this->getDomain($href[2][$i])."\n";
            //$domain = $this->getDomain($href[2][$i]);
            $link = $this->getLink($href[2][$i],$page_url_info);
            echo $link."\n";
        }

        // $source = explode("\n",file_get_contents($_REQUEST['url']));
        //
        // //echo "--".join("",$source);
        //
        // $links = array();
        // for($i=0;$i<count($source);$i++){
        //     $source[$i] = str_replace("\r","",$source[$i]);
        //     preg_match_all("/<a (.*?)href=['|\"](.*?)['|\"](.*?)>/",$source[$i],$getLink);
        //     //echo $source[$i];
        //     if(!count($getLink)){continue;}
        //     print_r($getLink);
        // }

    }

    function getUrlInfo($url=null){

        //query
        $url_sp = explode("?",$url);
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

}
