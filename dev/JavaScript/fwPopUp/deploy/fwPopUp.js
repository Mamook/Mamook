(function(e,q,y){function V(c){var e=N(),j="";if(e&&e[c]){delete e[c];for(var i in e)j+=i+(!0===e[i]?"":"="+encodeURIComponent(e[i]));y.location.hash=j;return!0}return!1}function z(c,e){var c=c.replace(/[\[]/,"\\[").replace(/[\]]/,"\\]"),j=RegExp("[\\?&]"+c+"=([^&#]*)").exec(e);return null===j?"":j[1]}function W(c){var e=N(),j={};return e&&e[c]?(j[c]=!0===e[c]?0:e[c],j):!1}function N(){for(var c=y.location.hash,c=c?c.replace(/^#/,"").split("&"):[],i={},j=0;j<c.length;j++){var q=c[j].split("=");i[q[0]]=
decodeURIComponent(q[1])||!0}e.isEmptyObject(i)&&(i=!1);return i}function X(){return y.pageYOffset?{scrollTop:y.pageYOffset,scrollLeft:y.pageXOffset}:q.documentElement&&q.documentElement.scrollTop?{scrollTop:q.documentElement.scrollTop,scrollLeft:q.documentElement.scrollLeft}:q.body?{scrollTop:q.body.scrollTop,scrollLeft:q.body.scrollLeft}:{scrollTop:0,scrollLeft:0}}var Y=e.fwPopup={initialized:!1,version:"2.0.0"},i=e(y),G,A=i.height(),p=i.width();e.fn.fwPopup=function(c){var O,j;function ka(a){a=
p/2+P.scrollLeft-a/2;0>a&&(a=0);return a}function la(a){a=A/2+P.scrollTop-a/2;0>a&&(a=0);return a}function D(){h&&(P=X(),A=i.height(),p=i.width(),h.css({top:la(h.outerHeight(!1)),left:ka(h.outerWidth(!1))}))}function E(a){if(!c.modal||!0===a)a=c.animationSpeed,G.is(":animated")||(h.stop().find("object,embed").css({visibility:"hidden"}),h.fadeOut(a,function(){e(this).remove()}),G.fadeOut(a,function(){c.hideFlash&&e("object,embed,iframe[src*=youtube],iframe[src*=vimeo]").css({visibility:"visible"});
e(this).remove();i.off("scroll.fwPopup");V(C);Y.isInitialized=!1;c.callback()}))}function Z(a){a=e(a.target);a.hasClass("button-expand")?a.removeClass("button-expand").addClass("button-contract").attr({"aria-label":"Contract the image",title:"Contract the image"}):a.removeClass("button-contract").addClass("button-expand").attr({"aria-label":"Expand the image",title:"Expand the image"});$(aa);return!1}function r(a,e){ba(a,e);var b=!1,d=a,f=e;if((s>p||B>A)&&c.allowResize&&!H){for(var b=!0,g=!1;!g;)s>
p?(f=p-200,d=a/e*f):B>A?(d=A-200,f=e/a*d):g=!0,B=d,s=f;(s>p||B>A)&&r(s,B);ba(d,f)}return{width:Math.floor(f),height:Math.floor(d),containerHeight:Math.floor(B),containerWidth:Math.floor(s)+2*c.horizontalPadding,contentHeight:Math.floor(ca),contentWidth:Math.floor(da),resized:b}}function ba(a,d){var a=parseFloat(a),d=parseFloat(d),b=h.clone().appendTo(e("body")).css({position:"absolute",top:-1E4}).addClass("testDiv").show(),g=b.find(".fwpContent");b.find(".fwpDetails").addClass(c.theme);b.find("#fwpFullRes").css({height:a,
width:d});ca=g.outerHeight(!1);da=d;B=b.outerHeight(!1);s=d;b.remove()}function ma(a){return a.match(/youtube\.com\/watch/i)||a.match(/youtu\.be/i)?"youtube":a.match(/vimeo\.com/i)?"vimeo":a.match(/\b.mov\b/i)?"quicktime":a.match(/\b.swf\b/i)?"flash":a.match(/\b.(mp3|ogg)\b/i)?(O="mpeg",j="mp3",a.match(/\b.ogg\b/i)&&(O="ogg",j="vorbis"),"audio"):a.match(/\biframe=true\b/i)?"iframe":a.match(/\bajax=true\b/i)?"ajax":a.match(/\bcustom=true\b/i)?"custom":"#"==a.substr(0,1)?"inline":"image"}function $(a){a=
a||function(){};Q.addClass("fwpLoading");t.find("object,embed").css({visibility:"hidden"});t.css({opacity:0});a()}function ea(a){if(c.keyboardAccessible)e(q).off("keydown.fwPopup").on("keydown.fwPopup",function(a){if(h&&h.is(":visible"))switch(a.keyCode){case 27:c.modal||E()}});I=W(C);!a&&I?(J=I[C]||0,a=e(R[J])):(a=e(a),J=R.index(a));g=a.attr("href");K=(u=a.data())&&u.desc?u.desc:a.attr("title");L=u&&u.title?u.title:a.find("img").attr("alt");if(c.deepLinking){var a=C,b=J;V(a);var k=N(),p="";k||(k=
{});k[a]=b;for(var f in k)p+=f+(!0===k[f]?"":"="+encodeURIComponent(k[f]));y.location.hash=p}Y.isInitialized=!0;var l=c.markup,v="",b=parseFloat(z("width",g))?z("width",g):c.defaultWidth,k=parseFloat(z("height",g))?z("height",g):c.defaultHeight,n,s;l.socialTools=l.socialTools?l.socialTools.replace(RegExp(fa,"g"),y.location).replace(RegExp(S,"g"),L):"";l.general=l.general.replace(ga,l.socialTools);e("body").append(l.general);h=e(".fwpHolder").addClass(c.theme);Q=h.find(".fwpContent");f=h.find(".fwpDescription");
t=h.find("#fwpFullRes");a=h.find(".fwpTitle");G=e(".overlay.fwp").css({opacity:0}).on("click",E);h.find(".button-close").off("click",E).on("click",E);if(c.allowExpand)h.find(".button-expand").off("click",Z).on("click",Z);c.hideFlash&&e("object,embed,iframe[src*=youtube],iframe[src*=vimeo]").css({visibility:"hidden"});c.showTitle&&L&&a.html(decodeURI(L)).removeClass("no_content");f.hide();K&&f.show().html(decodeURI(K));H=!1;k.toString().indexOf("%")+1&&(k=parseFloat(i.height()*parseFloat(k)/100-150),
H=!0);b.toString().indexOf("%")+1&&(b=parseFloat(i.width()*parseFloat(b)/100-150),H=!0);switch(ma(g)){case "ajax":s=!0;d=r(k,b);e.get(g,function(a){v=l.inline.replace(T,a);t[0].innerHTML=v;M()});break;case "audio":n=new Image;n.onload=function(){var a=new Audio,b=u&&u.image?'<img src="'+u.image+'" alt="Cover for '+K+'"/>':"";t[0].innerHTML=l.audio.replace(ha,j).replace(ia,b).replace(ja,O).replace(RegExp(m,"g"),g);t.find("audio").width(n.width);a.setAttribute("src",g);a.load();d=r(n.height+30,n.width);
M()};n.src=u.image;break;case "custom":d=r(k,b);v=l.custom;break;case "flash":d=r(k,b);f=g.substring(g.indexOf("flashvars")+10,g.length);a=g.substring(0,g.indexOf("?"));v=l.flash.replace(RegExp(w,"g"),d.width).replace(RegExp(x,"g"),d.height).replace(RegExp(F,"g"),c.wmode).replace(RegExp(m,"g"),a+(f?"?"+f:""));break;case "iframe":d=r(k,b);f=g.substr(0,g.indexOf("iframe")-1);v=l.iframe.replace(RegExp(w,"g"),d.width).replace(RegExp(x,"g"),d.height).replace(RegExp(m,"g"),f);break;case "image":n=new Image;
t[0].innerHTML=l.image.replace(RegExp(m,"g"),g);n.onload=function(){d=r(n.height,n.width);M()};n.onerror=function(){alert("Image cannot be loaded. Make sure the path is correct and image exist.");E(!0)};n.src=g;break;case "inline":t.addClass("fwpInline");f=e("div").addClass("fwpFullRes fwpInline").css({position:"absolute",top:-1E4,width:c.defaultWidth,"max-height":A}).appendTo(e("body"));f[0].innerHTML=e(g).html();d=r(f.outerWidth(!1),f.outerHeight(!1));f.remove();v=l.inline.replace(T,e(g).html());
break;case "quicktime":d=r(k,b);d.height+=15;d.contentHeight+=15;d.containerHeight+=15;v=l.quicktime.replace(RegExp(w,"g"),d.width).replace(RegExp(x,"g"),d.height).replace(RegExp(F,"g"),c.wmode).replace(RegExp(m,"g"),g).replace(RegExp(U,"g"),c.autoPlay);break;case "vimeo":d=r(k,b);a=g.match(/http(s?):\/\/(www\.)?vimeo.com\/(\d+)/)[3];f=d.width+"/embed/?moog_width="+d.width;a="//player.vimeo.com/video/"+a+"?title=0&byline=0&portrait=0";c.autoPlay&&(a+="&autoplay=1;");v=l.iframe.replace(RegExp(w,"g"),
f).replace(RegExp(x,"g"),d.height).replace(RegExp(m,"g"),a);break;case "youtube":d=r(k,b),f=z("v",g),f||(f=g.split("youtu.be/"),f=f[1],0<f.indexOf("?")&&(f=f.substr(0,f.indexOf("?"))),0<f.indexOf("&")&&(f=f.substr(0,f.indexOf("&")))),a="//www.youtube.com/embed/"+f,a=z("rel",g)?a+("?rel="+z("rel",g)):a+"?rel=1",c.autoPlay&&(a+="&autoplay=1"),v=l.iframe.replace(RegExp(w,"g"),d.width).replace(RegExp(x,"g"),d.height).replace(RegExp(F,"g"),c.wmode).replace(RegExp(m,"g"),a)}$();!n&&!s&&(t[0].innerHTML=
v,M())}function aa(){var a=c.animationSpeed;if(c.allowResize)i.off("scroll.fwPopup",D).on("scroll.fwPopup",function(){D()});i.off("resize.fwPopup",D).on("resize.fwPopup",D);G.show().fadeTo(a,c.opacity,function(){t.animate({opacity:1},a/2)});h.fadeIn(a,D);h.css({display:"block",opacity:0}).animate({opacity:1,top:top,left:0>p/2-d.containerWidth/2?0:p/2-d.containerWidth/2},a,function(){})}function M(){Q.removeClass("fwpLoading");h.find(".fwpFullResImage").height(d.height).width(d.width);c.allowExpand&&
(d.resized?e("a.button-expand,a.button-contract").show():e("a.button-expand").hide());aa();c.ajaxCallback()}var K="",u,J,h,t,Q,ca,da,B,s,d,I,C,R=this,g,H=!1,P=X(),L="",U="{%autoplay}",ha="{%codec}",T="{%content}",x="{%height}",ia="{%image}",fa="{%location_href}",ja="{%type}",m="{%path}",ga="{%social_tools}",S="{%title}",w="{%width}",F="{%wmode}",na=""+ia+'<audio controls autoplay class="audioPlayback"><source src="'+m+'" type="audio/'+ja+'" codec="'+ha+'"/></audio>',oa='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+
w+'" height="'+x+'"><param name="wmode" value="'+F+'" /><param name="allowfullscreen" value="true"/><param name="allowscriptaccess" value="always" /><param name="movie" value="'+m+'" /><embed src="'+m+'" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="'+w+'" height="'+x+'" wmode="'+F+'"></embed></object>',b;b=[];b.push('<div class="fwpHolder">');b.push('<div class="fwpTitle no_content"></div>');b.push('<div class="fwpTop">');b.push('<div class="fwpLeft"></div>');
b.push('<div class="fwpMiddle"></div>');b.push('<div class="fwpRight"></div>');b.push("</div>");b.push('<div class="fwpContainer">');b.push('<div class="fwpLeft">');b.push("</div>");b.push('<div class="fwpContent fwpMiddle fwpLoading">');b.push('<a tabindex="0" class="button-expand" title="Expand the image" aria-label="Expand the image">Expand</a>');b.push('<div id="fwpFullRes" class="fwpFullRes"></div>');b.push('<div class="fwpDetails">');b.push('<p class="fwpDescription no_content"></p>');b.push('<div class="fwpSocial">'+
ga+"</div>");b.push('<a tabindex="0" class="button-close" title="Close the modal" aria-label="Close the modal">Close</a>');b.push("</div>");b.push("</div>");b.push('<div class="fwpRight">');b.push("</div>");b.push("</div>");b.push('<div class="fwpBottom">');b.push('<div class="fwpLeft"></div>');b.push('<div class="fwpMiddle"></div>');b.push('<div class="fwpRight"></div>');b.push("</div>");b.push("</div>");b.push('<div class="overlay fwp"></div>');b=b.join("");c=e.extend(!0,{ajaxCallback:function(){},
allowExpand:!0,allowResize:!0,animationSpeed:500,autoPlay:!0,callback:function(){},defaultHeight:344,defaultWidth:500,deepLinking:!0,hideFlash:!1,hook:"data-fwPopup",hookWord:"fwPopup",horizontalPadding:20,ie6Fallback:!0,keyboardAccessible:!0,markup:{general:b,audio:na,custom:"",flash:oa,iframe:'<iframe src ="'+m+'" width="'+w+'" height="'+x+'" frameborder="no"></iframe>',image:'<img class="fwpFullResImage" src="'+m+'"/>',inline:'<div class="fwpInline">'+T+"</div>",quicktime:'<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="//www.apple.com/qtactivex/qtplugin.cab" height="'+
x+'" width="'+w+'"><param name="src" value="'+m+'"><param name="autoplay" value="'+U+'"><param name="type" value="video/quicktime"><embed src="'+m+'" height="'+x+'" width="'+w+'" autoplay="'+U+'" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',socialTools:'<div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none" data-text="'+S+'">Tweet</a><script src="//platform.twitter.com/widgets.js"><\/script></div><div class="facebook"><div class="fb-share-button" data-href="'+
fa+'" data-layout="button" data-caption="'+S+'"></div><script>(function($){$.ajaxSetup({cache:true});$.getScript("//connect.facebook.net/en_US/sdk.js",function(){FB.init({xfbml:1,version:"v2.5"})});})(jQuery)<\/script></div>'},modal:!1,opacity:1,showTitle:!0,theme:"default",wmode:"opaque"},c);C=c.hookWord;(I=W(C))&&ea();return R.off("click.fwPopup").on("click.fwPopup",function(a){a.preventDefault();ea(this)})}})(jQuery,document,window);