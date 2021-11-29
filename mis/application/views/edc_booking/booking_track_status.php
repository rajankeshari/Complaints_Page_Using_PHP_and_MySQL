<?php
	$ui = new UI();

	$Box1 = $ui->box()
		   ->uiType('primary')
		   ->icon($ui->icon("list"))
		   ->solid()
		   ->title("Track Booking Status")
		   ->open();

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
			while ($sno <= $total_rows)
			{
?>
				<tr>
					<td><a href="<?php echo site_url("edc_booking/booking_request/details/".$data_array[$sno][1]."/".$auth);?>"><?php echo $data_array[$sno][1];?></a></td>
					<td><?php echo $data_array[$sno][2];?></td>
					<td><?php echo $data_array[$sno][3];?></td>
					<td><center><?if($data_array[$sno]['hod_status'] == 'Cancel' || $data_array[$sno]['dsw_status'] == 'Cancel' || $data_array[$sno]['pce_status'] == 'Cancel') echo "Cancellation Pending";
						else { ?>
							<? $form = $ui->form()
								   ->multipart()
								   ->action('edc_booking/booking_request/cancellation/'.$data_array[$sno][1].'/'.$this->session->userdata('auth')[0])
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
						} ?>
					</center></td>
				</tr>
<?php
				$sno++;
			}
		echo '</table>';

	$Box1->close();
?>


<script>
	$(document).ready(function(){
		$('.dataTable').dataTable({
			"searchable":true,
    		"paginated":true,
    		"aaSorting":[[1,'asc']]
        });
	});
</script>
