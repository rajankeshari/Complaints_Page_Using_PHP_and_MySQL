<?php
	$ui = new UI();

	$tabBox1 = $ui->tabBox()
		   ->icon($ui->icon("list"))
		   ->title("Allotment Requests")
		   ->tab("pending_requests", "Pending Requests", true)
		   ->tab("approved_requests", "Allotted Requests")
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

		$tab2 = $ui->tabPane()->id("approved_requests")->open();

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
						</tr>
<?php
						$sno++;
					}
			echo '</table>';
		}
		$tab2->close();

		$tab3 = $ui->tabPane()->id("new_applications")->open();

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
		$tab3->close();

	$tabBox1->close();
?>

<script>
	$(document).ready(function(){
		$('#pending_requests table.dataTable').dataTable({
			"searchable":true,
			"paginated":true,
			"aaSorting":[[1,'asc']]
		});

		$('#approved_requests table.dataTable,\
			#new_applications table.dataTable').dataTable({
			"searchable":true,
			"paginated":true,
			"aaSorting":[[1,'desc']]
		});
	});
</script>
