<?php
class Edc_guest_model extends CI_Model
{
  function __construct() {
    // Call the Model constructor
    parent::__construct();
  }

	function guests($app_num) {
		$query = $this->db->query('
			SELECT *
			FROM edc_guest_details
			WHERE app_num = "'.$app_num.'"
		');
		return $query->result_array();
	}

  function guest_count($app_num) {
		$query = $this->db->query('
			SELECT SUM(group_count) AS guest_count
			FROM edc_guest_details
			WHERE app_num = "'.$app_num.'"
		');
		return intval($query->row_array()['guest_count']);
	}

  function checked_in_guest_count($app_num) {
    $query = $this->db->query('
      SELECT SUM(group_count) AS guest_count
      FROM edc_guest_details
      WHERE app_num = "'.$app_num.'"
        AND check_out IS NULL
    ');
    return intval($query->row_array()['guest_count']);
  }

  function guest_details($guest_id) {
    $query = $this->db->query('
      SELECT *
      FROM edc_guest_details
      WHERE id = "'.$guest_id.'"
    ');
    return $query->row_array();
  }

  function guest_id($data) {
    $query = $this->db->query('
      SELECT id
      FROM edc_guest_details
      WHERE app_num = "'.$data['app_num'].'"
        AND name = "'.$data['name'].'"
        AND check_in = "'.$data['check_in'].'"
    ');

    return $query->row_array()['id'];
  }

	function guest_rooms($guest_id) {
		$query = $this->db->query('
			SELECT DISTINCT id, building, floor, room_no, room_type, checked
			FROM edc_guest_rooms AS egr,
        edc_room_details AS erd
			WHERE egr.room_id = erd.id
				AND guest_id = "'.$guest_id.'"
			');
		return $query->result_array();
	}

  function room_guests($guest, $room_id) { //guests under same app_num
    $query = $this->db->query('
      SELECT egd.*
      FROM edc_guest_rooms AS egr,
        edc_guest_details AS egd
      WHERE egr.guest_id = egd.id
        AND room_id = "'.$room_id.'"
        AND app_num = "'.$guest['app_num'].'"
        AND id != "'.$guest['id'].'"
        AND (
          (check_in BETWEEN "'.$guest['check_in'].'" AND "'.$guest['check_out'].'")
          OR (check_out BETWEEN "'.$guest['check_in'].'" AND "'.$guest['check_out'].'")
          OR (check_in < "'.$guest['check_in'].'" AND (check_out > "'.$guest['check_out'].'" OR check_out IS NULL))
        )
      ORDER BY check_in
    ');
    return $query->result_array();
  }

  function shared_rooms($guest) { //rooms under same app_num and shared by our guest
    //return an array of rooms with room details of each room that is shared
    //and guest details of guests who stayed in that room during the stay period of our guest
    $shared_rooms = $this->db->query('
      SELECT DISTINCT room_id AS id, building, floor, room_no, room_type, checked
      FROM edc_room_details AS erd,
        edc_guest_rooms AS egr,
        edc_guest_details AS egd
      WHERE erd.id = egr.room_id
        AND egd.id = egr.guest_id
        AND egd.app_num = "'.$guest['app_num'].'"
        AND egd.id != "'.$guest['id'].'"
        AND (
          (check_in BETWEEN "'.$guest['check_in'].'" AND "'.$guest['check_out'].'")
          OR (check_out BETWEEN "'.$guest['check_in'].'" AND "'.$guest['check_out'].'")
          OR (check_in < "'.$guest['check_in'].'" AND (check_out > "'.$guest['check_out'].'" OR check_out IS NULL))
        )
        AND room_id IN (
          SELECT room_id
          FROM edc_guest_rooms
          WHERE guest_id = "'.$guest['id'].'"
        )
    ')->result_array();
    //add guest details for each guest that stayed in the shared room
    foreach($shared_rooms as $key => $val)
      $shared_rooms[$key]['guests'] = $this->room_guests($guest, $val['id']);

    return $shared_rooms;
  }

	function room_occupance_history($data) {
    $d1970 = '1970-01-01';
    $name = '';
    $date = '';
    $rooms = '';

    if($data['rooms']) {
      foreach($data['rooms'] as $room)
        $rooms .= $room.', ';
      $rooms = substr($rooms, 0, -2);
    }

    if($data['name'])
      $name = 'AND name LIKE "%'.urldecode($data['name']).'%"';
    if($data['rooms'])
      $rooms = 'AND room_id IN ('.$rooms.')';

    if($data['date'] !== $d1970)
      $date = 'AND DATE(check_in) = "'.$data['date'].'"'; //86400 is daytime for one day
    else if($data['check_in'] !== $d1970 && $data['check_out'] === $d1970)
      $date = 'AND DATE(check_in) >= "'.$data['check_in'].'"';
    else if($data['check_in'] === $d1970 && $data['check_out'] !== $d1970)
      $date = 'AND DATE(check_out) <= "'.$data['check_out'].'"';
    else if($data['check_in'] !== $d1970 && $data['check_out'] !== $d1970)
      $date = 'AND DATE(check_in) BETWEEN "'.$data['check_in'].'" AND "'.$data['check_out'].'"';

    $query = $this->db->query('
      SELECT DISTINCT egd.*
      FROM edc_guest_details AS egd,
        edc_guest_rooms AS egr
      WHERE egd.id = egr.guest_id
        '.$name.'
        '.$date.'
        '.$rooms.'
      ORDER BY app_num
    ')->result_array();

    return $query;
	}

  function room_checkin_paid($guest, $room) {
      //this function checks if the room for the checkin day of the guest has already been paid in full
      //for the application that the guest belongs to
      //different cases that might occur are:
      //  1. a guest staying alone for that day checked out without the room being shared for that day
      //  2. more than two guests already stayed in that room or is staying
      //but since a guest leaving before our guest decides whether he pays or not, only 1 matters
      $left_before = $this->db->query('
        SELECT egd.*
        FROM edc_guest_details AS egd,
          edc_guest_rooms AS egr
        WHERE app_num = "'.$guest['app_num'].'"
          AND id != "'.$guest['id'].'"
          AND room_id = "'.$room['id'].'"
          AND check_out BETWEEN "'.date('Y-m-d H:i:s', strtotime(explode(' ', $guest['check_in'])[0])).'" AND "'.$guest['check_in'].'"
      ')->result_array();
      if($left_before)
        return 1;
      else return 0;
  }

	function set_paid_data($guest_id, $total_sum) {
		$this->db->query('
			UPDATE edc_guest_details
			SET paid = "'.$total_sum.'"
			WHERE id = "'.$guest_id.'"
		');
	}

  function insert_guest_details ($data) {
    $this->db->insert('edc_guest_details', $data);
  }

  function insert_guest_rooms($guest_id, $room_ids) {
    foreach($room_ids as $room_id) {
      $this->db->query('
        INSERT INTO edc_guest_rooms
        VALUES ("'.$guest_id.'", "'.$room_id.'");
      ');
    }
  }

  function checkout($guest_id) {
    $this->db->query('
      UPDATE edc_guest_details
      SET check_out = now()
      WHERE id = "'.$guest_id.'"
    ');
  }

  function room_checkin($rooms) {
    foreach($rooms as $room) {
      $this->db->query('
        UPDATE edc_room_details
        SET checked = "'.$room['checked'].'"
        WHERE id = "'.$room['id'].'"
      ');
    }
  }

  function room_checkout($guest) {
    //get all rooms which are under guest and are shared
    $shared_rooms = $this->db->query('
      SELECT room_id AS id
      FROM edc_guest_rooms AS egr,
        edc_guest_details AS egd
      WHERE egd.id = egr.guest_id
        AND app_num = "'.$guest['app_num'].'"
        AND check_in <= "'.$guest['check_out'].'"
        AND check_out IS NULL
        AND room_id IN (
          SELECT room_id
          FROM edc_guest_rooms
          WHERE guest_id = "'.$guest['id'].'"
        )
    ')->result_array();
    //set the shared rooms checked to 1
    $rooms = '';
    $exclude_condition = '';
    if($shared_rooms) {
      foreach($shared_rooms as $room)
        $rooms .= $room['id'].', ';
      $rooms = substr($rooms, 0, -2);
      $this->db->query('
        UPDATE edc_room_details
        SET checked = 1
        WHERE id IN ('.$rooms.')
      ');
      $exclude_condition = 'AND id NOT IN ('.$rooms.')';
    }

    //set checked to 0 for all rooms under guest which are not in above list
    $this->db->query('
      UPDATE edc_room_details AS erd,
        edc_guest_rooms AS egr
      SET checked = 0
      WHERE erd.id = egr.room_id
        AND guest_id = "'.$guest['id'].'"
        '.$exclude_condition.'
    ');
  }
}
?>
