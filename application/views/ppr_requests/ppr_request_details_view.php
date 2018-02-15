<section class="content">
	<div class="row">
		<div class="col-md-12">
		<?php if($this->session->flashdata('ppr_request_message')) { ?>
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-info-circle"></i>Notification</h4>
				<?php echo $this->session->flashdata('ppr_request_message');?>
			</div>
		<?php } ?>
		</div>
		<div class="col-md-3">
			<div class="box box-danger ap_box_invoices">
				<div class="box-header with-border">
					<h3 class="box-title">Request Details</h3>
				</div>
				<div class="box-body">	
					<strong>Payment Process Request ID</strong>
					<p class="text-muted" id="txt_ppr_header_id" data-ppr_header_id="<?php echo $ppr_header_details->PPR_HEADER_ID;?>"><?php echo sprintf('%05d',$ppr_header_details->PPR_HEADER_ID); ?></p>
					<strong>Supplier</strong>
					<p class="text-muted"><?php echo $ppr_header_details->VENDOR_NAME;?></p>
					<strong>Planned Pay Date</strong>
					<p class="text-muted"><?php echo $ppr_header_details->PLANNED_PAY_DATE;?></p>
					<strong>Total Selected Invoices</strong>
					<p class="text-muted" id="txt_total_selected_invoices">0</p>
					<strong>Total Invoice Amount</strong>
					<p class="text-muted" id="txt_total_invoice_amount">0.00</p>
					<strong>Total Balance Amount</strong>
					<p class="text-muted" id="txt_total_balance_amount">0.00</p>
					<strong>Status</strong>
					<p class="text-muted">
						<?php echo $ppr_header_details->STATUS_NAME; ?>
					</p>
					<strong>Date Created</strong>
					<p class="text-muted">
						<?php echo $ppr_header_details->DATE_CREATED; ?>
					</p>
					<strong>Created By</strong>
					<p class="text-muted">
						<?php echo $ppr_header_details->CREATED_BY_NAME; ?>
					</p>
					<form id="frm_new_invoices" class="hidden">
						<input type="text" value="<?php echo $ppr_header_details->VENDOR_ID;?>" id="txt_supplier_id"/>
						<input type="text" value="<?php echo $ppr_header_details->DUE_DATE;?>" id="txt_due_date"/>
					</form>
				</div>
				<div class="box-footer">
					
				<?php
					// Only show Submit and Cancel if status is NEW 
					$current_user = $user_details->user_id;
					if($ppr_header_details->STATUS_ID == 1 && $ppr_header_details->CREATED_BY == $current_user) { 
				?>
					<button type="button" class="btn btn-danger btn-sm" id="btn_pop_submit">Submit</button>
					<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#dialog_cancel">Cancel</button>	
				<?php 
					} 
					else if ($ppr_header_details->STATUS_ID == 4 AND IN_ARRAY($user_type,array('Regular','Administrator'))){
				?>
					<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#dialog_cancel">Cancel</button>	
				<?php
					}
				?>
				<?php
					if($ppr_header_details->STATUS_ID == 4 AND IN_ARRAY($user_type,array('Regular','Administrator'))){
				?>
					<a class="btn btn-primary btn-sm" target="_blank" href="<?php echo base_url();?>pdf/print_request/<?php echo encode_string($ppr_header_details->PPR_HEADER_ID);?>">Print</a>
				<?php
					}
				?>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="nav-tabs-custom" style="min-height:550px;">
	            <ul class="nav nav-tabs ">
	                <li class="active"><a href="#selected_invoices_tab" data-toggle="tab">Selected</a></li>
	                <li class=""><a href="#removed_invoices_tab" data-toggle="tab">Removed</a></li>
	                <?php if($ppr_header_details->STATUS_ID == 1) { ?>
	                <li class=""><a href="#add_new_invoices_tab" data-toggle="tab" id="btn_generate_new_invoices">Add to request</a></li>
	       			<?php } ?>
	       		
              <li class="dropdown pull-right">
                <a aria-expanded="true" class="dropdown-toggle" data-toggle="dropdown" href="#">
                  Action <span class="caret"></span>
                
                <ul class="dropdown-menu">
                  <?php if($ppr_header_details->STATUS_ID == 1) { ?>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="btn_update_invoices" data-status_id = '5'>Select invoices</a></li>
              	  <li role="presentation"><a role="menuitem" tabindex="-1" href="#"  class="btn_update_invoices" data-status_id = '6'>Remove invoices</a></li>
              	  
              	  <li role="presentation"><a role="menuitem" tabindex="-1" href="#"  id="btn_add_new_selected_invoices">Add to request</a></li>
              	  
              	  <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url();?>pdf/print_request/<?php echo encode_string($ppr_header_details->PPR_HEADER_ID);?>">Print</a></li>
              	  <?php } ?>
              	  <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url();?>excel_c/export_request_xls/<?php echo $ppr_header_details->PPR_HEADER_ID;?>">Export as excel</a></li>
              	   <li role="presentation"><a role="menuitem" tabindex="-1" " href="<?php echo base_url();?>files/PAYMENT_LINES.dld" download>Download DataLoader Template</a></li>
                </ul>
           
              </li>
              
   
            </ul>

	            <div class="tab-content">
	            	<!-- SELECTED INVOICES -->
	                <div class="tab-pane active" id="selected_invoices_tab">
		                <div class="row">
							<div class="col-md-12">
								<table class="table table-condensed table-bordered nowrap" id="selected_invoices_tbl" >
									<thead style="font-size:85%;">
										<tr>
											<?php
												// Only show checkbox is status is NEW
												if($ppr_header_details->STATUS_ID == 1) { 
											?>
											<th><input type="checkbox" id="cb_invoices_main_sel"/></th>
											<?php
												}
											?>
											<th>Voucher No</th>
											<th>Invoice No</th>
											<th>Supplier Name</th>
											<th>Invoice Date</th>
											<th>GL Date</th>
											<th>Net</th>
											<th>Invoice Amount</th>
											<th>VAT</th>
											<th>WHT</th>
											<th>Payment Amount</th>
											<th>Balance</th>
											<th>Due Date</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$total_selected = 0;
										$total_invoice_amount = 0;
										$total_balance_amount = 0;
										foreach($selected_invoices as $inv){	
											
									?>
										<tr>
											<?php
												// Only show dropdown if status is NEW
												if($ppr_header_details->STATUS_ID == 1) { 
											?>
											<td><input type="checkbox" class="cb_invoice_sel" value="<?php echo $inv->AP_INVOICE_ID;?>" data-ppr_line_id = "<?php echo $inv->PPR_LINE_ID;?>"/></td>
											<?php
												}
											?>
											<td><?php echo $inv->AP_DOCUMENT_SEQUENCE_VALUE;?></td>	
											<td><?php echo $inv->AP_INVOICE_NUM;?></td>	
											<td><?php echo $inv->VENDOR_NAME;?></td>	
											<td><?php echo $inv->INVOICE_DATE;?></td>	
											<td><?php echo $inv->GL_DATE;?></td>	
											<td><?php echo number_format($inv->NET,2,'.',',');?></td>	
											<td data-invoice_amount="<?php echo $inv->INVOICE_AMOUNT;?>"><?php echo number_format($inv->INVOICE_AMOUNT,2,'.',',');?></td>	
											<td><?php echo number_format($inv->VAT,2,'.',',');?></td>
											<td><?php echo number_format($inv->WHT,2,'.',',');?></td>
											<?php if($ppr_header_details->STATUS_ID == 1) { ?>
											<td><input type="text" data-ppr_line_id="<?php echo $inv->PPR_LINE_ID;?>" value="<?php echo $inv->INVOICE_AMOUNT - $inv->WHT;?>" class="form-control input-sm"/></td>
											<?php } 
												else { 
											?>
											<td><?php echo number_format($inv->PROPOSED_PAYMENT_AMOUNT,2,'.',',');?></td>
											<?php } ?>
											<td data-invoice_balance="<?php echo $inv->BALANCE;?>"><?php echo number_format($inv->BALANCE,2,'.',',');?></td>	
											<td><?php echo $inv->DUE_DATE;?></td>
										</tr>
									<?php
											$total_selected++;
											$total_invoice_amount += $inv->INVOICE_AMOUNT;
											$total_balance_amount += $inv->BALANCE;
										}
									?>
									</tbody>
								</table>
								<input type="hidden" id="inp_total_selected" value="<?php echo $total_selected?>"/>
								<input type="hidden" id="inp_total_invoice_amount" value="<?php echo $total_invoice_amount?>"/>
								<input type="hidden" id="inp_total_balance_amount" value="<?php echo $total_balance_amount?>"/>
							</div>
						</div>
	                </div>
	                <!-- END OF SELECTED INVOICES -->
	                <!-- REMOVED INVOICES -->
	                <div class="tab-pane" id="removed_invoices_tab">
		                <div class="row">
							<div class="col-md-12">

								<table class="table table-condensed table-bordered" id="removed_invoices_tbl">
									<thead>
										<tr>
											<?php
												// Only show dropdown if status is NEW
												if($ppr_header_details->STATUS_ID == 1) { 
											?>
											<th><input type="checkbox" id='cb_invoices_main_rem' /></th>
											<?php } ?>
											<th>Voucher No</th>
											<th>Invoice No</th>
											<th>Supplier Name</th>
											<th>Invoice Date</th>
											<th>GL Date</th>
											<th>Net</th>
											<th>Invoice Amount</th>
											<th>VAT</th>
											<th>WHT</th>
											<th>Balance</th>
											<th>Due Date</th>
										</tr>
									</thead>
									<tbody>
									<?php
										foreach($removed_invoices as $inv){	
									?>
										<tr>	
											<?php
												// Only show checkbox if status is NEW
												if($ppr_header_details->STATUS_ID == 1) { 
											?>
											<td><input type="checkbox" class="cb_invoice_rem" value="<?php echo $inv->AP_INVOICE_ID;?>"  data-ppr_line_id = "<?php echo $inv->PPR_LINE_ID;?>"/></td>
											<?php
												}	
											?>
											<td><?php echo $inv->AP_DOCUMENT_SEQUENCE_VALUE;?></td>	
											<td><?php echo $inv->AP_INVOICE_NUM;?></td>	
											<td><?php echo $inv->VENDOR_NAME;?></td>	
											<td><?php echo $inv->INVOICE_DATE;?></td>	
											<td><?php echo $inv->GL_DATE;?></td>	
											<td><?php echo number_format($inv->NET,2,'.',',');?></td>	
											<td data-invoice_amount="<?php echo $inv->INVOICE_AMOUNT;?>"><?php echo number_format($inv->INVOICE_AMOUNT,2,'.',',');?></td>	
											<td><?php echo number_format($inv->VAT,2,'.',',');?></td>
											<td><?php echo number_format($inv->WHT,2,'.',',');?></td>
											<td data-invoice_balance="<?php echo $inv->BALANCE;?>"><?php echo number_format($inv->BALANCE,2,'.',',');?></td>	
											
											<td><?php echo $inv->DUE_DATE;?></td>	
										</tr>
									<?php
										}
									?>
									</tbody>
								</table>
							</div>
						</div>
	                </div>
	                <!-- END OF REMOVED INVOICES -->
	                <!-- START OF ADD INVOICES -->
	                <div class="tab-pane" id="add_new_invoices_tab">
		                <div class="row">
							<div class="col-md-12">
								<table class="table table-condensed table-bordered" id="add_new_invoices_tbl">
									<thead>
										<tr>
										
											<th><input type="checkbox" id="cb_invoices_main_add"/></th>
									
											<th>Voucher No</th>
											<th>Invoice No</th>
											<th>Supplier Name</th>
											<th>Invoice Date</th>
											<th>GL Date</th>
											<th>Net</th>
											<th>Invoice Amount</th>
											<th>VAT</th>
											<th>WHT</th>
											<th>Balance</th>
											<th>Due Date</th>
										</tr>
									</thead>
									<tbody>
									
									</tbody>
								</table>
								
							</div>
						</div>
	                </div>
	                <!-- END OF ADD INVOICES -->
	            </div>
            </div>

		</div>
	</div>
</section>

<!-- Error notif BlockUI -->
<div id="err_notif" style="cursor: default; display: none;">
	<table cellpadding="5">
		<tr>
			<td width="70"><i class="fa fa-exclamation-triangle fa-4x" style="color:#ECEA47;" aria-hidden="true"></i></td>
			<td width="200"><h3 style="margin:0;padding:0" id="err_title">Title</h3>
    			<h4 id="err_message">Message</h4>
    		</td>
		</tr>
	</table>
</div>
<!-- End of Error notif BlockUI -->

<!-- Dialog confirmation -->
<div class="modal fade" tabindex="-1" role="dialog" id="dialog_submit">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to submit your request?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="btn_submit_request">Yes</button>
         <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Dialog confirmation -->
<div class="modal fade" tabindex="-1" role="dialog" id="dialog_cancel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to cancel your request?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="btn_cancel_request">Yes</button>
         <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Dialog confirmation -->
<div class="modal fade" tabindex="-1" role="dialog" id="dialog_approve">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p id="dialog_approve_body">Are you sure you want to approve the request?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="btn_approve_request">Yes</button>
         <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End of dialog confirmation -->
<script src="<?php echo base_url('resources/input-mask/jquery.inputmask.js'); ?>"></script>
<script src="<?php echo base_url('resources/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?php echo base_url('resources/blockui/jquery.blockUI.js'); ?>"></script>
<script src="<?php echo base_url('resources/js/utils.js'); ?>"></script>
<!-- Data Tables -->
<script src="<?php echo base_url('resources/datatables/datatables.min.js');?>"></script>
<script>
var is_for_addition_loaded = false;

$(document).ready(function(){
	invoice_amount = parseFloat($("#inp_total_invoice_amount").val());
	balance_amount = parseFloat($("#inp_total_balance_amount").val());
	$("#txt_total_selected_invoices").text($("#inp_total_selected").val());
	$("#txt_total_invoice_amount").text(utils.format_number(invoice_amount.toFixed(2)));
	$("#txt_total_balance_amount").text(utils.format_number(balance_amount.toFixed(2)));


	// function for toggling of check and uncheck in checkboxes
	$("#cb_invoices_main_sel, #cb_invoices_main_rem").click(function(){
		// get id attribute of the element
		cb_main_id = $(this).attr("id"); 
		// assign which checkbox to toggle depending on the mother checkbox
		cb_child_invoice_id = cb_main_id == "cb_invoices_main_sel" ? ".cb_invoice_sel" : ".cb_invoice_rem";
		// loop onto each child checkboxes for toggling
    	$(cb_child_invoice_id).each(function(){
    		if($("#"  + cb_main_id).is(":checked")){
				$(cb_child_invoice_id).prop('checked', true);  // Checks the box
			}
			else {
				$(cb_child_invoice_id).prop('checked', false);  // Unchecks the box
			}
    	});
    }); // $("#cb_invoices_main_sel, #cb_invoices_main_rem").click(function(){


	$(".btn_update_invoices").on("click",function(){
		var status_id = $(this).data('status_id');
		var ppr_header_id = $("#txt_ppr_header_id").data('ppr_header_id');
		var ppr_lines = [];
		var index = 0;
		var source_checkbox = status_id == 5 ? ".cb_invoice_rem" : ".cb_invoice_sel";
		
		/*$(source_checkbox).each(function(){
			if($(this).is(":checked")){
	    		ppr_lines[index] = {
	    			"invoice_id" 		: $(this).val(),
	    			"ppr_line_id" : $(this).data('ppr_line_id')
	    		};
	    		index++;
			}
    	});*/
		var table;
		if(status_id == 5){
			table = $('#removed_invoices_tbl').DataTable();
		}
		else if(status_id == 6){
			table = $('#selected_invoices_tbl').DataTable();
		}

    	table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
	   		var data = this.data();
	    	var node = this.node();   
			var cb = $('td:nth-child(1) input', node);
			if(cb.is(":checked")){
				ppr_lines[index] = {
	    			"invoice_id" 		: cb.val(),
	    			"ppr_line_id": cb.data('ppr_line_id')
	    		};
	    		index++;
			}
		});

		if(index > 0){
    		$.blockUI({ 
    			message: '<h1 ><i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i><span class="sr-only">Loading...</span> Updating payment process request...</h1>',
    			baseZ: 2000,
    			overlayCSS : {
   					'z-index' : 1050
   				}
    		});
			$.ajax({
				type:"POST",
				data:{
					status_id : status_id,
					ppr_header_id : ppr_header_id,
					ppr_lines : JSON.stringify(ppr_lines)
				},
				url:"<?php echo base_url();?>/ppr_requests/ajax_update_request",
				success:function(response){
					window.location.href = "<?php echo base_url();?>ppr_requests/ppr_request_details/" + response;
				}
			});
    	}
    	else {
    		$("#err_title").text("Notification");
    		if(status_id == 5){
    			$("#err_message").text("Please select invoices to include.");
    		}
    		else {
				$("#err_message").text("Please select invoices to remove.");
    		}
    		
			$.blockUI({ 
				message: $('div#err_notif'), 
				fadeIn: 700, 
				fadeOut: 700, 
				timeout: 7000, 
				showOverlay: false, 
				centerY: false, 
				css: { 
				width: '270px', 
				top: '10px', 
				left: '', 
				right: '10px', 
				border: 'none', 
				padding: '5px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				opacity: .8, 
				color: '#fff',
				'z-index': 1500 
				} 
			}); 
    	}
	});


	$("#btn_submit_request").click(function(){

		$("#dialog_submit").modal("hide");
		var ppr_header_id = $("#txt_ppr_header_id").data('ppr_header_id');
		var status_id = 4; // status for submitted
		var ppr_lines = [];
		var index = 0;
		var table = $('#selected_invoices_tbl').DataTable();
		table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
	   		var data = this.data();
	    	var node = this.node();   
			var element = $('td:nth-child(11) input', node);
			ppr_lines[index] = {
    			"payment_amount" 		: element.val(),
    			"ppr_line_id": element.data('ppr_line_id')
    		};
    		index++;
		});
	
		$.blockUI({ 
    			message: '<h1 ><i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i><span class="sr-only">Loading...</span>Please wait...</h1>',
    			baseZ: 2000,
    			overlayCSS : {
   					'z-index' : 1050
   				}
    		});

		$.ajax({
			type:"POST",
			data:{
				ppr_header_id : ppr_header_id,
				status_id : status_id,
				ppr_lines : JSON.stringify(ppr_lines)
			},
			url:"<?php echo base_url();?>ppr_requests/ajax_submit_request",
			success:function(response){			
				window.location.href = "<?php echo base_url();?>pdf/print_request/" + response;
			}
			
		});

	});


	var approval_status_id = $(this).data("status_id");
	// 23 = Approved
	// 24 = Disapproved
	$("body").on("click",".btn_approve_request",function(){
		approval_status_id = $(this).data("status_id");
		if(approval_status_id == 23){
			$("#dialog_approve_body").html('Are you sure want to approve the request?');
		}
		else if(approval_status_id == 24){
			$("#dialog_approve_body").html('Are you sure want to disapprove the request?');
		}
		$("#dialog_approve").modal("show");
	});

	$("#btn_approve_request").click(function(){
		var ppr_header_id = $("#txt_ppr_header_id").data('ppr_header_id');
		$.ajax({
			type:"POST",
			data:{
				ppr_header_id : ppr_header_id,
				approval_status_id : approval_status_id
			},
			url:"<?php echo base_url();?>ppr_requests/ajax_approval_request",
			success:function(response){
				window.location.href = "<?php echo base_url();?>ppr_requests/ppr_request_details/" + response;
			}
		});
	});

	$("#btn_cancel_request").click(function(){
		$("#dialog_submit").modal("hide");
		var ppr_header_id = $("#txt_ppr_header_id").data('ppr_header_id');
		var status_id = 3; // status for cancellation
		
		$.blockUI({ 
    			message: '<h1 ><i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i><span class="sr-only">Loading...</span>Please wait...</h1>',
    			baseZ: 2000,
    			overlayCSS : {
   					'z-index' : 1050
   				}
    		});

		$.ajax({
			type:"POST",
			data:{
				ppr_header_id : ppr_header_id,
				status_id : status_id
			},
			url:"<?php echo base_url();?>ppr_requests/ajax_cancel_request",
			success:function(response){			
				window.location.href = "<?php echo base_url();?>ppr_requests/ppr_request_details/" + response;
			}
			
		});
	});

	$("#btn_pop_submit").click(function(){
		var count_selected = $("#selected_invoices_tbl tbody tr").length;
		if(count_selected > 0){
			$("#dialog_submit").modal('show');
		}
		else {
			$("#err_title").text("Notification");
    		$("#err_message").text("Please select invoices.");
			$.blockUI({ 
				message: $('div#err_notif'), 
				fadeIn: 700, 
				fadeOut: 700, 
				timeout: 7000, 
				showOverlay: false, 
				centerY: false, 
				css: { 
				width: '270px', 
				top: '10px', 
				left: '', 
				right: '10px', 
				border: 'none', 
				padding: '5px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				opacity: .8, 
				color: '#fff',
				'z-index': 1500 
				} 
			}); 
		}

	});

	$("#btn_generate_new_invoices").click(function(){
		var supplier_id = $("#txt_supplier_id").val();
		var due_date = $("#txt_due_date").val();
		if(due_date == ""){
    		due_date = null;
    	} 

    	if(!is_for_addition_loaded) {
	    	
	   		$.blockUI({ 
	   			message: '<h1><i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i><span class="sr-only">Loading...</span> Generating invoices...</h1>',
	   			baseZ: 2000,
	   			overlayCSS : {
	   				'z-index' : 1050
	   			}
	   		});

			//$("#add_new_invoices_tbl tbody").html("<tr><td colspan='8' align='center'>Loading</td></tr>");
			$.ajax({
	   			type:"POST",
	   			data:{
	   				supplier_id : supplier_id,
	   				due_date : due_date
	   			},
	   			url:"<?php echo base_url();?>ppr_requests/ajax_generate_invoices",
	   			success:function(response){
	   				
	   				is_for_addition_loaded = true;
	   				$.unblockUI();
	   				if(response == ""){
	   					$("#add_new_invoices_tbl tbody").html("<tr><td colspan='8' align='center'>No invoice was generated.</td></tr>");
	   				}
	   				else {
	   					$("#add_new_invoices_tbl tbody").html(response);
	   					if ( $.fn.dataTable.isDataTable( '#add_new_invoices_tbl' ) ) {
							tbl.destroy();
						}
						$("#add_new_invoices_tbl tbody").html(response);
						tbl = $('#add_new_invoices_tbl').DataTable({
					    	'bSort' : false
					    });
			   		}
			   	}
	   		});
	   	}
	});

	$("#btn_add_new_selected_invoices").click(function(){
		var invoices = [];
    	var index = 0;
    	var table = $('#add_new_invoices_tbl').DataTable();
		table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
	   		var data = this.data();
	    	var node = this.node();   
			var cb = $('td:nth-child(1) input', node);
			if(cb.is(":checked")){
				invoices[index] = {
	    			"invoice_id" 		: cb.val(),
	    			"doc_sequence_value": cb.data('doc_sequence_value'),
	    			"org_id"			: cb.data('org_id'),
	    			"invoice_num" 		: cb.data('invoice_num')
	    		};
	    		index++;
			}
		});

		if(index > 0){
    		$.blockUI({ 
    			message: '<h1 ><i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i><span class="sr-only">Loading...</span> Saving payment process request...</h1>',
    			baseZ: 2000,
    			overlayCSS : {
   					'z-index' : 1050
   				}
    		});

			$.ajax({
				type:"POST",
				data:{
					ppr_header_id : $("#txt_ppr_header_id").data('ppr_header_id'),
					selected_invoices : JSON.stringify(invoices)
				},
				url:"<?php echo base_url();?>ppr_requests/ajax_add_to_request",
				success:function(response){
					window.location.href = "<?php echo base_url();?>ppr_requests/ppr_request_details/" + response;
				}
			});
    	}

    	else {
    		$("#err_title").text("Notification");
    		$("#err_message").text("Please select invoices to add.");
			$.blockUI({ 
				message: $('div#err_notif'), 
				fadeIn: 700, 
				fadeOut: 700, 
				timeout: 7000, 
				showOverlay: false, 
				centerY: false, 
				css: { 
				width: '270px', 
				top: '10px', 
				left: '', 
				right: '10px', 
				border: 'none', 
				padding: '5px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				opacity: .8, 
				color: '#fff',
				'z-index': 1500 
				}
			});
		}
	});

	
	var rem_table = $('#removed_invoices_tbl').DataTable({
					    	'bSort' : false
					    });
	var sel_table = $('#selected_invoices_tbl').DataTable({
				    	'bSort' : false,
				    	'scrollX' : true
				    });
});    
</script>
