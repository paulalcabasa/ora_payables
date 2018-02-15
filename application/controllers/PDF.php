<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PDF extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('encryption');
		
	}

	public function print_request(){
		// generate pdf content
		$header = "";
		$body = "";
		$footer = "";


		if($this->uri->segment(3) != ""){
			$this->load->model('ppr_requests_model');
			$ppr_header_id = decode_string($this->uri->segment(3))	;
			$ppr_header_details = $this->ppr_requests_model->get_ppr_header_details($ppr_header_id);
			$ppr_line_details = $this->ppr_requests_model->get_ppr_line_details(array($ppr_header_id,5));
			/*$prepared_by = $this->input->post('txt_prepared_by');
			$checked_by = $this->input->post('txt_checked_by');
			$approved_by = $this->input->post('txt_approved_by');
*/
			$net_total = 0;
			$invoice_total = 0;
			$vat_total = 0;
			$wht_total = 0;
			$payment_total = 0;

			$header = '<h1 align="center" style="padding:0;margin:0;line-height:-2;">Payment Proposal</h1>
					   <table border="0" style="font-size:13px;">
					   <tr>
							<td width="190">Payment Process Request No: </td>
							<td width="550" style="font-weight:bold;font-size:14px;">'.sprintf('%05d',$ppr_header_id).'</td>
						</tr>
						<tr>
							<td width="190">Supplier Name: </td>
							<td width="550">'.$ppr_header_details->VENDOR_NAME.'</td>
						</tr>
						<tr>
							<td width="190">Supplier Site: </td>
							<td width="550">'.$ppr_header_details->VENDOR_SITE_CODE.'</td>
						</tr>
						<tr>
							<td width="190">Planned Pay Date: </td>
							<td width="550">'.$ppr_header_details->PLANNED_PAY_DATE.'</td>
						</tr>
					   </table>';
			$row_ctr = 1;
			$body = '<br/><br/><table border="0" style="font-size:11px;" cellpadding="3">
						<thead>
							<tr style="font-weight:bold;">
								<th style="width:10%;">No.</th>
								<th style="width:15%;">Invoice Number</th>
								<th style="width:15%;">Invoice Amount</th>
								<th style="width:15%;">VAT Amount</th>
								<th style="width:15%;">Net Amount</th>
								<th style="width:15%;">WHT Amount</th>
								<th style="width:15%;">Payment Amount</th>
							</tr>
						</thead>
						<tbody>
				     ';

			foreach($ppr_line_details as $row){
				$payment_amount = $row->PROPOSED_PAYMENT_AMOUNT == "" ? ($row->INVOICE_AMOUNT - $row->WHT) : $row->PROPOSED_PAYMENT_AMOUNT;
				$body .= '<tr>
							<td style="width:10%;">'.$row_ctr.'</td>
							<td style="width:15%;">'.$row->AP_INVOICE_NUM.'</td>
							<td style="width:15%;" align="right">'.number_format($row->INVOICE_AMOUNT,2,'.',',').'</td>
							<td style="width:15%;" align="right">'.number_format($row->VAT,2,'.',',').'</td>
							<td style="width:15%;" align="right">'.number_format($row->NET,2,'.',',').'</td>
							<td style="width:15%;" align="right">'.number_format($row->WHT,2,'.',',').'</td>
							<td style="width:15%;" align="right">'.number_format(($payment_amount),2,'.',',').'</td>
						  </tr>';
				$row_ctr++;
				$net_total += $row->NET;
				$invoice_total += $row->INVOICE_AMOUNT;
				$vat_total += $row->VAT;
				$wht_total += $row->WHT;
				$payment_total += $payment_amount;
			}

			$body .= '</tbody>';

			$footer = '<tfoot>
						<tr><td colspan="7"></td></tr>
						<tr style="font-weight:bold;" >
							<td>Grand Total</td>
							<td></td>
							<td align="right" style="border-top:thin solid black;border-bottom:thin solid double black;">'.number_format($invoice_total,2,'.',',').'</td>
							<td align="right" style="border-top:thin solid black;border-bottom:thin solid double black;">'.number_format($vat_total,2,'.',',').'</td>
							<td align="right" style="border-top:thin solid black;border-bottom:thin solid double black;">'.number_format($net_total,2,'.',',').'</td>
							<td align="right" style="border-top:thin solid black;border-bottom:thin solid double black;">'.number_format($wht_total,2,'.',',').'</td>
							<td align="right" style="border-top:thin solid black;border-bottom:thin solid double black;">'.number_format($payment_total,2,'.',',').'</td>
						</tr>
					   </tfoot>
					   </table>';
			$this->load->library('PDF_PPR');
		
			
			// create new PDF document
			$pdf = new PDF_PPR('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			/*$pdf->setData(
						array(
							"prepared_by" => $prepared_by,
							"checked_by" => $checked_by,
							"approved_by" => $approved_by,
						)
					);*/

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Isuzu');
			$pdf->SetTitle('IPC Portal');
			$pdf->SetSubject('IPC Portal');
			$pdf->SetKeywords('IPC Portal');
			// set default header data
			$pdf->SetheaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
			$pdf->setFooterData(array(0,0,0), array(0,0,0));
			// set header and footer fonts
			$pdf->setheaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT - 10, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT - 5);
			$pdf->SetheaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			// set some language-dependent strings (optional)
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
			// set default font subsetting mode
			$pdf->setFontSubsetting(true);
			// Add a page
			// tdis metdod has several options, check tde source code documentation for more information.
			$pdf->AddPage();


			$pdf_content = $header;
			$pdf_content .= $body;
			$pdf_content .= $footer;

			
			
			
			
			// output the HTML content
			$pdf->writeHTML($pdf_content, true, false, true, false, '');
			// ---------------------------------------------------------
			// Close and output PDF document
			// tdis metdod has several options, check tde source code documentation for more information.
			$pdf->Output("ppr-" . date('Ymdhis') . ".pdf",'I');
		}
		else {
			header("location:".base_url()."ppr_requests/all_ppr_requests");
		}
	}

	
}
