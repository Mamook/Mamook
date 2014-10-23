//<![CDATA[
$(document).ready(function(){
	var player = $("#player").get(0);
	$(function(){
		checkFields($(".audio_type_radio:checked"));
		$(".audio_type_radio").click(function(){checkFields(this);});
	});
	$("a[ref=openAudio]").click(function(){
		player.play();
	});
	$("a[ref=closeAudio]").click(function(){
		player.pause();
	});
	$(document).keyup(hideModal);
	$(".overlay").click(hideModal);

	function checkFields(el){
		var sel = $(el).val();
		$("#embed").toggle(sel == "embed");
		$("#file").toggle(sel == "file");
	};
	function hideModal(e){
		if(e.keyCode == 27)
			window.location.hash = "#";
		else if(e.type === "click")
			window.location.hash = "#";
		player.pause();
	};
});
//]]>