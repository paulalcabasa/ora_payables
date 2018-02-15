<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-danger ap_box_invoices">
				<div class="box-body">	
					<form class="form-horizontal" id="frm_print" method="POST" action="<?php echo base_url();?>reports/print_requests_by_range" target="_blank">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-md-3">From PPR No</label>
								<div class="col-md-9">
									<select class="form-control" id="sel_from_ppr_no" name="sel_from_ppr_no"></select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">To PPR No</label>
								<div class="col-md-9">
									<select class="form-control" id="sel_to_ppr_no" name="sel_to_ppr_no"></select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"></label>
								<div class="col-md-9">
								<button type="submit" class="btn btn-primary pull-right" id="btn_print">Print</button>
								</div>
							</div>
						</div>
						<div class="col-md-6"></div>
					</form>
				</div>
				<div class="box-footer">
				
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
<script>

$(document).ready(function(){
	$("#sel_from_ppr_no,#sel_to_ppr_no").select2({
      ajax: {
        url: "<?php echo base_url();?>reports/ajax_search_ppr_no",
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
      minimumInputLength: 1
    });

   
}); 

</script>
