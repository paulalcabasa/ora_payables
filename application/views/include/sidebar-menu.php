<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?php echo base_url('resources/images/default.png');?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p><?php echo ucwords(strtolower($this->session->userdata('fnbi_first_name'))) . ' ' . ucwords(strtolower($this->session->userdata('fnbi_last_name')));?></p>
			</div>
		</div>
		

		<ul class="sidebar-menu">
			<li class="header">PAYMENT PROCESS REQUEST</li>
			<?php
				$user_access = $this->session->userdata('fnbi_system_access')[0];
				if($user_access->SYSTEM_ID == 1 && ($user_access->USER_TYPE_NAME == "Administrator" || $user_access->USER_TYPE_NAME == "Regular") ){
			?>
			<li class="<?php echo ($this->uri->uri_string() == 'ppr_requests/create_request') ? 'active' : ''; ?>"><a href="<?php echo base_url();?>ppr_requests/create_request"><i class="fa fa-edit"></i> <span>Create Request</span></a></li>
			<?php
				}
			?>
			<li class="<?php echo ($this->uri->uri_string() == 'ppr_requests/all_ppr_requests') ? 'active' : ''; ?>"><a href="<?php echo base_url();?>ppr_requests/all_ppr_requests"><i class="fa fa-list"></i> <span>All Requests</span></a></li>
			<li class="header">DISBURSEMENT</li>
			<li class="<?php echo ($this->uri->uri_string() == 'disbursement/disbursement_list_view') ? 'active' : ''; ?>"><a href="<?php echo base_url();?>disbursement/disbursement_list"><i class="fa fa-list"></i> <span>Payments</span></a></li>
			<li class="header">VAT MONITORING</li>
			<li class="<?php echo ($this->uri->uri_string() == 'disbursement/vat_list') ? 'active' : ''; ?>"><a href="<?php echo base_url();?>disbursement/vat_list"><i class="fa fa-list"></i> <span>VAT</span></a></li>
			<li class="header">REPORTS</li>
			<li class="<?php echo ($this->uri->uri_string() == 'reports/print_requests') ? 'active' : ''; ?>"><a href="<?php echo base_url();?>reports/print_requests"><i class="fa fa-file"></i> <span>Print Requests</span></a></li>
			<li class="header">FINANCEBI HOME</li>
			<li><a href="http://ecommerce5/finance_bi/dashboard"><i class="fa fa-arrow-circle-left"></i> <span>Back to FSD Home</span></a></li>
		</ul>
	</section>
<!-- /.sidebar -->
</aside>

