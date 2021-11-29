<?
	$ui = new UI();
	
	
	$row = $ui->row()->open();
		$col = $ui->col()->width(3)->open();
		$col->close();
		
		$col = $ui->col()->width(6)->open();
			$box = $ui->box()->title('Change Password')->uiType('primary')->open();
				$form = $ui->form()->action('change_password/update_password')->open();
					$ui->input()->type('password')->name('old_password')->required()->placeholder('Old Password')->label('Old Password')->show();
					$ui->input()->type('password')->name('new_password')->required()->placeholder('New Password')->label('New Password')->show();
					$ui->input()->type('password')->name('confirm_password')->required()->placeholder('Confirm Password')->label('Confirm Password')->show();
					$ui->button()->type('submit')->name('submit')->uiType('primary')->value('Submit')->show();
				$form->close();
			$box->close();
		$col->close();
	$row->close();