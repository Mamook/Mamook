<?php /* application/views/secure/MyAccount/profile.php */

echo '<section id="main" class="main secure">',
	$display_content,
	# Display the profile form.
	$display,
'</section>',

'<section id="box1" class="box1">',
	'<div id="box1a">',
	'</div>',
	'<div id="box1b">';
		$img=$user_obj->getImg();
		$cv=$user_obj->getCV();
		if(!empty($img))
		{
			echo '<a href="'.IMAGES.'original/'.$img.'" class="profile-image" rel="lightbox" title="'.((!empty($img_title)) ? $img_title : $display_name).'" target="_blank"><img src="'.IMAGES.$img.'?vers='.mt_rand().'" alt="'.((!empty($img_title)) ? $img_title : $display_name).'" /></a>';
		}
		if(!empty($cv))
		{
			$user_cv='<div class="profile-cv">';
			$user_cv.='<span class="label">Your current <abbr title="Curriculum Vitae">CV</abbr> is:</span>';
			$user_cv.='<a href="'.DOWNLOADS.'?f='.$cv.'&t=cv" title="Download your CV">'.$cv.'</a>';
			$user_cv.='</div>';
			echo $user_cv;
		}
	echo '</div>',
	'<div id="box1c">',
	'</div>',
'</section>',

'<section id="menu2" class="box2">',
'</section>';