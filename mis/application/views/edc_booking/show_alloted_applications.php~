<?php
	$ui = new UI();



	$outer_row = $ui->row()->open();

	$column1 = $ui->col()->width(1)->open();
	$column1->close();

	$column2 = $ui->col()->width(10)->open();
	$box = $ui->box()
			  ->solid()
			  ->title("Allotted Application List")
			  ->uiType('primary')
			  ->open();

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
								<th>Application Number</th>
								<th>Name</th>
								<th>CheckIn</th>
								<th>CheckOut</th>
								<th>No of Guests</th>
							</tr>
						</thead>
<?php
					foreach($data_array_approved as $application)
					{
?>
						<tr>

									<td><a href='<?= site_url("edc_booking/guest_details/edit/".$application[1]);?>'><?=$application[1]?></a></td>
									<td><?=$application[2]?></td>
									<td><?=$application[3]?></td>
									<td><?=$application[4]?></td>
									<td><?=$application[5]?></td>
						</tr>
<?php

					}
		echo '</table>';
	}


	$box->close();
	$column2->close();

	$outer_row->close();
?>

<script>
	$(document).ready(function(){
		$('.dataTable').dataTable({
			"searchable":true,
    		"paginated":true,
    		"aaSorting":[[2,'asc']]
        });
	});
</script>
