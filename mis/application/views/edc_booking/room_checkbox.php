<style>
  .bg-warning{
    background-color:#C0C0C0;
  }
</style>

<?php
  $ui = new UI();

  foreach($room_array as $room_type) //room types suite_AC double_AC
  {
      $type = str_replace(' ', '_', strtolower($room_type['room_type']));
      $room_type_box = $ui->box()
                          ->name('floor_'.$type)
                          ->open();

      $row = $ui->row()->open();
        $col = $ui->col()->width(2)->open();
          echo '<h4>'.$room_type['room_type'].'</h4>';
        $col->close();
        $ui->button()
            ->value('Clear')
            ->name('clear_btn')
            ->id('clear_'.str_replace(' ', '_', strtolower($room_type['room_type'])))
            ->show();
        $ui->button()
            ->value('Select All')
            ->name('select_all_btn')
            ->id('sel_all_'.str_replace(' ', '_', strtolower($room_type['room_type'])))
            ->show();
      $row->close();
      echo '<br/>';
      $t = $ui->table()
              ->condensed()
              ->open();

      foreach($floor_room_array as $floor)
      {
        $temp_floor = $floor[0];
        echo '<tr>';
          echo '<th class="col-md-1">';
              echo ucwords($floor[0]).' Floor'; //these print floor numbers in floor plan
              unset($floor[0]);
            echo '</th>';
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
                  $output_str .= '<input type="checkbox" class="ckbox_'.str_replace(' ', '_', strtolower($room_type['room_type'])).'" name="checkbox_rooms[]" value="';
                  $output_str .= $row[0].'"></td>';
                }
                else if($row[4]) {
                  $output_str .= '"bg-warning" title="'.$row[5].'"';
                  $output_str .= 'align="center">'.$row[1].' ';
                  $output_str .= '<input type="checkbox" class="ckbox_'.str_replace(' ', '_', strtolower($room_type['room_type'])).'" name="checkbox_rooms[]" value="';
                  $output_str .= $row[0].'"></td>';
                }
                else {
                  $output_str .= '"bg-success"';
                  $output_str .= 'align="center">'.$row[1].' ';
                  $output_str .= '<input type="checkbox" class="ckbox_'.str_replace(' ', '_', strtolower($room_type['room_type'])).'" name="checkbox_rooms[]" value="';
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
      echo '<br/>';
      $room_type_box->close();
    }
?>

<script>
  $(document).ready(function(){
    $('[name="select_all_btn"]').addClass('pull-right');
    $('[name="clear_btn"]').addClass('pull-right');
    $('#remove').hide();
    $('#block').hide();
    $('#unblock').hide();

    $('#sel_all_double_bedded_ac').click(function(){
      $('.ckbox_double_bedded_ac').prop('checked', true);
    });

    $('#sel_all_ac_suite').click(function(){
      $('.ckbox_ac_suite').prop('checked', true);
    });

    $('#clear_double_bedded_ac').click(function(){
      $('.ckbox_double_bedded_ac').prop('checked', false);
    });

    $('#clear_ac_suite').click(function(){
      $('.ckbox_ac_suite').prop('checked', false);
    });

  });
</script>
