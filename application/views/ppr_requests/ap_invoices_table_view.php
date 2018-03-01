<?php
	foreach($invoices as $inv){

	$cb_state = "";
	$row_style = "";
	if($inv->STATUS != "Validated"){
		$cb_state = "disabled";
		$row_style = "style='background-color:red;color:#fff;'";
	}

?>

	<tr <?php echo $row_style;?>>
		<td><input type="checkbox" <?php echo $cb_state;?> class="cb_invoice" value="<?php echo $inv->INVOICE_ID;?>" data-org_id="<?php echo $inv->ORG_ID;?>" data-doc_sequence_value="<?php echo $inv->DOC_SEQUENCE_VALUE;?>" data-invoice_num="<?php echo $inv->INVOICE_NUM;?>"/></td>
		<td><?php echo $inv->INVOICE_NUM;?></td>
		<td><?php echo $inv->DOC_SEQUENCE_VALUE;?></td>	
		<td><?php echo $inv->SUPPLIER_NAME;?></td>	
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
