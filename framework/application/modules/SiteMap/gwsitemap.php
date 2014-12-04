<?php
/*
File Name: sitemap.php
Author: Gary White
Last modified: April 25, 2006

Copyright (C) 2004-2005  Gary White

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License in the included gpl.txt file for
details.

See the readme.txt file for installation and usage.

May 12, 2005 - Modified getTitle function to a slighly cleaner
regular expression for extracting the title from files.

April 25, 2006 - Fixed small bug to correct display when starting
in a directory other than the site's root.
 */

# A root relative path to the top level directory you want indexed.
$startin='/';

# A root relative path to the location where you place the images.
# Do NOT include a trailing slash. Correct usage would be similar to:
# $imgpath='/images/sitemap';
#
# If you leave it set to an empty string, the program will assume the
# images are located in the same directory as the script.
$imgpath=SCRIPTS.'sitemap/images';

# The $types array contains the file extensions of files you want to
# show in the site map.
$types=array(
	'.php',
	'.html',
	'.htm',
	'.shtm',
	'.sthml'
);

# The $htmltypes is an array containing the file types of HTML files,
# that is files that will contain the HTML <title> tag. The script will
# try to extract the <title> from these files. Any file types indexed
# that are NOT in this array will simply use the file name and not
# attempt to get the title.
$htmltypes=array(
	'.php',
	'.html',
	'.htm',
	'.shtm',
	'.sthml',
);

# Files and/or directories to ignore. Anything in this array will not
# be included in the site map.
$ignore=array(
	'.htaccess',
	'.htpasswd',
	'cgi-bin',
	'images',
	'index.htm',
	'index.html',
	'index.php',
	'robots.txt',
	'themes',
	'templates',
	'scripts',
	'error',
	'download',
	'secure',
	'error.log',
	'error_log',
	'test.php',
	'merge_data.php',
	'maintenance.php',
	'.svn',
	'gwsitemap.php',
	'phpinfo.php',
	'profile',
	'slideshow.php',
	'headcontent.php',
	'formmail.php',
	'error.php',
	'w3c'
);

$id=0;
$sitemap_display='<div id="sitemap"><ul id="list'.$id.'">';
$id++;
$divs='';
if(substr($startin, strlen($startin)-1, 1)=='/')
	$startin=trim($startin, '/');
$index='';
foreach($types as $type)
{
	if(file_exists(ROOT_PATH.$startin.'/index'.$type))
	{
		$index=ROOT_PATH.$startin.'/index'.$type;
		break;
	}
}

$types=join($types, '|');
$types='('.$types.')';

if(!is_array($htmltypes))
	$htmltypes=array();
if(count($htmltypes)==0)
	$htmltypes=$types;
if(!$imgpath)
	$imgpath='.';

$sitemap_display .= '<li class="sitemap">';
$sitemap_display .= '<img src="'.$imgpath.'/server.gif" align="texttop" alt=""/>';
$sitemap_display .= '<strong><a href="'.$startin.'/">'.$site_map->getTitle($index).'</a></strong>';
$sitemap_display .= $site_map->showList(ROOT_PATH.$startin);
$sitemap_display .= '</li></ul></div>';

if(is_array($divs))
{
	$divs="'".join($divs,"','")."'";
	$sitemap_display .= '<script type="text/javascript">';
	$sitemap_display .= '//<![CDATA[';
	$sitemap_display .= 'd=Array('.$divs.');';
	$sitemap_display .= 'for(i=0;i<d.length;i++){';
	$sitemap_display .= '\ttoggle("list"+d[i],"img"+d[i]);';
	$sitemap_display .= '}';
	$sitemap_display .= '//]]>';
	$sitemap_display .= '</script>';
}

return $sitemap_display;