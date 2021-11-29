<style>
  .bg-warning{
    background-color:#C0C0C0;
  }
</style>

<?php
  $ui = new UI();


  $t = $ui->table()->condensed()->open();

  $t->close();
	echo '<h4>';
	echo ucwords($building).' Building';echo '</h4>';
	
  foreach($room_array as $room_type) //room types suite_AC double_AC
  {
      $type = str_replace(' ', '_', strtolower($room_type['room_type']));
      $room_type_box = $ui->box()
                          ->name('floor_'.$type)
                          ->title($room_type['room_type'])
                          ->open();
      $t = $ui->table()
              ->condensed()
              ->open();
      foreach($floor_room_array as $floor)
      {
        echo '<tr>';
          echo '<th class="col-md-1">';
              echo ucwords($floor[0]).' Floor'; //these print floor numbers in floor plan
              unset($floor[0]);
            ?>
          </th>
          <?php
            foreach($floor as $row)
            {
              $count = 0;
              //room types are Double Bedded AC and AC Suite
              if($room_type['room_type']==$row[2])
              {
                $count++;
                $output_str = '<td class=';
                if($row[3]==0) {
                  $output_str .= '"bg-danger"';
                  $output_str .= 'align="center">'.$row[1].' ';
                  $output_str .= '<input type="checkbox" class="disabled" name="checkbox_'.$type.'[]" value="';
                  $output_str .= $row[0].'"></td>';
                }
                else if($row[4]) {
                  $output_str .= '"bg-warning" title="'.$row[5].'"';
                  $output_str .= 'align="center">'.$row[1].' ';
                  $output_str .= '<input type="checkbox" class="disabled" name="checkbox_'.$type.'[]" value="';
                  $output_str .= $row[0].'"></td>';
                }
                else {
                  $output_str .= '"bg-success"';
                  $output_str .= 'align="center">'.$row[1].' ';
                  $output_str .= '<input type="checkbox" name="checkbox_'.$type.'[]" value="';
                  $output_str .= $row[0].'"></td>';
                }
                echo $output_str;
              }
            }
          ?>
        </tr>
    <?php
      }
      $t->close();
      $room_type_box->close();
    }
?>

<script type="text/javascript">
  $(document).ready(function(){
$('[name="checkbox_double_bedded_ac[]"]').change(function()
	{
		if(!$(this).is(':checked'))
		{
			$('[name ="checkbox_double_bedded_ac[]"]').attr('disabled', false);
		}
	});
		$('[name="checkbox_ac_suite[]"]').change(function()
	{
		if(!$(this).is(':checked'))
		{
			$('[name ="checkbox_ac_suite[]"]').attr('disabled', false);
		}
	});
    var double_AC_limit = $('input[name="double_AC"]').val();
    var suite_AC_limit = $('input[name="suite_AC"]').val();

    $('.disabled').attr('disabled', true);


    if(double_AC_limit == 0) {
      $('[name ="checkbox_double_bedded_ac[]"]').attr('disabled', true);
    }

    if(suite_AC_limit == 0) {
      $('[name ="checkbox_ac_suite[]"]').attr('disabled', true);
    }
	//Get no. of boxes enabled .
	var unchecked_enabled=$('input[name="checkbox_double_bedded_ac[]"]:enabled').length;
	
	var unchecked_enabled_suite=$('input[name="checkbox_ac_suite[]"]:enabled').length;

	//Check if rooms can be allotted or not . Accordingly display the text of submit button . 	
	if(unchecked_enabled-double_AC_limit<0 || unchecked_enabled_suite-suite_AC_limit<0)
	{
		
		$('#room_alloc_button').text('Rooms Unavailable');
		$('[name="checkbox_double_bedded_ac[]"]').attr('disabled',true);
		$('[name="checkbox_ac_suite[]"]').attr('disabled',true);
		$fla=1;
	}
	else
	{
		$('#room_alloc_button').text('Submit');
	}	
	
   
    $('[name="floor_double_bedded_ac"]').click(function () {
      var double_AC_limit = $('input[name="double_AC"]').val();
      if ($('[name ="checkbox_double_bedded_ac[]"]:checked').length < double_AC_limit) {
     //Rooms can be allotted .
      }
      else {
	//Disable room selection if rooms can't be allotted .
        $('[name ="checkbox_double_bedded_ac[]"]').not(":checked").attr('disabled', true);
      }
      $('.disabled').attr('disabled', true);
    });

    $('[name="floor_ac_suite"]').click(function () {
      var suite_AC_limit = $('input[name="suite_AC"]').val();
      if ($('[name ="checkbox_ac_suite[]"]:checked').length < suite_AC_limit) {
       //Rooms can be allotted .
      }
      else {
	//Disable if rooms can't be allotted .
        $('[name ="checkbox_ac_suite[]"]').not(":checked").attr('disabled', true);
      }
      $('.disabled').attr('disabled', true);
    });

  });
</script>
