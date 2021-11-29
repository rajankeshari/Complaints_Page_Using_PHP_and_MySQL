<?php 
	$ui = new UI();
//echo form_open('complaint/register_complaint/insert');   
	
	$row = $ui->row()->open();
	
	$column1 = $ui->col()->width(2)->open();
	$column1->close();
	
	$column2 = $ui->col()->width(8)->open();
	$box = $ui->box()
			  ->title('On Line Complaint Form')
			  ->solid()	
			  ->uiType('primary')
			  ->open();

	$form = $ui->form()->action('complaint/register_mis_complaint/insert')->open();//calling function insert from complaint model named register_complaint on submit

	$ui->input()->type('text')->placeholder('Emp/Admission Number')->label('Emp/Admission Number')->name('ak')->required()->show();




	/*$ui->input()->type('text')->placeholder('Time of Availability')->label('Time of Availability')->name('time')->required()->show(); */

	$inputRow1 = $ui->row()->open();
		$ui->select()
		   ->label('Category of Complaint')
		   ->name('type')
		   ->required()
		   ->options(array( $ui->option()->value('""')->text('Select'),
							$ui->option()->value("examacad")->text('Exam & Academic'),
							$ui->option()->value("Attendance")->text('Attendance'),
							$ui->option()->value("Personal_Details")->text('Personal Details'),
							//$ui->option()->value("Login")->text('Login'),
							$ui->option()->value("Feedback")->text('Feedback'),
							$ui->option()->value("Semester_Registration")->text('Semester Registration'),
                            $ui->option()->value("Grade_Sheet")->text('Grade Sheet'),
							$ui->option()->value("Hall_Ticket")->text('Hall Ticket'),
							$ui->option()->value("Salary")->text('Salary Issues'),
							//$ui->option()->value("abc")->text('abc'),
							$ui->option()->value("Others")->text('Others')
						  )
					)
		   ->width(6)
		   ->show();
		$ui->textarea()->label('Problem Details')->name("problemDetails")->placeholder("Problem Details")->required()
		   ->width(6)
		   ->show();
	$inputRow1->close();
?>
<center>
<?php
	$ui->button()
		->value('Submit')
		->submit(true)
		->id('mis complaint')
		->uiType('primary')
		->show();
	//echo form_close(); 
	$form->close();

	$box->close();
	
	$column2->close();
	
	$row->close();
?>
</center>