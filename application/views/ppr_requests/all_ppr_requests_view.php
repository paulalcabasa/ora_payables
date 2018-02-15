<style>

	.select2-container--default .select2-selection--single {
		border-radius: 0;
	}
	.select2-container .select2-selection--single {
		height:30px;
		font-size:12px;
	}

	.select2-container .select2-selection--single .select2-selection__rendered{
		padding-left:0;
	}

	.sel_signatories {
		width:100%;
		font-size:12px;
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom" style="min-height:550px;">
	            <ul class="nav nav-tabs ">
	           		<?php
	           			if($user_type == "Regular") {
	           		?>
	                <li class="active tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="1" class="btn_status">New</a></li>
	                <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="4" class="btn_status">Submitted</a></li>
	                <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="3" class="btn_status">Cancelled</a></li>
<!-- 	                <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="24" class="btn_status">Disapproved</a></li>
	                <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="23" class="btn_status">Approved</a></li>
	                <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="41" class="btn_status">Paid</a></li>
	                <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="all" class="btn_status">All</a></li> -->
	            	<?php 
	            		}
	            	?>
	            	<?php
	           			if($user_type == "Administrator") {
	           		?>
	         <!--        <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="1" class="btn_status">New</a></li> -->
	                <li class="tab_links active"><a href="#payment_process_request" data-toggle="tab" data-status_id="4" class="btn_status">Requests</a></li>
<!-- 	                <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="3" class="btn_status">Cancelled</a></li> -->
<!-- 	                <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="24" class="btn_status">Disapproved</a></li>
	                <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="23" class="btn_status">Approved</a></li>
	                <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="41" class="btn_status">Paid</a></li>
	                <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="all" class="btn_status">All</a></li> -->
	            	<?php 
	            		}
	            	?>
	            	<?php
	           			if($user_type == "TreasuryPayer") {
	           		?>
	<!-- 
	                <li class="active tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="23" class="btn_status">For payment</a></li>
	                <li class="tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="41" class="btn_status">Paid</a></li> -->
	                <li class="active tab_links"><a href="#payment_process_request" data-toggle="tab" data-status_id="all" class="btn_status">All</a></li>
	            	<?php 
	            		}
	            	?>

	            </ul>

	            <div class="tab-content">
	            	<!-- SELECTED INVOICES -->
	                <div class="tab-pane active" id="payment_process_request">
	                <?php
		                $current_date = date('Y-m-d');
		                $current_month_range = rangeMonth($current_date);
              		?>
                <div class="row">
					<div class="col-md-6">
						<div class="form-group">  
							<label class="col-md-3 control-label">Date</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="txt_display_date" value="<?php echo format_date_slash($current_month_range['start']) . ' - ' . format_date_slash($current_month_range['end']);?>"/>
								<input type="hidden" id="txt_initial_start_date" value="<?php echo format_date_slash($current_month_range['start'])?>"/>
								<input type="hidden" id="txt_initial_end_date" value="<?php echo format_date_slash($current_month_range['end'])?>"/>
							</div>
						</div>
					</div>
                </div>
                	
	                	<table class="table nowrap table-condensed table-bordered" id="tbl_payment_process_request">
							<thead >
								<tr>
									<th>Details</th>
									<th>Print</th>
									<th>PPR ID</th>
									<th>Supplier Name</th>
									<th>Total Invoices</th>
									<th>Total Invoice Amount</th>
									<th>Total Balance Amount</th>
									<th>Bank Account Name</th>
									<th>Check Voucher</th>
									<th>Payment Date</th>
									<th>Created By</th>
									<th>Date Created</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
						
							</tbody>
						</table>
					</div>
				</div>
			</a>
		</div>
	</div>
</section>

<!-- 
<div class="modal fade"  role="dialog" id="dialog_payment_details">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Payment Details - <span id="spn_ppr_header_id"></span></h4>
      </div>
      <div class="modal-body">
		<div class="alert alert-info hidden" role="alert" id="alert_message"></div>
   	   	<form class="form">
   	   		<div class="row">
	   	   		<div class="col-md-4">
		   	   		<input type="hidden" readonly="readonly"  class="form-control" id="txt_ppr_header_id"/>
		   	   		
		   	   		<div class="form-group">
		   	   			<label class="control-label">Bank Account Name</label>
		   	   			<select class="form-control" style="width:100%;font-size:12px" id="sel_bank_account">
		   	   			<option value=""></option>
		   	   			<?php
		   	   				foreach($bank_accounts as $row){
		   	   			?>
		   	   				<option value="<?php echo $row->BANK_ACCOUNT_NUM;?>" data-bank_name="<?php echo $row->BANK_NAME;?>"><?php echo $row->BANK_ACCOUNT_NAME;?></option>
		   	   			<?php
		   	   				}
		   	   			?>
		   	   			</select>
		   	   			<span class="text-block text-danger" id="msg_bank_account_name"></span>
		   	   		</div>
		   	   		<div class="form-group">
		   	   			<label class="control-label">Bank</label>
		   	   			<input type="text" readonly="readonly" class="form-control input-sm" id="txt_bank"/>
		   	   		</div>
		   	   		<div class="form-group">
		   	   			<label class="control-label">Check Voucher No</label>
		   	   		<!-- 	<select class="form-control"></select> 
						<div class="input-group">
							<input type="text" class="form-control input-sm" id="txt_check_voucher_no" placeholder="Search for voucher no...">
							<span class="input-group-btn">
								<button class="btn btn-danger btn-sm" type="button" id="btn_search_check"><i class="fa fa-search fa-1x"></i></button>
							</span>

						</div>
						<span class="text-block text-danger" id="msg_check_voucher"></span>
		   	   		</div>
		   	   		<div class="form-group">
		   	   			<label class="control-label">Payment Document</label>
		   	   			<input type="text" readonly="readonly" class="form-control input-sm" id="txt_payment_document"/>
		   	   		</div>
		   	   		
		   	   	</div>
		   	   	<div class="col-md-4">
		   	   		<div class="form-group">
		   	   			<label class="control-label">Check No</label>
		   	   			<input type="text" readonly="readonly" class="form-control input-sm" id="txt_check_number"/>
		   	   		</div>
		   	   		<div class="form-group">
		   	   			<label class="control-label">Payment Date</label>
		   	   			<input type="text" readonly="readonly" class="form-control input-sm" id="txt_payment_date"/>
		   	   		</div>
		   	   		<div class="form-group">
		   	   			<label class="control-label">Amount</label>
		   	   			<div class="input-group input-group-sm">
						  <span class="input-group-addon" id="txt_currency">CURR</span>
						  <input type="text" class="form-control input-sm" readonly="readonly" placeholder="Amount" aria-describedby="txt_currency" id="txt_amount">
						</div>
		   	   		</div>
		   	   		<div class="form-group">
		   	   			<label class="control-label">Status</label>
		   	   			<input type="text" readonly="readonly" class="form-control input-sm" id="txt_status"/>
		   	   		</div>
		   	   	</div>
		   	   	<div class="col-md-4">
		   	   		<div class="form-group">
		   	   			<label class="control-label">Treasury Date</label>
		   	   			<input type="text" readonly="readonly" class="form-control input-sm" id="txt_treasury_date"/>
		   	   		</div>
		   	   		<div class="form-group">
		   	   			<label class="control-label">OR Number</label>
		   	   			<input type="text" readonly="readonly" class="form-control input-sm" id="txt_or_number"/>
		   	   		</div>
		   	   		<div class="form-group">
		   	   			<label class="control-label">OR Date</label>
		   	   			<input type="text" readonly="readonly" class="form-control input-sm" id="txt_or_date"/>
		   	   		</div>
		   	   		<div class="form-group">
		   	   			<label class="control-label">Voucher Text</label>
		   	   			<input type="text" readonly="readonly" class="form-control input-sm" id="txt_voucher_text"/>
		   	   		</div>
		   	   	</div>
	   	   	</div>
   	   	</form>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-danger btn-sm" id="btn_save_payment_details">Save Changes</button>
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content 
  </div><!-- /.modal-dialog 
</div><!-- /.modal -->

<!-- Dialog confirmation 
<div class="modal fade"  role="dialog" id="dialog_printing_options">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Printing Options</h4>
      </div>
      <div class="modal-body">
	      <form id="frm_ppr_request" target="_blank" method="POST" action="<?php echo base_url();?>pdf/print_request"> 
	      	<input type="hidden" id="txt_print_ppr_header_id" name="ppr_header_id" />
	      	<div class="form-group">
	      		<label class="control-label">Prepared By</label>
	      		<input type="text" readonly="readonly" name="txt_prepared_by" id="txt_prepared_by" class="form-control" value="<?php echo $current_person_details->PERSON_NAME;?>"/>
	      	</div>
	      <!-- 	<div class="form-group">
	      		<label class="control-label">Checked By</label>
	      		<select class="form-control sel_signatories" id="txt_checked_by" name="txt_checked_by" style="width:100%;font-size:12px;">
				<option value=""></option>
	      		<?php 
	      		foreach($fsd_signatories as $row){
	      		?>
	      			<option value="<?php echo $row->PERSON_NAME?>"><?php echo $row->PERSON_NAME;?></option>
	      		<?php
	      		}
	      		?>
	      		</select>
	      	</div> 
	      	<div class="form-group">
	      		<label class="control-label">Approved By</label>
	      		<input type="text" name="txt_approved_by" id="txt_approved_by" value="MARY GRACE SERVAÑEZ / ERIC ALCONES"/>
	      		<!-- <select class="form-control sel_signatories" style="width:100%;font-size:12px;" id="txt_approved_by" name="txt_approved_by">
	      		<option value=""></option>
	      		<?php 
	      		foreach($fsd_signatories as $row){
	      		?>
	      			<option value="<?php echo $row->PERSON_NAME?>"><?php echo $row->PERSON_NAME;?></option>
	      		<?php
	      		}
	      		?>
	      		</select> 
	      	</div>
	      </form>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-danger btn-sm" id="btn_print_request">Print</button>
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content 
  </div><!-- /.modal-dialog
</div><!-- /.modal --> 


<!-- Printing Options -->
<input type="hidden" id="txt_user_type" value="<?php echo $user_type;?>"/>
<script src="<?php echo base_url('resources/input-mask/jquery.inputmask.js'); ?>"></script>
<script src="<?php echo base_url('resources/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?php echo base_url('resources/blockui/jquery.blockUI.js'); ?>"></script>
<script src="<?php echo base_url('resources/js/utils.js'); ?>"></script>
<script src="<?php echo base_url('resources/daterangepicker/moment.min.js');?>"></script>
<script src="<?php echo base_url('resources/daterangepicker/daterangepicker.js');?>"></script>
<!-- Data Tables -->
<script src="<?php echo base_url('resources/datatables/datatables.min.js');?>"></script>
<script>
var tbl;

var user_type = $("#txt_user_type").val();
function initialize_table(start_date,end_date,status_id){
	$("#tbl_payment_process_request tbody").html('<tr><td colspan="9" align="center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span><span class="text-bold" style="font-size:14pt;"> Please wait...</span></td></tr>');
	$.ajax({
		type:"POST",
		data:{
			start_date : start_date,
			end_date : end_date,
			status_id : status_id
		},
		url:"<?php echo base_url();?>ppr_requests/dt_all_ppr_requests",
		success:function(response){
	
			if ( $.fn.dataTable.isDataTable( '#tbl_payment_process_request' ) ) {
				tbl.destroy();
			}
			$("#tbl_payment_process_request tbody").html(response);
			tbl = $('#tbl_payment_process_request').DataTable({
		    	'bSort' : false,
		    	'scrollX' : true
				
		    });
		}
	});  
}

function initialize(){
	initial_start_date = moment(new Date($("#txt_initial_start_date").val())).format("DD-MMM-YYYY");
	initial_end_date = moment(new Date($("#txt_initial_end_date").val())).format("DD-MMM-YYYY");
	initialize_table(initial_start_date,initial_end_date,status_id);
}

var status_id = 0 // initial status will depend on the type of user
$(".tab_links").each(function(){
	if($(this).hasClass('active')){
		status_id = $(this).children("a").data('status_id');
	}
});

var check_voucher_validity = false;

var ppr_row_element;

var ppr_header_id;


$(document).ready(function(){
	

	initialize();

	$("body").on("click",'.btn_status',function(){
		status_id = $(this).data('status_id');
		initialize_table(initial_start_date,initial_end_date,status_id,user_type);
	});

	$('#txt_display_date').daterangepicker({
        "showDropdowns": true,
        "showWeekNumbers": true,
          ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $('#txt_display_date').on('apply.daterangepicker', function(ev, picker) {
        start_date = picker.startDate.format('YYYY-MM-DD');
        end_date = picker.endDate.format('YYYY-MM-DD');
       	start_date = moment(new Date(start_date)).format("DD-MMM-YYYY");
       	end_date = moment(new Date(end_date)).format("DD-MMM-YYYY");
       	$("#txt_initial_start_date").val(start_date);
       	$("#txt_initial_end_date").val(end_date);
       	initialize_table(start_date,end_date,status_id,user_type);
    });

    /*$("body").on("click",".btn_pop_update_payment",function(){
    	$("#txt_ppr_header_id").val($(this).data('ppr_header_id'));
    	$("#spn_ppr_header_id").text($(this).data('ppr_header_id'));
    	ppr_row_element = $(this).parent().parent().parent().parent().parent();
    	//alert(ppr_row_element.html());
    	var ppr_header_id = $(this).data('ppr_header_id');
    	$("#alert_message").html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span><span class="text-bold" style="font-size:14pt;"> Please wait...</span>');
		$("#alert_message").removeClass('hidden');
    	$("#dialog_payment_details").modal('show');
    	
    	$.ajax({
    		type:"POST",
    		data:{
    			ppr_header_id : ppr_header_id
    		},
    		url:"<?php echo base_url();?>ppr_requests/ajax_get_payment_details",
    		success:function(response){
    			var data = JSON.parse(response);
    			
				$("#txt_ppr_header_id").val(data.ppr_header_details.PPR_HEADER_ID);
    			$("#spn_ppr_header_id").text(data.ppr_header_details.PPR_HEADER_ID);
    			$("#alert_message").addClass('hidden');
    			if(data.check_details != ""){
    				$("#sel_bank_account").val(data.ppr_header_details.BANK_ACCOUNT_NUM).trigger('change.select2');
    				$("#txt_bank").val($("#sel_bank_account").children("option:selected").data('bank_name'));
    				$("#txt_check_voucher_no").val(data.ppr_header_details.AP_CHECK_VOUCHER_NO);
    				$("#txt_payment_document").val(data.check_details.PAYMENT_DOCUMENT_NAME);
    				$("#txt_check_number").val(data.check_details.CHECK_NUMBER);			
					$("#txt_payment_date").val(data.check_details.CHECK_DATE);	
					$("#txt_currency").val(data.check_details.CURRENCY_CODE);
					$("#txt_amount").val(data.check_details.AMOUNT);	
					$("#txt_status").val(data.check_details.STATUS_LOOKUP_CODE);
					$("#txt_treasury_date").val(data.check_details.TREASURY_PAY_DATE);			
					$("#txt_or_number").val(data.check_details.OR_NUMBER);			
					$("#txt_or_date").val(data.check_details.OR_DATE);			
					$("#txt_voucher_text").val(data.check_details.VOUCHER_TEXT);
					check_voucher_validity = true;	
    			}
    			

    			if(data.ppr_header_details.STATUS_ID == 23 ||
    			   data.ppr_header_details.STATUS_ID == 41){
    				$("#btn_save_payment_details").show();
    			}
    			else {
    				$("#btn_save_payment_details").hide();
    			}
    			
    		}
    	});

		
		
	});
	*/

	/*$("#btn_search_check").click(function(){
		var check_voucher_no = $("#txt_check_voucher_no").val();
		var bank_account_num = $("#sel_bank_account").val();
		var isError = false;

		if(bank_account_num == ""){
			$("#msg_bank_account_name").text('*Please select a bank account');
			isError = true;
		}

		if(check_voucher_no == ""){
			$("#msg_check_voucher").text('*Please enter the voucher no');
			isError = true;
		}
		
		if (!isError) {
			$("#msg_check_voucher").html("Please wait... <i class='fa fa-spinner fa-pulse fa-1x fa-fw'></i>");
			$.ajax({
				type:"POST",
				data:{
					check_voucher_no : check_voucher_no,
					bank_account_num : bank_account_num
				},
				url:"<?php echo base_url();?>ppr_requests/ajax_search_check",
				success:function(response){
				
					if(response != "invalid"){
						var data = JSON.parse(response);
						$("#txt_payment_document").val(data[0].PAYMENT_DOCUMENT_NAME);			
						$("#txt_check_number").val(data[0].CHECK_NUMBER);			
						$("#txt_payment_date").val(data[0].CHECK_DATE);			

						$("#txt_currency").val(data[0].CURRENCY_CODE);			
						$("#txt_amount").val(data[0].AMOUNT);			
						$("#txt_status").val(data[0].STATUS_LOOKUP_CODE);			
						$("#txt_treasury_date").val(data[0].TREASURY_PAY_DATE);			
						$("#txt_or_number").val(data[0].OR_NUMBER);			
						$("#txt_or_date").val(data[0].OR_DATE);			
						$("#txt_voucher_text").val(data[0].VOUCHER_TEXT);			
						$("#msg_check_voucher").html("");
						check_voucher_validity = true;
						$("#msg_check_voucher").html("");
					}
					else {
						$("#msg_check_voucher").html("Check Voucher Number not found.");
						check_voucher_validity = false;
					}
				}
			});
		}
	});
*/
	/* Bank 
	$("#sel_bank_account").select2();

	$("#sel_bank_account").on('change',function(){
		if($(this).val()!=""){
			$("#msg_bank_account_name").text("");
		}
		$("#txt_bank").val($("#sel_bank_account").children("option:selected").data('bank_name'));
	});
	*/

	/*$("#btn_save_payment_details").click(function(){
		var check_voucher_no = $("#txt_check_voucher_no").val();
		var bank_account_num = $("#sel_bank_account").val();
		var isError = false;

		if(bank_account_num == ""){
			$("#msg_bank_account_name").text('*Please select a bank account');
			isError = true;
		}

		if(check_voucher_no == ""){
			$("#msg_check_voucher").text('*Please enter the voucher no');
			isError = true;
		}

		if(check_voucher_validity && !isError){
			$("#alert_message").html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span><span class="text-bold" style="font-size:14pt;"> Please wait...</span>');
			$("#alert_message").removeClass('hidden');
			var ppr_header_id = $("#txt_ppr_header_id").val();
			var check_voucher_no = $("#txt_check_voucher_no").val();
			var bank_account_num = $("#sel_bank_account").val();
			var bank_account_name = $("#sel_bank_account").children("option:selected").text();
			var bank_name = $("#txt_bank").val();
			$.ajax({
				type:'POST',
				data:{
					ppr_header_id : ppr_header_id,
					check_voucher_no : check_voucher_no,
					bank_account_num : bank_account_num,
					bank_account_name : bank_account_name,
					bank_name : bank_name
				},
				url:"<?php echo base_url()?>/ppr_requests/ajax_save_payment_details",
				success:function(response){
					if(response == "success"){
						$("#alert_message").text('You have successfully updated the payment details.');
						if(status_id == 23){
							ppr_row_element.remove();
						}
					}
					else {
						$("#alert_message").html(response);
					}
					
				}
			});
		}

	});
	*/
	/*
	$("#dialog_payment_details").on("hidden.bs.modal",function(){
		$("#txt_payment_document").val("");			
		$("#txt_check_number").val("");			
		$("#txt_payment_date").val("");			
		$("#txt_bank").val("");			
		$("#txt_bank_account_name").val("");	
		$("#txt_currency").val("");			
		$("#txt_amount").val("");			
		$("#txt_status").val("");			
		$("#txt_treasury_date").val("");			
		$("#txt_or_number").val("");			
		$("#txt_or_date").val("");			
		$("#txt_voucher_text").val("");			
		$("#msg_check_voucher").html("");
		check_voucher_validity = false;
		$("#msg_check_voucher").html("");
		$("#alert_message").html("");
		$("#alert_message").addClass("hidden");
		$("#txt_check_voucher_no").val("");
		$('#sel_bank_account').val('').trigger('change.select2');;
	});

	$("body").on("click",".btn_pop_print",function(){
		ppr_header_id = $(this).data('ppr_header_id');
		$("#txt_print_ppr_header_id").val(ppr_header_id);
		$("#frm_ppr_request").submit();
		//$("#dialog_printing_options").modal('show');
	});

	$(".sel_signatories").select2({
		placeholder : "Select a person"
	});

	$("#btn_print_request").click(function(){
		$("#frm_ppr_request").submit();
	});*/

}); 
</script>
