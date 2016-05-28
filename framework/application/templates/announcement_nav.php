<?php /* framework/application/templates/announcement_nav.php */

# Retrieve records by year.
$years=$subcontent_obj->returnYears('Announcement');
$year_list='';
# Check if there were records returned.
if($years!==NULL)
{
	foreach($years as $year)
	{
		$year_list.='<li class="list-nav-1'.Document::addHereClass(APPLICATION_URL.'announcement/?year='.$year->year, FALSE, FALSE).'">
			<a href="'.APPLICATION_URL.'announcement/?year='.$year->year.'" title="Announcements from '.$year->year.'">'.$year->year.'</a>
		</li>';
	}
}

$branch_nav='<ul class="nav-1 branch">'.
	'<li class="list-nav-1'.Document::addHereClass(APPLICATION_URL.'announcement/', TRUE, FALSE).'">'.
		'<a href="'.APPLICATION_URL.'announcement/" title="All Announcements">All Announcements</a>'.
	'</li>'.
	$year_list.
'</ul>';