<?php
	foreach($ppr_list as $ppr){
?>
	<tr>
		<!-- <td> 
			<div class="dropdown">
			  <button class="btn btn-danger btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Action
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			    <li><a href="ppr_request_details/<?php echo encode_string($ppr->PPR_HEADER_ID);?>" target="_blank">View details</a></li>
				<?php
				if($ppr->STATUS_NAME == "Submitted"){
				?>
				<li><a target="_blank" href="<?php echo base_url();?>pdf/print_request/<?php echo encode_string($ppr->PPR_HEADER_ID);?>">Print</a></li>
				<?php
				}
				?>
	
			  </ul>
			</div> 
		</td> -->
		<td><a  class="btn btn-xs btn-primary" href="ppr_request_details/<?php echo encode_string($ppr->PPR_HEADER_ID);?>" target="_blank"><i class="fa fa-search fa-1x"></i></a></td>	
		<td><?php
				if($ppr->STATUS_NAME == "Submitted"){
				?>
				<a class="btn btn-xs btn-danger" target="_blank" href="<?php echo base_url();?>pdf/print_request/<?php echo encode_string($ppr->PPR_HEADER_ID);?>"><i class="fa fa-print fa-1x"></i></a>
				<?php
				} ?></td>
		<td><?php echo sprintf('%05d',$ppr->PPR_HEADER_ID);?></td>	
		<td><?php echo $ppr->VENDOR_NAME;?></td>	
		<td><?php echo $ppr->TOTAL_INVOICES;?></td>	
		<td><?php echo $ppr->TOTAL_INVOICE_AMOUNT;?></td>	
		<td><?php echo $ppr->TOTAL_BALANCE_AMOUNT;?></td>	
		<td><?php echo $ppr->BANK_ACCOUNT_NAME;?></td>	
		<td><?php echo $ppr->AP_CHECK_VOUCHER_NO;?></td>	
		<td><?php echo $ppr->CHECK_DATE?></td>	
		<td><?php echo $ppr->CREATED_BY;?></td>	
		<td><?php echo $ppr->DATE_CREATED;?></td>	
		<td><?php echo $ppr->STATUS_NAME;?></td>	
	</tr>
<?php
	}
?>