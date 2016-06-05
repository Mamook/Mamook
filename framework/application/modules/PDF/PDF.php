<?php /* framework/application/modules/PDF/PDF.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FPDF Class.
require_once Utility::locateFile(MODULES.'Vendor'.DS.'FPDF'.DS.'fpdf.php');

/**
 * PDF
 *
 * The PDF class is used to generate PDF files.
 *
 */
class PDF extends FPDF
{
	/**
	 * getTitle
	 *
	 * Returns the data member $metadata['Title'].
	 *
	 * @access public
	 */
	public function getTitle()
	{
		return $this->metadata['Title'];
	} #==== End -- getTitle

	/**
	 * Header
	 *
	 * PDF page header.
	 *
	 * @access public
	 */
	public function Header()
	{
		# Set the title to a variable.
		$title=$this->getTitle();
		# If there is a title set.
		if($title)
		{
			# Arial bold 15
			$this->SetFont('Arial', 'B', 15);
			# Calculate width of title and position.
			$title_width=$this->GetStringWidth($title)+6;
			$this->SetX((210-$title_width)/2);
			# Set the width ($title_width),
			#	height (9),
			#	content ($title),
			#	border (0),
			#	current position (1),
			#	alignment ('C') of the cell,
			$this->Cell($title_width, 9, $title, 0, 1, 'C');
			# Line break
			$this->Ln(10);
		}
	} #==== End -- Header

	/**
	 * Footer
	 *
	 * PDF page footer.
	 *
	 * @access public
	 */
	public function Footer()
	{
		# Position at 1.5 cm from bottom
		$this->SetY(-15);
		# Arial italic 8
		$this->SetFont('Arial', 'I', 8);
		# Page number
		$this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
	} #==== End -- Footer
} #=== End PDF class.