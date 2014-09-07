<?php /* application/views/secure/admin/ManageUsers/ManageUser.php */

echo '<section id="main" class="main secure">',
	$display_content;
	# Get the profile form template.
	require TEMPLATES.'forms'.DS.'profile.php';
	# Display the profile form.
	echo $profile_form,
'</section>',

'<section id="box1" class="box1">',
	'<div id="box1a">',
	'</div>',
	'<div id="box1b">';
		if(!empty($img))
		{
			$user_image='<div class="user_image">';
			$user_image.='<a href="'.IMAGES.'original/'.$img.'" rel="lightbox" title="'.((!empty($img_title)) ? $img_title : $display_name).'" target="_blank"><img src="'.IMAGES.$img.'?vers='.mt_rand().'" alt="'.((!empty($img_title)) ? $img_title : $display_name).'" /></a>';
			$user_image.='</div>';
			echo $user_image;
		}
		if(!empty($cv))
		{
			$user_cv='<div class="user_cv">';
			$user_cv.='<span class="label">Your current CV is:</span>';
			$user_cv.='<a href="'.DOWNLOADS.'?f='.$cv.'&t=cv" title="Download '.$display_name.'\'s CV">'.$cv.'</a>';
			$user_cv.='</div>';
			echo $user_cv;
		}
	echo '</div>',
	'<div id="box1c">',
	'</div>',
'</section>',

'<section id="menu2" class="box2">',
'</section>';