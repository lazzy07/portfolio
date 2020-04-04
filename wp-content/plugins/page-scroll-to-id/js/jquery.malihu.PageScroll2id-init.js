(function($){
	var _p="mPS2id",
		_o=mPS2id_params,
		shortcodeClass=_o.shortcode_class, //shortcode without suffix 
		_hash=location.hash || null,
		_validateLocHash=function(val,forAll){
			try{ var $val=$(val); }catch(error){ return false; } //avoid js errors on invalid selectors
			return $(val).length && (forAll || $("a[href*='"+val+"']").filter(function(){return $(this).data(_p+"Element")==true}).length);
		},
		_offset=function(val){
			if(val.indexOf(",")!==-1){
				var arr=val.split(","),y=arr[0] || "0",x=arr[1] || "0";
				return {"y":y,"x":x};
			}else{
				return val;
			}
		},
		_screen=function(val){
			if(val.indexOf(",")!==-1){
				var arr=val.split(","),x=arr[0] || "0",y=arr[1] || "0";
				return [x,y];
			}else{
				return val;
			}
		},
		_toTop=function(layout){
			if(layout!=="horizontal"){
				$(window).scrollTop(0); //stop jump to hash straight away
			}
			if(layout!=="vertical"){
				$(window).scrollLeft(0); //stop jump to hash straight away
			}
		},
		_unbindUnrelatedClickEvents=function(evt,s){
			for(var i=evt.click.length-1; i>=0; i--){
				var handler=evt.click[i];
				if(handler && handler.namespace != "mPS2id"){
					if(handler.selector==='a[href*="#"]'){
						handler.selector='a[href*="#"]:not(._mPS2id-h)';
					}else if(handler.selector==='a[href*=#]:not([href=#])'){
						handler.selector='a[href*=#]:not([href=#]):not(._mPS2id-h)';
					}else if(handler.selector && handler.selector.indexOf("mobmenu")!==-1){
						//special cases
						s.off("click");
					}else{
						s.off("click",handler.handler);
					}
				}
			}
		},
		autoSelectors="a[data-ps2id-api='true'][href*='#'],.ps2id > a[href*='#'],a.ps2id[href*='#']";
	$(document).ready(function(){
		for(var k=0; k<_o.total_instances; k++){
			//generate id from class name (e.g. class ps2id-id-myid gives element the id myid)
			var c2iSel=$("[class*='ps2id-id-']");
			if(c2iSel.length){
				c2iSel.each(function(){
					var c2i=$(this),
						c2iClasses=c2i.attr("class").split(" "),
						c2iVal;
					if(!c2i.attr("id")){
						for(var c2iClass in c2iClasses){
							if(String(c2iClasses[c2iClass]).match(/^ps2id-id-\S+$/)){
								c2iVal=c2iClasses[c2iClass].split("ps2id-id-")[1];
								if(!$("#"+c2iVal).length) c2i.attr("id",c2iVal);
								break;
							}
						}
					}
				});
			}
			//scroll to location hash on page load
			if(_o.instances[_p+"_instance_"+k]["scrollToHash"]==="true" && _hash){
				$(_o.instances[_p+"_instance_"+k]["selector"]+",."+shortcodeClass+","+autoSelectors).not(_o.instances[_p+"_instance_"+k]["excludeSelector"]).each(function(){
					$(this).data(_p+"Element",true);
				});
				if(_validateLocHash(_hash,_o.instances[_p+"_instance_"+k]["scrollToHashForAll"]==="true")){
					var href=_o.instances[_p+"_instance_"+k]["scrollToHashRemoveUrlHash"]==="true" ? window.location.href.replace(/#.*$/,"") : window.location.href.replace(/#.*$/,"#");
					_toTop(_o.instances[_p+"_instance_"+k]["layout"]); //stop jump to hash straight away
					if(window.history && window.history.replaceState){
						window.history.replaceState("","",href);
					}else{
						window.location.href=href;
					}
				}
			}
		}
	});
	$(window).on("load",function(){
		for(var i=0; i<_o.total_instances; i++){
			var sel=$(_o.instances[_p+"_instance_"+i]["selector"]+",."+shortcodeClass+","+autoSelectors),
				autoCorrectScrollOpt=_o.instances[_p+"_instance_"+i]["autoCorrectScroll"],autoCorrectScroll=0;
			sel.mPageScroll2id({
				scrollSpeed:_o.instances[_p+"_instance_"+i]["scrollSpeed"],
				autoScrollSpeed:(_o.instances[_p+"_instance_"+i]["autoScrollSpeed"]==="true") ? true : false,
				scrollEasing:_o.instances[_p+"_instance_"+i]["scrollEasing"],
				scrollingEasing:_o.instances[_p+"_instance_"+i]["scrollingEasing"],
				pageEndSmoothScroll:(_o.instances[_p+"_instance_"+i]["pageEndSmoothScroll"]==="true") ? true : false,
				layout:_o.instances[_p+"_instance_"+i]["layout"],
				offset:_offset(_o.instances[_p+"_instance_"+i]["offset"].toString()),
				highlightSelector:_o.instances[_p+"_instance_"+i]["highlightSelector"],
				clickedClass:_o.instances[_p+"_instance_"+i]["clickedClass"],
				targetClass:_o.instances[_p+"_instance_"+i]["targetClass"],
				highlightClass:_o.instances[_p+"_instance_"+i]["highlightClass"],
				forceSingleHighlight:(_o.instances[_p+"_instance_"+i]["forceSingleHighlight"]==="true") ? true : false,
				keepHighlightUntilNext:(_o.instances[_p+"_instance_"+i]["keepHighlightUntilNext"]==="true") ? true : false,
				highlightByNextTarget:(_o.instances[_p+"_instance_"+i]["highlightByNextTarget"]==="true") ? true : false,
				disablePluginBelow:_screen(_o.instances[_p+"_instance_"+i]["disablePluginBelow"].toString()),
				appendHash:(_o.instances[_p+"_instance_"+i]["appendHash"]==="true") ? true : false,
				onStart:function(){
					if(autoCorrectScrollOpt==="true" && mPS2id.trigger==="selector") autoCorrectScroll++;
				},
				onComplete:function(){
					if(autoCorrectScroll==1){
						if(mPS2id.clicked.length) mPS2id.clicked.trigger("click.mPS2id");
						autoCorrectScroll=0;
					}
				},
				excludeSelectors:_o.instances[_p+"_instance_"+i]["excludeSelector"]
			});
			//scroll to location hash on page load
			if(_o.instances[_p+"_instance_"+i]["scrollToHash"]==="true" && _hash){
				if(_validateLocHash(_hash,_o.instances[_p+"_instance_"+i]["scrollToHashForAll"]==="true")){
					_toTop(_o.instances[_p+"_instance_"+i]["layout"]); //jump/start from the top
					var scrollToHashUseElementData=_o.instances[_p+"_instance_"+i]["scrollToHashUseElementData"],
						linkMatchesHash=$("a._mPS2id-h[href$='"+_hash+"'][data-ps2id-offset]:not([data-ps2id-offset=''])").last();
					setTimeout(function(){
						if(scrollToHashUseElementData==="true" && linkMatchesHash.length){
							linkMatchesHash.trigger("click.mPS2id");
						}else{
							$.mPageScroll2id("scrollTo",_hash);
						}
						if(window.location.href.indexOf("#")!==-1){
							if(window.history && window.history.replaceState){
								window.history.replaceState("","",_hash);
							}else{
								window.location.hash=_hash;
							}
						}
					},_o.instances[_p+"_instance_"+i]["scrollToHashDelay"]);
				}
			}
			//attempt to unbind click events from other scripts 
			if(_o.instances[_p+"_instance_"+i]["unbindUnrelatedClickEvents"]==="true"){
				setTimeout(function(){
					var $events=sel.length ? $._data(sel[0],"events") : null,
						$docEvents=sel.length ? $._data($(document)[0],"events") : null;
					if($events) _unbindUnrelatedClickEvents($events,sel);
					if($docEvents) _unbindUnrelatedClickEvents($docEvents,sel);
				},300);
			}
			//force zero dimensions on anchor-point targets (firefox fix)
			if(_o.instances[_p+"_instance_"+i]["normalizeAnchorPointTargets"]==="true"){
				$("a._mPS2id-t[id]:empty").css({
					"display":"inline-block",
					"line-height":0,
					"width": 0,
					"height": 0,
					"border": "none"
				});
			}
			//stop scroll on mouse-wheel, touch-swipe etc.
			if(_o.instances[_p+"_instance_"+i]["stopScrollOnUserAction"]==="true"){
				$(document).on("mousewheel DOMMouseScroll touchmove",function(){
					var el=$("html,body");
					if(el.is(":animated")) el.stop();
				});
			}
		}
	});
	//extend jQuery's selectors
	$.extend($.expr[":"],{
		//position based - e.g. :fixed
		absolute:$.expr[":"].absolute || function(el){return $(el).css("position")==="absolute";},
		relative:$.expr[":"].relative || function(el){return $(el).css("position")==="relative";},
		static:$.expr[":"].static || function(el){return $(el).css("position")==="static";},
		fixed:$.expr[":"].fixed || function(el){return $(el).css("position")==="fixed";},
		//width based - e.g. :width(200), :width(>200), :width(>200):width(<300) etc.
		width:$.expr[":"].width || function(a,i,m){
			var val=m[3].replace("&lt;","<").replace("&gt;",">");
			if(!val){return false;}
			return val.substr(0,1)===">" ? $(a).width()>val.substr(1) : val.substr(0,1)==="<" ? $(a).width()<val.substr(1) : $(a).width()===parseInt(val);
		},
		//height based - e.g. :height(200), :height(>200), :height(>200):height(<300) etc.
		height:$.expr[":"].height || function(a,i,m){
			var val=m[3].replace("&lt;","<").replace("&gt;",">");
			if(!val){return false;}
			return val.substr(0,1)===">" ? $(a).height()>val.substr(1) : val.substr(0,1)==="<" ? $(a).height()<val.substr(1) : $(a).height()===parseInt(val);
		}
	});
})(jQuery);