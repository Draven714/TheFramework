(function(a){function j(){var a=location.href;return hashtag=-1!==a.indexOf("#fwPopup")?decodeURI(a.substring(a.indexOf("#fwPopup")+1,a.length)):!1}function d(a,d){var a=a.replace(/[\[]/,"\\[").replace(/[\]]/,"\\]"),i=RegExp("[\\?&]"+a+"=([^&#]*)").exec(d);return null==i?"":i[1]}a.fwPopup={version:"1.0.0"};a.fn.fwPopup=function(h){function m(){a(".pp_loaderIcon").hide();projectedTop=scroll_pos.scrollTop+(g/2-b.containerHeight/2);0>projectedTop&&(projectedTop=0);$ppt.fadeTo(settings.animation_speed,
1);$pp_pic_holder.find(".pp_content").animate({height:b.contentHeight,width:b.contentWidth},settings.animation_speed);$pp_pic_holder.animate({top:projectedTop,left:0>e/2-b.containerWidth/2?0:e/2-b.containerWidth/2,width:b.containerWidth},settings.animation_speed,function(){$pp_pic_holder.find(".pp_hoverContainer,#fullResImage").height(b.height).width(b.width);$pp_pic_holder.find(".pp_fade").fadeIn(settings.animation_speed);isSet&&"image"==n(pp_images[set_position])?$pp_pic_holder.find(".pp_hoverContainer").show():
$pp_pic_holder.find(".pp_hoverContainer").hide();settings.allow_expand&&(b.resized?a("a.pp_expand,a.pp_contract").show():a("a.pp_expand").hide());settings.autoplay_slideshow&&(!p&&!r)&&a.fwPopup.startSlideshow();settings.changepicturecallback();r=!0});isSet&&settings.overlay_gallery&&"image"==n(pp_images[set_position])?(itemWidth=57,navWidth="facebook"==settings.theme||"pp_default"==settings.theme?50:30,itemsPerPage=Math.floor((b.containerWidth-100-navWidth)/itemWidth),itemsPerPage=itemsPerPage<pp_images.length?
itemsPerPage:pp_images.length,totalPage=Math.ceil(pp_images.length/itemsPerPage)-1,0==totalPage?(navWidth=0,$pp_gallery.find(".pp_arrow_next,.pp_arrow_previous").hide()):$pp_gallery.find(".pp_arrow_next,.pp_arrow_previous").show(),galleryWidth=itemsPerPage*itemWidth,fullGalleryWidth=pp_images.length*itemWidth,$pp_gallery.css("margin-left",-(galleryWidth/2+navWidth/2)).find("div:first").width(galleryWidth+5).find("ul").width(fullGalleryWidth).find("li.selected").removeClass("selected"),goToPage=Math.floor(set_position/
itemsPerPage)<totalPage?Math.floor(set_position/itemsPerPage):totalPage,a.fwPopup.changeGalleryPage(goToPage),$pp_gallery_li.filter(":eq("+set_position+")").addClass("selected")):$pp_pic_holder.find(".pp_content").unbind("mouseenter mouseleave");h.ajaxcallback()}function i(f){$pp_pic_holder.find("#pp_full_res object,#pp_full_res embed").css("visibility","hidden");$pp_pic_holder.find(".pp_fade").fadeOut(settings.animation_speed,function(){a(".pp_loaderIcon").show();f()})}function c(a,b){resized=!1;
v(a,b);imageWidth=a;imageHeight=b;if((k>e||l>g)&&doresize&&settings.allow_resize&&!q){resized=!0;for(fitting=!1;!fitting;)k>e?(imageWidth=e-200,imageHeight=b/a*imageWidth):l>g?(imageHeight=g-200,imageWidth=a/b*imageHeight):fitting=!0,l=imageHeight,k=imageWidth;(k>e||l>g)&&c(k,l);v(imageWidth,imageHeight)}return{width:Math.floor(imageWidth),height:Math.floor(imageHeight),containerHeight:Math.floor(l),containerWidth:Math.floor(k)+2*settings.horizontal_padding,contentHeight:Math.floor(s),contentWidth:Math.floor(w),
resized:resized}}function v(f,b){f=parseFloat(f);b=parseFloat(b);$pp_details=$pp_pic_holder.find(".pp_details");$pp_details.width(f);detailsHeight=parseFloat($pp_details.css("marginTop"))+parseFloat($pp_details.css("marginBottom"));$pp_details=$pp_details.clone().addClass(settings.theme).width(f).appendTo(a("body")).css({position:"absolute",top:-1E4});detailsHeight+=$pp_details.height();detailsHeight=34>=detailsHeight?36:detailsHeight;$pp_details.remove();$pp_title=$pp_pic_holder.find(".ppt");$pp_title.width(f);
titleHeight=parseFloat($pp_title.css("marginTop"))+parseFloat($pp_title.css("marginBottom"));$pp_title=$pp_title.clone().appendTo(a("body")).css({position:"absolute",top:-1E4});titleHeight+=$pp_title.height();$pp_title.remove();s=b+detailsHeight;w=f;l=s+titleHeight+$pp_pic_holder.find(".pp_top").height()+$pp_pic_holder.find(".pp_bottom").height();k=f}function n(a){return a.match(/youtube\.com\/watch/i)||a.match(/youtu\.be/i)?"youtube":a.match(/vimeo\.com/i)?"vimeo":a.match(/\b.mov\b/i)?"quicktime":
a.match(/\b.swf\b/i)?"flash":a.match(/\biframe=true\b/i)?"iframe":a.match(/\bajax=true\b/i)?"ajax":a.match(/\bcustom=true\b/i)?"custom":"#"==a.substr(0,1)?"inline":"image"}function t(){doresize&&"undefined"!=typeof $pp_pic_holder&&(scroll_pos=x(),contentHeight=$pp_pic_holder.height(),contentwidth=$pp_pic_holder.width(),projectedTop=g/2+scroll_pos.scrollTop-contentHeight/2,0>projectedTop&&(projectedTop=0),contentHeight>g||$pp_pic_holder.css({top:projectedTop,left:e/2+scroll_pos.scrollLeft-contentwidth/
2}))}function x(){if(self.pageYOffset)return{scrollTop:self.pageYOffset,scrollLeft:self.pageXOffset};if(document.documentElement&&document.documentElement.scrollTop)return{scrollTop:document.documentElement.scrollTop,scrollLeft:document.documentElement.scrollLeft};if(document.body)return{scrollTop:document.body.scrollTop,scrollLeft:document.body.scrollLeft}}function y(){settings.social_tools&&(facebook_like_link=settings.social_tools.replace("{location_href}",encodeURIComponent(location.href)));settings.markup=
settings.markup.replace("{pp_social}","");a("body").append(settings.markup);$pp_pic_holder=a(".pp_pic_holder");$ppt=a(".ppt");$pp_overlay=a("div.pp_overlay");if(isSet&&settings.overlay_gallery){currentGalleryPage=0;toInject="";for(var b=0;b<pp_images.length;b++)pp_images[b].match(/\b(jpg|jpeg|png|gif)\b/gi)?(classname="",img_src=pp_images[b]):(classname="default",img_src=""),toInject+="<li class='"+classname+"'><a href='#'><img src='"+img_src+"' width='50' alt='' /></a></li>";toInject=settings.gallery_markup.replace(/{gallery}/g,
toInject);$pp_pic_holder.find("#pp_full_res").after(toInject);$pp_gallery=a(".pp_pic_holder .pp_gallery");$pp_gallery_li=$pp_gallery.find("li");$pp_gallery.find(".pp_arrow_next").click(function(){a.fwPopup.changeGalleryPage("next");a.fwPopup.stopSlideshow();return!1});$pp_gallery.find(".pp_arrow_previous").click(function(){a.fwPopup.changeGalleryPage("previous");a.fwPopup.stopSlideshow();return!1});$pp_pic_holder.find(".pp_content").hover(function(){$pp_pic_holder.find(".pp_gallery:not(.disabled)").fadeIn()},
function(){$pp_pic_holder.find(".pp_gallery:not(.disabled)").fadeOut()});itemWidth=57;$pp_gallery_li.each(function(b){a(this).find("a").click(function(){a.fwPopup.changePage(b);a.fwPopup.stopSlideshow();return!1})})}settings.slideshow&&($pp_pic_holder.find(".pp_nav").prepend('<a href="#" class="pp_play">Play</a>'),$pp_pic_holder.find(".pp_nav .pp_play").click(function(){a.fwPopup.startSlideshow();return!1}));$pp_pic_holder.attr("class","pp_pic_holder "+settings.theme);$pp_overlay.css({opacity:0,height:a(document).height(),
width:a(window).width()}).bind("click",function(){settings.modal||a.fwPopup.close()});a("a.pp_close").bind("click",function(){a.fwPopup.close();return!1});settings.allow_expand&&a("a.pp_expand").bind("click",function(){a(this).hasClass("pp_expand")?(a(this).removeClass("pp_expand").addClass("pp_contract"),doresize=!1):(a(this).removeClass("pp_contract").addClass("pp_expand"),doresize=!0);i(function(){a.fwPopup.open()});return!1});$pp_pic_holder.find(".pp_previous, .pp_nav .pp_arrow_previous").bind("click",
function(){a.fwPopup.changePage("previous");a.fwPopup.stopSlideshow();return!1});$pp_pic_holder.find(".pp_next, .pp_nav .pp_arrow_next").bind("click",function(){a.fwPopup.changePage("next");a.fwPopup.stopSlideshow();return!1});t()}var h=jQuery.extend({hook:"rel",animation_speed:"fast",ajaxcallback:function(){},slideshow:5E3,autoplay_slideshow:!1,opacity:0.8,show_title:!0,allow_resize:!0,allow_expand:!0,default_width:500,default_height:344,counter_separator_label:"/",theme:"pp_default",horizontal_padding:20,
hideflash:!1,wmode:"opaque",autoplay:!0,modal:!1,deeplinking:!0,overlay_gallery:!0,overlay_gallery_max:30,keyboard_shortcuts:!0,changepicturecallback:function(){},callback:function(){},ie6_fallback:!0,markup:'<div class="pp_pic_holder"> \t\t\t\t\t\t<div class="ppt">&nbsp;</div> \t\t\t\t\t\t<div class="pp_top"> \t\t\t\t\t\t\t<div class="pp_left"></div> \t\t\t\t\t\t\t<div class="pp_middle"></div> \t\t\t\t\t\t\t<div class="pp_right"></div> \t\t\t\t\t\t</div> \t\t\t\t\t\t<div class="pp_content_container"> \t\t\t\t\t\t\t<div class="pp_left"> \t\t\t\t\t\t\t<div class="pp_right"> \t\t\t\t\t\t\t\t<div class="pp_content"> \t\t\t\t\t\t\t\t\t<div class="pp_loaderIcon"></div> \t\t\t\t\t\t\t\t\t<div class="pp_fade"> \t\t\t\t\t\t\t\t\t\t<a href="#" class="pp_expand" title="Expand the image">Expand</a> \t\t\t\t\t\t\t\t\t\t<div class="pp_hoverContainer"> \t\t\t\t\t\t\t\t\t\t\t<a class="pp_next" href="#">next</a> \t\t\t\t\t\t\t\t\t\t\t<a class="pp_previous" href="#">previous</a> \t\t\t\t\t\t\t\t\t\t</div> \t\t\t\t\t\t\t\t\t\t<div id="pp_full_res"></div> \t\t\t\t\t\t\t\t\t\t<div class="pp_details"> \t\t\t\t\t\t\t\t\t\t\t<div class="pp_nav"> \t\t\t\t\t\t\t\t\t\t\t\t<a href="#" class="pp_arrow_previous">Previous</a> \t\t\t\t\t\t\t\t\t\t\t\t<p class="currentTextHolder">0/0</p> \t\t\t\t\t\t\t\t\t\t\t\t<a href="#" class="pp_arrow_next">Next</a> \t\t\t\t\t\t\t\t\t\t\t</div> \t\t\t\t\t\t\t\t\t\t\t<p class="pp_description"></p> \t\t\t\t\t\t\t\t\t\t\t<div class="pp_social">{pp_social}</div> \t\t\t\t\t\t\t\t\t\t\t<a class="pp_close" href="#">Close</a> \t\t\t\t\t\t\t\t\t\t</div> \t\t\t\t\t\t\t\t\t</div> \t\t\t\t\t\t\t\t</div> \t\t\t\t\t\t\t</div> \t\t\t\t\t\t\t</div> \t\t\t\t\t\t</div> \t\t\t\t\t\t<div class="pp_bottom"> \t\t\t\t\t\t\t<div class="pp_left"></div> \t\t\t\t\t\t\t<div class="pp_middle"></div> \t\t\t\t\t\t\t<div class="pp_right"></div> \t\t\t\t\t\t</div> \t\t\t\t\t</div> \t\t\t\t\t<div class="pp_overlay"></div>',
gallery_markup:'<div class="pp_gallery"> \t\t\t\t\t\t\t\t<a href="#" class="pp_arrow_previous">Previous</a> \t\t\t\t\t\t\t\t<div> \t\t\t\t\t\t\t\t\t<ul> \t\t\t\t\t\t\t\t\t\t{gallery} \t\t\t\t\t\t\t\t\t</ul> \t\t\t\t\t\t\t\t</div> \t\t\t\t\t\t\t\t<a href="#" class="pp_arrow_next">Next</a> \t\t\t\t\t\t\t</div>',image_markup:'<img id="fullResImage" src="{path}" />',flash_markup:'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',
quicktime_markup:'<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',iframe_markup:'<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
inline_markup:'<div class="pp_inline">{content}</div>',custom_markup:"",social_tools:'<div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"><\/script></div><div class="facebook"><iframe src="//www.facebook.com/plugins/like.php?locale=en_US&href={location_href}&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:23px;" allowTransparency="true"></iframe></div>'},
h),u=this,q=!1,b,r,s,w,l,k,g=a(window).height(),e=a(window).width(),p;doresize=!0;scroll_pos=x();a(window).unbind("resize.fwpopup").bind("resize.fwpopup",function(){t();g=a(window).height();e=a(window).width();"undefined"!=typeof $pp_overlay&&$pp_overlay.height(a(document).height()).width(e)});h.keyboard_shortcuts&&a(document).unbind("keydown.fwpopup").bind("keydown.fwpopup",function(b){if("undefined"!=typeof $pp_pic_holder&&$pp_pic_holder.is(":visible"))switch(b.keyCode){case 37:a.fwPopup.changePage("previous");
b.preventDefault();break;case 39:a.fwPopup.changePage("next");b.preventDefault();break;case 27:settings.modal||a.fwPopup.close(),b.preventDefault()}});a.fwPopup.initialize=function(){settings=h;"pp_default"==settings.theme&&(settings.horizontal_padding=16);theRel=a(this).attr(settings.hook);galleryRegExp=/\[(?:.*)\]/;pp_images=(isSet=galleryRegExp.exec(theRel)?!0:!1)?jQuery.map(u,function(b){if(-1!=a(b).attr(settings.hook).indexOf(theRel))return a(b).attr("href")}):a.makeArray(a(this).attr("href"));
pp_titles=isSet?jQuery.map(u,function(b){if(-1!=a(b).attr(settings.hook).indexOf(theRel))return a(b).find("img").attr("alt")?a(b).find("img").attr("alt"):""}):a.makeArray(a(this).find("img").attr("alt"));pp_descriptions=isSet?jQuery.map(u,function(b){if(-1!=a(b).attr(settings.hook).indexOf(theRel))return a(b).attr("title")?a(b).attr("title"):""}):a.makeArray(a(this).attr("title"));pp_images.length>settings.overlay_gallery_max&&(settings.overlay_gallery=!1);set_position=jQuery.inArray(a(this).attr("href"),
pp_images);rel_index=isSet?set_position:a("a["+settings.hook+"^='"+theRel+"']").index(a(this));y(this);settings.allow_resize&&a(window).bind("scroll.fwpopup",function(){t()});a.fwPopup.open();return!1};a.fwPopup.open=function(f,e,g,i){"undefined"==typeof settings&&(settings=h,pp_images=a.makeArray(f),pp_titles=e?a.makeArray(e):a.makeArray(""),pp_descriptions=g?a.makeArray(g):a.makeArray(""),isSet=1<pp_images.length?!0:!1,set_position=i?i:0,y(f.target));settings.hideflash&&a("object,embed,iframe[src*=youtube],iframe[src*=vimeo]").css("visibility",
"hidden");1<a(pp_images).size()?a(".pp_nav").show():a(".pp_nav").hide();a(".pp_loaderIcon").show();settings.deeplinking&&"undefined"!=typeof theRel&&(location.hash=theRel+"/"+rel_index+"/");settings.social_tools&&(facebook_like_link=settings.social_tools.replace("{location_href}",encodeURIComponent(location.href)),$pp_pic_holder.find(".pp_social").html(facebook_like_link));$ppt.is(":hidden")&&$ppt.css("opacity",0).show();$pp_overlay.show().fadeTo(settings.animation_speed,settings.opacity);$pp_pic_holder.find(".currentTextHolder").text(set_position+
1+settings.counter_separator_label+a(pp_images).size());"undefined"!=typeof pp_descriptions[set_position]&&""!=pp_descriptions[set_position]?$pp_pic_holder.find(".pp_description").show().html(unescape(pp_descriptions[set_position])):$pp_pic_holder.find(".pp_description").hide();movie_width=parseFloat(d("width",pp_images[set_position]))?d("width",pp_images[set_position]):settings.default_width.toString();movie_height=parseFloat(d("height",pp_images[set_position]))?d("height",pp_images[set_position]):
settings.default_height.toString();q=!1;-1!=movie_height.indexOf("%")&&(movie_height=parseFloat(a(window).height()*parseFloat(movie_height)/100-150),q=!0);-1!=movie_width.indexOf("%")&&(movie_width=parseFloat(a(window).width()*parseFloat(movie_width)/100-150),q=!0);$pp_pic_holder.fadeIn(function(){settings.show_title&&""!=pp_titles[set_position]&&"undefined"!=typeof pp_titles[set_position]?$ppt.html(unescape(pp_titles[set_position])):$ppt.html("&nbsp;");imgPreloader="";skipInjection=!1;switch(n(pp_images[set_position])){case "image":imgPreloader=
new Image;nextImage=new Image;isSet&&set_position<a(pp_images).size()-1&&(nextImage.src=pp_images[set_position+1]);prevImage=new Image;isSet&&pp_images[set_position-1]&&(prevImage.src=pp_images[set_position-1]);$pp_pic_holder.find("#pp_full_res")[0].innerHTML=settings.image_markup.replace(/{path}/g,pp_images[set_position]);imgPreloader.onload=function(){b=c(imgPreloader.width,imgPreloader.height);m()};imgPreloader.onerror=function(){alert("Image cannot be loaded. Make sure the path is correct and image exist.");
a.fwPopup.close()};imgPreloader.src=pp_images[set_position];break;case "youtube":b=c(movie_width,movie_height);movie_id=d("v",pp_images[set_position]);""==movie_id&&(movie_id=pp_images[set_position].split("youtu.be/"),movie_id=movie_id[1],0<movie_id.indexOf("?")&&(movie_id=movie_id.substr(0,movie_id.indexOf("?"))),0<movie_id.indexOf("&")&&(movie_id=movie_id.substr(0,movie_id.indexOf("&"))));movie="http://www.youtube.com/embed/"+movie_id;d("rel",pp_images[set_position])?movie+="?rel="+d("rel",pp_images[set_position]):
movie+="?rel=1";settings.autoplay&&(movie+="&autoplay=1");toInject=settings.iframe_markup.replace(/{width}/g,b.width).replace(/{height}/g,b.height).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,movie);break;case "vimeo":b=c(movie_width,movie_height);movie_id=pp_images[set_position];movie="http://player.vimeo.com/video/"+movie_id.match(/http(s?):\/\/(www\.)?vimeo.com\/(\d+)/)[3]+"?title=0&amp;byline=0&amp;portrait=0";settings.autoplay&&(movie+="&autoplay=1;");vimeo_width=b.width+"/embed/?moog_width="+
b.width;toInject=settings.iframe_markup.replace(/{width}/g,vimeo_width).replace(/{height}/g,b.height).replace(/{path}/g,movie);break;case "quicktime":b=c(movie_width,movie_height);b.height+=15;b.contentHeight+=15;b.containerHeight+=15;toInject=settings.quicktime_markup.replace(/{width}/g,b.width).replace(/{height}/g,b.height).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,pp_images[set_position]).replace(/{autoplay}/g,settings.autoplay);break;case "flash":b=c(movie_width,movie_height);flash_vars=
pp_images[set_position];flash_vars=flash_vars.substring(pp_images[set_position].indexOf("flashvars")+10,pp_images[set_position].length);filename=pp_images[set_position];filename=filename.substring(0,filename.indexOf("?"));toInject=settings.flash_markup.replace(/{width}/g,b.width).replace(/{height}/g,b.height).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,filename+"?"+flash_vars);break;case "iframe":b=c(movie_width,movie_height);frame_url=pp_images[set_position];frame_url=frame_url.substr(0,
frame_url.indexOf("iframe")-1);toInject=settings.iframe_markup.replace(/{width}/g,b.width).replace(/{height}/g,b.height).replace(/{path}/g,frame_url);break;case "ajax":doresize=!1;b=c(movie_width,movie_height);skipInjection=doresize=!0;a.get(pp_images[set_position],function(a){toInject=settings.inline_markup.replace(/{content}/g,a);$pp_pic_holder.find("#pp_full_res")[0].innerHTML=toInject;m()});break;case "custom":b=c(movie_width,movie_height);toInject=settings.custom_markup;break;case "inline":myClone=
a(pp_images[set_position]).clone().append('<br clear="all" />').css({width:settings.default_width}).wrapInner('<div id="pp_full_res"><div class="pp_inline"></div></div>').appendTo(a("body")).show(),doresize=!1,b=c(a(myClone).width(),a(myClone).height()),doresize=!0,a(myClone).remove(),toInject=settings.inline_markup.replace(/{content}/g,a(pp_images[set_position]).html())}!imgPreloader&&!skipInjection&&($pp_pic_holder.find("#pp_full_res")[0].innerHTML=toInject,m())});return!1};a.fwPopup.changePage=
function(b){currentGalleryPage=0;"previous"==b?(set_position--,0>set_position&&(set_position=a(pp_images).size()-1)):"next"==b?(set_position++,set_position>a(pp_images).size()-1&&(set_position=0)):set_position=b;rel_index=set_position;doresize||(doresize=!0);settings.allow_expand&&a(".pp_contract").removeClass("pp_contract").addClass("pp_expand");i(function(){a.fwPopup.open()})};a.fwPopup.changeGalleryPage=function(a){"next"==a?(currentGalleryPage++,currentGalleryPage>totalPage&&(currentGalleryPage=
0)):"previous"==a?(currentGalleryPage--,0>currentGalleryPage&&(currentGalleryPage=totalPage)):currentGalleryPage=a;slide_speed="next"==a||"previous"==a?settings.animation_speed:0;slide_to=currentGalleryPage*itemsPerPage*itemWidth;$pp_gallery.find("ul").animate({left:-slide_to},slide_speed)};a.fwPopup.startSlideshow=function(){"undefined"==typeof p?($pp_pic_holder.find(".pp_play").unbind("click").removeClass("pp_play").addClass("pp_pause").click(function(){a.fwpopup.stopSlideshow();return!1}),p=setInterval(a.fwPopup.startSlideshow,
settings.slideshow)):a.fwPopup.changePage("next")};a.fwPopup.stopSlideshow=function(){$pp_pic_holder.find(".pp_pause").unbind("click").removeClass("pp_pause").addClass("pp_play").click(function(){a.fwPopup.startSlideshow();return!1});clearInterval(p);p=void 0};a.fwPopup.close=function(){$pp_overlay.is(":animated")||(a.fwPopup.stopSlideshow(),$pp_pic_holder.stop().find("object,embed").css("visibility","hidden"),a("div.pp_pic_holder,div.ppt,.pp_fade").fadeOut(settings.animation_speed,function(){a(this).remove()}),
$pp_overlay.fadeOut(settings.animation_speed,function(){settings.hideflash&&a("object,embed,iframe[src*=youtube],iframe[src*=vimeo]").css("visibility","visible");a(this).remove();a(window).unbind("scroll.fwpopup");-1!==location.href.indexOf("#fwPopup")&&(location.hash="fwPopup");settings.callback();doresize=!0;r=!1;delete settings}))};!pp_alreadyInitialized&&j()&&(pp_alreadyInitialized=!0,hashRel=hashIndex=j(),hashIndex=hashIndex.substring(hashIndex.indexOf("/")+1,hashIndex.length-1),hashRel=hashRel.substring(0,
hashRel.indexOf("/")),setTimeout(function(){a("a["+h.hook+"^='"+hashRel+"']:eq("+hashIndex+")").trigger("click")},50));return this.unbind("click.fwpopup").bind("click.fwpopup",a.fwPopup.initialize)}})(jQuery);var pp_alreadyInitialized=!1;
(function(a){a.fn.customAlert=function(j){var d={alertOk:"OK",draggable:!1};j&&a.extend(d,j);document.getElementById&&(window.defaultAlert=window.alert,window.alert=function(h,m){if(!(0<a(".overlay").length||void 0===h||void 0===m)){var i=a("<div>").addClass("overlay").show(),c=a("<div>").addClass("title").html(h),j=a("<div>").addClass("message").html(m),n=a("<a>").addClass("okBtn").text(d.alertOk).attr("href","#"),c=a("<div>").addClass("alertBox").append(c).append(j).append(n);a("body").append(c).append(i);
c.css({top:a(window).height()/2-c.outerHeight(!0)/2+"px",left:a(window).width()/2-c.outerWidth(!0)/2+"px"});d.draggable&&c.draggable&&(c.draggable({handle:".title",opacity:0.4}),a(".alertBox .title").css("cursor","move"));a(".alertBox .okBtn, .overlay").click(function(c){c.preventDefault();a(".alertBox, .overlay").remove()});a(window).keydown(function(c){"13"==c.keyCode&&(a(".alertBox .okBtn").click(),a(this).unbind("keydown"))})}})}})(jQuery);