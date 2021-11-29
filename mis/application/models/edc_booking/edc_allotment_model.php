<?php
class Edc_allotment_model extends CI_Model
{
    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function initialize_building($building = '', $id = '') {
      /*  $this->db->query('
          INSERT INTO edc_room_details (id, building, floor, room_no, room_type, remark)
          VALUES ("'.$id.'", "'.$building.'", "ground", "0", "dummy", "dummy_room")
        ');
*/    }

    function add_rooms($data) {
        $this->db->insert('edc_room_details', $data);
    }

    function remove_rooms($data) {
        $query = 'DELETE FROM edc_room_details WHERE id IN (';
        foreach($data AS $room)
        $query .= $room.',';
        $query = substr($query, 0, -1).')';
        $this->db->query($query);
    }

    function block_rooms($rooms, $remark) {
        $query = 'UPDATE edc_room_details SET blocked = "1", remark = "'.$remark.'" WHERE id IN (';
        foreach($rooms AS $room)
        $query .= $room.',';
        $query = substr($query, 0, -1).')';
        $this->db->query($query);
    }

    function unblock_rooms($rooms) {
        $query = 'UPDATE edc_room_details SET blocked = "", remark="" WHERE id IN (';
        foreach($rooms AS $room)
        $query .= $room.',';
        $query = substr($query, 0, -1).')';
        $this->db->query($query);
    }

    function set_ctk_status($status,$app_num) {
        $this->db->query ("
          UPDATE edc_registration_details
          SET ctk_allotment_status = '".$status."', ctk_action_timestamp = now(), pce_status = 'Pending'
          WHERE app_num = '".$app_num."'
        ");
    }

    function insert_booking_details($data) {
        $this->db->insert('edc_booking_details',$data);
    }

    function delete_room_booking($app_num = '') {
      if($app_num == '') {
        $this->db->query('
          DELETE FROM edc_booking_details
          WHERE app_num IN (
              SELECT app_num
              FROM edc_registration_details
              WHERE check_out < TIMESTAMP(now())
              OR pce_status = "Rejected"
          )
        ');
      }
      else {
        $this->db->query('
          DELETE FROM edc_booking_details
          WHERE app_num = "'.$app_num.'"
        ');
      }
    }

    function room_details($room_id) {
        $this->db->where('id', $room_id);
        $query = $this->db->get('edc_room_details');
        return $query->row_array();
    }

    function room_group_details($rooms) {
      $rooms_str = '';
      $query = $this->db->query('
          SELECT *
          FROM edc_room_details
          WHERE id IN ('.implode(', ', $rooms).')
      ');
      return $query->result_array();
    }

    function allotted_rooms($app_num) {
  		$query = $this->db->query("
  			SELECT erd.*
  			FROM edc_booking_details AS ebd,
  				edc_room_details AS erd
        WHERE ebd.room_id = erd.id
    			AND ebd.app_num = '".$app_num."'
  		");
  		return $query->result_array();
  	}

    function floors($building) {
        $this->db->where('building',$building);
        $this->db->group_by('floor');
        $query = $this->db->order_by('id')->get('edc_room_details');
        return $query->result_array();
    }

    function rooms($building, $floor) {
        $this->db->where('building',$building);
        $this->db->where('floor',$floor);
        $query = $this->db->order_by('room_no','asc')->get('edc_room_details');
        return $query->result_array();
    }

    function room_types() {
        $result = array(
            0 => array('room_type' => 'AC Suite'),
            1 => array('room_type' => 'Double Bedded AC')
        );
        return $result;
    }

    function booked_rooms() {
        //this function returns all rooms which are booked or are being used
        $query = $this->db->query("
            SELECT room_id
            FROM edc_booking_details AS ebd
              INNER JOIN edc_registration_details AS erd
            ON ebd.app_num = erd.app_num
            WHERE check_out > CURRENT_TIMESTAMP
        ");
        return $query->result_array();
    }

    function noneditable_rooms() {
      //this function returns all rooms which are booked or are being used
      $query = $this->db->query("
        SELECT DISTINCT room_id
        FROM (
          SELECT room_id
          FROM edc_booking_details AS ebd
            INNER JOIN edc_registration_details AS erd
          ON ebd.app_num = erd.app_num
          WHERE check_out > CURRENT_TIMESTAMP

          UNION

          SELECT room_id
          FROM edc_guest_details AS egd,
            edc_guest_rooms AS egr
          WHERE egd.id = egr.guest_id
            AND check_out IS NULL
        ) AS T
      ");
      return $query->result_array();
    }

    function no_of_rooms($building) {
        return intval($this->db->query('
        SELECT COUNT(*) AS count
        FROM edc_room_details
        WHERE building = "'.$building.'"
        ')->row_array()['count']);
    }

    function check_unavail($check_in, $check_out) {
        //select all those rooms which are booked or are currently checked in
	//echo $check_in;
	//$arr=explode('%20',$check_in);
	//$check_in=implode(" ",$arr);
	
	
	//$arr=explode('%20',$check_out);
	//$check_out=implode(" ",$arr);
//	$check_in=strtotime($check_in);
//	$check_out=strtotime($check_out);
	
	//echo $check_in;
	//echo $check_out;
$check_in=rawurldecode($check_in);
	$check_in=DateTime::createFromFormat("Y-m-d H:i:s",$check_in);
	//$check_in=getTimestamp($check_in);
	$check_in=$check_in->getTimestamp();
	//$check_in=$check_in->getTimestamp();
	//echo $check_in;echo " ";
	//strptime($check_out);$check_out=rawurldecode($check_out);
	//echo $check_out;print_r($check_out);
	//$check_out=mktime($check_out);
	$check_out=rawurldecode($check_out);
	$check_out=DateTime::createFromFormat("Y-m-d H:i:s",$check_out);
//	$check_out=new DateTime($check_in);
	$check_out=$check_out->getTimestamp();
        $query = $this->db->query("
          SELECT DISTINCT room_id
          FROM (
            SELECT room_id
            FROM edc_registration_details AS erd,
              edc_booking_details AS ebd
            WHERE erd.app_num = ebd.app_num
              AND ((UNIX_TIMESTAMP(check_out) >=  '".$check_in."'
                AND UNIX_TIMESTAMP(check_in) <=  '".$check_in."')
              OR (UNIX_TIMESTAMP(check_in) >=  '".$check_in."'
                AND UNIX_TIMESTAMP(check_in) <=  '".$check_out."'))

            UNION

            SELECT room_id
            FROM edc_guest_details AS egd,
              edc_guest_rooms AS egr
            WHERE egd.id = egr.guest_id
              AND check_out IS NULL
          ) AS T
        ");
        return $query->result_array();
    }

    function checked_app($room_id) {
        $query = $this->db->query('
            SELECT DISTINCT egd.app_num
            FROM edc_guest_details AS egd,
              edc_guest_rooms AS egr
            WHERE egd.id = egr.guest_id
              AND check_out IS NULL
              AND room_id = "'.$room_id.'"
        ')->row_array();
        if($query)
            return $query['app_num'];
        else return '';
    }

    function room_bookings($room_id) {
        $query = $this->db->query('
          SELECT DISTINCT app_num
          FROM edc_booking_details as ebd
          WHERE app_num NOT IN (
              SELECT DISTINCT app_num
              FROM edc_guest_details)
          AND room_id = "'.$room_id.'"
	 AND ebd.app_num IN
	
	(SELECT DISTINCT erd.app_num FROM edc_registration_details as erd WHERE (pce_status!="Cancelled" OR pce_status IS NULL) AND (dsw_status!="Cancel" OR dsw_status IS NULL)  AND (hod_status!="Cancel"  OR hod_status IS NULL) AND (pce_status !="Cancel" OR pce_status IS NULL) AND (pce_status !="Rejected" OR pce_status IS NULL) AND check_out > CURRENT_TIMESTAMP)
		
        ');

        return $query->result_array();
    }

    function checked_rooms() {
        $query = $this->db->query('
          SELECT DISTINCT room_id
          FROM edc_guest_details AS egd,
            edc_guest_rooms AS egr
          WHERE egd.id = egr.guest_id
            AND check_out IS NULL
        ');

        return $query->result_array();
    }
}
?>
