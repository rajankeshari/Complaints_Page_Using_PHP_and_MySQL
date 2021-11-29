<?php
    $ui = new UI();
        foreach($room_array as $room_type) { //room types suite_AC double_AC
            $type = str_replace(' ', '_', strtolower($room_type['room_type']));
            $room_type_box = $ui->box()
                                ->name('floor_'.$type)
                                ->title($room_type['room_type'])
                                ->open();

                $room_table = $ui->table()
                    ->condensed()
                    ->open();

                    foreach($floor_room_array as $floor) {
                        $temp_floor = $floor[0];
                        echo '<tr>';
                        echo '<th class="col-md-1">';
                        echo ucwords($floor[0]).' Floor'; //these print floor numbers in floor plan
                        unset($floor[0]);
                        echo '</th>';
                        foreach($floor as $row) {
                            $count = 0;
                            //room types are Double Bedded AC and AC Suite
                            if($room_type['room_type']==$row[2]) {
                                $count++;
                                $output_str = '<td class=';
                                if($row[3]==1) {
                                    $output_str .= '"bg-danger"';
                                    $output_str .= 'align="center"><a class="room-app" href="#" data-toggle="modal" data-target="#room_app_list" data-room_id="'.$row[0].'">'.$row[1].'</a></td>';
                                }
                                else if($row[3]==2) {
                                    $output_str .= '"bg-occupied"';
                                    $output_str .= 'align="center"><a class="room-app" href="#" data-toggle="modal" data-target="#room_app_list" data-room_id="'.$row[0].'">'.$row[1].'</a></td>';
                                }
                                else if($row[4]) {
                                    $output_str .= '"bg-warning" title="'.$row[5].'"';
                                    $output_str .= 'align="center">'.$row[1].'</td>';
                                }
                                else {
                                    $output_str .= '"bg-success"';
                                    $output_str .= 'align="center">'.$row[1].'</td>';
                                }
                                echo $output_str;
                            }
                        }
                }
                $room_table->close();
            $room_type_box->close();
        }
?>
        <div id="room_app_list" class="modal fade" role="dialog" data-auth="<?=$auth?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        Application List
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>

        <script>
            $('.room-app').click(function() {
                var room_id = $(this).data('room_id');
                $.ajax({
                    url : site_url('edc_booking/management/room_status/' + room_id + '/' + $('#room_app_list').data('auth')),
                    error : function(e) {
                        alert(e.responseText);
                    },
                    success : function(result) {
                        $('.modal-body').html(result);
                    }
                });
            });
        </script>
