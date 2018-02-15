<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_C extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function export_request_xls(){
		$this->load->model('ppr_requests_model');
		$ppr_id = $this->uri->segment(3);
		$params = array(
					$ppr_id, // ppr_header_id
					5 // line status of selected
				  );
		$ppr_lines = $this->ppr_requests_model->get_ppr_line_details($params);

		
    
        $this->load->library('excel');
        $excel = PHPExcel_IOFactory::load("./files/ppr_lines_template.xlsx");


        $row = 1;
        $ctr = 1;
 
        foreach($ppr_lines as $line){
            
            $excel
             ->getActiveSheet()
             ->getCellByColumnAndRow('A', $row)
             ->setValueExplicit($line->AP_INVOICE_NUM, PHPExcel_Cell_DataType::TYPE_STRING);

            $excel->getActiveSheet()
                                  //->setCellValue('A' . $row, $line->AP_INVOICE_NUM)
                                  ->setCellValue('B' . $row , "\\{TAB}")
                                  ->setCellValue('C' . $row, $line->PROPOSED_PAYMENT_AMOUNT)
                                  ->setCellValue('D' . $row, "\\{DOWN}");



          


                   
            $row++;
    
        }


        foreach(range('B','D') as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $excel->getActiveSheet()->removeRow($row);

        /** Borders for all data */
   /*    $excel->getActiveSheet()
             ->getStyle('B4:'.'D'.($row - 1))
             ->getBorders()
             ->getAllBorders()
             ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);*/

     

        $this->load->helper('download');

        // Save and capture output (into PHP memory)
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        ob_start();
        $objWriter->save('php://output');
        $excelFileContents = ob_get_clean();

        // Download file contents using CodeIgniter
        force_download('payment_lines-'.date('YmdHis').'.xlsx', $excelFileContents);

	}
}
