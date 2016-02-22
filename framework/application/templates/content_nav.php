<?php /* framework/application/templates/content_nav.php */

echo '<ul class="nav-2">',
	'<li class="list-nav-2', Document::addHereClass(ADMIN_URL.'ManageContent/content/', FALSE, FALSE), '">',
		'<a href="', ADMIN_URL, 'ManageContent/content/" title="Manage Main Content on Pages">Manage Main Content</a>',
	'</li>';

if($login->checkAccess(ANNOUNCEMENT_USERS)===TRUE)
{
	echo '<li class="list-nav-2 hover', Document::addHereClass(ADMIN_URL.'ManageContent/announcement/', FALSE, FALSE), '">',
		'<a href="', ADMIN_URL, 'ManageContent/announcement/" title="Manage Announcements">Announcements</a>',
			'<ul class="nav-3">',
				'<li class="list-nav-3', Document::addHereClass(ADMIN_URL.'ManageContent/announcement/', TRUE, FALSE), '">',
					'<a href="', ADMIN_URL, 'ManageContent/announcement/" title="Post Announcement">Post Announcement</a>',
				'</li>',
				'<li class="list-nav-3', Document::addHereClass(ADMIN_URL.'ManageContent/announcement/?edit', TRUE, FALSE), '">',
					'<a href="', ADMIN_URL, 'ManageContent/announcement/?edit" title="Edit / Delete Post">Edit / Delete Announcement</a>',
				'</li>',
				'<li class="list-nav-3">',
					'<a href="', APPLICATION_URL, 'announcement/" title="New News about You">Announcements</a>',
				'</li>',
			'</ul>',
	'</li>';
}

if($login->checkAccess(ALL_BRANCH_USERS)===TRUE)
{
	echo /* '<li class="list-nav-2 hover', Document::addHereClass(ADMIN_URL.'ManageContent/categories/', FALSE, FALSE), '">',
		'<a href="', ADMIN_URL, 'ManageContent/categories/" title="Add / Edit / Delete a Category">Categories</a>',
			'<ul class="nav-3">',
				'<li class="list-nav-3', Document::addHereClass(ADMIN_URL.'ManageContent/categories/', FALSE, FALSE), '">',
					'<a href="', ADMIN_URL, 'ManageContent/categories/" title="Add a New Category">Add a New Category</a>',
				'</li>',
				'<li class="list-nav-3', Document::addHereClass(ADMIN_URL.'ManageContent/categories/edit/', FALSE, FALSE), '">',
					'<a href="', ADMIN_URL, 'ManageContent/categories/edit/" title="Edit / Delete a Category">Edit / Delete a Category</a>',
				'</li>',
			'</ul>',
	'</li>', */
	'<li class="list-nav-2 hover', Document::addHereClass(ADMIN_URL.'ManageContent/institutions/', FALSE, FALSE), '">',
		'<a href="', ADMIN_URL, 'ManageContent/institutions/" title="Add / Edit / Delete a Institution"', ((strstr(FULL_URL, 'admin/ManageContent/institutions/')!==FALSE) ? '' : ' class="hover"'), '>Institutions</a>',
			'<ul class="nav-3">',
				'<li class="list-nav-3', Document::addHereClass(ADMIN_URL.'ManageContent/institutions/', FALSE, FALSE), '">',
					'<a href="', ADMIN_URL, 'ManageContent/institutions/" title="Add / Edit / Delete an Institution">Add / Edit / Delete an Institution</a>',
				'</li>',
			'</ul>',
	'</li>',
	'<li class="list-nav-2 hover', Document::addHereClass(ADMIN_URL.'ManageContent/languages/', FALSE, FALSE), '">',
		'<a href="', ADMIN_URL, 'ManageContent/languages/" title="Add / Edit / Delete a Language">Languages</a>',
			'<ul class="nav-3">',
				'<li class="list-nav-3', Document::addHereClass(ADMIN_URL.'ManageContent/languages/', FALSE, FALSE), '">',
					'<a href="', ADMIN_URL, 'ManageContent/languages/" title="Add / Edit / Delete a Language">Add / Edit / Delete a Language</a>',
				'</li>',
			'</ul>',
	'</li>',
	'<li class="list-nav-2 hover', Document::addHereClass(ADMIN_URL.'ManageContent/publishers/', FALSE, FALSE), '">',
		'<a href="', ADMIN_URL, 'ManageContent/publishers/" title="Add / Edit / Delete a Publisher">Publishers</a>',
			'<ul class="nav-3">',
				'<li class="list-nav-3', Document::addHereClass(ADMIN_URL.'ManageContent/publishers/', FALSE, FALSE), '">',
					'<a href="', ADMIN_URL, 'ManageContent/publishers/" title="Add, Edit, or Delete a Publisher">Add / Edit / Delete Publisher</a>',
				'</li>',
			'</ul>',
	'</li>',
	'<li class="list-nav-2 hover', Document::addHereClass(ADMIN_URL.'ManageContent/products/', FALSE, FALSE), '">',
		'<a href="', ADMIN_URL, 'ManageContent/products/" title="Add / Edit / Delete a Product">Products</a>',
			'<ul class="nav-3">',
				'<li class="list-nav-3', Document::addHereClass(ADMIN_URL.'ManageContent/products/', FALSE, FALSE), '">',
					'<a href="', ADMIN_URL, 'ManageContent/products/" id="child_link4p1" title="Add, Edit, or Delete a Product">Add / Edit / Delete Product</a>',
				'</li>',
			'</ul>',
	'</li>';
}

if($login->checkAccess(ADMIN_USERS)===TRUE)
{
	echo /* '<li class="list-nav-2 hover', Document::addHereClass(ADMIN_URL.'ManageContent/links/', FALSE, FALSE), '">',
		'<a href="', ADMIN_URL, 'ManageContent/links/" title="Add / Edit / Delete a Link">Links</a>',
			'<ul class="nav-3">',
				'<li class="list-nav-3', Document::addHereClass(ADMIN_URL.'ManageContent/links/', FALSE, FALSE), '">',
					'<a href="', ADMIN_URL, 'ManageContent/links/" title="Add a New Link">Add a New Link</a>',
				'</li>',
				'<li class="list-nav-3', Document::addHereClass(ADMIN_URL.'ManageContent/links/edit/', FALSE, FALSE), '">',
					'<a href="', ADMIN_URL, 'ManageContent/links/edit/" title="Edit / Delete a Link">Edit / Delete a Link</a>',
				'</li>',
			'</ul>',
	'</li>', */
	'<li class="list-nav-2 hover', Document::addHereClass(ADMIN_URL.'ManageContent/positions/', FALSE, FALSE), '">',
		'<a href="', ADMIN_URL, 'ManageContent/positions/" title="Add / Edit / Delete a Position">Positions</a>',
			'<ul class="nav-3">',
				'<li class="list-nav-3', Document::addHereClass(ADMIN_URL.'ManageContent/positions/', FALSE, FALSE), '">',
					'<a href="', ADMIN_URL, 'ManageContent/positions/" title="Add, Edit, or Delete a Position">Add / Edit / Delete Position</a>',
				'</li>',
			'</ul>',
	'</li>';
}
echo '</ul>';