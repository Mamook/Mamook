<?php

/***
 * SiteMap
 *
 * The SiteMap class is used to search directories for an index file and display them in a list.
 *
 */
class SiteMap
{
	/*** data members ***/

	/*** End data members ***/



	/*** magic methods ***/

	public function __construct()
	{
		return;
	}

	/*** End magic methods ***/



	/*** mutator methods ***/

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/*** End accessor methods ***/



	/*** public methods ***/

	public function showList($path)
	{
		global $ignore, $id, $divs, $imgpath, $types, $startin;

		$dirs=array();
		$files=array();

		if(is_dir($path))
		{
			if($dir = @opendir($path))
			{
				while(($file = readdir($dir)) !== false)
				{
					if($file!="." && $file!=".." && !in_array($file,$ignore))
					{
						if(is_dir("$path/$file"))
						{
							if(file_exists("$path/$file/index.php"))
								$dirs[$file]=$this->getTitle("$path/$file/index.php");
							elseif(file_exists("$path/$file/index.html"))
								$dirs[$file]=$this->getTitle("$path/$file/index.html");
							elseif(file_exists("$path/$file/index.htm"))
								$dirs[$file]=$this->getTitle("$path/$file/index.htm");
							else
								$dirs[$file]=$file;
							if(WP_INSTALLED===TRUE)
							{
								if($path.'/'.$file==WP_PATH)
								{
									$dirs[$file]=$file;
								}
							}
						}
						else {
							if(preg_match($types, $file))
							{
								$files[$file]=$this->getTitle("$path/$file");
								if(strlen($files[$file])==0)
									$files[$file]=$file;
							}
						}
					}
				}
				closedir($dir);
			}

			natcasesort($dirs);
			$url=str_replace(ROOT_PATH, '', $path);
			$n=substr_count("$url/$", "/");
			$base=substr_count($startin, "/")+1;
			$indent=str_pad("", $n-1, "\t");

			$display = $indent.'<ul id="list'.$id.'">'."\n";

			if($n>$base)
				$divs[]="$id";

			$imgsrc="minus";
			foreach($dirs as $d=>$t)
			{
				$id++;
				$display .= "$indent\t<li class=\"sitemap\">";
				$display .= "<a href=\"javascript:toggle('list$id','img$id')\"><img src=\"$imgpath/$imgsrc.gif\" id=\"img$id\" align=\"texttop\" border=\"0\" alt=\"\" /></a>";
				$display .= "<img src=\"$imgpath/folder.gif\" alt=\"\" align=\"texttop\" />";
				$display .= " <strong><a href=\"$url/$d/\">$t</a></strong>\n";
				if(WP_INSTALLED===TRUE)
				{
					if("$path/$d"!=WP_PATH)
					{
						$display .= $this->showList("$path/$d");
						$imgsrc="plus";
					}
				}
				else
				{
					$display .= $this->showList("$path/$d");
				}
				$display .= "$indent\t</li>\n";
			}
			natcasesort($files);
			$id++;
			foreach($files as $f=>$t)
			{
				$display .= "$indent\t<li class=\"sitemap\"><img style=\"padding-left:20px;\" src=\"$imgpath/html.gif\" alt=\"\" border=\"0\" /> <a href=\"$url/$f\">$t</a></li>\n";
			}
			$display .= "$indent</ul>\n";
		}
		return $display;
	}

	public function getTitle($file)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Content object to a local variable.
		$main_content=Content::getInstance();
		global $htmltypes;

		$title="";

		$p=pathinfo($file);
		$c=str_replace(ROOT_PATH, '', $p["dirname"]);

		$row = $db->get_row("SELECT `page_title`, `page` FROM `".DBPREFIX."content` WHERE `page` = '".ltrim($c."/".$p['basename'], '/')."' AND `sub_domain` IS NULL");
		if($row!==NULL)
		{
			$title=(($row->page_title) ? $row->page_title : $p['basename']);
			if($title=='%{site_name}')
			{
				$title=$main_content->getSiteName();
			}
			return htmlentities(trim(strip_tags($title)));
		}
		else { return $p['basename']; }
	}

	/*** End public methods ***/



	/*** protected methods ***/

	/*** End protected methods ***/

}