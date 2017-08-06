<?php

class MYNT_DATE{
  function conv($unix){
    $y = date("Y" , $unix);	//年
  	$m = date("m" , $unix);//月
  	$d = date("d" , $unix);	//日
  	$h = date("H" , $unix);	//時
  	$i = date("i" , $unix);		//分
  	$s = date("s" , $unix);	//秒
  	$w = date("w" , $unix);	//曜日
    return array(
      "year"=>date("Y" , $unix),
      "month"=>date("m" , $unix),
      "date"=>date("d" , $unix),
      "hour"=>date("H" , $unix),
      "minute"=>date("i" , $unix),
      "second"=>date("s" , $unix)
    );
  }
  function format_ymd($unix){
    if(!$unix){return "";}
    $data = $this->conv($unix);
    return $data["year"]."/".$data["month"]."/".$data["date"];
  }
  function format_ymdhis($unix){
    if(!$unix){return "";}
    $data = $this->conv($unix);
    return $data["year"]."/".$data["month"]."/".$data["date"]." ".$data["hour"].":".$data["minute"].":".$data["second"];
  }
}
