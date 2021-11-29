<style>
    #booking_table td, #checked_table td {
        width: 50%;
    }
</style>

<?php
    $ui = new UI();

    if($checked_app) {
        $table = $ui->table()
                ->id('checked_table')
                ->condensed()
                ->open();

            echo '
            <tr>
                <th class="occupied" colspan="2">Checked In Application</th>
            </tr>
            <tr class="bg-success">
                <td><a href="'.site_url('edc_booking/booking_request/details/'.$checked_app['app_num'].'/'.$auth).'">'.$checked_app['app_num'].'</a></td>
                <td>'.$checked_app['name'].'</td>
            </tr>
            <tr>
                <td>Check In</td>
                <td>'.$checked_app['check_in'].'</td>
            </tr>
            <tr>
                <td>Check Out (Expected)</td>
                <td>'.$checked_app['check_out'].'</td>
            </tr>';

        $table->close();

        echo '<hr/>';
    }

  if($room_bookings) {
    $table = $ui->table()
            ->id('booking_table')
            ->condensed()
            ->open();

        echo '
        <tr>
            <th class="danger" colspan="2">Bookings</th>
        </tr>';
		if(isset($room_bookings))
        foreach($room_bookings as $booking) {
            echo '
            <tr class="bg-success">
                <td><a href="'.site_url('edc_booking/booking_request/details/'.$booking['app_num'].'/'.$auth).'">'.$booking['app_num'].'</a></td>
                <td>'.$booking['name'].'</td>
            </tr>';
        }
    $table->close();
  };
?>
