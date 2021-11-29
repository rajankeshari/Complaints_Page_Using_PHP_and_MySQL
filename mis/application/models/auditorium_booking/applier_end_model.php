<?php
/**
 * Created by PhpStorm.
 * User: Jay Doshi
 * Date: 15/6/15
 * Time: 11:01 AM
 */
require_once 'auditorium_constants.php';
class Applier_end_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function get_audit_list()
    {
        $q ="SELECT name as auditorium_name, auditorium_id FROM auditorium_details WHERE 1 ";
        $res = $this->db->query($q)->result_array();
        return $res;
    }
    public function check_avail($audit,$s_ts,$e_ts)
    {
        $q = "SELECT id FROM auditorium_booking where auditorium_id='$audit' AND  '$s_ts' > UNIX_TIMESTAMP(book_from) AND '$s_ts' < UNIX_TIMESTAMP(book_to)";
        $res= $this->db->query($q)->result_array();
        $q1 = "SELECT id FROM auditorium_booking where auditorium_id='$audit' AND '$e_ts' > UNIX_TIMESTAMP(book_from) AND '$e_ts' < UNIX_TIMESTAMP(book_to)";
        $res1= $this->db->query($q1)->result_array();
//        print_r($q);
//        print_r($q1);
        if(count($res )==0 && count($res1)==0)
            return 0;
        else
            return 1;
    }
    public function check_avail_dayslot($audit)
    {
        $q= "SELECT book_from,book_to FROM auditorium_booking WHERE auditorium_id = '$audit' AND  status = ".Auditorium_constants::$PENDING_BOOKING. " OR status = ".Auditorium_constants::$ACCEPT_BOOKING." OR status = ".Auditorium_constants::$WAITING_CANCEL_AT_ACCEPT;
        $res = $this->db->query($q)->result_array();
        return $res;
    }
    public function multi_check_avail($audit,$s_date,$e_date,$s_time,$e_time)
    {
        $q ="select id from ".Auditorium_constants::$STATUS_TABLE." where (status = ".Auditorium_constants::$PENDING_BOOKING. " OR status = ".Auditorium_constants::$ACCEPT_BOOKING." OR status = ".Auditorium_constants::$WAITING_CANCEL_AT_ACCEPT.") and auditorium_id = '$audit' and
    (
    (
        unix_timestamp('$e_date') >= unix_timestamp(cast(book_from as date)) AND
		unix_timestamp('$e_date') <= unix_timestamp(cast(book_to as date))
	) OR
	(
        unix_timestamp('$s_date') <= unix_timestamp(cast(book_from as date)) AND
		unix_timestamp('$e_date') >= unix_timestamp(cast(book_to as date))
	) OR
	(
        unix_timestamp('$s_date') >= unix_timestamp(cast(book_from as date)) AND
		unix_timestamp('$s_date') <= unix_timestamp(cast(book_to as date))
	) OR
	(
        unix_timestamp('$s_date') >= unix_timestamp(cast(book_from as date)) AND
		unix_timestamp('$e_date') <= unix_timestamp(cast(book_from as date))
	)
)
  AND
(
(
        time_to_sec('$e_time') > time_to_sec(cast(book_from as time)) AND
		time_to_sec('$e_time') <= time_to_sec(cast(book_to as time))
	) OR
	(
        time_to_sec('$s_time') <=time_to_sec(cast(book_from as time)) AND
		time_to_sec('$e_time') >= time_to_sec(cast(book_to as time))
	) OR
	(
        time_to_sec('$s_time') >= time_to_sec(cast(book_from as time)) AND
		time_to_sec('$s_time') < time_to_sec(cast(book_to as time))
	) OR
	(
        time_to_sec('$s_time') >= time_to_sec(cast(book_from as time)) AND
		time_to_sec('$e_time') <= time_to_sec(cast(book_to as time))
	)
)";
        $res = $this->db->query($q)->result_array();
        return $res;
    }

    public function booked_slots($audit,$s_date,$e_date)
    {
        $q ="select book_from,book_to from ".Auditorium_constants::$STATUS_TABLE." where (status = ".Auditorium_constants::$PENDING_BOOKING. " OR status = ".Auditorium_constants::$ACCEPT_BOOKING." OR status = ".Auditorium_constants::$WAITING_CANCEL_AT_ACCEPT.") and auditorium_id = '$audit' and
    (
    (
        unix_timestamp('$e_date') >= unix_timestamp(cast(book_from as date)) AND
		unix_timestamp('$e_date') <= unix_timestamp(cast(book_to as date))
	) OR
	(
        unix_timestamp('$s_date') <= unix_timestamp(cast(book_from as date)) AND
		unix_timestamp('$e_date') >= unix_timestamp(cast(book_to as date))
	) OR
	(
        unix_timestamp('$s_date') >= unix_timestamp(cast(book_from as date)) AND
		unix_timestamp('$s_date') <= unix_timestamp(cast(book_to as date))
	) OR
	(
        unix_timestamp('$s_date') >= unix_timestamp(cast(book_from as date)) AND
		unix_timestamp('$e_date') <= unix_timestamp(cast(book_from as date))
	)
    )";
        $res = $this->db->query($q)->result_array();
        return $res;
    }
    public function find_approver($applier_id)
    {
        $auth = $this->session->userdata['auth'][0];


        $dept_type =$this->session->userdata['dept_type'];

       if($dept_type == 'academic')
       {
           if($auth = 'emp')
            {
               $branch =$this->session->userdata['branch_id'];
               $q ="SELECT id FROM user_auth_types WHERE auth_id='hod' and id in (select id from user_details where dept_id='$branch')";
               $res= $this->db->query($q)->result_array();
               return $res[0]['id'];
            }

            else if($auth ='stu')
           {
               $q = "SELECT id FROM user_auth_types WHERE auth_id ='dsw'";
               $res= $this->db->query($q)->result_array();
               return $res[0]['id'];
           }

       }
        else if($dept_type == 'nonacademic' )
        {
            $branch =$this->session->userdata['branch_id'];
            $q ="SELECT id FROM user_auth_types WHERE auth_id='hos' and id in (select id from user_details where dept_id='$branch')";
            $res= $this->db->query($q)->result_array();
            return $res[0]['id'];
        }
    }
    public function book_insert($applier_id,$audit,$s_ts,$e_ts,$purpose,$approver_id)
    {
        $curr_time = date_create();
        $ts = date_format($curr_time, 'Y-m-d H:i:s');
        $q = "Insert into auditorium_booking (applier_id,auditorium_id,book_from,book_to,purpose,status,approver_id,timestamp,request_level) values('$applier_id','$audit','$s_ts','$e_ts','$purpose',1,'$approver_id','$ts','1') ";
        $res= $this->db->query($q);
        $q = " Select id from auditorium_booking where applier_id = '$applier_id' AND timestamp = '$ts'";
        $res =$this->db->query($q)->result_array();
        return $res;

    }
    public function getBookingStatusOnConstraints($aud_id, $s_date, $e_date, $status){

        $q = "";
        $res = array();
        $date = new DateTime();
        $unix_ts = $date->getTimestamp();
        $user_id = $this->session->userdata['id'];

        if (strlen($s_date)  == 0 || strlen($e_date) == 0){
            $q= "SELECT * from auditorium_booking WHERE applier_id ='$user_id' AND auditorium_id like '$aud_id' AND UNIX_TIMESTAMP(book_to) >='$unix_ts'" ;
        }
        else{
            $q= "SELECT * from auditorium_booking WHERE applier_id ='$user_id' AND auditorium_id like '$aud_id' AND
                   ((unix_timestamp('$e_date') >= unix_timestamp(cast(book_from as date)) AND
		unix_timestamp('$e_date') <= unix_timestamp(cast(book_to as date))
	) OR
	(
        unix_timestamp('$s_date') <= unix_timestamp(cast(book_from as date)) AND
		unix_timestamp('$e_date') >= unix_timestamp(cast(book_to as date))
	) OR
	(
        unix_timestamp('$s_date') >= unix_timestamp(cast(book_from as date)) AND
		unix_timestamp('$s_date') <= unix_timestamp(cast(book_to as date))
	) OR
	(
        unix_timestamp('$s_date') >= unix_timestamp(cast(book_from as date)) AND
		unix_timestamp('$e_date') <= unix_timestamp(cast(book_from as date))
	))" ;
        }
        $res = $this->db->query($q)->result_array();
        return $res;
    }

    public function cancelBookingRequest($booking_id){
        $curr_time = date_create();
        $ts = date_format($curr_time, 'Y-m-d H:i:s');
        $q = "SELECT incharge_id FROM ".Auditorium_constants::$DETAILS_TABLE. " as t1 WHERE t1.auditorium_id IN ".
            " (SELECT auditorium_id FROM ".Auditorium_constants::$STATUS_TABLE." WHERE id = '$booking_id')";
        $incharge_id = $this->db->query($q)->result_array()[0]['incharge_id'];

        $cancel_at_pending = false;
        $q = "SELECT status FROM ".Auditorium_constants::$STATUS_TABLE." WHERE id = '$booking_id'";
        $current_status = $this->db->query($q)->result_array()[0]['status'];
        $s = 0;
        
        if ($current_status == Auditorium_constants::$ACCEPT_BOOKING){
            $s = Auditorium_constants::$WAITING_CANCEL_AT_ACCEPT;
        }
        else if ($current_status == Auditorium_constants::$PENDING_BOOKING){
            $q = "DELETE FROM ".Auditorium_constants::$STATUS_TABLE." WHERE id = '$booking_id'";
            $res = $this->db->query($q);
            $cancel_at_pending = true;
        }
        if ($cancel_at_pending == false){
            $incharge_id = 0;
            $q = "UPDATE ".Auditorium_constants::$STATUS_TABLE." SET status = ".$s." ".
                ", approver_id = '$incharge_id', timestamp = '$ts', request_level = ".Auditorium_constants::$REQUEST_HEAD." ".
                "WHERE id = '$booking_id'";
            $this->db->query($q);
        }
        return $incharge_id;
    }
}