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
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-search fa-1x"></i> Filter</h3>
					
				</div>
				<div class="box-body">
					
	             	<form class="form-horizontal" method="POST" action="disbursement_list" id="frm_disbursement_filter">
	             		<div class="col-md-6">
							<div class="form-group">  
								<label class="col-md-3 control-label">Check Date</label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="txt_display_date" value="<?php echo format_date_slash($start_date) . ' - ' . format_date_slash($end_date);?>"/>
									<input type="hidden" id="txt_initial_start_date" name="start_date" value="<?php echo $start_date; ?>"/>
									<input type="hidden" id="txt_initial_end_date" name="end_date" value="<?php echo $end_date; ?>"/>
								</div>
							</div>

							<div class="form-group">  
								<label class="col-md-3 control-label">Release Status</label>
								<div class="col-md-9">
									<select class="form-control" name="release_status">
										<option value="" <?php if($release_status == ""){ echo "selected";}?> ></option>
										<option value="unreleased" <?php if($release_status == "unreleased"){ echo "selected";}?> >Unreleased</option>
										<option value="released" <?php if($release_status == "released"){ echo "selected";}?> >Released</option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">  
								<label class="col-md-3 control-label">Check Number</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="check_number" value="<?php echo $check_number;?>"/>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<button type="button" id="btn_search" class="btn btn-danger btn-sm">Submit</button>
					</div>
				</div>
			
			</div>

			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-list fa-1x"></i> Payment Details</h3>
				</div>
				<div class="box-body">
                	<table class="table nowrap table-condensed table-bordered" id="tbl_disbursement_list">
						<thead>
							<tr>
								<th>OR</th>
								<th>Supplier ID</th>
								<th>Supplier Name</th>
								<th>Payment Document Name</th>
								<th>Check No</th>
								<th>Check Voucher No</th>
								<th>Check Amount</th>
								<th>Check Date</th>
								<th>Status</th>
								<th>Bank</th>
								<th>Release Date</th>
								<th>OR No.</th>
								<th>OR Date</th>
								<th>Entry Date</th>
								
								<th>Remarks</th>
							</tr>
						</thead>
						<tbody>
						<?php
							foreach($disbursement_list as $row){
						?>
							<tr>
								<td><a href="#"  data-check_id="<?php echo $row->CHECK_ID;?>" data-official_receipt_id="<?php echo $row->OFFICIAL_RECEIPT_ID;?>" class="btn btn-danger btn-xs btn_add_update_or_details"><i class="fa fa-edit fa-1x"></i></a></td>
								<td><?php echo $row->SUPPLIER_ID;?></td>
								<td><?php echo $row->SUPPLIER_NAME;?></td>
								<td><?php echo $row->PAYMENT_DOCUMENT_NAME;?></td>
								<td><?php echo $row->CHECK_NUMBER;?></td>
								<td><?php echo $row->CHECK_VOUCHER_NO;?></td>
								<td><?php echo $row->CHECK_AMOUNT;?></td>
								<td><?php echo $row->CHECK_DATE;?></td>
								<td><?php echo $row->STATUS;?></td>
								<td><?php echo $row->BANK_NAME;?></td>
								<td><?php echo $row->RELEASE_DATE;?></td>
								<td><?php echo $row->OR_NO;?></td>
								<td><?php echo $row->OR_DATE;?></td>
								<td><?php echo $row->ENTRY_DATE;?></td>
								<td><?php echo $row->REMARKS;?></td>
							</tr>
						<?php
							}
						?>
						</tbody>
					</table>	
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Dialog confirmation -->
<div class="modal fade" tabindex="-1" role="dialog" id="dialog_or_details">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Official Receipt Details</h4>
      </div>
      <div class="modal-body">
      	<p class="alert alert-success hidden" id="msg_or_details"></p>
      	<form class="form">
      		<div class="form-group">  
				<label class=" control-label">Check Number</label>
				<input type="text" readonly="readonly" class="form-control" id="txt_check_number"/>
				
			</div>
			<div class="form-group">  
				<label class=" control-label">Check Voucher Number</label>
				<input type="text" readonly="readonly" class="form-control" id="txt_check_voucher_no"/>
			</div>
      		<div class="form-group">  
				<label class=" control-label">OR Number</label>
				<input type="text" class="form-control" id="txt_or_number"/>
				<span class="help-block text-red hidden">* Required field : OR Number</span>
			</div>
			<div class="form-group">  
				<label class=" control-label">OR Date (MM/DD/YYYY)</label>
				<input type="text" class="form-control" id="txt_or_date"/>
				<span class="help-block text-red hidden">* Required field : OR Date</span>
			</div>
			<div class="form-group">  
				<label class=" control-label">Remarks</label>
				<textarea class="form-control" id="txt_remarks"></textarea>
			</div>
      	</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" id="btn_save_official_receipt">Save Changes</button>
         <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Printing Options -->
<script src="<?php echo base_url('resources/input-mask/jquery.inputmask.js'); ?>"></script>
<script src="<?php echo base_url('resources/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?php echo base_url('resources/blockui/jquery.blockUI.js'); ?>"></script>
<script src="<?php echo base_url('resources/js/utils.js'); ?>"></script>
<script src="<?php echo base_url('resources/daterangepicker/moment.min.js');?>"></script>
<script src="<?php echo base_url('resources/daterangepicker/daterangepicker.js');?>"></script>
<!-- Data Tables -->
<script src="<?php echo base_url('resources/datatables/datatables.min.js');?>"></script>
<script>


$(document).ready(function(){

	var check_number;
	var check_voucher_no;
	var official_receipt_id;
	var operation;
	var table_row;
	var or_number;
	var or_date;
	var remarks;
	var check_id;
	
	$("#tbl_disbursement_list").DataTable({
		scrollX : true,
		order : []
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
	       	//start_date = moment(new Date(start_date)).format("DD-MMM-YYYY");
	      // 	end_date = moment(new Date(end_date)).format("DD-MMM-YYYY");
	       	$("#txt_initial_start_date").val(start_date);
	       	$("#txt_initial_end_date").val(end_date);
	       //	initialize_table(start_date,end_date,status_id,user_type);
	    });

	$("#btn_search").click(function(){
		$("#frm_disbursement_filter").submit();
	});   

	
	$("body").on("click",".btn_add_update_or_details",function(){
		table_row = $(this).parent().parent();
		check_number = table_row.find("td:nth-child(5)").text();
		check_voucher_no = table_row.find("td:nth-child(6)").text();
		or_number = table_row.find("td:nth-child(12)").text();
		or_date = table_row.find("td:nth-child(13)").text();
		check_id = $(this).data("check_id");
		remarks = table_row.find("td:nth-child(15)").text();
		
		$("#txt_check_number").val(check_number);
		$("#txt_check_voucher_no").val(check_voucher_no);
		$("#txt_or_number").val(or_number);
		$("#txt_or_date").val(or_date);
		$("#txt_remarks").val(remarks);
		$("#dialog_or_details").modal('show');
		
	});


	$('#txt_or_date').inputmask('mm/dd/yyyy', {'placeholder' : 'mm/dd/yyyy'});

	$("#btn_save_official_receipt").click(function(){
		official_receipt_id = table_row.find("td:nth-child(1)").find("a:first-child").attr("data-official_receipt_id");
		operation = official_receipt_id == "" ? "insert" : "update";
		
		or_number = $("#txt_or_number").val();
		or_date = $("#txt_or_date").val();
		or_date_orig = $("#txt_or_date").val();
		remarks = $("#txt_remarks").val();
		var error_ctr = 0;
		if(or_number == ""){
			$("#txt_or_number").next().removeClass("hidden");
			error_ctr++;
		}
		else {
			$("#txt_or_number").next().addClass("hidden");
		}
		if(or_date == ""){
			$("#txt_or_date").next().removeClass("hidden");
			error_ctr++;
		}
		else {
    		or_date = moment(new Date(or_date)).format("DD-MMM-YYYY");	
			$("#txt_or_date").next().addClass("hidden");
		}
		//official_receipt_id table_row.find("td:nth-child(1)").find("a:first-child").attr("data-official_receipt_id",data.official_receipt_id);
					
		if(error_ctr == 0){
		//	alert(official_receipt_id + " " + operation + " " + or_number + " " + or_date + " " + remarks);
			$.ajax({
				type:"POST",
				data:{
					official_receipt_id : official_receipt_id,
					operation : operation,
					or_number : or_number,
					or_date : or_date,
					remarks : remarks,
					check_number : check_number,
					check_voucher_no : check_voucher_no,
					check_id : check_id
				},
				url:"<?php echo base_url();?>disbursement/ajax_save_or_details",
				success:function(response){
					var data = JSON.parse(response);
					table_row.find("td:nth-child(12)").text(or_number);
					table_row.find("td:nth-child(13)").text(or_date_orig);
					table_row.find("td:nth-child(14)").text(data.entry_date);
					table_row.find("td:nth-child(15)").text(remarks);
					table_row.find("td:nth-child(1)").find("a:first-child").attr("data-official_receipt_id",data.official_receipt_id);
					//official_receipt_id = data.official_receipt_id;
					//console.log(table_row.find("td:nth-child(1)").find("a:first-child").data('official_receipt_id'));
					//	table_row.find("td:nth-child(15)").text(remarks);
				//	$("#dialog_or_details").modal("hide");
					$("#msg_or_details").html(data.message).removeClass('hidden');

				}
			});
		}
	});

	$("#dialog_or_details").on("hidden.bs.modal",function(){
		$("#txt_or_number,#txt_or_date,#txt_remarks").val("");
		$("#msg_or_details").addClass("hidden");
	});


}); 
</script>
