<?php
	$ui = new UI();

	$tabBox1 = $ui->tabBox()
		   ->icon($ui->icon("list"))
		   ->id("request_tabs")
		   ->title("Booking Requests")
		   ->tab("pending_requests", "Pending Requests", true)
		   ->tab("cancel_requests", "Cancellation Requests")
		   ->tab("rejected_requests", "Rejected Requests")
		   ->tab("approved_requests", "Approved Requests")
		   ->tab("new_applications", "New Applications")
		   ->open();

	$tab1 = $ui->tabPane()->id("pending_requests")->active()->open();

	if ($total_rows_pending == 0) {
		$ui->callout()
		   ->uiType("info")
		   ->title("No Pending Requests.")
		   ->desc("")
		   ->show();
	}

	else {
		echo '<table id="thisone" class="table table-hover table-bordered dataTable">';
?>
		<thead>
			<tr>
				<th>Application No.</th>
				<th>Applied On</th>
				<th>Applied By</th>
				<th>No. of Guests</th>
			</tr>
		</thead>
<?php
		$sno=1;
		while ($sno <= $total_rows_pending)
		{
?>
			<tr>
				<td><a href="<?php echo site_url("edc_booking/booking_request/details/".$data_array_pending[$sno][1]."/".$auth);?>"><?php echo $data_array_pending[$sno][1];?></a></td>
				<td><?php echo $data_array_pending[$sno][2];?></td>
				<td><?php echo $data_array_pending[$sno][3];?></td>
				<td><?php echo $data_array_pending[$sno][4];?></td>
			</tr>
<?php
						$sno++;
					}
			echo '</table>';
	}
		$tab1->close();

		$tab12 = $ui->tabPane()->id("cancel_requests")->open();

	if ($total_rows_cancel == 0) {
		$ui->callout()
		   ->uiType("info")
		   ->title("No Cancellation Requests.")
		   ->desc("")
		   ->show();
	}

	else {
		echo '<table class="table table-hover table-bordered dataTable">';
?>
		<thead>
			<tr>
				<th>Application No.</th>
				<th>Applied On</th>
				<th>Applied By</th>
				<th>No. of Guests</th>
				<th>Cancellation Reason</th>
			</tr>
		</thead>
<?php
		$sno=1;
		while ($sno <= $total_rows_cancel)
		{
?>
			<tr>
				<td><a href="<?php echo site_url("edc_booking/booking_request/details/".$data_array_cancel[$sno][1]."/".$auth);?>"><?php echo $data_array_cancel[$sno][1];?></a></td>
				<td><?php echo $data_array_cancel[$sno][2];?></td>
				<td><?php echo $data_array_cancel[$sno][3];?></td>
				<td><?php echo $data_array_cancel[$sno][4];?></td>
				<td><?php echo $data_array_cancel[$sno][5];?></td>
			</tr>
<?php
						$sno++;
					}
			echo '</table>';
	}
		$tab12->close();

		$tab2 = $ui->tabPane()->id("rejected_requests")->open();

			if ($total_rows_rejected == 0) {
				$ui->callout()
				   ->uiType("info")
				   ->title("No Rejected Requests.")
				   ->desc("")
				   ->show();
			}

			else {
				echo '<table id="thisone" class="table table-hover table-bordered dataTable">';
?>
					<thead>
						<tr>
							<th>Application No.</th>
							<th>Applied On</th>
							<th>Applied By</th>
							<th>No. of Guests</th>
						</tr>
					</thead>
<?php
					$sno=1;
					while ($sno <= $total_rows_rejected)
					{
?>
						<tr>
							<td><a href="<?php echo site_url("edc_booking/booking_request/details/".$data_array_rejected[$sno][1]."/".$auth);?>"><?php echo $data_array_rejected[$sno][1];?></a></td>
							<td><?php echo $data_array_rejected[$sno][2];?></td>
							<td><?php echo $data_array_rejected[$sno][3];?></td>
							<td><?php echo $data_array_rejected[$sno][4];?></td>
						</tr>
<?php
						$sno++;
					}
			echo '</table>';
		}
		$tab2->close();

		$tab3 = $ui->tabPane()->id("approved_requests")->open();

			if ($total_rows_approved == 0) {
				$ui->callout()
				   ->uiType("info")
				   ->title("No Approved Requests.")
				   ->desc("")
				   ->show();
			}

			else {
				echo '<table id="thisone" class="table table-hover table-bordered dataTable">';
?>
					<thead>
						<tr>
							<th>Application No.</th>
							<th>Applied On</th>
							<th>Applied By</th>
							<th>No. of Guests</th>
							<? if($auth == 'pce') echo '<th>Forced Cancellation</th>'; ?>
						</tr>
					</thead>
<?php
					$sno=1;
					while ($sno <= $total_rows_approved)
					{
?>
						<tr>
							<td><a href="<?php echo site_url("edc_booking/booking_request/details/".$data_array_approved[$sno][1]."/".$auth);?>"><?php echo $data_array_approved[$sno][1];?></a></td>
							<td><?php echo $data_array_approved[$sno][2];?></td>
							<td><?php echo $data_array_approved[$sno][3];?></td>
							<td><?php echo $data_array_approved[$sno][4];?></td>
							<? if($auth == 'pce')
								{
									echo '<td>';
									if($data_array_approved[$sno]['guest_checked_in'] != 0)
										echo '<center>'.$ui->icon('remove').'Cancel Not Permitted</center>';
									else if($data_array_approved[$sno]['pce_status'] == 'Cancelled') echo '<center>Cancelled</center>';
									else {
										echo '<center>';
										$form = $ui->form()
												   ->multipart()
												   ->action('edc_booking/booking_request/cancellation/'.$data_array_approved[$sno][1].'/pce')
												   ->open();

										echo '<input type="hidden" name="cancel_reason" value="">';

										$ui->button()
											->name('cancel')
											->value('Cancel Request')
											->uiType('danger')
											->icon($ui->icon('remove'))
											->submit()
											->show();
										echo '</center>';
										}
									echo '</td>';
								}
							?>
						</tr>
<?php
						$sno++;
					}
			echo '</table>';
		}
		$tab3->close();

		$tab4 = $ui->tabPane()->id("new_applications")->open();

			if ($total_new_apps == 0) {
				$ui->callout()
				   ->uiType("info")
				   ->title("No New Applications.")
				   ->desc("")
				   ->show();
			}

			else {
				echo '<table id="thisone" class="table table-hover table-bordered dataTable">';
?>
					<thead>
						<tr>
							<th>Application No.</th>
							<th>Applied On</th>
							<th>Applied By</th>
							<th>No. of Guests</th>
						</tr>
					</thead>
<?php
					$sno=1;
					while ($sno <= $total_new_apps)
					{
?>
						<tr>
							<td><a href="<?php echo site_url("edc_booking/booking_request/details/".$data_array_new_apps[$sno][1]."/".$auth);?>"><?php echo $data_array_new_apps[$sno][1];?></a></td>
							<td><?php echo $data_array_new_apps[$sno][2];?></td>
							<td><?php echo $data_array_new_apps[$sno][3];?></td>
							<td><?php echo $data_array_new_apps[$sno][4];?></td>
						</tr>
<?php
						$sno++;
					}
			echo '</table>';
		}
		$tab4->close();

	$tabBox1->close();
?>

<script>
	$(document).ready(function(){
		$('#pending_requests table.dataTable').dataTable({
			"searchable":true,
			"paginated":true,
			"aaSorting":[[1,'asc']]
		});

		$('#cancel_requests table.dataTable,\
			#rejected_requests table.dataTable,\
			#approved_requests table.dataTable,\
			#new_applications table.dataTable').dataTable({
			"searchable":true,
			"paginated":true,
			"aaSorting":[[1,'desc']]
		});
	});
</script>
