(function(){
	var $$={};

	$$.set=function(){
		var js_ua = document.getElementById("js_ua");
		if(js_ua!=null){
			js_ua.innerHTML = navigator.userAgent;
		}

		var js_cookie = document.getElementById("js_cookie");
		if(js_cookie!=null){
			var ck = document.cookie;
			if(!ck){ck="--Non-data"}
			js_cookie.innerHTML = ck;
		}

		var js_storage = document.getElementById("js_storage");
		if(js_storage!=null){
			var ls = "";
			for(var i in localStorage){
				ls += i+"="+localStorage[i]+"<br>";
			}
			if(!ls){ls="--Non-data"}
			js_storage.innerHTML = ls;
		}

		//new button
		var new_button = document.getElementById("new-button");
		if(new_button!=null){
			new_button.onclick=function(){
				var url = location.href.split("?");
				location.href = url[0]+"?menu=maintenance";
			};
		}


	};

	$$LIB.eventAdd(window,"load",$$.set);
	window.$$plugin = $$;
	return $$;
})();
