<?php
 // update
class AP_Invoices_model extends CI_Model {
	
	private $oracle = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->oracle = $this->load->database('oracle', true);
	}

	public function get_unpaid_invoices($supplier_id,$supplier_site_id,$due_date){

    $sql = "SELECT *
            FROM (SELECT 
                         invoice_id,
                        doc_sequence_value,
                        nvl(sum(proposed_payment_amount),0) proposed_payment_amount,
                        invoice_amount, 
                        total_invoice_amount, 
                        paid_amount, 
                        invoice_amount_less_wht,
                        invoice_amount_less_wht -  nvl(sum(nvl(proposed_payment_amount,paid_amount)),0) balance, 
                        wht,
                        net,
                        vat,
                        gl_date, 
                        creation_date, 
                        supplier_id, 
                        supplier_name, 
                        invoice_num, 
                        invoice_date, 
                        invoice_currency_code, 
                        exchange_rate, 
                        goods_received_date, 
                        terms_date, 
                        terms, 
                        due_date, 
                        status, 
                        approval_status, 
                        created_by, 
                        created_by_name, 
                        liability_account,
                        org_id
                      
            FROM (
                            SELECT    
            --                ppr_header_id,
            --                ppr_line_id,
                                        proposed_payment_amount,
                                        invoice_amount - wht invoice_amount_less_wht,
                                        invoice_id,
                                        doc_sequence_value, 
                                        gl_date, 
                                        creation_date, 
                                        supplier_id, 
                                        supplier_name, 
                                        invoice_num, 
                                        invoice_date, 
                                        invoice_amount, 
                                        total_invoice_amount, 
                                        paid_amount, 
                                        invoice_amount - paid_amount balance, 
                                        invoice_currency_code, 
                                        exchange_rate, 
                                        goods_received_date, 
                                        terms_date, 
                                        name terms, 
                                        due_date, 
                                        status, 
                                        approval_status, 
                                        created_by, 
                                        created_by_name, 
                                        liability_account,
                                        org_id,
                                        wht,
                                        net,
                                        vat
                        FROM (
                                SELECT
                                       
                        --               pr_details.ppr_header_id,
                        --               pr_details.ppr_line_id,
                                       pr_details.proposed_payment_amount proposed_payment_amount,
                                       aia.doc_sequence_value, 
                                       TO_CHAR(aia.gl_date) gl_date, 
                                       TO_CHAR(aia.creation_date) creation_date, 
                                       aps.segment1 supplier_id, 
                                       aps.vendor_name supplier_name, 
                                       aia.invoice_num, 
                                       aia.invoice_id,
                                       aia.org_id,
                                       TO_CHAR(aia.invoice_date) invoice_date, 
                                       aia.invoice_amount, 
                                       aia.invoice_amount total_invoice_amount,
                                   --    APPS.IPC_GET_TOTAL_INVOICE_AMOUNT(aia.invoice_id) total_invoice_amount, 
                                       aia.invoice_currency_code, 
                                       aia.exchange_rate, 
                                       TO_CHAR(aia.goods_received_date) goods_received_date, 
                                       TO_CHAR(aia.terms_date) terms_date, 
                                       TO_CHAR(aia.terms_date + atl.due_days) due_date, 
                                       apt.name, 
                                       DECODE(AP_INVOICES_PKG.GET_POSTING_STATUS(aia.INVOICE_ID), 'D', 'No', 'N', 'No', 'P', 'Partial', 'Y', 'Yes') accounted, 
                                       DECODE(APPS.AP_INVOICES_PKG.GET_APPROVAL_STATUS(aia.invoice_id, aia.invoice_amount, aia.payment_status_flag, aia.invoice_type_lookup_code), 'NEVER APPROVED', 'Never Validated', 'NEEDS REAPPROVAL', 'Needs Revalidation', 'CANCELLED', 'Cancelled', 'Validated') status, 
                                       aia.wfapproval_status approval_status, 
                                       aia.created_by, 
                                       ppf.full_name created_by_name, 
                                       gcc.segment6 liability_account, 
                                       nvl(AP_INVOICES_PKG.GET_AMOUNT_WITHHELD (aia.INVOICE_ID),0) wht,
                                        aia.invoice_amount - nvl(aia.total_tax_amount,0) net,
                                        aia.total_tax_amount vat,
                                        nvl(aia.amount_paid,0) paid_amount
                                  FROM ap_invoices_all aia 
                                       INNER JOIN ap_suppliers aps 
                                          ON aia.vendor_id = aps.vendor_id 
                                       INNER JOIN ap_terms_tl apt 
                                          ON apt.term_id = aia.terms_id 
                                       INNER JOIN ap_terms_lines atl 
                                          ON atl.term_id = apt.term_id          
                                       INNER JOIN fnd_user fu 
                                         ON fu.user_id = aia.created_by 
                                       LEFT JOIN per_people_f ppf 
                                         ON ppf.employee_number = fu.user_name 
                                       INNER JOIN gl_code_combinations_kfv gcc 
                                         ON gcc.code_combination_id = aia.accts_pay_code_combination_id 
                                       -- payments
                                                 
                                        -- PPR Requests
                                        LEFT JOIN (SELECT pr_head.ppr_header_id,
                                                                       pr_line.ppr_line_id,
                                                                       pr_line.ap_invoice_id,
                                                                       pr_line.proposed_payment_amount
                                                          FROM IPC.IPC_PPR_LINES pr_line,
                                                                     IPC.IPC_PPR_HEADERS pr_head 
                                                          WHERE  1 = 1
                                                                        AND  PR_HEAD.PPR_HEADER_ID = pr_line.ppr_header_id
                                                                        AND pr_line.status_id = 5
                                                                        AND pr_head.status_id = 4) pr_details
                                            ON pr_details.ap_invoice_id = aia.invoice_id

                                 WHERE aia.cancelled_date IS NULL
                                              -- CASE WHEN aia.payment_status_flag IN ('P','N') THEN 0 ELSE aia.invoice_id END
                                            AND aia.vendor_site_id = ?
                                   --        and aia.invoice_date > '15-DEC-2017'
                          )
                         WHERE 1 = 1 
                              --     AND invoice_num = '0000190'
                                    AND abs(paid_amount) < abs(invoice_amount)
                                    AND CASE WHEN wht = 0 THEN 1
                                    ELSE WHT END <> (invoice_amount - paid_amount)
                                    AND supplier_id = NVL(?, supplier_id)
                                    AND TO_DATE(due_date) <= NVL(TO_DATE(?),TO_DATE(due_date))
                         ORDER BY invoice_num ASC
            )
            GROUP BY 
                        invoice_amount_less_wht,
                        invoice_id,
                        doc_sequence_value, 
                        gl_date, 
                        creation_date, 
                        supplier_id, 
                        supplier_name, 
                        invoice_num, 
                        invoice_date, 
                        invoice_amount, 
                        total_invoice_amount, 
                        paid_amount, 
                        balance, 
                        invoice_currency_code, 
                        exchange_rate, 
                        goods_received_date, 
                        terms_date, 
                        terms, 
                        due_date, 
                        status, 
                        approval_status, 
                        created_by, 
                        created_by_name, 
                        liability_account,
                        org_id,
                        wht,
                        net,
                        vat)
            WHERE BALANCE <> 0
            ORDER BY invoice_num";
		$query = $this->oracle->query($sql,array($supplier_site_id,$supplier_id,$due_date));
		return $query->result();
	}

	
}