(function(){tinymce.PluginManager.requireLangPack("youtubeIframe");tinymce.create("tinymce.plugins.YoutubeIframePlugin",{init:function(a,b){a.addCommand("mceYoutubeIframe",function(){a.windowManager.open({file:b+"/index.html",width:650,height:350,inline:1},{plugin_url:b,some_custom_arg:"custom arg"})});a.addButton("youtubeIframe",{title:"youtubeIframe.desc",cmd:"mceYoutubeIframe",image:b+"/img/youtube.png"});a.onNodeChange.add(function(a,b,c){var d=false;if(c.nodeName=="IMG"){try{var e=c.attributes["src"].value;var f=c.attributes["alt"].value;var g=e.match("vi/([^&#]*)/0.jpg");d=g[1]===f}catch(h){}}b.setActive("youtubeIframe",d)})},createControl:function(a,b){return null},getInfo:function(){return{longname:"Youtube Iframe PlugIn",author:"Darius Matulionis",authorurl:"http://matulionis.lt",infourl:"darius@matulionis.lt",version:"1.1"}}});tinymce.PluginManager.add("youtubeIframe",tinymce.plugins.YoutubeIframePlugin)})();