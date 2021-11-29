<style>
  .bg-warning{
    background-color:#C0C0C0;
  }
</style>

<?php
  $ui = new UI();

  $form = $ui->form()
               ->id('form_rooms')
               ->multipart()
               ->action('edc_booking/guest_details/check')
               ->open();

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
                  $output_str .= '<input type="checkbox" class="disabled" name="checkbox_rooms[]" value="';
                  $output_str .= $row[0].'"></td>';
                }
                else if($row[4]) {
                  $output_str .= '"bg-warning" title="'.$row[5].'"';
                  $output_str .= 'align="center">'.$row[1].' ';
                  $output_str .= '<input type="checkbox" class="blocked" name="checkbox_rooms[]" value="';
                  $output_str .= $row[0].'"></td>';
                }
                else {
                  $output_str .= '"bg-success"';
                  $output_str .= 'align="center">'.$row[1].' ';
                  $output_str .= '<input type="checkbox" class="available" name="checkbox_rooms[]" value="';
                  $output_str .= $row[0].'"></td>';
                }
                echo $output_str;
              }
            }
            echo '<td class="primary bg-info" align="center"><a href="add_form/'.$building.'/'.$temp_floor.'/'.$room_type['room_type'].'"><i class="fa fa-plus"></i></a></td>';
          ?>
        </tr>
    <?php
      }
      echo '<th class="col-md-1">Add Floor</th><td class="primary bg-info" align="center"><a href="add_form/'.$building.'/new/'.$room_type['room_type'].'"><i class="fa fa-plus"></i></a></td>';
      $t->close();
      echo '<br/>';
      $room_type_box->close();
    }
    $row = $ui->row()->id('row'.$type)->open();
        echo '<center>';
          $ui->button()
             ->uiType('danger')
             ->value('Remove')
             ->id('remove')
             ->submit()
             ->show();
          echo ' ';
          $ui->button()
             ->uiType('warning')
             ->value('Block')
             ->id('block')
             ->submit()
             ->show();
          echo ' ';
          $ui->button()
             ->uiType('success')
             ->value('Unblock')
             ->id('unblock')
             ->submit()
             ->show();
        echo '</center>';
        echo '<input type="hidden" name="remark" value="">';
      $form->close();
    $row->close();
?>

<script>
  $(document).ready(function(){

    $('#remove').hide();
    $('#block').hide();
    $('#unblock').hide();
    $('.disabled').attr('disabled', true);

    $('[type=checkbox]').change(function(){
      if($('[type=checkbox]:checked').length > 0){
        if($('.available:checked').length > 0)
          $('#block').show();
        else $('#block').hide();
        $('#remove').show();
        $('#unblock').show();
      }
      else {
        $('#remove').hide();
        $('#block').hide();
        $('#unblock').hide();
      }
      $('.disabled').attr('disabled', true);
    });

    $('#remove').click(function(){
      //onclick set form action to remove page
      $('#form_rooms').attr('action', 'remove_rooms');
      if(confirm("Remove Room?") == false)
        return false;
    });

    $('#block').click(function(){
      //onclick set form action to block page
      $('#form_rooms').attr('action', 'block_rooms');
      var remark = prompt('Block Reason?');
      $('[name="remark"]').val(remark);
    });

    $('#unblock').click(function(){
      //onclick set form action to unblock page
      $('#form_rooms').attr('action', 'unblock_rooms');
    });

  });
</script>
