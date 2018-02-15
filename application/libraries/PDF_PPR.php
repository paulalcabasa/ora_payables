<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');  

require_once APPPATH."/third_party/TCPDF/tcpdf.php";
 
class PDF_PPR extends TCPDF {

	private $data = array();

	protected $last_page_flag = false;

	public function __construct() {
		parent::__construct();
	}

	public function setData($data){
		
		$this->data = $data;
	}

	public function Close() {
		$this->last_page_flag = true;
		parent::Close();
	}

	//Page header
    public function Header() {
        //~ Logo
		$image_file = base_url() . 'images/isuzu_logo.jpg';
		$this->Image($image_file, 10, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		
		//~ IPC
		$this->SetFont('helvetica', 'B', 12);
		$html = "Philippines Corporation";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 41, $y = 10, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		
		/*$this->SetFont('helvetica', 'N', 9);
		$html = "114 Technology Avenue, Laguna Technopark Phase II, Biñan, Laguna 4024 Philippines";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 16, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		
		$html = "Tel. No. (049) 541-0224 to 26	|	Fax No. (+632) 842-0202	| VAT Reg. TIN : 004-834-871-00000";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 20, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);*/
		
		//~ line
		/*$style = array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10, 26, 200, 26, $style);*/
    }


    // Page footer
   public function Footer() {
	   
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->SetFont('helvetica', '', 8);
        // Set font
       // $this->SetFont('helvetica', 'I', 10);
		
		if ($this->last_page_flag) {
		// ... footer for the last page ...
			$html = '<table>
						<tr>
							<td>Prepared By:</td>
							
							<td>Approved By:</td>
						</tr>
						<tr>
							<td>'.$_SESSION['fnbi_first_name'] . " " . $_SESSION['fnbi_last_name'].'</td>
						
							<td>MARY GRACE SERVAÑEZ / ERIC ALCONES</td>
						</tr>
					 </table>';
			$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 285, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		
		//	$this->Cell(0, 10, $this->data['checked_by'], 0, false, 'L', 0, '', 0, false, 'T', 'M');
			//$this->Cell(0, 10, $this->data['approved_by'], 0, false, 'L', 0, '', 0, false, 'T', 'M');
		}
		/* else {
		// ... footer for the normal page ...
		}*/
	}

}
