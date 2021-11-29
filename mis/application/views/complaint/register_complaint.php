 
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

	$form = $ui->form()->action('complaint/register_complaint/insert')->open();

$res = $this->db->query("SELECT new_complaint,value FROM new_complaint;");
$x="Select";
echo "<div class='form-group col-md-6 col-lg-6'>";
echo "<label for='type'>Type of Complaint</label>";
echo "<select name='type' class='form-control'>";
// foreach ($result as $va) {
	echo "<option value='" . $x . "'>" . $x . "</option>";
foreach ($res->result() as $va)
	{
    echo "<option value='" . $va->value . "'>" . $va->new_complaint . "</option>";
}
echo "</select>";
echo "</div>";



$inputRow1 = $ui->row()->open();

		// $ui->select()
		//    ->label('Type of Complaint')
		//    ->name('type')
		//    ->required()
		//    ->options(array( $ui->option()->value('""')->text('Select'),
		// 					$ui->option()->value("Civil")->text('Civil'),
		// 					$ui->option()->value("Electrical")->text('Electrical'),
		// 					$ui->option()->value("Mess")->text('Mess'),
        //                     //$ui->option()->value("MIS")->text('MIS'),
		// 					//$ui->option()->value("Sanitary")->text('Sanitary')
		// 					//$ui->option()->value("Internet")->text('Internet')
		// 					$ui->option()->value("Sanitary")->text('Sanitation'),
		// 					$ui->option()->value("Internet")->text('Internet'),
        //                     $ui->option()->value("PC")->text('Computer'),
		// 					$ui->option()->value("UPS")->text('UPS Related'),
		// 					$ui->option()->value("Telephone")->text('Telephone'),
		// 					$ui->option()->value("Contingency")->text('Student Contingency')
		// 				  )
		// 			)
		//    ->width(6)
		//    ->show();
 		   
		$ui->select()
		   ->label('Location Type')
		   ->name('locationtype')
		   ->id('locationtype')
		   ->required()
		   ->options(array( $ui->option()->value('""')->text('Select'),
							$ui->option()->value("Hostel")->text('Hostel'),  
							$ui->option()->value("Non Hostel")->text('Non-Hostel')
						  )
					)
		   ->width(6)
		   ->show();
	$inputRow1->close();

	$inputRow2 = $ui->row()->open();
	$ui->select()
	->label('Location')
	->name('location')
	->id('location')
	->required()
	->options(array( 	$ui->option()->value("''")->text('Select')
				   )
			 )
	->width(6)
	->show();
	$inputRow2->close();

	$inputRow3 = $ui->row()->open();
		$ui->textarea()->placeholder('Location Details')->label('Location Details')->name('locationDetails')->id('locationDetails')->required()
		   ->width(6)
		   ->show();
		$ui->textarea()->label('Problem Details')->name("problemDetails")->placeholder("Problem Details")->required()
		   ->width(6)
		   ->show();
	$inputRow3->close();

	$ui->input()->type('text')->placeholder('Time of Availability')->label('Time of Availability')->name('time')->required()->show();

?>

<script type="text/javascript">

	// $("#locationtype").change(function() {
	// 	if ($(this).data('options') === undefined) {
	// 	  /*Taking an array of all options-2 and kind of embedding it on the select1*/
	// 	  $(this).data('options', $('#location option').clone());
	// 	}
	// 	var id = $(this).val();
	// 	var options = $(this).data('options').filter('[value=' + id + ']');
	// 	$('#location').html(options);
	//   });
	$(document).ready(function () {
    $("#locationtype").change(function () {
        var val = $(this).val();
        if (val == "Hostel") {
            $("#location").html("<option value=''>Select</option><option value='Amber Hostel'>Amber Hostel</option><option value='Diamond Hostel'>Diamond Hostel</option><option value='Emerald Hostel'>Emerald Hostel</option><option value='International Hostel'>International Hostel</option><option value='Jasper Hostel'>Jasper Hostel</option><option value='JRF Hostel'>JRF Hostel</option><option value='Opal Hostel'>Opal Hostel</option><option value='Rosaline Hostel'>Rosaline Hostel</option><option value='Ruby'>Ruby</option><option value='Ruby Annex'>Ruby Annex</option><option value='Sapphire Hostel'>Sapphire Hostel</option><option value='Topaz Hostel'>Topaz Hostel</option><option value='Others'>Others</option>");
        } else if (val == "Non Hostel") {
            $("#location").html("<option value=''>Select</option><option value='NA'>NA for Student Contingency</option><option value='Department'>Department</option><option value='Office'>Office</option><option value='Residence'>Residence</option><option value='Shanti Bhawan'>Shanti Bhawan</option><option value='SAH'>SAH</option><option value='EDC'>EDC</option><option value='Others'>Others</option>");
        } 
		// else if (val == "item3") {
        //     $("#size").html("<option value='test'>item3: test 1</option><option value='test2'>item3: test 2</option>");
        // } else if (val == "item0") {
        //     $("#size").html("<option value=''>--select one--</option>");
        // }
    });
});

</script>


<center>
<?php
	$ui->button()
		->value('Submit')
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