<?php

/**
 * Author: Nishant Raj
*/

require_once 'result.php';
require_once 'leave_constants.php';
require_once 'leave_users_details_model.php';
class Leave_station_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * @param $emp_id
     * @param $leaving_date
     * @param $leaving_time
     * @param $arrival_date
     * @param $arrival_time
     * @param $purpose
     * @param $addr
     * @return : NONE
     */
    function insert_station_leave_details($emp_id, $leaving_date, $leaving_time, $arrival_date, $arrival_time, $purpose, $addr , $nature_of_leave , $ph_no , $fragment)
    {

        $leaving_date = strtotime($leaving_date);
        $leaving_date = date('Y-m-d', $leaving_date);


        $arrival_date = strtotime($arrival_date);
        $arrival_date = date('Y-m-d', $arrival_date);

        $current_date = date("Y-m-d");
        $sql = "INSERT INTO " . Leave_constants::$TABLE_STATION_LEAVE .
            " VALUES('','$emp_id','$current_date', '$leaving_date' , '$leaving_time' , '$arrival_date' , '$arrival_time' , '$purpose' , '$addr' , $nature_of_leave , '$ph_no' ,$fragment)";

        $this->db->query($sql);
    }

    /**
     * @param $leave_id
     * @param $current_emp
     * @param $next_emp
     * @param $status
     */
    function insert_station_leave_status($leave_id, $current_emp, $next_emp, $status)
    {

        $sql = "INSERT INTO " . Leave_constants::$TABLE_STATION_LEAVE_STATUS .
            " VALUES($leave_id , '$current_emp','$next_emp',$status,CURRENT_TIMESTAMP)";

        $this->db->query($sql);
    }

    /**
     * @param $emp_id
     * @param $applying_date
     * @param $leaving_date
     * @param $leaving_time
     * @param $arrival_date
     * @param $arrival_time
     * @param $nature_of_leave
     * @return mixed
     */
    function get_station_leave_id($emp_id, $applying_date, $leaving_date, $leaving_time, $arrival_date, $arrival_time , $nature_of_leave)
    {

        $leaving_date = strtotime($leaving_date);
        $leaving_date = date('Y-m-d', $leaving_date);

        $arrival_date = strtotime($arrival_date);
        $arrival_date = date('Y-m-d', $arrival_date);

        $applying_date = strtotime($applying_date);
        $applying_date = date('Y-m-d', $applying_date);


        $sql = "SELECT id , emp_id , leaving_date , leaving_time , arrival_date , arrival_time " .
            "FROM " . Leave_constants::$TABLE_STATION_LEAVE .
            " WHERE emp_id = '$emp_id' and leaving_date = '$leaving_date' and leaving_time = '$leaving_time'
                 and arrival_date = '$arrival_date' and arrival_time = '$arrival_time' and applying_date = '$applying_date' and nature_of_leave=$nature_of_leave";

        $result = $this->db->query($sql)->result_array();

        foreach ($result as $row) {

            return $row['id'];
        }
    }

    /**
     * @param $emp_id
     * @return array
     */
    function get_station_leave_history($emp_id)
    {

        $sql = "SELECT * FROM " . Leave_constants::$TABLE_STATION_LEAVE .
            " WHERE emp_id = '$emp_id'";

        $result = $this->db->query($sql)->result_array();
        $i = 0;
        $data = array();
        $data['data'] = NULL;
        foreach ($result as $row) {
            $data['data'][$i] = array();
            $data['data'][$i]['id'] = $row['id'];
            $date = $row['applying_date'];
            $date = strtotime($date);
            $date = date('d-M-Y' , $date);
            $data['data'][$i]['applying_date'] = $date;
            $date = $row['leaving_date'];
            $date = strtotime($date);
            $date = date('d-M-Y' , $date);
            $data['data'][$i]['leaving_date'] = $date;
            $data['data'][$i]['leaving_time'] = $row['leaving_time'];
            $data['data'][$i]['arrival_time'] = $row['arrival_time'];
            $data['data'][$i]['leave_id'] = $row['id'];
            $date = $row['arrival_date'];
            $date = strtotime($date);
            $date = date('d-M-Y' , $date);
            $data['data'][$i]['arrival_date'] = $date;
            $data['data'][$i]['purpose'] = $row['purpose'];
            $data['data'][$i]['addr'] = $row['addr'];
            $temp = $this->get_station_leave_status($row['id']);
            $data['data'][$i]['status'] = $temp['status'];
            $data['data'][$i]['fwd_by'] = $temp['fwd_by'];
            $data['data'][$i]['fwd_to'] = $this->get_user_name_by_id($temp['fwd_to']);
            $data['data'][$i]['fwd_at'] = $temp['fwd_at'];
            $data['data'][$i]['name'] = $this->get_user_name_by_id($row['emp_id']);
            $time_str = $row['leaving_date']." ".$row['leaving_time'];
            $lv_date = strtotime($time_str);
            $time_str = $row['arrival_date']." ".$row['arrival_time'];
            $rt_date = strtotime($time_str);
            $period = (($rt_date - $lv_date) / (24 * 60 * 60)) + 1;
            $data['data'][$i]['period'] = $this->get_period_in_days_hours($row['leaving_date'] , $row['leaving_time'] ,
                $row['arrival_date'] , $row['arrival_time']);;
            $i++;
        }
        return $data;
    }

    /**
     * @param $leave_id
     * @return array
     */
    function get_station_leave_status($leave_id)
    {

        $sql = "SELECT * FROM " . Leave_constants::$TABLE_STATION_LEAVE_STATUS .
            " WHERE id = '$leave_id' ORDER BY time DESC";

        $result = $this->db->query($sql)->result_array();

        $data = array();
        foreach ($result as $row) {
            $data['status'] = $row['status'];
            $data['fwd_by'] = $row['current'];
            $data['fwd_to'] = $row['next'];
            $data['fwd_at'] = $row['time'];
            return $data;
        }
    }

    function get_station_leave_status_before_cancellation($leave_id){
        $sql = "SELECT * FROM " . Leave_constants::$TABLE_STATION_LEAVE_STATUS .
            " WHERE id = '$leave_id' ORDER BY time DESC";

        $result = $this->db->query($sql)->result_array();
        $i = 0;
        foreach ($result as $row) {
            if($i == 1) {
                $status = $row['status'];
                return $status;
            }
            $i++;
        }
    }
    /**
     * @param $emp_id
     * @return string
     */
    function get_user_name_by_id($emp_id)
    {

        $sql = "SELECT * FROM user_details WHERE id = '$emp_id'";

        $result = $this->db->query($sql)->result_array();

        foreach ($result as $row) {
            $salutation = $row['salutation'];
            $f_name = $row['first_name'];
            $m_name = $row['middle_name'];
            $l_name = $row['last_name'];

            $name = "$salutation " . "$f_name " . "$m_name " . "$l_name";
            return $name;
        }
    }

    /**
     * @param $emp_id
     * @return array
     */
    function get_pending_station_leave($emp_id)
    {
        $pending = Leave_constants::$PENDING;
        $forwarded = Leave_constants::$FORWARDED;
        $sql = "SELECT * FROM " . Leave_constants::$TABLE_STATION_LEAVE_STATUS .
            " WHERE next = '$emp_id' GROUP BY id ";

        $result = $this->db->query($sql)->result_array();
        $data = array();
        $data['data'] = array();
        $data['data'] = NULL;
        $i = 0;
        foreach ($result as $row) {

            $status = $this->get_station_leave_status($row['id']);
            if (($status['status'] == Leave_constants::$PENDING || $status['status'] == Leave_constants::$WAITING_CANCELLATION || $status['status'] == Leave_constants::$FORWARDED) && $status['fwd_to'] == $emp_id) {
                $data['data'][$i] = array();
                if ($status['status'] == Leave_constants::$PENDING || $status['status'] == Leave_constants::$FORWARDED)
                    $data['data'][$i]['type'] = Leave_constants::$PENDING;
                else if($status['status'] == Leave_constants::$WAITING_CANCELLATION)
                    $data['data'][$i]['type'] = Leave_constants::$WAITING_CANCELLATION;
                $temp = array();
                $temp = $this->get_station_leave_by_id($row['id']);
                $data['data'][$i]['status'] = $status['status'];
                $data['data'][$i]['leave_id'] = $row['id'];
                $data['data'][$i]['crt_emp'] = $emp_id;
                $data['data'][$i]['emp_id'] = $temp['emp_id'];
                $data['data'][$i]['name'] = $this->get_user_name_by_id($temp['emp_id']);
                $data['data'][$i]['apl_date'] = $temp['applying_date'];
                $data['data'][$i]['lv_date'] = $temp['leaving_date'];
                $data['data'][$i]['lv_time'] = $temp['leaving_time'];
                $data['data'][$i]['rt_date'] = $temp['arrival_date'];
                $data['data'][$i]['rt_time'] = $temp['arrival_time'];
                $data['data'][$i]['purpose'] = $temp['purpose'];
                $data['data'][$i]['addr'] = $temp['addr'];
                $data['data'][$i]['period'] =$this->get_period_in_days_hours($temp['leaving_date'] , $temp['leaving_time'] ,
                    $temp['arrival_date'] , $temp['arrival_time']);
                $i++;
            }
        }
        return $data;
    }

    /**
     * @param $leave_id
     * @return string
     */
    function get_station_leave_by_id($leave_id)
    {
        $sql = "SELECT * FROM " . Leave_constants::$TABLE_STATION_LEAVE .
            " WHERE id = '$leave_id'";

        $result = $this->db->query($sql)->result_array();

        $data = NULL;
        foreach ($result as $temp) {
            $data['emp_id'] = $temp['emp_id'];
            $data['applying_date'] = $temp['applying_date'];
            $data['leaving_date'] = $temp['leaving_date'];
            $data['leaving_time'] = $temp['leaving_time'];
            $data['arrival_date'] = $temp['arrival_date'];
            $data['arrival_time'] = $temp['arrival_time'];
            $data['purpose'] = $temp['purpose'];
            $data['fragment'] = $temp['fragment'];
            $data['addr'] = $temp['addr'];
            $data['nature_of_leave'] = $temp['nature_of_leave'];
            $data['ph_no'] = $temp['emergency_phone_no'];
            $status = $this->get_station_leave_status($temp['id']);
            $data['status'] = $status['status'];
            $data['next_emp'] = $status['fwd_to'];
            return $data;
        }

    }

    function getCancellableStationLeave($emp_id)
    {

        $sql = "SELECT * FROM " . Leave_constants::$TABLE_STATION_LEAVE .
            " WHERE emp_id = '$emp_id'";

        $result = $this->db->query($sql)->result_array();
        $i = 0;
        $data = array();
        $data['data'] = NULL;
        foreach ($result as $row) {
            $temp = $this->get_station_leave_status($row['id']);
            if ($temp['status'] == Leave_constants::$APPROVED || $temp['status'] == Leave_constants::$PENDING ||
                $temp['status'] == Leave_constants::$FORWARDED
            ) {
                $data['data'][$i] = array();
                $data['data'][$i]['emp_id'] = $emp_id;
                $data['data'][$i]['id'] = $row['id'];
                $data['data'][$i]['applying_date'] = $row['applying_date'];
                $date = strtotime($row['leaving_date']);
                $date = date('d-M-Y' , $date);
                $data['data'][$i]['leaving_date'] = $date;
                $data['data'][$i]['leaving_time'] = $row['leaving_time'];
                $data['data'][$i]['arrival_time'] = $row['arrival_time'];
                $date = strtotime($row['arrival_date']);
                $date = date('d-M-Y' , $date);
                $data['data'][$i]['arrival_date'] = $date;
                $data['data'][$i]['purpose'] = $row['purpose'];
                $data['data'][$i]['addr'] = $row['addr'];
                $data['data'][$i]['status'] = $temp['status'];
                $data['data'][$i]['fwd_by'] = $temp['fwd_by'];
                $data['data'][$i]['fwd_to'] = $this->get_user_name_by_id($temp['fwd_to']);
                $data['data'][$i]['fwd_at'] = $temp['fwd_at'];
                $time_str = $row['leaving_date']." ".$row['leaving_time'];
                $lv_date = strtotime($time_str);
                $time_str = $row['arrival_date']." ".$row['arrival_time'];
                $rt_date = strtotime($time_str);
                $period = (($rt_date - $lv_date) / (24 * 60 * 60)) + 1;
                $data['data'][$i]['period'] = $this->get_period_in_days_hours($row['leaving_date'] , $row['leaving_time'] ,
                    $row['arrival_date'] , $row['arrival_time']);
                $i++;
            }
        }
        return $data;
    }

    //This will return leave having status = $status caused by employee having employee ID = $emp_id
    function get_approved_rejected_leave_by_emp($emp_id, $status , $type)
    {
        $sql = "SELECT id   FROM " . Leave_constants::$TABLE_STATION_LEAVE_STATUS .
            " WHERE next = '$emp_id' and status = $status GROUP BY id";

        $result = $this->db->query($sql)->result_array();

        $sql = "SELECT id FROM designations";
        $res = $this->db->query($sql)->result();
        $flg = false;
        if($type == ""){
            $of_emp = NULL;
            $designation = NULL;
        }
        foreach($res as $row){
            if($row->id == $type) {
                $designation = $type;
                $of_emp = NULL;
                $flg = true;
                break;
            }
        }
        if($flg == false) {
            $of_emp = $type;
            $designation = NULL;
        }
        $data = array();
        if($designation ==NULL && $of_emp == NULL){
            $j = 0;
            foreach($result as $row){
                $data[$j] = $row['id'];
                $j++;
            }
        }
        else if($designation != NULL){
            $j = 0;
            foreach($result as $row){
                $emp_of_applying_leave = $this->get_emp_id_by_leave_id($row['id']);
                if($designation == Leave_users_details_model::get_user_designation($emp_of_applying_leave)){
                    $data[$j] = $row['id'];
                    $j++;
                }
            }
        }

        else if($of_emp != NULL){
            $j = 0;
            foreach($result as $row){
                $emp_of_applying_leave = $this->get_emp_id_by_leave_id($row['id']);
                if($of_emp == $emp_of_applying_leave){
                    $data[$j] = $row['id'];
                    $j++;
                }
            }
        }
        //var_dump($data);
        return $this->get_multiple_leave_details($data);
    }

    //This will return leave forwarded by employee having employee ID = $emp_id
    function get_forwarded_leave_by_emp($emp_id , $type)
    {
        $forward = Leave_constants::$FORWARDED;
        $sql = "SELECT id   FROM " . Leave_constants::$TABLE_STATION_LEAVE_STATUS .
            " WHERE current = '$emp_id' and status = $forward GROUP BY id";

        $result = $this->db->query($sql)->result_array();
        $sql = "SELECT id FROM designations";
        $res = $this->db->query($sql)->result();
        $flg = false;
        if($type == ""){
            $of_emp = NULL;
            $designation = NULL;
        }
        foreach($res as $row){
            if($row->id == $type) {
                $designation = $type;
                $of_emp = NULL;
                $flg = true;
                break;
            }
        }
        if($flg == false) {
            $of_emp = $type;
            $designation = NULL;
        }
        $data = array();
        if($designation ==NULL && $of_emp == NULL){
            $j = 0;
            foreach($result as $row){
                $data[$j] = $row['id'];
                $j++;
            }
        }
        else if($designation != NULL){
            $j = 0;
            foreach($result as $row){
                $emp_of_applying_leave = $this->get_emp_id_by_leave_id($row['id']);
                if($designation == Leave_users_details_model::get_user_designation($emp_of_applying_leave)){
                    $data[$j] = $row['id'];
                    $j++;
                }
            }
        }
        else if($of_emp != NULL){
            $j = 0;
            foreach($result as $row){
                $emp_of_applying_leave = $this->get_emp_id_by_leave_id($row['id']);
                if($of_emp == $emp_of_applying_leave){
                    $data[$j] = $row['id'];
                    $j++;
                }
            }
        }
        //var_dump($data);
        return $this->get_multiple_leave_details($data);
    }

    /**
     * @param $array
     * $array will contains leave id
     */
    function get_multiple_leave_details($array){

        if (!empty($array)) {
            $i = 0;
            $data['leave_details'] = array();
            for ($j = 0 ; $j < sizeof($array) ; $j++) {
                $leave_details = $this->get_station_leave_by_id($array[$j]);
                $data['leave_details'][$i] = array();
                $data['leave_details'][$i]['applying_date'] = $leave_details['applying_date'];
                $data['leave_details'][$i]['leaving_date'] = $leave_details['leaving_date'];
                $data['leave_details'][$i]['leaving_time'] = $leave_details['leaving_time'];
                $data['leave_details'][$i]['arrival_time'] = $leave_details['arrival_time'];
                $data['leave_details'][$i]['arrival_date'] = $leave_details['arrival_date'];
                $data['leave_details'][$i]['purpose'] = $leave_details['purpose'];
                $data['leave_details'][$i]['addr'] = $leave_details['addr'];
                $data['leave_details'][$i]['emp_id'] = $leave_details['emp_id'];
                $data['leave_details'][$i]['id'] = $array[$j];
                $data['leave_details'][$i]['name'] = $this->get_user_name_by_id($leave_details['emp_id']);
                $data['leave_details'][$i]['period'] = $this->get_period_in_days_hours($leave_details['leaving_date'] , $leave_details['leaving_time'] ,
                    $leave_details['arrival_date'] , $leave_details['arrival_time']);
                $i++;
            }
            return $data;
        }
        return NULL;
    }

    function get_period_in_days_hours($leaving_date , $leaving_time , $arrival_date , $arrival_time){
        $time_str = $leaving_date." ".$leaving_time;
        $lv_date = strtotime($time_str);
        $time_str = $arrival_date." ".$arrival_time;
        $rt_date = strtotime($time_str);
        $period = ($rt_date - $lv_date);
        $years = abs(floor($period / 31536000));
        $days = abs(floor(($period-($years * 31536000))/86400));
        $hours = abs(floor(($period-($years * 31536000)-($days * 86400))/3600));
        //$mins = abs(floor(($period-($years * 31536000)-($days * 86400)-($hours * 3600))/60));#floor($difference / 60);
        if($days == 1 && $hours == 1)
            $str = $days." Day ".$hours." Hour";
        else if($days > 1 && $hours == 1)
            $str = $days." Days ".$hours." Hour";
        else if($days == 1 && $hours > 1)
            $str = $days." Day ".$hours." Hours";
        else
            $str = $days." Days ".$hours." Hours";
        return $str;
    }
    function get_emp_id_by_leave_id($leave_id){

        $sql = "SELECT emp_id FROM ".Leave_constants::$TABLE_STATION_LEAVE .
                " WHERE id = $leave_id";

        $result = $this->db->query($sql)->result();

        foreach($result as $row)
            return $row->emp_id;
    }

    function insert_cancel_reason($leave_id , $reason){
        $sql = "INSERT INTO ".Leave_constants::$TABLE_STATION_LEAVE_REJECTING_REASON .
                " VALUES ($leave_id , '$reason')";
        $this->db->query($sql);
    }

    function get_all_station_leave(){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_STATION_LEAVE;

        return $this->db->query($sql)->result_array();
    }

    /**
     * @param $designation
     * @param $dept
     * @param $emp_id
     * @param $dept_type
     * @return mixed
     */
    function query_demand($designation , $dept , $emp_id , $dept_type)
    {
        if($designation == NULL){
            $designation = '%';
        }
        if($dept == NULL){
            $dept = '%';
        }
        if($emp_id == NULL){
            $emp_id = '%';
        }
        if($dept_type == NULL){
            $dept_type = '%';
        }

        $sql = "SELECT ud.id AS id , salutation , first_name , middle_name , last_name FROM  emp_basic_details  INNER JOIN user_details as ud ".
               "  ON emp_basic_details.id = ud.id WHERE designation LIKE '$designation' AND ".
               "  dept_id LIKE '$dept' AND ud.id LIKE '$emp_id' AND auth_id LIKE '$dept_type' ORDER BY  first_name, middle_name, last_name;";

        $result = $this->db->query($sql)->result();
        return $result;
    }

    /**
     * @param $parent
     * @param $child
     * @param $leave_type
     */
    // this function will insert in leave_fragment table and add child of parent leave;
    function insert_leave_fragment_details($parent , $child , $leave_type){
        $sql = " INSERT INTO ".Leave_constants::$TABLE_LEAVE_FRAGMENT.
                " VALUES (".$parent.",".$child.",".$leave_type.",CURRENT_TIMESTAMP)";
        $this->db->query($sql);
    }

    /**
     * @param $leave_id
     * @param $fragment_value
     */
    // if any leave is fragmented then this will update in leave_station_details table ;
    function update_station_leave_fragmentation_details($leave_id , $fragment_value){

        $sql = "UPDATE ".Leave_constants::$TABLE_STATION_LEAVE.
                " SET fragment=".$fragment_value." WHERE id=".$leave_id ;

        $this->db->query($sql);
    }

    // This function will return all child leave of parent leave if not available then return null;
    function get_child_leave($leave_id){
        $sql = "SELECT child FROM ".Leave_constants::$TABLE_LEAVE_FRAGMENT.
                " WHERE parent=".$leave_id;

        return $this->db->query($sql)->result();
    }

    function update_station_leave_details($leave_id , $leaving_date , $leaving_time , $arrival_date , $arrival_time , $purpose ,$addr , $nature_of_leave , $ph_no){
        $leaving_date = strtotime($leaving_date);
        $leaving_date = date('Y-m-d', $leaving_date);


        $arrival_date = strtotime($arrival_date);
        $arrival_date = date('Y-m-d', $arrival_date);

        $sql = "UPDATE ".Leave_constants::$TABLE_STATION_LEAVE.
                " SET leaving_date = '$leaving_date' ".
                " ,leaving_time = '$leaving_time' ".
                " ,arrival_date = '$arrival_date' ".
                " ,arrival_time = '$arrival_time' " .
                " ,purpose = '$purpose' ".
                " ,addr = '$addr' ".
                " ,nature_of_leave = ".$nature_of_leave.
                " ,emergency_phone_no = '$ph_no' ".
                " WHERE id = ".$leave_id;
        $this->db->query($sql);
    }
    function delete_station_leave($leave_id){
        $sql = "DELETE FROM ".Leave_constants::$TABLE_STATION_LEAVE." WHERE id = ".$leave_id;
        $this->db->query($sql);
    }
}