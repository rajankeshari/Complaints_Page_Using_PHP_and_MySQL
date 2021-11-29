<?php
	$ui = new UI();
	$drop_down = array();
	$app_num;
	$i=1;
	// making of droup down menu
	foreach($rooms as $room) {
		if(($room['room_type'] === 'AC Suite' && $room['checked'] == 0) ||
			$room['room_type'] === 'Double Bedded AC' && $room['checked'] < 2) {
			$drop_down[$i++] = $ui->option()->value($room['id'])->text(ucfirst($room['building'])." - ".$room['room_no'].' '.ucfirst($room['room_type'].' ['.$room['checked'].' occupied]'));
		}
	}

	$column_blank = $ui->col()->width(1)->open()->close();

	$column_main = $ui->col()->width(10)->open();
	$row_app_detail = $ui->row()->open();
	$app_num = $app_detail['app_num'];
	$box = $ui->box()
			  ->solid()
			  ->id('app_detail_box')
			  ->title("Application No. : ".$app_detail['app_num'])
			  ->uiType('primary')
			  ->open();
	echo '<div id="app_info">';
		$table = $ui->table()->hover()
					->open();
?>
		<tr>
			<th><? $ui->icon("clock-o")->show() ?>Applied On</th>
			<td><?= $app_detail['app_date'] ?></td>
		</tr>
		<tr>
			<th>Purpose of Visit</th>
			<td><?= $app_detail['purpose_of_visit']?></td>
		</tr>
		<tr>
			<th>Applicant Name</th>
			<td><?= $app_detail['name'] ?></td>
		</tr>
		<tr>
			<th>Designation</th>
			<td><?= $app_detail['designation'] ?></td>
		</tr>
		<tr>
			<th><? $ui->icon("clock-o")->show() ?> Check In</th>
			<td><?= $app_detail['check_in'] ?></td>
		</tr>
		<tr>
			<th><? $ui->icon("clock-o")->show() ?> Check Out</th>
			<td><?= $app_detail['check_out'] ?></td>
		</tr>
		<tr>
			<th>Number of Guests</th>
			<td><?= $app_detail['no_of_guests'] ?></td>
		</tr>
			<? if(count($rooms) > 0) { ?>
		<tr>
			<th>Rooms allotted</th>
			<td><?
				foreach($rooms as $room)
					echo ucfirst($room['building']).' - '.$room['room_no'].' '.$room['room_type'].'<br/>'; ?>
			</td>
		</tr>
			<? }?>

		<tr>
			<th>Whether School Guest?</th>
			<td><?if ($app_detail['school_guest'] == '1') echo "Yes"; else echo "No";  ?></td>
		</tr>
		<tr>
			<th>Remark</th>
			<td><?= $app_detail['Remark']?></td>
		</tr>
		<? if ($app_detail['school_guest'] == '1') { ?>
			<tr>
				<th>Approval Letter</th>
				<td><a href="<?= site_url('../assets/files/edc_booking/'.$app_detail['user_id'].'/'.$app_detail['file_path']) ?>">View Approval Letter</a></td>
			</tr>
		<? } ?>
	<?php

	$table->close();
	echo '</div>';
	$box->close();
	$row_app_detail->close();

	if(count($guest_details) != 0) {
		$row_guest_details = $ui->row()->open();
		$box_guest_details = $ui->box()
					  			->title('Guest Details')
								  ->solid()
								  ->uiType('primary')
								  ->open();
		$table = $ui->table()->hover()->bordered()
								->sortable()->searchable()->paginated()
							    ->open();
		?>
								<thead>
									<tr>
										<th>Sl No.</th>
										<th>Name</th>
										<th>#Guests</th>
										<th>Rooms Allotted</th>
										<th>ID Card</th>
										<th>CheckIn</th>
										<th>CheckOut</th>
										<th>Receipt</th>
									</tr>
								</thead>

		<?php

							$i=1;
							foreach($guest_details as $guest) {
		?>
								<tr>

									<td><?= $i++ ?></td>
									<td><a href="<?= site_url('edc_booking/guest_details/guest_info/'.$app_detail['user_id'].'/'.$guest['id']) ?>"><?=$guest['name']?></a></td>
									<td><?= $guest['group_count'] ?></td>
									<td><? if(!count($guest['rooms']))
														echo 'No rooms allotted!';
												 foreach($guest['rooms'] as $room)
										   			echo ucfirst($room['building']).' - '.$room['room_no'].' '.$room['room_type'].'<br/>'; ?></td>

									<td><a href="<?= site_url('../assets/files/edc_booking/'.$app_detail['user_id'].'/'.$guest['identity_card']) ?>">View</a></td>
									<td><?=date('d/m/Y g:i A', strtotime($guest['check_in']))?></td>
									<td<?
										if($guest['check_out'] != NULL)
											echo '>'.date('d/m/Y g:i A', strtotime($guest['check_out']));
										else {
											echo ' align="center">
												<a href="'.site_url('edc_booking/guest_details/confirm_checkout/'.$guest['id']).'">';
												$ui->button()
													->icon($ui->icon('remove'))
													->mini()
													->uiType('danger')
													->value('CheckOut')
													->show();
											echo '</a>';
										}?>
									</td>
									<td><?
										if($guest['paid'] === NULL) {
											$function_string = 'generate_bill';
											$bill_string = 'View Bill';
										}
										else {
											$function_string = 'generate_receipt';
											$bill_string = 'View Receipt';
										}
										if($guest['check_out']!=NULL)
											echo '<a href="'.site_url('edc_booking/guest_details/'.$function_string.'/'.$guest['id']).'">'.$bill_string.'</a>';
										else echo 'Checkout Pending'; ?></td>
								</tr>
							<? }

							$table->close();
		$box_guest_details->close();
		$row_guest_details->close();
	}

	if($guest_count < $app_detail['no_of_guests'] &&
		(($app_detail['check_out'] < date('Y-m-d H:i:s') && $checked_in_guest_count)
		|| $app_detail['check_out'] > date('Y-m-d H:i:s'))) { //the total number of guests that have checked in is not equal to the number of guests mentioned in application
		$row_add_checkin = $ui->row()->open();
		$box = $ui->box()
				  ->title('Add Guest Checkin')
				  ->solid()
				  ->uiType('primary')
				  ->open();
		?>
		<ul class="nav nav-tabs nav-justified">
	  		<li class="active"><a data-toggle="tab" href="#group">Group</a></li>
	  		<li ><a data-toggle="tab" href="#individual">Individual</a></li>
		</ul>
		<br />
		<div class="tab-content">
			<div id="group" class="tab-pane fade in active">
				<? $form = $ui->form()->multipart()->action('edc_booking/guest_details/insert_guest/'.$app_num.'/group')->open();
				$inputRow1 = $ui->row()->open();
					$ui->input()
					   ->type('text')
					   ->label('Name<span style= "color:red;"> *</span>')
					   ->name('name')
					   ->required()
					   ->width(6)
					   ->show();
				 	$ui->input()
				       ->type('text')
				   	   ->label('Designation')
				       ->name('designation')
				   	   ->width(6)
				       ->show();
				$inputRow1->close();
				$inputRow2 = $ui->row()->open();
					 $ui->input()
					 	->type('text')
					    ->label('Address<span style= "color:red;"> *</span>')
						->name('address')
						->width(6)
						->required()
						->show();
					$ui->select()
					    ->label('Gender<span style= "color:red;"> *</span>')
						->name('gender')
						->options(array($ui->option()->value('m')->text('Male'),
										$ui->option()->value('f')->text('Female')))
						->width(6)
						->required()
						->show();
				$inputRow2->close();

			$contact_row = $ui->row()->open();
					$ui->input()
					    ->label('Contact Number<span style= "color:red;"> *</span>')
						->name('contact')
						->width(6)
						->required()
						->show();

					$ui->input()
					    ->label('Email ID<span style= "color:red;"> *</span>')
						->name('email')
						->width(6)
						->type('email')
						->required()
						->show();
				$contact_row->close();

				$inputRow3 = $ui->row()->open();
					$guest_no = array();
					for($i = 1; $i <= $app_detail['no_of_guests'] - $guest_count; $i++)
						$guest_no[$i] =  $ui->option()->value($i)->text($i);
					$ui->select()
						->label('#Guests')
						->name('group_count')
						->options($guest_no)
						->width(6)
						->required()
						->show();
					$checkbox_col = $ui->col()->width(6)->open();
					echo '<label for="rooms[]">Select Rooms</label><br/>';
					foreach($rooms as $room) {
						$disabled = '';
						if(($room['room_type'] === 'AC Suite' && $room['checked'] == 1) ||
							$room['room_type'] === 'Double Bedded AC' && $room['checked'] == 2)
							$disabled = 'disabled="disabled"';
						echo '<input type="checkbox" name="rooms[]" '.$disabled.' value="'.$room['id'].'" data-room_type="'.$room['room_type'].'" data-checked="'.$room['checked'].'">'.ucfirst($room['building']).' - '.$room['room_no'].' '.ucfirst($room['room_type']).' ['.$room['checked'].' occupied]<br/>';
					}
					echo '<br/>';
					$checkbox_col->close();
				$inputRow3->close();
				$inputRow4 = $ui->row()->open();
					$ui->input()
					 	->type('file')
					    ->label('Upload Identity Card')
						->name('identity_card')
						->required()
						->width(12)
						->show();
				$inputRow4->close();
				echo '<center>';
					$ui->button()
						->value('Add Checkin')
				    ->uiType('primary')
						->icon($ui->icon('plus'))
				    ->submit()
				    ->id('ckbox_btn')
				    ->show();
				echo '</center>';
				$form->close();

			echo '</div><div id="individual" class="tab-pane fade">';

				$form = $ui->form()->multipart()->action('edc_booking/guest_details/insert_guest/'.$app_num.'/individual')->open();
				$inputRow1 = $ui->row()->open();
					$ui->input()
					   ->type('text')
					   ->label('Name<span style= "color:red;"> *</span>')
					   ->name('name')
					   ->required()
					   ->width(6)
					   ->show();
				 	$ui->input()
				       ->type('text')
				   	   ->label('Designation')
				       ->name('designation')
				   	   ->width(6)
				       ->show();
				$inputRow1->close();
				$inputRow2 = $ui->row()->open();
					 $ui->input()
					 	->type('text')
					    ->label('Address<span style= "color:red;"> *</span>')
						->name('address')
						->required()
						->width(6)
						->show();
					$ui->select()
					    ->label('Gender<span style= "color:red;"> *</span>')
						->name('gender')
						->options(array($ui->option()->value('m')->text('Male'),
										$ui->option()->value('f')->text('Female')))
						->width(6)
						->required()
						->show();
				$inputRow2->close();

				$contact_row = $ui->row()->open();
					echo '<div class="form-group col-md-6 col-lg-6">
						<label for="contact">Contact Number<span style= "color:red;"> *</span></label>
						<input class="form-control" type="number" min="0" name="contact" />
					</div>';

					$ui->input()
					    ->label('Email ID<span style= "color:red;"> *</span>')
						->name('email')
						->width(6)
						->type('email')
						->required()
						->show();
				$contact_row->close();

				$inputRow3 = $ui->row()->open();
					if($drop_down)
						$ui->select()
					    ->label('Select Room<span style= "color:red;"> *</span>')
						->name('rooms')
						->options($drop_down)
						->width(6)
						->required()
						->show();
					else {
						$col = $ui->col()->width(6)->open();
							echo '<label for="room_full">Select Rooms</label>';
							echo '<p name="room_full">Allotted Rooms Full!</p>';
						$col->close();
					}
					$ui->input()
						->name('group_count')
						->value('1')
						->type('hidden')
						->show();
				$inputRow3->close();
				echo '<br/>';
				$inputRow4 = $ui->row()->open();
					$ui->input()
					 	->type('file')
					  ->label('Upload Identity Card')
						->name('identity_card')
						->required()
						->width(12)
						->show();
				$inputRow4->close();
				echo '<center>';
				 	$ui->button()
					->value('Add Checkin')
				    ->uiType('primary')
					->icon($ui->icon('plus'))
				    ->submit()
				    ->id('drpdwn_btn')
				    ->show();
				echo '</center>';
				$form->close();
			echo '</div></div>';

		$row_add_checkin->close();
	}

	$column_main->close();
?>
