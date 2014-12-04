<?php /* application/js/fileOption-submit.php */

# Minified version. (minified at http://closure-compiler.appspot.com/home)
$js='var audio=$(".submit-audio"),content=$(".submit-content"),file=$(".submit-file"),post=$(".submit-post"),product=$(".submit-product"),video=$(".submit-video"),submitButton;submitButton=0<audio.length?audio:0<content.length?content:0<file.length?file:0<post.length?post:0<product.length?product:video;$("#fileOption").change(function(){$(submitButton).click()});$("#imageOption").change(function(){$(submitButton).click()});$("#institution").change(function(){"add"==$(this).val()&&$(submitButton).click()});
$("#language").change(function(){"add"==$(this).val()&&$(submitButton).click()});$("#text_language").change(function(){"add"==$(this).val()&&$(submitButton).click()});$("#trans_language").change(function(){"add"==$(this).val()&&$(submitButton).click()});$("#publisher").change(function(){"add"==$(this).val()&&$(submitButton).click()});';

# Long version.
/*$js='
var audio = $(\'.submit-audio\');
var content = $(\'.submit-content\');
var file = $(\'.submit-file\');
var post = $(\'.submit-post\');
var product = $(\'.submit-product\');
var video = $(\'.submit-video\');
var submitButton;
if(audio.length>0)
   submitButton=audio;
else if(content.length>0)
   submitButton=content;
else if(file.length>0)
   submitButton=file;
else if(post.length>0)
   submitButton=post;
else if(product.length>0)
   submitButton=product;
else submitButton=video;
$(\'#fileOption\').change(function(){
   $(submitButton).click();
});
$(\'#imageOption\').change(function(){
   $(submitButton).click();
});
$(\'#institution\').change(function(){
   if($(this).val()==\'add\')
       $(submitButton).click();
});
$(\'#language\').change(function(){
   if($(this).val()==\'add\')
       $(submitButton).click();
});
$(\'#text_language\').change(function(){
   if($(this).val()==\'add\')
       $(submitButton).click();
});
$(\'#trans_language\').change(function(){
   if($(this).val()==\'add\')
       $(submitButton).click();
});
$(\'#publisher\').change(function(){
   if($(this).val()==\'add\')
       $(submitButton).click();
});';*/