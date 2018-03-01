
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
			<!-- <p id="msg"></p> -->
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title">Filter</h3>
					<button type="button" class="btn btn-danger btn-sm pull-right" id="btn_generate_invoices">Generate Invoices</button>
				</div>
				<div class="box-body">
					<form class="form">
						<div class="col-md-8">
							<div class="form-group">
								<label class="control-label">Supplier</label>
								<select class="form-control" id="sel_supplier_name">
								  <option value="" selected="selected">Select Supplier</option>
								</select>								
							</div>
							<div class="form-group">
								<label class="control-label">Site</label>
								<select class="form-control" id="sel_supplier_name_site">
							
								</select>								
							</div>

						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Due Date</label>
								<input type="text" class="form-control" id="txt_due_date" name="txt_due_date" />
														
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Planned Pay Date</label>
								<input type="text" class="form-control" id="txt_planned_pay_date" name="txt_planned_pay_date" />						
							</div>
						</div>
					</form>
				</div>
			
			</div>

			<div class="box box-danger ap_box_invoices">
				<div class="box-header with-border">
					<h3 class="box-title">Invoices</h3>
					<div class='pull-right'>
						<button type="button" class="btn btn-danger btn-sm" id="btn_save_request">Save Request</button>
					</div>

				</div>
				<div class="box-body">	
					
					<div class="row">
						<div class="col-md-6">
							<span>Total Invoices : <span class="badge badge-danger" id="spn_total_invoices">0</span></span><br/>
							<span>Selected Invoices : <span class="badge badge-danger" id="spn_selected_invoices">0</span></span><br/>
						</div>
						<div class="col-md-6">
							<p class="pull-right">
								<span>Total Invoice Amount : <span class="text-bold text-danger" id="spn_total_invoice_amount">0.00</span></span><br/>
								<span>Total Balance Amount : <span class="text-bold text-danger" id="spn_balance_amount">0.00</span></span>
							</p>
						</div>
					</div>
					<Br/>
					<div class="row">
						<div class="col-md-12">
							<table class="table table-condensed table-bordered" id="ap_invoices_tbl" style="font-size:90%;" >
								<thead>
									<tr>
										<th><input type="checkbox" id="cb_invoice_main"/></th>
										<th>Invoice No</th>
										<th>Voucher No</th>
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
			</div>

		</div>
	</div>
</section>
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
<script src="<?php echo base_url('resources/input-mask/jquery.inputmask.js'); ?>"></script>
<script src="<?php echo base_url('resources/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?php echo base_url('resources/blockui/jquery.blockUI.js'); ?>"></script>
<script src="<?php echo base_url('resources/js/utils.js'); ?>"></script>

<!-- Data Tables -->
<script src="<?php echo base_url('resources/datatables/datatables.min.js');?>"></script>
<script>

function updateSummary(){
	var total_invoice_amount = 0;
	var total_balance_amount = 0;
	var total_selected_invoices = 0;
	var table = $('#ap_invoices_tbl').DataTable();
	table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
   		var data = this.data();
    	var node = this.node();   
		var cb = $('td:nth-child(1) input', node);
		var balance_amount = $("td:nth-child(11)",node);
		var invoice_amount = $("td:nth-child(8)",node);
		if(cb.is(":checked")){
			total_invoice_amount += parseFloat(invoice_amount.data('invoice_amount'));
			total_balance_amount += parseFloat(balance_amount.data('invoice_balance'));
			total_selected_invoices++;
		}
	});

	$("#spn_selected_invoices").text(total_selected_invoices);
	$("#spn_total_invoice_amount").text(utils.format_number(total_invoice_amount.toFixed(2)));
	$("#spn_balance_amount").text(utils.format_number(total_balance_amount.toFixed(2)));
}

$(document).ready(function(){

    $("#sel_supplier_name").select2({
      ajax: {
        url: "ajax_search_supplier",
        dataType: 'json',
         
        type: 'GET',
        delay: 250,
        data: function (params) {
      	  return {
            q: params.term // search term
          };
        },
        processResults: function (data, page) {
          return {
            results: data  
          };
        },
        cache: true
      },
      minimumInputLength: 3
    });

    $("#sel_supplier_name").on("change",function(){
    	var supplier_id = $(this).val();
    	$.ajax({
    		type:"POST",
    		data:{
    			supplier_id : supplier_id
    		},
    		url:"<?php echo base_url();?>ppr_requests/ajax_get_supplier_sites",
    		success:function(response){
    			$("#sel_supplier_name_site").html(response);
    		}
    	});
    });

    $('#txt_due_date').inputmask('mm/dd/yyyy', {'placeholder' : 'mm/dd/yyyy'});
    $('#txt_planned_pay_date').inputmask('mm/dd/yyyy', {'placeholder' : 'mm/dd/yyyy'});

    $("#btn_generate_invoices").click(function(){
    	var supplier_id = $("#sel_supplier_name").val();
    	var due_date = $("#txt_due_date").val();
    	var supplier_site_id = $("#sel_supplier_name_site").val();
    
    	if(due_date != ""){
    		due_date = moment(new Date($("#txt_due_date").val())).format("DD-MMM-YYYY");
    	} 
    	else {
    		due_date = null;
    	}
   		$.blockUI({ 
   			message: '<h1><i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i><span class="sr-only">Loading...</span> Generating invoices...</h1>',
   			baseZ: 2000,
   			overlayCSS : {
   				'z-index' : 1050
   			}
   		});
   		
   		$.ajax({
   			type:"POST",
   			data:{
   				supplier_id : supplier_id,
   				supplier_site_id : supplier_site_id,
   				due_date : due_date
   			},
   			url:"ajax_generate_invoices",
   			success:function(response){			
   				$.unblockUI();
   				if(response == ""){
   					$("#ap_invoices_tbl tbody").html("<tr><td colspan='8' align='center'>No invoice was generated.</td></tr>");
   				}
   				else {
   					$("#ap_invoices_tbl tbody").html(response);
   					$("#spn_total_invoices").text($("#ap_invoices_tbl tbody tr").length);
   					if ( $.fn.dataTable.isDataTable( '#ap_invoices_tbl' ) ) {
						tbl.destroy();
					}
					$("#ap_invoices_tbl tbody").html(response);
					tbl = $('#ap_invoices_tbl').DataTable({
				    	'bSort' : false
				    });
		   		
		   				
		   		}
		   	}
   		});
    });

    $("body").on("change",".cb_invoice",function(){
    	updateSummary();
    });

    $("#cb_invoice_main").click(function(){
    	var table = $('#ap_invoices_tbl').DataTable();

   // $('#checkall').click(function () {
        
   // });
    	$(".cb_invoice").each(function(){
    		if($("#cb_invoice_main").is(":checked")){
    			$(':checkbox', table.rows().nodes()).prop('checked', true);
				//$(".cb_invoice").prop('checked', true);  // Checks the box
			}
			else {
				$(':checkbox', table.rows().nodes()).prop('checked', false);
			//	$(".cb_invoice").prop('checked', false);  // Checks the box
			}
    	});
    	updateSummary();
    });

    $("#btn_save_request").click(function(){
		//var supplier_id = $("#sel_supplier_name").val();
		var supplier_data = $('#sel_supplier_name').select2('data')
		var supplier_name = supplier_data[0].text;
		var supplier_id = supplier_data[0].id;
		var supplier_site_id = $("#sel_supplier_name_site").val();
	
    	var due_date = $("#txt_due_date").val();
    	var planned_pay_date = $("#txt_planned_pay_date").val();
    	
    	if(due_date != ""){
    		due_date = moment(new Date($("#txt_due_date").val())).format("DD-MMM-YYYY");
    	} 
    	else {
    		due_date = null;
    	}

    	if(planned_pay_date != ""){
    		planned_pay_date = moment(new Date($("#txt_planned_pay_date").val())).format("DD-MMM-YYYY");
    	} 
    	else {
    		planned_pay_date = null;
    	}

    	
    /*	$(".cb_invoice").each(function(){
			if($(this).is(":checked")){
	    		invoices[index] = {
	    			"invoice_id" 		: $(this).val(),
	    			"doc_sequence_value": $(this).data('doc_sequence_value'),
	    			"org_id"			: $(this).data('org_id'),
	    			"invoice_num" 		: $(this).data('invoice_num')

	    		};
	    		index++;
			}
    	});
    	return false;*/
    	var invoices = [];
    	var index = 0;
    	var table = $('#ap_invoices_tbl').DataTable();
		table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
	   		var data = this.data();
	    	var node = this.node();   
			var cb = $('td:nth-child(1) input', node);
			/*var balance_amount = $("td:nth-child(8)",node);
			var invoice_amount = $("td:nth-child(7)",node);*/
			if(cb.is(":checked")){
				invoices[index] = {
	    			"invoice_id" 		: cb.val(),
	    			"doc_sequence_value": cb.data('doc_sequence_value'),
	    			"org_id"			: cb.data('org_id'),
	    			"invoice_num" 		: cb.data('invoice_num')
	    		};
	    		index++;
				/*total_invoice_amount += parseFloat(invoice_amount.data('invoice_amount'));
				total_balance_amount += parseFloat(balance_amount.data('invoice_balance'));
				total_selected_invoices++;*/
			}
		});

   		if(supplier_id != ""){
	    	if($("#txt_planned_pay_date").val() != ""){
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
							supplier_id : supplier_id,
							supplier_site_id : supplier_site_id,
							supplier_name : supplier_name,
							due_date : due_date,
							selected_invoices : JSON.stringify(invoices),
							planned_pay_date : planned_pay_date
						},
						url:"ajax_save_request",
						success:function(response){
							//$("#msg").html(response);
							window.location.href = "create_request";
							//$.unblockUI();
						}
					});
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
		    }
		    else {
		    	$("#err_title").text("Notification");
		    		$("#err_message").text("Please enter the planned pay date.");
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
		 }
		 else {
		 	$("#err_title").text("Notification");
    		$("#err_message").text("Please select a supplier");
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

    $("#err_notif").click(function(){
    	$.unblockUI();
    });

    $("#sel_supplier_name_site").select2();
});    
</script>
