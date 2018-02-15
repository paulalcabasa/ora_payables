<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
	</head>
	<body style=" margin: 0;padding: 0;">
		<table  cellspacing="0" cellpadding="0" border="1" style="width:100%;background-color: #ebebeb;font-family: arial,sans-serif;color: #a1a2a5;border-collapse: collapse;">
			<tbody>
				<tr>
					<td align="center" valign="top" style="padding:30px 0;">
						<table style="font-family: arial,sans-serif;margin-left: auto;margin-right: auto;width: 544px;" border="0" cellpadding="0" cellspacing="0" width="544">
							<tbody>
								<tr>
									<td style="font-family: arial,sans-serif;background-color: #d73925; padding: 20px;padding-bottom:5px;padding-left:10px;color: #ffffff;font-weight: bold;font-size: 16px;">
										IPC FSD Systems - AP System
									</td>
								</tr>
								<tr>
									<td style="background-color: #f6f6f7;padding: 15px;">
										<h1 style="text-align: center;;font-size: 15px;">
											<span><?php echo convert_to_utf8($msg_header);?></span>
										</h1>
									
										<b style="text-align: left;font-size: 14px;">PPR No</b>
										<p style="font-size: 14px;font-weight:bold;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $ppr_no; ?></p>
										
										<b style="text-align: left;font-size: 12px;">Status</b>
										<p style="font-size: 12px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $ppr_header_details->STATUS_NAME; ?></p>

										<b style="text-align: left;font-size: 12px;">Date Created</b>
										<p style="font-size: 12px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $ppr_header_details->CREATED_BY_NAME; ?></p>

										<b style="text-align: left;font-size: 12px;">Created By</b>
										<p style="font-size: 12px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $ppr_header_details->DATE_CREATED; ?></p>

									</td>
								</tr>
							
								<tr>
									<td class="bottomCorners">
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tbody>
												<tr>
													<td>&nbsp;</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="font-size: 12px;text-align: center;">&copy; 2017 Management Information System. All Rights Reserved.<br>
										<p>IPC FSD System - AP</p>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>
