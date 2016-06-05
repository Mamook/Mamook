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
	 * Footer()
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
		$this->SetFont('Arial','I',8);
		# Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
} #=== End PDF class.