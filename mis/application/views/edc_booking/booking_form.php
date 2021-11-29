<?php

	$ui = new UI();

	$row = $ui->row()->open();

	$col1 = $ui->col()
				 ->width(1)
	             ->open();
	$col1->close();

	$col2 = $ui->col()
				 ->width(10)
	             ->open();

	$box = $ui->box()
			 ->uiType('primary')
			 ->title('EDC Room Allotment Form')
			 ->solid()
			 ->open();

		$form = $ui->form()
		   ->multipart()
		   ->action('edc_booking/booking/insert_edc_registration_details')
		   ->open();

		if ($auth == 'emp') {
			$ui->select()
			   ->name('purpose')
			   ->label('Purpose')
			   ->addonLeft($ui->icon("bars"))
			   ->options(array(
	                   $ui->option()->value('Official')->text('Official'),
	                   $ui->option()->value('Personal')->text('Personal')))
	           ->required()
			   ->show();
		}

		$ui->textarea()->label('Purpose of Visit')->name('purpose_of_visit')->placeholder("Enter the purpose of visit")->required()->show();
		$row0 = $ui->row()->open();
		$ui->input()
			->width(6)
			->placeholder('Name')
			->type('text')
			->label('Accomodation Required for (Name)')
			->name('name')
			->required ()
		    ->show();

		$ui->input()
			->width(6)
			->placeholder('Designation')
			->type('text')
			->label('Designation')
			->name('designation')
			->required ()
		    ->show();
		$row0->close();

		$check_in_row = $ui->row()->open();
		$ui->datePicker()
			 ->width(6)
			 ->label ('Check-In-Date')
			 ->id('checkin')
			 ->name('checkin')
		   	 ->placeholder("yyyy-mm-dd")
			 ->addonLeft($ui->icon("calendar"))
			 ->dateFormat('yyyy-mm-dd')
			 ->required()
			 ->show();

		$ui->timePicker()->width(6)->label("Check-In-Time")->name('checkin_time')->addonLeft($ui->icon("clock-o"))->show();
		$check_in_row->close();

		$check_out_row = $ui->row()->open();
		$ui->datePicker()
			 ->width(6)
			 ->label ('Check-Out-Date')
			 ->id('checkout')
			 ->name('checkout')
		   	 ->placeholder("yyyy-mm-dd")
			 ->addonLeft($ui->icon("calendar"))
			 ->dateFormat('yyyy-mm-dd')
			 ->required()
			 ->show();

		$ui->timePicker()->width(6)->label("Check-Out-Time")->name('checkout_time')->addonLeft($ui->icon("clock-o"))->show();
		$check_out_row->close();

		$no_of_guests_row = $ui->row()->open();
		$ui->input()
			->width(6)
		   ->label('Number of Guests')
		   ->name('no_of_guests')
		   ->id('no_of_guests')
		   ->addonLeft($ui->icon("bars"))
		   ->value(1)
		   ->type('number')
	       ->required()
		   ->show();

			if($this->session->userdata['auth'][0] != 'stu')
			{
				$radio_col = $ui->col()->width(3)->id('school_guest_row')->open();
					echo '<label for="school_guest_radio">Whether School Guest</label>
					<div id = "school_guest_radio">';
						$yes_col = $ui->col()->width(1)->open();
						echo '<input type="radio" name="school_guest" value="1">Yes';
						$yes_col->close();
						$dump_col = $ui->col()->width(1)->open()->close();
						$no_col = $ui->col()->width(1)->open();
						echo '<input type="radio" name="school_guest" value="0" checked="checked">No';
						$no_col->close();
					echo '</div>';
				$radio_col->close();
			}

			$radio_col = $ui->col()->width(3)->open();
				echo '<label for="boarding_required_radio">Boarding Required</label>
				<div id = "boarding_required_radio">';
					$yes_col = $ui->col()->width(1)->open();
					echo '<input type="radio" name="boarding_required" value="1" checked="checked">Yes';
					$yes_col->close();
					$dump_col = $ui->col()->width(1)->open()->close();
					$no_col = $ui->col()->width(1)->open();
					echo '<input type="radio" name="boarding_required" value="0">No';
					$no_col->close();
				echo '</div>';
			$radio_col->close();
		$no_of_guests_row->close();

		$row_room_type = $ui->row()->open();
			$ui->select()
				->width(3)
				->id('double_AC')
			   ->label('Double AC Rooms Required')
			   ->name('double_AC')
			   ->addonLeft($ui->icon("bars"))
			   ->options(array(
			   		$ui->option()->value('0')->text('0')->selected(),
			   		$ui->option()->value('1')->text('1')))
			   ->show();

			$ui->select()
				->width(3)
				->id('suite_AC')
			   ->label('Suite AC Rooms Required')
			   ->name('suite_AC')
			   ->addonLeft($ui->icon("bars"))
			   ->options(array(
			   		$ui->option()->value('0')->text('0')->selected(),
			   		$ui->option()->value('1')->text('1')))
			   ->show();

			$col1 = $ui->col()->id('approval_letter_col')->width(6)->open();
				echo '<label for="approval_letter">Approval Letter</label>
				<input type="file" id="approval_letter" name="approval_letter" accept="image/*" data-toggle="tooltip" title="Select image only"/>';
			$col1->close();
		$row_room_type->close();
?>
<center>
<?
		$ui->button()
		   ->id('booking_form')
		   ->value('Submit')
		   ->uiType('primary')
		   ->submit()
		   ->name('submit')
		   ->show();
?>
</center>
<?
		$form->close();

	$box->close();

	$col2->close();

	$row->close();
?>

<!-- <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> -->
<script>
	//$(document).tooltip();
</script>
