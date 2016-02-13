<?php /* framework/application/templates/forms/publisher_form_defaults.php */

# Get the Contributor Class.
require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
# Instantiate a new Contributor object.
$contributor=new Contributor();
# Add/Update the contributor table in the Database to reflect this user.
$contributor->addContributor();

# Create defaults.
$publisher_id=NULL;
$publisher_contributor_id=$contributor->getContID();
$publisher_date=date('Y-m-d');
$publisher_recent_contributor_id=NULL;
$publisher_last_edit_date=NULL;
$publisher_info='';
$publisher_name=NULL;
$publisher_unique=0; # Set the default to "Not Unique" (0)

# Check if there is GET data called "publisher".
if(isset($_GET['publisher']))
{
	# Get the Publisher class.
	require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
	# Instantiate a new instance of the Publisher class.
	$publisher=new Publisher();
	# Set the passed publisher ID to the Publisher data member, effectively "cleaning" it.
	$publisher->setID($_GET['publisher']);
	# Set the cleaned SubContent id to a local variable.
	$pub_id=$publisher->getID();
	# Get the publisher's content from the `publisher` table.
	if($publisher->getThisPublisher($pub_id)===TRUE)
	{
		# Reset the defaults.
		$publisher_id=$pub_id;
		$publisher_contributor_id=$publisher->getContID();
		$publisher_date=$publisher->getContID();
		$publisher_recent_contributor_id=$contributor->getContID();
		$publisher_last_edit_date=date('Y-m-d');
		$publisher_info=$publisher->getInfo();
		$publisher_name=$publisher->getPublisher();
		$publisher_unique=1; # Set to "Unique" (1) since it is already a publisher.
	}
}

# The key MUST be the name of a "set" mutator method in either the Publisher, PublisherFormPopulator, or FormPopulator classes (ie setID, setUnique).
$default_data=array(
		'ID'=>$publisher_id,
		'ContID'=>$publisher_contributor_id,
		'Date'=>$publisher_date,
		'RecentContID'=>$publisher_recent_contributor_id,
		'LastEdit'=>$publisher_last_edit_date,
		'Info'=>$publisher_info,
		'Publisher'=>$publisher_name,
		'Unique'=>$publisher_unique
	);