<?php

	$check_in = date('Y-m-d H:i:s', strtotime('now') + 12600);
	$check_out = date('Y-m-d H:i:s', strtotime('now') + 12600);
	//room booking history
	$ui = new UI();
	$box = $ui->box()
			  ->width(10)
			  ->title("Search Booking History")
			  ->solid()
			  ->uiType("primary")
			  ->open();

$form = $ui->form()->id('bkh_form')->multipart()->action('edc_booking/guest_details/display_booking_history')->open();

	$alert_row = $ui->row()->open();
		$ui->alert()
			->title('Fill the Known Fields Only')
			->uiType('info')
			->width(6)
			->show();
	$alert_row->close();
	 //search by name of guest
	$fields_row = $ui->row()->open();

		$ui->input()
			->label("Enter Name")
			->name('name')
			->id('name')
			->width(6)
			->show();

			$ui->select()
				->name('building')
				->label('Select Building')
				->addonLeft($ui->icon("bars"))
				->options(array(
					$ui->option()->value()->text('Select All')->selected(),
					$ui->option()->value('old')->text('Old'),
					$ui->option()->value('extension')->text('Extension')))
				->show();
		$fields_row->close();

		$floor_box = $ui->row()->open();
			$col4 = $ui->col()
				->name('floor')
				->id('floor')
				->width(12)
				->open();
			$col4->close();
		$floor_box->close();
	//search by date
	$radio_row = $ui->row()->open();
		$radio_col = $ui->col()->width(6)->open();
						echo '<label for="date__selection_radio">Date selection </label>
						<div id = "date_selection_radio">';
						$specific_col = $ui->col()->width(1)->open();
						echo '<input type="radio" name="date_selection" value="1">Specific';
						$specific_col->close();
						$dump_col = $ui->col()->width(1)->open()->close();
						$range_col = $ui->col()->width(1)->open();
						echo '<input type="radio" name="date_selection" value="0">Range';
						$range_col->close();
						echo '</div>';
		$radio_col->close();
		$col = $ui->col()->width(6)->id('specific_col')->open();
			$ui->datepicker()
				->label("Enter Specific Date")
				->name('date')
				->id('specific_date')
				->placeholder("dd-mm-yyyy")
				->addonLeft($ui->icon("calendar"))
				->show();
		$col->close();

		$col = $ui->col()->width(6)->id('range_col')->open();
			$ui->datepicker()
				->label("From")
				->name('check_in')
				->width(6)
				->id('check_in')
				->placeholder("dd-mm-YYYY")
				->addonLeft($ui->icon("calendar"))
				->show();

			$ui->datepicker()
				->label("To")
				->name('check_out')
				->width(6)
				->id('check_out')
				->placeholder("dd-mm-YYYY")
				->addonLeft($ui->icon("calendar"))
				->show();
		$col->close();
	$radio_row->close();
	echo '<br/>';
	echo '<input type="hidden" name="auth" value="'.$auth.'"></input>';
	$button_row = $ui->row()->open();
			echo '<center>';
					$ui->button()
						->uiType('primary')
						->value('Get Details')
						->name('get_details')
						->id('get_details')
						->submit()
						->show();
			echo '</center>';
	$button_row->close();

$form->close();
$box->close();

?>

<script>
	$(document).ready(function(){
		$('#specific_col').attr('style', 'padding-left:0;padding-right:0');
		$('#range_col').attr('style', 'padding-left:0;padding-right:0');
		$("#specific_col").hide();
		$("#range_col").hide();
		$('select[name="building"] option:contains("Select All")').prop('selected', true);
		$('input[name=date_selection]').iCheck('uncheck');
		$('#specific_date').val('');
		$('#check_in').val('');
		$('#check_out').val('');

		$('input[name="date_selection"]').on('ifChanged', function(){
			var value  = this.value;
			if(value === '0'){
				$("#specific_col").hide();
				$('#specific_date').val('');
				$("#range_col").show();
			}
			else if(value === '1'){
				$("#range_col").hide();
				$('#check_in').val('');
				$('#check_out').val('');
				$("#specific_col").show();
			}
		});

		$('select[name="building"]').change(function(){
			if($(this).val() == 'Select All'){
				$('#floor').empty();
			}
			else {
				$.ajax({url : site_url("edc_booking/guest_details/room_planning/"+$(this).val()),
					success : function (result) {
						$('#floor').html(result);
				}});
			}
		});

		$('select[name="building"]').change(function(){
			$('html, body').animate({
				scrollTop: $("#floor").offset().top
			}, 750);
		});

		$('#get_details').click(function(){
			var building = $('select[name="building"]').val();
			var checked = $('[type=checkbox]:checked').length;
			if(checked == 0)
				$('[type=checkbox]').attr('checked', true);
		});
	});
</script>
