<?php /* templates/ManageImage_nav.php */

$image_nav='<div id="menu2a">'.
'</div>'.
'<div id="menu2b">'.
  '<ul>'.
    '<li'.Document::addHereClass(ADMIN_URL.'ManageMedia/image/?add').' id="menu2_list1">'.
      '<a href="'.ADMIN_URL.'ManageMedia/image/?add=yes" id="menu2_link1" title="Add an Image">Add Image</a>'.
    '</li>'.
    '<li'.Document::addHereClass(ADMIN_URL.'ManageMedia/image/?edit').' id="menu2_list2">'.
    	'<a href="'.ADMIN_URL.'ManageMedia/image/?edit=yes" id="menu2_link2" title="Edit an Image">Edit Image</a>'.
    '</li>'.
    '<li'.Document::addHereClass(ADMIN_URL.'ManageMedia/image/?delete').' id="menu2_list3">'.
      '<a href="'.ADMIN_URL.'ManageMedia/image/?delete=yes" id="menu2_link3" title="Delete an Image">Delete Image</a>'.
    '</li>'.
  '</ul>'.
'</div>'.
'<div id="menu2c">'.
'</div>';