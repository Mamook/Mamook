<?php /* framework/application/controllers/secure/admin/ManageUsers/index.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'AccountFormProcessor.php');
# Get the SearchFormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'SearchFormProcessor.php');
# Get the PageNavigator Class.
require_once Utility::locateFile(MODULES.'PageNavigator'.DS.'PageNavigator.php');

# Check if the logged in User is an Admin.
$login->checkLogin(ALL_ADMIN_MAN);

$page_class='manageUserspage';

$login->findUserData();

# Create a new User object.
$user_obj=new User();

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';
$display_search_form='';

$display='';
$head='';
# Create an empty variable for passed params.
$params='';
# Create an empty variable for the "AND" SQL statment.
$and_sql='';

$order_field='ID';
$order_direction='ASC';
# Check if there is GET data.
if(isset($_GET['orderby']) && ($_GET['orderby']=='ID' || $_GET['orderby']=='Username' || $_GET['orderby']=='FirstName' || $_GET['orderby']=='LastName'))
{
	if($_GET['orderby']=='ID') { $order_field='ID'; $params='orderby=ID'; }
	if($_GET['orderby']=='Username') { $order_field='username'; $params='orderby=Username'; }
	if($_GET['orderby']=='FirstName') { $order_field='fname'; $params='orderby=FirstName'; }
	if($_GET['orderby']=='LastName') { $order_field='lname'; $params='orderby=LastName'; }
	if(isset($_GET['dir']) && ($_GET['dir']=='ASC' || $_GET['dir']=='DESC'))
	{
		$order_direction=$_GET['dir'];
		$params.='&dir='.$_GET['dir'];
	}
}

# Check if the User is NOT a site admin.
if($login->checkAccess(ADMIN_USERS)===FALSE)
{
	# Get the Branch class.
	require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');
	# Instantiate a new Branch object.
	$branch=new Branch();
	# Retrieve all branches from the branches table.
	$retrieve_branches=$branch->getBranches(NULL, '`id`, `branch`');
	# Get all retrieved branches.
	$all_branches=$branch->getAllBranches();
	# Create an empty array to hold the branch user level values.
	$branch_levels = array();
	# Loop through the branch rows.
	foreach($all_branches as $row)
	{
		# Set the branches and their id's to the branches array.
		$branch_id=$row->id;
		$branch_name=$row->branch;
		# Set the branch admin level to the $branch_admin variable.
		$branch_admin=substr_replace($branch_id, 1, -1, 1);
		# Check the User's level.
		if($login->checkAccess($branch_admin)===TRUE)
		{
			# Set the branch user level to the $branch_levels array.
			$branch_levels[]=substr_replace($branch_id, 2, -1, 1);
			# Set the branch candidate level to the $branch_levels array.
			$branch_levels[]=substr_replace($branch_id, 4, -1, 1);
			# Set the branch author level to the $branch_levels array.
			$branch_levels[]=substr_replace($branch_id, 5, -1, 1);
		}
	}
	# Implode the $branch_levels array to stings saparated by pipes (|).
	$branch_levels=implode('|', $branch_levels);
	# Create the "AND" SQL statement.
	$and_sql='WHERE `level` REGEXP '.$db->quote('-('.$branch_levels.')-');
}

# Create a new PageNavigator object.
$paginator=new PageNavigator(25, 4, CURRENT_PAGE, 'page', $user_obj->countUsers(NULL, $and_sql), $params);
$paginator->setStrNext('Next Page');
$paginator->setStrPrevious('Previous Page');

# Get the Users
$fields='`ID`, `username`, `level`, `fname`, `lname`';
$user_obj->getUsers($paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), $fields, $order_field, $order_direction, $and_sql);

$records=$user_obj->getAllUsers();

# Reverse the value of $order_direction.
$order_direction=(($order_direction=='ASC') ? 'DESC' : 'ASC');

# Check if there is GET data and that the passed variable is $_GET['user'].
if(isset($_GET['user']))
{
	# Set the User ID to the data member; effectively cleaning it.
	$user_obj->setID($_GET['user']);
	# Set the data member to a variable.
	$id=$user_obj->getID();
	# Find the User's username and set it to a variable.
	$current_username=$user_obj->findUsername($id);

	# Make sure the User is an admin.
	if($login->checkAccess(ADMIN_USERS)===TRUE)
	{
		$head='<h3 class="h-3">Please use the form below to update user: '.$current_username.'</h3>';

		# Get the User's display name and set it to a variable.
		$display_name=$user_obj->findDisplayName($id);
		# Set the page title.
		$page_title=$display_name.'\'s Profile';
	}
	$form_processor=new AccountFormProcessor();
	require_once Utility::locateFile(TEMPLATES.'forms'.DS.'account_form.php');

	$img=$user_obj->getImg();
	$display_name=$user_obj->getDisplayName();
	$cv=$user_obj->getCV();
	if(!empty($img))
	{
		$img_title=$user_obj->getImgTitle();
		$user_image='<div class="profile-image">';
		$user_image.='<a href="'.IMAGES.'original/'.$img.'" rel="'.FW_POPUP_HANDLE.'" title="'.((!empty($img_title)) ? $img_title : $display_name).'" target="_blank"><img src="'.IMAGES.$img.'?vers='.mt_rand().'" alt="'.((!empty($img_title)) ? $img_title : $display_name).'" /></a>';
		$user_image.='</div>';
		$display_box1b.=$user_image;
	}
}
else
{
	# Instantiate a new SearchFormProcessor object.
	$search_form_processor=new SearchFormProcessor();
	# Set the type of search to a variable.
	#	Get's set in the search_form.php template.
	$search_type=array('users');
	# Set the search label.
	$search_label='Search Users';
	# Get the search form.
	require_once Utility::locateFile(TEMPLATES.'forms'.DS.'search_form.php');
}

if(!isset($_GET['user']))
{
	# Set the Search object created in SearchFormPopulator to a variable.
	$search_obj=$populator->getSearchObject();
	# Set the results to a variable.
	$results=$search_obj->getAllResults();
	# Check if this is a search.
	if(isset($results))
	{
		# Set $records to the search results to use in the foreach.
		$records=$results;
	}
	$display.='<table width="100%">'.
		'<tr>'.
			'<th>'.
				'<a href="'.ADMIN_URL.'ManageUsers/?orderby=ID&dir='.$order_direction.'" title="Sort by ID">ID</a>'.
			'</th>'.
			'<th>'.
				'<a href="'.ADMIN_URL.'ManageUsers/?orderby=Username&dir='.$order_direction.'" title="Sort by Username">Username</a>'.
			'</th>'.
			'<th>'.
				'<a href="'.ADMIN_URL.'ManageUsers/?orderby=FirstName&dir='.$order_direction.'" title="Sort by First Name">First Name</a>'.
			'</th>'.
			'<th>'.
				'<a href="'.ADMIN_URL.'ManageUsers/?orderby=LastName&dir='.$order_direction.'" title="Sort by Last Name">Last Name</a>'.
			'</th>'.
			/*
			'<th>'.
				'User Level'.
			'</th>'.
			*/
		'</tr>';
		foreach($records as $row)
		{
			$display.='<tr>'.
				'<td>'.
					$row->ID.
				'</td>'.
				'<td>'.
					'<a href="'.ADMIN_URL.'ManageUsers/?user='.$row->ID.'">'.$row->username.'</a>'.
				'</td>'.
				'<td>'.
					'<a href="'.ADMIN_URL.'ManageUsers/?user='.$row->ID.'">'.$row->fname.'</a>'.
				'</td>'.
				'<td>'.
					'<a href="'.ADMIN_URL.'ManageUsers/?user='.$row->ID.'">'.$row->lname.'</a>'.
				'</td>'.
				/*
				'<td>'.
					$row->level.
				'</td>'.
				*/
			'</tr>';
		}
	$display.='</table>';
	# Display the pagenavigator.
	$display.=$paginator->getNavigator();
}

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add any display content to main-2.
$display_main2.=$display_search_form.$display;

# Get the quote text to display in main-3.
$display_main3.=$main_content->displayQuote();

# Set the default style sheet(s) we are using for the site. (must be absolute location)
//$doc->setStyle(THEME.'css/secure.css');
# Do we need some javascripts? (Use the script file name before the ".js".)
$doc->setJavaScripts('uniform');
# Do we need some JavaScripts in the footer? (Use the script file name before the ".php".)
$doc->setFooterJS('uniform-file');

/*
** In the page template we
** get the header
** get the masthead
** get the subnavbar
** get the navbar
** get the page view
** get the quick registration box
** get the footer
*/
require Utility::locateFile(TEMPLATES.'page.php');