 
 <?php 
	$ui = new UI();
//echo form_open('complaint/register_complaint/insert');  

	$row = $ui->row()->open();
	
	$column1 = $ui->col()->width(2)->open();
	$column1->close();
	
	$column2 = $ui->col()->width(8)->open();
	$box = $ui->box()
			  ->title('You can add new type of complaints here')
			  ->solid()	
			  ->uiType('primary')
			  ->open();

	$form = $ui->form()->action('complaint/add_new_complaint/insert')->open();


	// $inputRow1 = $ui->row()->open();
	// 	$ui->select()
	// 	   ->label('Type of Complaint')
	// 	   ->name('type')
	// 	   ->required()
	// 	   ->options(array( $ui->option()->value('""')->text('Select'),
	// 						$ui->option()->value("Civil")->text('Civil'),
	// 						$ui->option()->value("Electrical")->text('Electrical'),
	// 						$ui->option()->value("Mess")->text('Mess'),
    //                         //$ui->option()->value("MIS")->text('MIS'),
	// 						//$ui->option()->value("Sanitary")->text('Sanitary')
	// 						//$ui->option()->value("Internet")->text('Internet')
	// 						$ui->option()->value("Sanitary")->text('Sanitation'),
	// 						$ui->option()->value("Internet")->text('Internet'),
    //                         $ui->option()->value("PC")->text('Computer'),
	// 						$ui->option()->value("UPS")->text('UPS Related'),
	// 						$ui->option()->value("Telephone")->text('Telephone'),
	// 						$ui->option()->value("Contingency")->text('Student Contingency')
	// 					  )
	// 				)
	// 	   ->width(6)
	// 	   ->show();
 		   
	// 	$ui->select()
	// 	   ->label('Location Type')
	// 	   ->name('locationtype')
	// 	   ->id('locationtype')
	// 	   ->required()
	// 	   ->options(array( $ui->option()->value('""')->text('Select'),
	// 						$ui->option()->value("1")->text('Hostel'),  
	// 						$ui->option()->value("2")->text('Non-Hostel')
	// 					  )
	// 				)
	// 	   ->width(6)
	// 	   ->show();
	// $inputRow1->close();

	// $inputRow2 = $ui->row()->open();
	// $ui->select()
	// ->label('Location')
	// ->name('location')
	// ->id('location')
	// ->required()
	// ->options(array( $ui->option()->value("1")->text('Select'),
 	// 				 $ui->option()->value("2")->text('Select'),
	// 				 $ui->option()->value("1")->text('Amber Hostel'),
	// 				 $ui->option()->value("1")->text('Diamond Hostel'),
	// 				 $ui->option()->value("1")->text('Emerald Hostel'),
	// 				 $ui->option()->value("1")->text('International Hostel'),
	// 				 $ui->option()->value("1")->text('Jasper Hostel'),
	// 				 $ui->option()->value("1")->text('JRF Hostel'),
	// 				 $ui->option()->value("1")->text('Opal Hostel'),
	// 				 $ui->option()->value("1")->text('Rosaline Hostel'),
	// 				 $ui->option()->value("1")->text('Ruby'),
	// 				 $ui->option()->value("1")->text('Ruby Annex'),
	// 				 $ui->option()->value("1")->text('Sapphire Hostel'),
	// 				 $ui->option()->value("1")->text('Topaz Hostel'),
	// 				 $ui->option()->value("1")->text('Others'),
	// 				 $ui->option()->value("2")->text('NA for Student Contingency'),  
	// 				 $ui->option()->value("2")->text('Department'),
	// 				 $ui->option()->value("2")->text('Office'),
	// 				 $ui->option()->value("2")->text('Residence'),
	// 				 $ui->option()->value("2")->text('Shanti Bhawan'),
	// 				 $ui->option()->value("2")->text('SAH'),
	// 				 $ui->option()->value("2")->text('EDC'), 
	// 				 $ui->option()->value("2")->text('Others')
	// 			   )
	// 		 )
	// ->width(6)
	// ->show();
	// $inputRow2->close();

	$inputRow1 = $ui->row()->open();
		$ui->textarea()->placeholder('Enter new type of complaint')->label('New complaint')->name('newcomplaint')->id('newcomplaint')->required()
		   ->width(6)
		   ->show();
		// $ui->textarea()->label('Problem Details')->name("problemDetails")->placeholder("Problem Details")->required()
		//    ->width(6)
		//    ->show();
	$inputRow1->close();

	// $ui->input()->type('text')->placeholder('Time of Availability')->label('Time of Availability')->name('time')->required()->show();

?>


<center>
<?php
	$ui->button()
		->value('Add')
		->submit(true)
		->id('complaint')
		->uiType('primary')
		->show();
	//echo form_close(); 
	$form->close();

	$box->close();
	
	$column2->close();
	
	$row->close();
?>
</center>