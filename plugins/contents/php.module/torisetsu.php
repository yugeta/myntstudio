<?php

class torisetsu extends fw_define{

    function dataLists(){
        $dataPath = "data/".$_REQUEST['plugins']."/sites.json";
        if(!is_file($dataPath)){return;}

        $lines = explode("\n",file_get_contents($dataPath));

        $html = "";

        for($i=0;$i<count($lines);$i++){
            if(!$lines[$i]){continue;}

            $json = json_decode($lines[$i],true);

            //name
            $html.= "<h3>".$json['name']."</h3>"."\n";

            //id
            $html.= "<div>ID : ".$json['id']."</div>"."\n";

            //link
            $html.= "<a href=".$json['url']." target='_blank'>".$json['url']."</a>"."\n";
        }

        return $html;
    }

}
