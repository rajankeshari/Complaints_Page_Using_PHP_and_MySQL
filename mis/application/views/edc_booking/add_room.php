<?php
	$ui = new UI();

	$box = $ui->box()
			  ->title($building.' Building - Add Room')
			  ->width(12)
			  ->open();
		$form = $ui->form()
		   		   ->multipart()
		   		   ->action('edc_booking/management/add_rooms')
		   		   ->open();

			$ui->input()
				->width(6)
				->type('text')
				->placeholder('Room No.')
				->type('text')
				->label('Room No.')
				->name('room_no')
				->required ()
			    ->show();

			$ui->input()
				->width(6)
				->type('text')
				->placeholder('Floor')
				->label('Floor')
				->name('floor')
				->required ()
				->value(ucfirst($floor))
				->disabled()
			    ->show();

			$ui->input()
				->width(6)
				->type('text')
				->label('Room Type')
				->name('type')
				->required ()
				->value(urldecode($type))
				->disabled()
			    ->show();
			
			$ui->input()
				->width(6)
				->placeholder('Remark')
				->type('text')
				->label('Remark')
				->name('remark')
				->required ()
			    ->show();

			echo '<input type="hidden" value="'.$building.'" name="building"></input>';

			echo '<center>';
				$ui->button()
				   ->value('Submit')
				   ->uiType('primary')
				   ->id('submit')
				   ->submit()
				   ->show();
			echo '</center>';
		$form->close();

	$box->close();
?>

<script>
	$(document).ready(function(){
		$('[name="type"]').attr('disabled', true);
		$('[name="floor"]').attr('disabled', true);
		if($('[name="floor"]').val() == 'New'){
			$('[name="floor"]').attr('disabled', false);
			$('[name="floor"]').val('');
		}
		$('#submit').click(function(){
			$('[name="type"]').attr('disabled', false);
			$('[name="floor"]').attr('disabled', false);
		});
	});
</script>