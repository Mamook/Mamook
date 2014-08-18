<?php /* templates/s_error_box.php */

if((isset($error) && !empty($error)) || (isset($_SESSION['message']) && !empty($_SESSION['message'])))
{
	echo '<div class="empty"></div>'.
	'<div class="s_error_box">';
		if(isset($error))
		{
			echo '<p>'.$error.'</p>';
			# clear the error
			unset($error);
		}
		if(isset($_SESSION['message']))
		{
			echo '<p>'.$_SESSION['message'].'</p>';
			# Clear the message
			unset($_SESSION['message']);
		}
	echo '</div>'.
	'<div class="empty"></div>';
}