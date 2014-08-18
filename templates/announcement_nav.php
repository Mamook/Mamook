<?php /* templates/announcement_nav.php */

# Retrieve records by year.
$years=$subcontent->returnYears('Announcement');
$year_list='';
# Check if there were records returned.
if($years!==NULL)
{
	foreach($years as $year)
	{
		$year_list='<li'.Document::addHereClass(APPLICATION_URL.'announcement/?year='.$year->year).' id="menu2_list2">
			<a href="'.APPLICATION_URL.'announcement/?year='.$year->year.'" title="Announcements from '.$year->year.'" name="menu2_link2a" id="menu2_link2a">'.$year->year.'</a>
		</li>';
	}
}

$branch_nav='<ul>'.
	'<li'.Document::addHereClass(APPLICATION_URL.'announcement/', TRUE).' id="menu2_list1">'.
		'<a href="'.APPLICATION_URL.'announcement/" name="menu2_link1a" id="menu2_link1a" title="All Announcements">All Announcements</a>'.
	'</li>'.
	$year_list.
'</ul>';