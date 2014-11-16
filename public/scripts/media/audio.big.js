//<![CDATA[
$(document).ready(function(){
	var player = $('#player').get(0);
	$(function(){
		checkFields($('.audio_type_radio:checked'));
		$('.audio_type_radio').click(function(){checkFields(this);});
	});
	$('a[ref=openAudio]').click(function(){
		player.play();
	});
	$('a[ref=closeAudio]').click(function(){
		player.pause();
	});
	$(document).keyup(hideModal);
	$('.overlay').click(hideModal);

	function checkFields(selector){
		var value=$(selector).val();
		$('#embed').toggle(value=='embed');
		$('#file').toggle(value=='file');
	};
	function hideModal(event){
		if(event.keyCode == 27)
			window.location.hash = '#';
		else if(event.type === 'click')
			window.location.hash = '#';
		player.pause();
	};
});
//]]>