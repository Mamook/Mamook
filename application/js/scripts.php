<?php /* application/js/scripts.php */

	# If Javascript is enabled in the user's browser, display errors in a java created div.
	$js=$this->addJSErrorBox();

	$js.=
	'$(\'#wrapper\').removeClass(\'noscript\');'.
	'$(function(){clearInput(\'#emailGo\',\'value\')});'.
	'$(function(){$(\'[rel^=lightbox]\').prettyPhoto({
		opacity:1,
		theme:\'\',
		show_title:false,
		markup:
			\'<div class="pp_pic_holder"> \
				<div class="ppt">&nbsp;</div> \
				<div class="pp_content_container"> \
					<div class="pp_content"> \
						<div class="pp_loaderIcon"></div> \
						<div class="pp_fade"> \
							<a href="#" class="pp_expand" title="Expand the image">Expand</a> \
							<div class="pp_hoverContainer"> \
							<a class="pp_previous" href="#">previous</a> \
							<a class="pp_next" href="#">next</a> \
						</div> \
						<div id="pp_full_res"></div> \
						<div class="pp_details"> \
							<p class="pp_description"></p> \
							{pp_social} \
							<a class="pp_close" href="#">Close</a> \
							<div class="pp_nav"> \
								<a href="#" class="pp_arrow_previous">Previous</a> \
								<p class="currentTextHolder">0/0</p> \
								<a href="#" class="pp_arrow_next">Next</a> \
							</div> \
						</div> \
					</div> \
				</div> \
			</div> \
		</div> \
		<div class="pp_overlay overlay"></div>\',
		social_tools: '.
		(($main_content->getUseSocial()!==NULL) ?
			'\'<!-- AddThis Button BEGIN --> \
				<div class="addthis_default_style pp_social_box"> \
				<a class="addthis_button_preferred_1"></a> \
				<a class="addthis_button_preferred_2"></a> \
				<a class="addthis_button_google_plusone"></a> \
				<a class="addthis_button_preferred_4"></a> \
				<a class="addthis_button_compact"></a> \
				<a class="addthis_counter addthis_bubble_style"></a> \
				</div> \
				<!-- AddThis Button END -->\',
			changepicturecallback:function(){addthis.toolbox(\'.pp_social_box\')}' : 'null').
	'})});'.
	'$(\'.addthis_button_google_plusone\').attr(\'g:plusone:annotation\', \'none\');';