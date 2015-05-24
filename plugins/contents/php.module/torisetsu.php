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
        $this->types  = $this->getJson("types.json");
        $this->images = $this->getJson("images.json");

        $this->items  = $this->getJson("items.json");

        if(!count($this->items)){return;}



        $html = "";

        foreach($this->items as $key=>$json){

            //$json = json_decode($items[$i],true);

            $prop_link = $libUrl->getUrl()."?menu=".$_REQUEST['menu']."&id=".$json['id'];

            //name
            $html.= "<h3><a href='".$prop_link."'>".$json['name']."</a></h3>"."\n";

            //id
            $html.= "<div>ID : ".$json['id']."</div>"."\n";

            //image
            $html.= $this->getImages($json['id']);
            $html.= "\n";

            //group
            $html.= "<div>種別 : ".$this->groups[$json['groups.json']]['name']."</div>"."\n";

            //group
            $html.= "<div>型番 : ".$json['stock_number']."</div>"."\n";

            //brand
            $html.= "<div>メーカー・ブランド : ".$this->brands[$json['brands.json']]['name']."</div>"."\n";

            //link
            $html.= "<div>リンク : ";
            $html.= "<ol>\n";
            $html.= $this->getLink($json['id']);
            //$html.= $json['url']."\n";
            //$html.= "<a href=".$json['url']." target='_blank'>".$json['url']."</a>"."\n";
            $html.= "</ol>\n";
            $html.= "</html>"."\n";

        }
        
        return $html;
    }

    function getJson($file){
        $path = "./data/contents/";
        if(!is_dir($path)){return;}

        if(!is_file($path.$file)){return;}

        $json = explode("\n",file_get_contents($path.$file));
        $data = array();
        for($i=0;$i<count($json);$i++){

            if(!$json[$i]){continue;}

            $line = json_decode($json[$i],true);

            if(isset($line['id']) && $data[$line['id']]){unset($data[$line['id']]);}

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
}
