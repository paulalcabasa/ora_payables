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
					<form class="form-horizontal" method="POST" action="vat_list" id="frm_vat_filter">
	             		<div class="col-md-6">
							<div class="form-group">  
								<label class="col-md-3 control-label">Invoice GL Date</label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="txt_display_date" value="<?php echo format_date_slash($start_date) . ' - ' . format_date_slash($end_date);?>"/>
									<input type="hidden" id="txt_initial_start_date" name="start_date" value="<?php echo $start_date; ?>"/>
									<input type="hidden" id="txt_initial_end_date" name="end_date" value="<?php echo $end_date; ?>"/>
								</div>
							</div>
							<div class="form-group">  
								<label class="col-md-3 control-label">Voucher Number</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="voucher_no" value="<?php echo $voucher_no;?>"/>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">  
								<label class="col-md-3 control-label">Invoice Number</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="invoice_num" value="<?php echo $invoice_num;?>"/>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">  
								<label class="col-md-3 control-label">Transaction Types</label>
								<div class="col-md-9">
									<select class="form-control" name="sel_transaction_type">
									<option value=""></option>
									<?php
										foreach($transaction_types as $row){

									?>
										<option value="<?php echo $row->FLEX_VALUE;?>"><?php echo $row->VALUE_DESCRIPTION;?></option>
									<?php
										}
									?>
									</select>
								</div>
							</div>
						</div>
					</form>
	             
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<button type="button" id="btn_search" class="btn btn-danger btn-sm">Find</button>
					</div>
				</div>
			
			</div>

			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-list fa-1x"></i> Payment Details</h3>
				</div>
				<div class="box-body">
                	<table class="table nowrap table-condensed table-bordered" id="tbl_vat_list">
						<thead>
							<tr>
								<th>Transaction Type</th>
								<th>Application Period</th>
								<th>APV No</th>
								<th>Invoice Line Number</th>
								<th>Voucher Date</th>
								<th>Account</th>
								<th>VAT Amount</th>
								<th>Supplier ID</th>
								<th>Supplier Name</th>
								<th>Tax Code</th>
								<th>Invoice Num</th>
								<th>Third Party Supplier ID</th>
								<th>Third Party Supplier</th>
								<th>Bank Name</th>
								<th>Check Date</th>
								<th>Check Number</th>
								<th>Payment Amount</th>
								<th>Release Date</th>
								<th>OR No</th>
								<th>OR Date</th>
								<th>Entry Date</th>
								
							</tr>
						</thead>
						<tbody>
						<?php
							foreach($vat_list as $row){
						?>
							<tr>
								<td><?php echo $row->TRANSACTION_TYPE;?></td>
							<?php 
								if($row->APPLICATION_PERIOD == "") { 
							?>
								<td>
									<div class="input-group">
										<input type="text" class="form-control input-sm input_application_period" placeholder="MM/YYYY">
										<span class="input-group-btn">
											<button class="btn btn-danger btn-sm btn_save_application_period" 
													type="button"
													data-ap_voucher_no="<?php echo $row->DOC_SEQUENCE_VALUE;?>"
													data-ap_line_number="<?php echo $row->LINE_NUMBER;?>"
													data-ap_dist_line_number="<?php echo $row->DISTRIBUTION_LINE_NUMBER;?>"
													data-vat_detail_id="<?php echo $row->VAT_DETAIL_ID;?>"
											><i class="fa fa-save fa-1x"></i></button>
										</span>
								    </div>
								    <span class="help-block text-red hidden">* Enter a value for Application Period</span>
								</td>
								
								<?php 
								} else { 
							?>
								<td>
									<a href="#" class="btn_edit_vat_details"><i class="fa fa-edit fa-1x"></i></a>
									<span class="spn_display_application_period"><?php echo $row->APPLICATION_PERIOD;?></span>
									<div class="input-group hidden">
										<input type="text" value="<?php echo $row->APPLICATION_PERIOD;?>" class="form-control input-sm input_application_period" placeholder="MM/YYYY">
										<span class="input-group-btn">
											<button class="btn btn-danger btn-sm btn_save_application_period" 
													type="button"
													data-ap_voucher_no="<?php echo $row->DOC_SEQUENCE_VALUE;?>"
													data-ap_line_number="<?php echo $row->LINE_NUMBER;?>"
													data-ap_dist_line_number="<?php echo $row->DISTRIBUTION_LINE_NUMBER;?>"
													data-vat_detail_id="<?php echo $row->VAT_DETAIL_ID;?>"
											><i class="fa fa-save fa-1x"></i></button>
										</span>
								    </div>
								    <span class="help-block text-red hidden"></span>
								</td>
							<?php } ?>
								<td><?php echo $row->DOC_SEQUENCE_VALUE;?></td>
								<td><?php echo $row->LINE_NUMBER;?></td>
								<td><?php echo $row->GL_DATE;?></td>
								<td><?php echo $row->ACCOUNT;?></td>
								<td><?php echo $row->VAT_AMOUNT;?></td>
								<td><?php echo $row->SUPPLIER_ID;?></td>
								<td><?php echo $row->SUPPLIER_NAME;?></td>
								<td><?php echo $row->TAX_CODE;?></td>
								<td><?php echo $row->INVOICE_NUM;?></td>
								<td><?php echo $row->THIRD_PARTY_SUPPLIER_ID;?></td>
								<td><?php echo $row->THIRD_PARTY_SUPPLIER;?></td>
								<td><?php echo $row->BANK_NAME;?></td>
								<td><?php echo $row->CHECK_DATE;?></td>
								<td><?php echo $row->CHECK_NUMBER;?></td>
								<td><?php echo $row->PAYMENT_AMOUNT;?></td>
								<td><?php echo $row->RELEASE_DATE;?></td>
								<td><?php echo $row->OR_NO;?></td>
								<td><?php echo $row->OR_DATE;?></td>
								<td><?php echo $row->ENTRY_DATE;?></td>
								
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




<!-- Printing Options -->
<script src="<?php echo base_url('resources/input-mask/jquery.inputmask.js'); ?>"></script>
<script src="<?php echo base_url('resources/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?php echo base_url('resources/blockui/jquery.blockUI.js'); ?>"></script>
<script src="<?php echo base_url('resources/js/utils.js'); ?>"></script>
<script src="<?php echo base_url('resources/daterangepicker/moment.min.js');?>"></script>
<script src="<?php echo base_url('resources/daterangepicker/daterangepicker.js');?>"></script>
<!-- Data Tables -->
<script src="<?php echo base_url('resources/datatables/datatables.min.js');?>"></script>
<script src="<?php echo base_url('resources/DataTables-1.10.16/Buttons-1.4.2/js/dataTables.buttons.min.js');?>"></script>
<script src="<?php echo base_url('resources/DataTables-1.10.16/Buttons-1.4.2/js/buttons.bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('resources/DataTables-1.10.16/Buttons-1.4.2/buttons.flash.min.js');?>"></script>
<script src="<?php echo base_url('resources/DataTables-1.10.16/Buttons-1.4.2/jszip.min.js');?>"></script>
<script src="<?php echo base_url('resources/DataTables-1.10.16/Buttons-1.4.2/pdfmake.min.js');?>"></script>
<script src="<?php echo base_url('resources/DataTables-1.10.16/Buttons-1.4.2/vfs_fonts.js');?>"></script>
<script src="<?php echo base_url('resources/DataTables-1.10.16/Buttons-1.4.2/buttons.html5.min.js');?>"></script>
<script src="<?php echo base_url('resources/DataTables-1.10.16/Buttons-1.4.2/buttons.print.min.js');?>"></script>



<script>


$(document).ready(function(){
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
       	// start_date = moment(new Date(start_date)).format("DD-MMM-YYYY");
      	// end_date = moment(new Date(end_date)).format("DD-MMM-YYYY");
       	$("#txt_initial_start_date").val(start_date);
       	$("#txt_initial_end_date").val(end_date);
        // initialize_table(start_date,end_date,status_id,user_type);
    });


	$("#btn_search").click(function(){
		$("#frm_vat_filter").submit();
	}); 

	$("#tbl_vat_list").DataTable({
		scrollX : true,
		order : [],
		  dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel'
            // , 'pdf', 'print'
        ]
	});

	$(".input_application_period").inputmask('mm/yyyy', {'placeholder' : 'mm/yyyy'});

	$("body").on("click",".btn_save_application_period",function(){
		var application_period = $(this).parent().prev().val();
		var ap_voucher_no = $(this).data('ap_voucher_no');
		var ap_line_number = $(this).data('ap_line_number');
		var ap_dist_line_number = $(this).data('ap_dist_line_number');
		var vat_detail_id = $(this).data('vat_detail_id');
		var td_element = $(this).parent().parent();
		if(application_period == ""){
			$(this).parent().parent().next().removeClass('hidden');
		}
		else {
			$(this).parent().parent().next().addClass('hidden');
			$(this).parent().parent().html("Please wait <img src='../images/ajax-loader-red.gif'/>");
			alert(vat_detail_id);
			
			$.ajax({
				type:"POST",
				data:{
					application_period : application_period,
					ap_voucher_no : ap_voucher_no,
					ap_line_number : ap_line_number,
					ap_dist_line_number : ap_dist_line_number,
					vat_detail_id : vat_detail_id
				},
				url:"<?php echo base_url();?>disbursement/ajax_save_vat_details",
				success:function(response){
					alert(response);
					td_element.html(application_period);
				}
			});

			
		}

	});

	$("body").on("click",".btn_edit_vat_details",function(){
		$(this).hide();
		$(this).next().hide();
		$(this).next().next().removeClass('hidden');
	});
}); 
</script>
