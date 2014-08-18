<?php /* applications/views/secure/admin/ManageUsers/privacy.php */

echo '<div id="main" class="main secure privacy">',
	# Display the content.
	$main_content->displayContent($image_link);
	require TEMPLATES.'forms'.DS.'privacy.php';
	echo $display_privacy_form;
echo '</div>';