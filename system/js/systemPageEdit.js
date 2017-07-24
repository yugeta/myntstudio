(function(){
	var $$ = function(){
		this.setEvent(window , "DOMContentLoaded" , $$.prototype.set);
	};

	$$.prototype.set = function(){
		var fileNameElm = document.getElementById("fileName");
		if(fileNameElm === null){return;}


		// // select-value
		// var urlData = $$.prototype.urlinfo();
		// if(typeof(urlData.query.file) !== -1 || urlData.query.file){
		// // 	document.getElementById("fileNameArea").style.setProperty("display","block","");
		// // }
		// // else{
		// 	fileNameElm.value = urlData.query.file;
		// }

		// url-change
		fileNameElm.onchange = $$.prototype.changeSelect;
	};

	$$.prototype.changeSelect = function(event){console.log(+new Date())
		var target = event.target;
		var urlData = $$.prototype.urlinfo();
		var url = urlData.url+"?p="+urlData.query.p+"&file="+target.value;
		// console.log(url);
		// alert(url);
		location.href = url;
	};

	$$.prototype.urlinfo=function(uri){
		if(!uri){uri = location.href;}
		var data={};
		//URLとクエリ分離分解;
		var query=[];
		if(uri.indexOf("?")!=-1){query = uri.split("?")}
		else if(uri.indexOf(";")!=-1){query = uri.split(";")}
		else{
			query[0] = uri;
			query[1] = '';
		}
		//基本情報取得;
		var sp = query[0].split("/");
		var data={
			url:query[0],
			dir:$$.prototype.pathinfo(uri).dirname,
			domain:sp[2],
			protocol:sp[0].replace(":",""),
			query:(query[1])?(function(q){
				var data=[];
				var sp = q.split("&");
				for(var i=0;i<sp .length;i++){
					var kv = sp[i].split("=");
					if(!kv[0]){continue}
					data[kv[0]]=kv[1];
				}
				return data;
			})(query[1]):[],
		};
		return data;
	};
	$$.prototype.pathinfo = function(p){
		var basename="",
		    dirname=[],
				filename=[],
				ext="";
		var p2 = p.split("?");
		var urls = p2[0].split("/");
		for(var i=0; i<urls.length-1; i++){
			dirname.push(urls[i]);
		}
		basename = urls[urls.length-1];
		var basenames = basename.split(".");
		for(var i=0;i<basenames.length-1;i++){
			filename.push(basenames[i]);
		}
		ext = basenames[basenames.length-1];
		return {
			"hostname":urls[2],
			"basename":basename,
			"dirname":dirname.join("/"),
			"filename":filename.join("."),
			"extension":ext,
			"query":(p2[1])?p2[1]:"",
			"path":p2[0]
		};
	};

	$$.prototype.setEvent = function(target, mode, func){
		//other Browser
		if (target.addEventListener){target.addEventListener(mode, func, false)}
		else{target.attachEvent('on' + mode, function(){func.call(target , window.event)})}
	};



	new $$();

})();
