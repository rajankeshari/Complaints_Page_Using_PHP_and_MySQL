<style>
	@media print {
  		a[href]:after {
    		content: none !important;
    	}
  	}
</style>

<?php
     // room booking history view

    $ui = new UI();

	//$day_time = 86400;
	$total_funds = 0;

    $row_guest_history = $ui->row()->open();
		$box_guest_history = $ui->box()
					  			->title('Guests Details')
								  ->solid()
								  ->uiType('primary')
								  ->open();

			  if(!$guests) {
				$ui->callout()
					->uiType('info')
					->title('No History Found')
					->desc("No booking details found  for entered details")
					->width(12)
					->show();
				}

			$table = $ui->table()->hover()->bordered()
							->sortable()->searchable()->paginated()
						    ->open();
			if($guests) {
		?>
						<thead>
							<tr>
								<th>S. No.</th>
								<th>App.No.</th>
								<th>Name</th>
								<th>Address</th>
								<th>Rooms</th>
								<th>CheckIn</th>
								<th>CheckOut</th>
								<th>Contact </th>
								<th>Email ID</th>
								<th>Bill</th>
							</tr>
						</thead>

<?php
					$i=1;
					for($i = 0; $i < count($guests); $i++) {
						$total_funds += $guests[$i]['paid'];
?>
						<tr>
							<td><?=($i + 1)?></td>
							<td><? echo '<a href="'.site_url("edc_booking/booking_request/details/".$guests[$i]['app_num']."/".$auth).'">'.$guests[$i]['app_num'].'</a>'; ?></td>
							<td><?=$guests[$i]['name']?></td>
							<td><?=$guests[$i]['address'] ?></td>
							<td><? if($guests[$i]['rooms'])
										foreach($guests[$i]['rooms'] as $rooms)
											echo ucfirst($rooms['building']).' - '.$rooms['room_no'].' '.$rooms['room_type'].'<br/>';
									else echo 'No Record!';
							?></td>
							<td><?= $guests[$i]['check_in'] ?></td>
							<td>
							<?php
								if($guests[$i]['check_out'])
									echo $guests[$i]['check_out'];//date('d M Y g:i a', strtotime());
								else
								{
								 echo '<p style= "color:red">Checkout Pending </p>';
								}
							?>
							</td>
							<td><?= $guests[$i]['contact'] ?></td>
							<td><?= $guests[$i]['email'] ?></td>
							<td><?
								if($guests[$i]['paid'])
									echo $guests[$i]['paid'];
								else echo '-';
							?></td>
						</tr>
					<? }
					}
			$table->close();
			if($guests) {
				echo '<br/>';
				$row = $ui->row()->open();
					$ui->col()->width(9)->open()->close();
					$col = $ui->col()->width(3)->open();
						$table = $ui->table()->open(); ?>
						<tr class="danger">
							<th>Total Funds</th>
							<td align="right"><?= $total_funds ?></td>
						</tr>
						<? $table->close();
					$col->close();
				$row->close();
			}
		$box_guest_history->close();
		$row_guest_history->close();

	?>
	<br/>
	<div class="row">
	<div class="col-md-5"></div>
	<div class="col-md-1"><button class="btn" id="btn_print">Print</button></div>
	<div class="col-md-5"></div>
</div>
<script>
	$(document).ready(function(){
		$('#btn_print').click(function(){
			$(this).hide();
			window.print();
			$(this).show();
		});

	});
</script>
