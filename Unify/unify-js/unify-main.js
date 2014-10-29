// Unify - Main
// Developed for Unify by Unit Interactive
// unify.unitinteractive.com
//
// Built on jQuery - jquery.com
//
var ifrDoc;$(function(){if(!$("#un1fyDashScrollWrap").length){tempCache()}$("#un1fyConfigRetry").bind("click",function(){window.location.reload(true)});$("#un1fyLoginWrap").css({display:"block",opacity:"0"}).fadeTo(600,1,function(){if($.browser.msie){$(this,ifrDoc)[0].style.removeAttribute("filter")}overlay()});$(window).bind("resize",windowResize);$("#un1fyLoginForm").bind("submit",loginSubmit);$("#forgotPass").bind("click",forgotShow);$("#un1fyUsername input").focus();$("#un1fyHome").bind("click",function(){checkURL(fpath())});fBrowseBind()});var cancel=function(){var e=getTinyDoc();if($("#un1fyEditWrap").length&&!$("#un1fyPromptWrap").length){$("object, embed",e).remove();tinyMCE.execCommand("mceRemoveControl",false,"un1fyEditContent");$(e).remove();$("#un1fyEditWrap").remove();$(window).unbind("keypress, keydown");if($(".unifyRepeatArea",ifrDoc).length){unifyRepBind()}removeOverlay();$("object, embed",ifrDoc).removeClass("un1fyFlash");createIcons()}else if($("#un1fyEditWrap").length&&$("#un1fyPromptWrap").length){$("#un1fyPromptWrap, #un1fyFilesWrap, #overlayEditWrap").remove();keyHandler(false);$("#un1fyEditWrap").removeClass("disabled");$("object, embed",e).removeClass("un1fyFlash")}else{$("#un1fyPromptWrap, #un1fyFilesWrap, #overlayEditWrap").remove();$(window).unbind("keypress, keydown");if(!$(".un1fyIcon, .un1fyRepIcon",ifrDoc).length&&$("[unifyid]",ifrDoc).length){createIcons()}removeOverlay();$("object, embed",ifrDoc).removeClass("un1fyFlash")}$("#un1fyUploadImgClone, #un1fyVidClone",ifrDoc).remove();$(window).bind("resize",windowResize)};var checkURL=function(e){var t=e.match("://")?e.split("://"):e;$("#un1fyFilesWrap").remove();if(t[1]){if(t[1].match("/")){t=t[1].split("/")}t=t[0];t=t.replace("www.","");var n=window.location.href;var r=n.split("//");r=r[1].split("/");r=r[0].replace("www.","");if(t==r){if($("#un1fyPageEdits .edit",ifrDoc).length){navAwayPrompt(e,"loc")}else{if($("#un1fyPageSrc").length){$.ajax({type:"POST",url:"functions/getpage.php",data:"page="+e,success:function(){if(frames["un1fyPageSrc"].contentWindow){frames["un1fyPageSrc"].contentWindow.location.href=e}else{frames["un1fyPageSrc"].location.href=e}}})}else{$.ajax({type:"POST",url:"../functions/getpage.php",data:"page="+e,success:function(){window.location="../"}})}}}else{navAwayPrompt(e)}}};var destroyFileLoader=function(e){$("#un1fyFileLoading").remove();if(e=="img"){$("#un1fyFiles h2").html("Select the image you are looking for:")}else if(e=="pages"){$("#un1fyFiles h2").html("Select the page you are looking for:")}else{$("#un1fyFiles h2").html("Select the file you are looking for:")}$("#un1fyFClose").bind("click",linkClose);if(e=="pages"){$("#un1fyPagesUL li").unbind("click").bind("click",function(){checkURL($(this).attr("href"));return false})}else if(e!="none"){$("#un1fyFileULs li").unbind("click").bind("click",function(){linkSel(this)})}if(e=="all"){$("#filesTab").css("display","block");$("#un1fyFileULs ul").css("display","none");$("#un1fyPagesUL").css("display","block");$("#pages").addClass("on");$("#filesTab a").unbind("click").bind("click",function(){$("#filesTab li.on").removeClass("on");$(this).parent().addClass("on");$("#un1fyFileULs ul").css("display","none");$($(this).attr("rel")).css("display","block")})}else{$("#filesTab").css("display","none")}$("#un1fyFileFrame").remove()};var fBrowseBind=function(){$(".un1fyFBrowse").unbind("click").bind("click",function(){linkFiles($(this).attr("rel"))})};var fileBrowser=function(e){var t;var n=e.match(/#un1fyImgAddress/)?"img":e.match(/#un1fyPages/)?"pages":"all";try{t=document.createElement('<iframe name="un1fyFileFrame" >')}catch(r){t=document.createElement("iframe")}$(t).attr({id:"un1fyFileFrame",name:"un1fyFileFrame",src:fpath()+un1fyPath+"/functions/fileload.php?type="+n,application:"yes"}).css("display","none").appendTo("body")};var fileOption=function(e){$("#un1fyFileULs").append(e)};var forgotHide=function(){$("#un1fyLoginForm").attr({action:"functions/forgotpassword.php"}).find("p.error").css("display","none");$("<li/>").attr("id","un1fyPassword").html("<br/>").prepend($("<label/>").html("Password")).append($("<input/>").addClass("un1fyInput").attr({name:"un1fyPassword",type:"password",tabindex:"2"})).append($("<p/>").addClass("error").html("Required")).insertAfter("#un1fyUsername");$("#forgot").css("display","none");$("#un1fyLoginForm").attr({action:"functions/login.php"});$("#forgot p.message").html("").css({display:"none"})};var forgotReset=function(e){var t=e.match(/domain/gi)?"#666":"#fff";$("#forgot p.message").html(e).css({color:t,display:"block"});$("#un1fyLoginIframe").remove()};var forgotShow=function(){$("#un1fyLoginForm").attr({action:"functions/forgotpassword.php"}).find("p.error").css("display","none");$("#forgot").css({opacity:"0",display:"block"}).fadeTo(300,1,function(){$("#forgotCancel").unbind("click").bind("click",forgotHide);$("#un1fyPassword").remove()})};var fpath=function(){var e=new RegExp("(/"+un1fyPath+"(/[^/]*|))$");var t=un1fyGlobalPath.replace(e,"/");return t};var getIfrDoc=function(){ifrDoc=$("#un1fyPageSrc")[0];ifrDoc=ifrDoc.contentDocument?ifrDoc.contentDocument:ifrDoc.contentWindow.document};var linkBind=function(){$("a",ifrDoc).not('.un1fyIcon, .un1fyRepIcon, [href="javascript:"], [href="javascript:void(0);"], [href="#"]').each(function(){var e=$(this,ifrDoc).attr("rel");if(e!=undefined&&!e.match(/lightbox/)){$(this,ifrDoc).unbind("click").bind("click",function(){checkURL($(this,ifrDoc)[0].href);return false})}})};var linkClose=function(){$("#un1fyFilesWrap, #un1fyPrompt #overlayEditWrap").remove();if(!$("#un1fyEditWrap, #un1fyPrompt").length){$("#overlay").remove()}$("#un1fyPrompt").removeClass("disabled");if($("[unifyid]",ifrDoc).length&&!$("#un1fyEditWrap, #un1fyPrompt").length){createIcons()}};var linkFiles=function(e){$("body").prepend($("<div/>").attr("id","un1fyFilesWrap").load(fpath()+un1fyPath+"/unify-temps/file-browser.html",function(){overlay();$("#un1fyFilesWrap").css("display","block");fileTarget=e;fileBrowser(e);promptOverlay()}))};var linkSel=function(e){var t=$(e).find("p").length?$(e).find("p").html():$(e).html();$(fileTarget).val(t);if(fileTarget=="#un1fyImgAddress"){imageSrc()}linkClose()};var loginIncorr=function(e){$("#un1fyEnter p.error").html(e).css("display","block");$("#un1fyLoginIframe").remove()};var loginSubmit=function(){if(loginValid()){var e;try{e=document.createElement('<iframe name="un1fyLoginIframe" >')}catch(t){e=document.createElement("iframe")}$(e).attr({id:"un1fyLoginIframe",name:"un1fyLoginIframe",application:"yes"}).css("display","none");$("body").append(e);$("#un1fyLoginForm")[0].submit()}else{return false}};var loginValid=function(){var e=$("#un1fyUsername input").val();var t=false;if(e==""){$("#un1fyUsername p").css("display","inline")}else{$("#un1fyUsername p").css("display","none")}if($("#un1fyPassword input").length){var n=$("#un1fyPassword input").val();if(n==""){$("#un1fyPassword p").css("display","inline")}else{$("#un1fyPassword p").css("display","none")}if(e!=""&&n!=""){t=true}return t}else{if(e!=""){t=true}return t}};var navAwayPrompt=function(e,t){overlay();if(!$("#un1fyPrompt").length){$("body").append($("<div/>").attr("id","un1fyPromptWrap").load("unify-temps/prompt.html",function(){var n=t?"Continue":"Leave Unify";var r=t?"continue":"leave";$("#un1fyPrompt h4.un1fyPromptTitle").html(n);$("#un1fyPrompt p").html("Are you sure you want proceed? You will lose any unpublished changes.");$("#un1fyPromptBtn").attr("title",n).html(r).bind("click",function(){navAwaySubmit(e,t)});$("#un1fyCancelBtn").bind("click",cancel);showPrompt("nodrag")}))}};var navAwaySubmit=function(e,t){if(t){if(e=="settings/"){window.location=e}else{if(frames["un1fyPageSrc"].contentWindow){frames["un1fyPageSrc"].contentWindow.location.href=e}else{frames["un1fyPageSrc"].location.href=e}}cancel()}else{window.location=e}};var overlay=function(e){if(!$("#overlay").length){$("<div/>").attr("id","overlay").height($(document).height()).width($(document).width()).appendTo("body").css({display:"block",opacity:"0"}).fadeTo(300,.55,function(){if(ifrDoc){if($(".un1fyIcon, .un1fyRepIcon",ifrDoc).length){destroyIcons()}if(e){if(e.tagName=="IMG"){imageEditBuilder(e)}else if(e.tagName=="OBJECT"||e.tagName=="EMBED"){videoPrompt(e)}else{createTextArea(e)}}}})}else if($("#un1fyEditWrap").length){$("#un1fyEditWrap").addClass("disabled").prepend($('<div id="overlayEditWrap"></div>'))}};var promptOverlay=function(){if($("#un1fyPrompt").length){$("#un1fyPrompt").addClass("disabled").prepend($('<div id="overlayEditWrap"></div>'))}};var removeOverlay=function(){$("#overlay").remove();$(".un1fyEle",ifrDoc).css("visibility","visible").removeClass("un1fyEle");$(".un1fyEle").css("visibility","visible").removeClass("un1fyEle")};var tempCache=function(){$.ajax({url:"unify-temps/editor.html",cache:true,dataType:"html"});$.ajax({url:"unify-temps/image-editor.html",cache:true,dataType:"html"});$.ajax({url:"unify-temps/video-editor.html",cache:true,dataType:"html"});$.ajax({url:"unify-temps/html-editor.html",cache:true,dataType:"html"});$.ajax({url:"unify-temps/link-editor.html",cache:true,dataType:"html"});$.ajax({url:"unify-temps/table-editor.html",cache:true,dataType:"html"});$.ajax({url:"unify-temps/prompt.html",cache:true,dataType:"html"});$.ajax({url:"unify-temps/upload.html",cache:true,dataType:"html"})};var windowResize=function(){try{destroyIcons()}catch(e){}if($("#overlay").length){$("#overlay").css({width:$(window).width(),height:$(window).height()})}if($(".un1fyEle",ifrDoc).length){if($("#un1fyEditWrap").length){$("#un1fyEditWrap").css("opacity","0");if(typeof (windowOrient=="function")){windowOrient()}}}if(!$("#un1fyLoginWrap, #un1fyEditWrap, #un1fyPrompt").length){if(!$(".un1fyIcon, .un1fyRepIcon",ifrDoc).length&&$("[unifyid]",ifrDoc).length){createIcons()}$("#un1fyPageSrc").height($(window).height()-60)}repCopyDragDelete()};un1fyDiagnostic=function(){publish=function(){if($("#un1fyPageEdits textarea.edit",ifrDoc).length){$("#un1fyPageEdits",ifrDoc)[0].submit()}};restoreSubmit=function(){$("#un1fyRestoreForm",ifrDoc)[0].submit()};destroyFolderLoader=null;destroyFileLoader=null;saveFeedback=null;removeFrames=null;return"Diagnostic Mode Initiated - Refresh to Exit Diagnostic Mode"}