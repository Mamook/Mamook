<?php /* application/views/secure/admin/ManageUsers/index.php */

echo '<main id="main" class="main secure" role="main">';
# Display the content and any errors.
$main_content->displayContent($image_link);
# Check if there is GET data and that the passed variable is $_GET['user'].
if(isset($_GET['user'])&&($login->checkAccess(ADMIN_USERS)===TRUE))
{
	# Display the profile form.
	echo $display;
}
else
{
	echo '<table width="100%">',
		'<tr>',
			'<th>',
				'<a href="'.ADMIN_URL.'ManageUsers/?orderby=ID&dir='.$order_direction.'" title="Sort by ID">ID</a>',
			'</th>',
			'<th>',
				'<a href="'.ADMIN_URL.'ManageUsers/?orderby=Username&dir='.$order_direction.'" title="Sort by Username">Username</a>',
			'</th>',
			'<th>',
				'<a href="'.ADMIN_URL.'ManageUsers/?orderby=FirstName&dir='.$order_direction.'" title="Sort by First Name">First Name</a>',
			'</th>',
			'<th>',
				'<a href="'.ADMIN_URL.'ManageUsers/?orderby=LastName&dir='.$order_direction.'" title="Sort by Last Name">Last Name</a>',
			'</th>',
			/*
			'<th>',
				'User Level',
			'</th>',
			*/
		'</tr>';
		foreach($records as $row):
			echo '<tr>',
				'<td>',
					$row->ID,
				'</td>',
				'<td>',
					'<a href="'.ADMIN_URL.'ManageUsers/?user='.$row->ID.'">'.$row->username.'</a>',
				'</td>',
				'<td>',
					'<a href="'.ADMIN_URL.'ManageUsers/?user='.$row->ID.'">'.$row->fname.'</a>',
				'</td>',
				'<td>',
					'<a href="'.ADMIN_URL.'ManageUsers/?user='.$row->ID.'">'.$row->lname.'</a>',
				'</td>',
				/*
				'<td>',
					$row->level,
				'</td>',
				*/
			'</tr>';
		endforeach;
	echo '</table>',
	# Display the pagenavigator.
	$paginator->getNavigator();
}
echo '</main>',

'<section id="box1" class="box1">',
	'<div id="box1a" class="box1-a">',
	'</div>',
	'<div id="box1b" class="box1-b">';
# Check if there is GET data and that the passed variable is $_GET['user'].
if(isset($_GET['user']))
{
	$img=$staff_obj->getImg();
	$display_name=$staff_obj->getDisplayName();
	$cv=$staff_obj->getCV();
	if(!empty($img))
	{
		$img_title=$staff_obj->getImgTitle();
		$user_image='<div class="profile-image">';
		$user_image.='<a href="'.IMAGES.'original/'.$img.'" rel="lightbox" title="'.((!empty($img_title)) ? $img_title : $display_name).'" target="_blank"><img src="'.IMAGES.$img.'?vers='.mt_rand().'" alt="'.((!empty($img_title)) ? $img_title : $display_name).'" /></a>';
		$user_image.='</div>';
		echo $user_image;
	}
	if(!empty($cv))
	{
		$user_cv='<div class="profile-cv">';
		$user_cv.='<span class="label">'.$display_name.'\'s current CV is:</span>';
		$user_cv.='<a href="'.DOWNLOADS.'?f='.$cv.'&t=cv" title="Download '.$display_name.'\'s CV">'.$cv.'</a>';
		$user_cv.='</div>';
		echo $user_cv;
	}
}
echo '</div>',
	'<div id="box1c" class="box1-c">',
	'</div>',
'</section>',

'<section id="box2" class="box2">',
'</section>';