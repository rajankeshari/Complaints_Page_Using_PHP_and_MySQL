<?php
	$ui = new UI();

	$tabBox1 = $ui->tabBox()
		   ->icon($ui->icon("list"))
		   ->title("Booking History")
		   ->tab("approved_requests", "Approved Bookings",true)
		   ->tab("rejected_requests", "Rejected Bookings")
		   ->tab("cancelled_requsts", "Cancelled Bookings")
		   ->open();

		$tab1 = $ui->tabPane()->id("approved_requests")->active()->open();

			if ($total_rows_approved == 0) {
				$ui->callout()
				   ->uiType("info")
				   ->title("No Approved Bookings.")
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
							<th>No. of Guests</th>
							<th>Cancel Booking</th>
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
							<td><? if($data_array_approved[$sno][4]['hod_status'] == 'Cancel' || $data_array_approved[$sno][4]['dsw_status'] == 'Cancel' || $data_array_approved[$sno][4]['pce_status'] == 'Cancel') echo '<center>'.$ui->icon('clock-o').'Cancellation Pending</center>';
							else if($data_array_approved[$sno][4]['pce_status'] == 'Cancelled') echo 'Cancellation Approved';
							else if($data_array_approved[$sno]['check_out'] < date('Y-m-d H:i:s') || $data_array_approved[$sno]['guest_checked_in'] != 0) echo '<center>'.$ui->icon('remove').'Cancel Not Permitted</center>';
							else {?><center>
							<? $form = $ui->form()
									   ->multipart()
									   ->action('edc_booking/booking_request/cancellation/'.$data_array_approved[$sno][1].'/'.$this->session->userdata('auth')[0])
									   ->open();
									echo '<input type="hidden" name="cancel_reason" value="">';

									$ui->button()
										->name('cancel')
										->value('Cancel Request')
										->uiType('danger')
										->icon($ui->icon('remove'))
										->submit()
										->show();
									$form->close();
										echo '</center>'; } ?></td>
						</tr>
<?php
						$sno++;
					}
			echo '</table>';
		}
		$tab1->close();

		$tab2 = $ui->tabPane()->id("rejected_requests")->open();

			if ($total_rows_rejected == 0) {
				$ui->callout()
				   ->uiType("info")
				   ->title("No Rejected Bookings.")
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
							<th>No. of Guests</th>
							<th>Rejected By</th>
							<th>Rejection Reason</th>
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
							<td><?= $data_array_rejected[$sno][5]?></td>
						</tr>
<?php
						$sno++;
					}
			echo '</table>';
		}
		$tab2->close();

		$tab3 = $ui->tabPane()->id("cancelled_requsts")->open();

			if ($total_rows_cancelled == 0) {
				$ui->callout()
				   ->uiType("info")
				   ->title("No Cancelled Bookings.")
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
							<th>No. of Guests</th>
							<th>Cancellation Reason</th>
							<th>Cancelled On</th>
						</tr>
					</thead>
<?php
					$sno=1;
					while ($sno <= $total_rows_cancelled)
					{
?>
						<tr>
							<td><a href="<?php echo site_url("edc_booking/booking_request/details/".$data_array_cancelled[$sno][1]."/".$auth);?>"><?php echo $data_array_cancelled[$sno][1];?></a></td>
							<td><?= $data_array_cancelled[$sno][2];?></td>
							<td><?= $data_array_cancelled[$sno][3];?></td>
							<td><?= $data_array_cancelled[$sno][4];?></td>
							<td><?= $data_array_cancelled[$sno][5];?></td>
						</tr>
<?php
						$sno++;
					}
			echo '</table>';
		}
		$tab3->close();

	$tabBox1->close();
?>

<script>
	$(document).ready(function(){
		$('.dataTable').dataTable({
			"searchable":true,
    		"paginated":true,
    		"aaSorting":[[1,'desc']]
        });
	});
</script>
