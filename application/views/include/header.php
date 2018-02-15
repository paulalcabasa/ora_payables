<!-- Main Header -->
<header class="main-header">
	<!-- Logo -->
	<a href="<?php echo base_url();?>" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"><b>IPC</b></span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><img src="<?php echo base_url('resources/images/logo_white.png');?>"></span>
	</a>

	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top" role="navigation">
		<!-- Sidebar toggle button-->
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>
	<!-- Navbar Right Menu -->
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				
				<!-- User Account Menu -->
				<li class="dropdown user user-menu">
					<!-- Menu Toggle Button -->
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<!-- The user image in the navbar-->
						<img src="<?php echo base_url('resources/images/default.png');?>" class="user-image" alt="User Image">
						<!-- hidden-xs hides the username on small devices so only the image appears. -->
						<span class="hidden-xs"><?php echo $this->session->userdata('fnbi_user_name');?></span>
					</a>
					<ul class="dropdown-menu">
						<!-- The user image in the menu -->
						<li class="user-header">
							<img src="<?php echo base_url('resources/images/default.png');?>" class="img-circle" alt="User Image">
							<p>
								<?php echo ucwords(strtolower($this->session->userdata('fnbi_first_name'))) . ' ' . ucwords(strtolower($this->session->userdata('fnbi_last_name')));?>
							</p>
						</li>
				
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left">
								<a href="#" data-toggle="control-sidebar" class="btn btn-default btn-flat">Profile</a>
							</div>
							<div class="pull-right">
								<a href="/finance_bi/login/logout" class="btn btn-default btn-flat">Sign out</a>
							</div>
						</li>
					</ul>
				</li>
				
				<!-- Control Sidebar Toggle Button -->
<!--
				<li>
					<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
				</li>
-->
			</ul>
		</div>
	</nav>
</header>   <!-- Header End -->
