<?php
	$ui = new UI();
	//echo form_open('complaint/register_complaint/insert');
	$row = $ui->row()->open();

	$column1 = $ui->col()->width(1)->open();
	$column1->close();

	$column2 = $ui->col()->width(10)->open();
	$box = $ui->box()
			->solid()
			->title("Application No. : ".$app_num)
			->uiType('primary')
			->open();

	$table = $ui->table()->hover()
				->open();

	$row = $ui->row()->open();
		$ui->input()
		   ->width(6)
		   ->type('text')
		   ->label('Applied By')
		   ->value($user)
		   ->disabled()
	       ->show();

	    $ui->input()
		   ->width(6)
		   ->type('text')
		   ->label('<i class="fa fa-clock-o"></i> Applied On')
		   ->value($app_date)
		   ->disabled()
	       ->show();
	$row->close();



	$row = $ui->row()->open();
		if ($purpose != 'Personal' && strlen($hod_status) != 0 && $hod_status != 'Cancelled') {
			$label = '<i class="fa fa-clock-o"></i> ';
			if($academic == 'yes')
				$label .= 'HOD status';
			else $label .= 'HOS status';

			if($hod_status == 'Approved'){
		    	$ui->input()
				   ->width(3)
				   ->uiType('success')
				   ->type('text')
				   ->label($label)
				   ->value('Approved : '.date('d/m/Y H:m', strtotime($hod_action_timestamp)))
				   ->disabled()
			       ->show();
		    }
			else {
		    	$ui->input()
				   ->width(3)
				   ->uiType('warning')
				   ->type('text')
				   ->label($label)
				   ->value($hod_status)
				   ->disabled()
			       ->show();
		    }
		}

		if (strlen($dsw_status) != 0 && $dsw_status != 'Cancelled') {
			if($dsw_status == 'Approved'){
		    	$ui->input()
				   ->width(3)
				   ->uiType('success')
				   ->type('text')
				   ->label('<i class="fa fa-clock-o"></i> DSW Status')
				   ->value('Approved : '.date('d/m/Y H:m', strtotime($dsw_action_timestamp)))
				   ->disabled()
			       ->show();
		    }
			else {
		    	$ui->input()
				   ->width(3)
				   ->uiType('warning')
				   ->type('text')
				   ->label('<i class="fa fa-clock-o"></i> DSW Status')
				   ->value($dsw_status)
				   ->disabled()
			       ->show();
		    }
		}

		if(($auth != 'stu' && $auth != 'emp') && (strlen($ctk_allotment_status) != 0 && $hod_status != 'Rejected' && $dsw_status != 'Rejected' && $ctk_allotment_status != 'Cancelled')) {
		    if($ctk_allotment_status == 'Approved'){
		    	$ui->input()
				   ->width(3)
				   ->uiType('success')
				   ->type('text')
				   ->label('<i class="fa fa-clock-o"></i> Caretaker Status')
				   ->value('Allotted : '.date('d/m/Y H:m', strtotime($ctk_action_timestamp)))
				   ->disabled()
			       ->show();
		    }
			else {
		    	$ui->input()
				   ->width(3)
				   ->uiType('warning')
				   ->type('text')
				   ->label('<i class="fa fa-clock-o"></i> Caretaker Status')
				   ->value($ctk_allotment_status)
				   ->disabled()
			       ->show();
		    }
		}

		if ((($auth != 'stu' && $auth != 'emp' && strlen($pce_status) != 0) || (($auth == 'stu' || $auth == 'emp') && ($ctk_allotment_status == 'Pending' || strlen($pce_status) != 0))) && $hod_status != 'Rejected' && $dsw_status != 'Rejected') {
		    if($pce_status == 'Approved')
		    	$status = 'Approved : '.date('d/m/Y H:m', strtotime($pce_action_timestamp));
			else if($pce_status == '')
				$status = 'Pending';
			else $status = $pce_status;
		    if($pce_status == 'Approved'){
		    	$ui->input()
				   ->width(3)
				   ->uiType('success')
				   ->type('text')
				   ->label('<i class="fa fa-clock-o"></i> PCE Status')
				   ->value($status)
				   ->disabled()
			       ->show();
		    }
			else {
		    	$ui->input()
				   ->width(3)
				   ->uiType('warning')
				   ->type('text')
				   ->label('<i class="fa fa-clock-o"></i> PCE Status')
				   ->value($status)
				   ->disabled()
			       ->show();
		    }
		}

		if ($deny_reason) if($deny_reason != 'NULL' && $deny_reason != NULL) {
			if($pce_status != 'Cancelled' && $hod_status != 'Cancel' && $dsw_status != 'Cancel' && $pce_status != 'Cancel')
				$label = 'Reason of Rejection';
			else $label = 'Cancellation Reason';

			$ui->input()
			   ->type('text')
			   ->width(3)
			   ->label($label)
			   ->value($deny_reason)
			   ->disabled()
		       ->show();
		}
	$row->close();

	$row = $ui->row()->open();
		$ui->input()
		   ->width(6)
		   ->type('text')
		   ->label('Name')
		   ->value($name)
		   ->disabled()
	       ->show();

	    $ui->input()
		   ->width(6)
		   ->type('text')
		   ->label('Designation')
		   ->value($designation)
		   ->disabled()
	       ->show();
	$row->close();

	$row = $ui->row()->open();
		$ui->input()
		   ->width(3)
		   ->type('text')
		   ->label('Purpose')
		   ->value($purpose)
		   ->disabled()
	       ->show();

	    $ui->input()
		   ->type('text')
		   ->width(9)
		   ->label('Purpose of Visit')
		   ->value($purpose_of_visit)
		   ->disabled()
	       ->show();
	$row->close();

	$row = $ui->row()->open();
		$ui->input()
		   ->width(3)
		   ->type('text')
		   ->label('Check In')
		   ->value($check_in)
		   ->disabled()
	       ->show();

	    $ui->input()
		   ->width(3)
		   ->type('text')
		   ->label('Check Out')
		   ->value($check_out)
		   ->disabled()
	       ->show();

	    $ui->input()
		   ->width(6)
		   ->type('text')
		   ->label('Number of Guests')
		   ->value($no_of_guests)
		   ->disabled()
	       ->show();
	$row->close();

	$row = $ui->row()->open();
	    $ui->input()
		   ->width(3)
		   ->type('text')
		   ->label('Double AC Rooms (Requested)')
		   ->value($double_AC)
		   ->disabled()
	       ->show();

	    $ui->input()
		   ->width(3)
		   ->type('text')
		   ->label('Suite AC Rooms (Requested)')
		   ->value($suite_AC)
		   ->disabled()
	       ->show();

	    if ($boarding_required == '1')
		    	$output = "Yes";
			else $output = "No";
		$ui->input()
		   ->width(3)
		   ->type('text')
		   ->label('Boarding Required')
		   ->value($output)
		   ->disabled()
	       ->show();
	    $row->close();

	    $ui->input()
		   ->type('text')
		   ->width(9)
		   ->label('Remark')
		   ->value($Remark)
		   ->disabled()
	       ->show();
        //$row->close();
	    if($purpose == 'Official' && $auth != 'stu') {
	    	if ($school_guest == '1')
		    	$output = "Yes";
			else $output = "No";
		    $ui->input()
			   ->width(3)
			   ->type('text')
			   ->label('Whether School Guest')
			   ->value($output)
			   ->disabled()
		       ->show();
		}
	$row->close();

	$row = $ui->row()->open();
		if($no_of_rooms == 0 && $ctk_allotment_status == 'Approved') {
			$ui->input()
			   ->width(6)
			   ->type('text')
			   ->label('Rooms Allotted')
			   ->value('Failed to Allot Rooms!')
			   ->disabled()
		       ->show();
		}
		else if($no_of_rooms > 0) {
			$col = $ui->col()
					  ->width(6)
					  ->open();
				echo '<label>Rooms Allotted</label><br/>';
				foreach($rooms as $room)
					echo ucfirst($room['building']).' - '.$room['room_no'].' '.$room['room_type'].'<br/>';
			$col->close();
		}

		if($school_guest == '1')
		{
			$col = $ui->col()->width(6)->open();
				echo '<label>Approval Letter</label><br/>';
				echo '<a href="'.site_url('../assets/files/edc_booking/'.$user_id.'/'.$file_path).'">Click to view</a>';
			$col->close();
		}
	$row->close();

	if(($pce_status != 'Cancelled') &&
		(($auth == 'hod' || $auth == 'hos') && $hod_status == 'Pending') ||
		($auth == 'dsw' && $dsw_status == 'Pending') ||
		($auth == 'pce' && $pce_status == 'Pending'))
	{
		echo '<br/>';
		//reject approve and submit buttons
		$form = $ui->form()->action('edc_booking/booking_request/official_action/'.$app_num.'/'.$auth)->open();
				$statusbutton_row = $ui->row()->open();
		?>
				<input type='hidden' id='status' name='status' value=''>
		<?
					//$col = $ui->col()->width(2)->open()->close();
					$reject_col =$ui->col()->width(6)->open();
					echo '<center>';
					$ui->button()
						->value('Reject')
						->id('reject')
						->uiType('danger')
						->show();
					echo '</center>';
					$reject_col->close();

					if($no_of_rooms == 0 && $ctk_allotment_status == 'Approved'){
						$approve_col =$ui->col()->width(6)->open();
						echo '<center>';
						$ui->button()
							->value('Approve')
							->id('approve')
							->uiType('primary')
							->disabled()
							->show();
						echo '</center>';
						$approve_col->close();
					}
					else {
						if($auth === 'pce')
							$value = 'Approve';
						else $value = 'Forward';
						$approve_col =$ui->col()->width(6)->open();
						echo '<center>';
						$ui->button()
							->value($value)
							->id('approve')
							->uiType('primary')
							->show();
						echo '</center>';
						$approve_col->close();
					}
				$statusbutton_row->close();
				echo '<br/>';
				$reason_row = $ui->row()
								->id('rejection_reason')
								->open();
				$reason_col = $ui->col()->open();
					$ui->textarea()->id('reason')->label('Reason for Rejection')->name("reason")->placeholder('Reason for Rejection')->show();
				$reason_row->close();
				$reason_col->close();
				$submit_row = $ui->row()->open();
				$submit_col =$ui->col()->width(12)->open(); ?>
					<center>
						<?
						$ui->button()
						->value('Submit')
						->submit(true)
						->id('details_submit')
						->uiType('primary')
						->show();
				$submit_col->close();
				$submit_row->close();
		$form->close();
	}
	if((($auth == 'hod' || $auth == 'hos') && $hod_status == 'Cancel' && $pce_status!='Cancel') || ($auth == 'dsw' && $dsw_status == 'Cancel' && $pce_status!='Cancel') || ($auth == 'pce' && $pce_status == 'Cancel'))
	{
		$form = $ui->form()->id('cancel_form')->action('edc_booking/booking_request/cancellation/'.$app_num.'/'.$auth)->open();
			$cancel_row = $ui->row()->open();
				$cancel_column = $ui->col()->open();
					echo '<br/><center>';
					//if($pce_status!='Cancel'&& $auth=='dsw')
					$ui->button()
						->value('Approve Cancellation')
						->submit(true)
						->id('approve_cancel')
						->uiType('danger')
						->show();
				$cancel_column->close();
			$cancel_row->close();
		$form->close();
	}
	$box->close();
	$column2->close();
	$row->close();
?>
