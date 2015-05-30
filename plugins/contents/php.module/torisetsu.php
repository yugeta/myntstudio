<?php

class torisetsu extends fw_define{

    function dataLists(){
        $dataPath = "data/".$_REQUEST['plugins']."/sites.json";
        if(!is_file($dataPath)){return;}

        $lines = explode("\n",file_get_contents($dataPath));

        $libUrl = new libUrl();

        $html = "";

        for($i=0;$i<count($lines);$i++){
            if(!$lines[$i]){continue;}

            $json = json_decode($lines[$i],true);

            $prop_link = $libUrl->getUrl()."?menu=".$_REQUEST['menu']."&id=".$json['id'];

            //name
            $html.= "<h3><a href='".$prop_link."'>".$json['name']."</a></h3>"."\n";

            // //id
            // $html.= "<div>ID : ".$json['id']."</div>"."\n";

            //link
            //$html.= $json['url']."\n";
            $html.= "<a href=".$json['url']." target='_blank'>".$json['url']."</a>"."\n";

        }
        return $html;
    }

    //items
    function itemLists(){

        $libUrl = new libUrl();

        $this->brands = $this->getJson("brands.json");
        $this->groups = $this->getJson("groups.json");
        $this->tags   = $this->getJson("tags.json");
        $this->sites  = $this->getJson("sites.json");
        $this->manuals= $this->getJson("manuals.json");
        $this->types  = $this->getJson("types.json");
        //$this->images = $this->getJson("images.json");

        $this->items  = $this->getJson("items.json");

        if(!count($this->items)){return;}

        $tpl_file = "plugins/".$_REQUEST['plugins']."/template/items-list.html";

        $tpl_base = file_get_contents($tpl_file);

        $html = "";

        foreach($this->items as $key=>$json){

            $tpl = $tpl_base;
            //$prop_link = $libUrl->getUrl()."?menu=".$_REQUEST['menu']."&id=".$json['id'];
            $prop_link = $libUrl->getUrl()."?menu=maintenance&id=".$json['id'];

            foreach($json as $k1=>$v1){

                //value
                $tpl=str_replace("<%value:".$k1."%>",$v1,$tpl);

                //link
                if(preg_match("/^(.*)\.json$/",$k1,$m)){
                    $tpl=str_replace("<%link:".$k1."%>",$this->{$m[1]}[$json[$k1]]['name'],$tpl);
                }

            }

            //image
            //$tpl=str_replace("<%img:src%>",$this->getImageSrc($json['id']),$tpl);
            $tpl=str_replace("<%img:src%>",$json['img'],$tpl);

            //list
            $tpl=str_replace("<%list:link%>",$this->getLink($json['id']),$tpl);

            //manual
            $tpl=str_replace("<%list:manual%>",$this->getManual($json['id']),$tpl);

            //a
            $tpl=str_replace("<%a:edit%>",$prop_link,$tpl);

            $html.= $tpl;

        }

        return $html;
    }

    function getJson($file){
        $path = "./data/contents/";
        if(!is_dir($path)){return;}

        if(!is_file($path.$file)){return;}

        $json = explode("\n",file_get_contents($path.$file));
        $data = array();
        //$cnt = count($json);
        for($i=0;$i<count($json);$i++){
        //for($i=$cnt-1;$i>=0;$i--){

            if(!$json[$i]){continue;}

            $line = json_decode($json[$i],true);

            if(isset($line['id']) && $data[$line['id']]){unset($data[$line['id']]);}
            //if(isset($line['id']) && isset($data[$line['id']])){continue;}

            if($line['flg']=="1"){continue;}
            $data[$line['id']] = $line;
        }
        return $data;
    }




    function getLink($id){
        $html="";
        foreach($this->sites as $key=>$val){
            if($val['flg']==1){continue;}
            if($val['items.json']!=$id){continue;}
            if(!$val['url'] || !$val['name']){continue;}
            $html.= "<li><a href='".$val['url']."' target='_blank'>(".$this->types[$val['types.json']]['name'].") ".$val['name']."</a></li>"."\n";
        }
        return $html;
    }
    function getManual($id){
        $html="";
        foreach($this->manuals as $key=>$val){
            if($val['flg']==1){continue;}
            if($val['items.json']!=$id){continue;}
            if(!$val['url'] || !$val['name']){continue;}
            $html.= "<li><a href='".$val['url']."' target='_blank'>".$val['name']."</a></li>"."\n";
        }
        return $html;
    }


    function getImages($id){
        $html = "";
        foreach($this->images as $key=>$val){
            if($val['flg']==1){continue;}
            if($val['items.json']!=$id){continue;}
            if(!$val['url']){continue;}
            $html.= "<img class='items' src='".$val['url']."'>"."\n";
        }
        return $html;
    }
    function getImageSrc($id){
        //$html = "";
        foreach($this->images as $key=>$val){
            if($val['flg']==1){continue;}
            if($val['items.json']!=$id){continue;}
            if(!$val['url']){continue;}
            //$html.= "<img class='items' src='".$val['url']."'>"."\n";
            return $val['url'];
        }
        //return $html;
    }
    function getID(){
        if(isset($_REQUEST['id']) && $_REQUEST['id']){
            return $_REQUEST['id'];
        }
        else{
            $_REQUEST['id'] = $this->getUniqID();
            return $_REQUEST['id'];
        }
    }
    function getUniqID(){
        return uniqid();
    }
    function getOne($jsonFile,$id){
        $file = "data/contents/".$jsonFile;
        if(!is_file($file)){return;}

        $datas = explode("\n",file_get_contents($file));
        for($i=(count($datas)-1);$i>=0;$i--){
            if(!$datas[$i]){continue;}
            $json = json_decode($datas[$i],true);
            if($json['id']==$id){return $json;}
            break;
        }
    }
    
}
