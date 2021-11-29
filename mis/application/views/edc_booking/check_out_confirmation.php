<?php
$ui = new UI();

$row = $ui->row()->open();
  $ui->col()->width(3)->open()->close();
  $col = $ui->col()->width(6)->open();
    echo '<div align="center">';
    $row1 = $ui->row()->open();
      echo '<h3>Confirm Checkout</h3>';
    $row1->close();
    $row1 = $ui->row()->open();
      $ui->col()->width(4)->open()->close();
      $col1 = $ui->col()->width(4)->open();
        echo '<a href="'.site_url('edc_booking/guest_details/edit/'.$app_num).'"><button type="button" class="btn btn-danger">Cancel</button></a>';
        echo '&nbsp;';
        echo '<a href="'.site_url('edc_booking/guest_details/add_checkout/'.$guest_id).'"><button type="button" class="btn btn-success">Confirm</button></a>';
      $col1->close();
    $row1->close();
    echo '<p><small><b><i>NOTE</i></b>:To be able to check in new guests, do no check out until a guest checks in</small></p>';
    echo '</div>';

  $col->close();
$row->close();
?>
