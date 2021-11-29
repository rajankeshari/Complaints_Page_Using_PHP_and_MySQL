<?php
/**
 * Created by PhpStorm.
 * User: nishant raj
 * Date: 4/6/15
 * Time: 2:26 PM
 */

require_once 'result.php';
require_once 'leave_constants.php';
require_once 'leave_users_details_model.php';
require_once 'leave_station_model.php';
class Leave_general_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

/** START:- Insertion part */

    /**
     * @param $leave_type
     * @param $emp_id
     * @param $period
     * @param $reason
     * @return mixed
     */
    function insertAndGetLeaveID($leave_type , $emp_id , $period , $reason){
        $sql = "INSERT INTO ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
               " VALUES ('','$emp_id',$period ,'$reason' ,$leave_type , CURRENT_TIMESTAMP)";

        $this->db->query($sql);

        $sql = "SELECT id FROM ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS." WHERE id=LAST_INSERT_ID()";
        return $this->db->query($sql)->result_array()[0]['id'];
    }

    /**
     * @param $leave_id
     * @param $applied_for_date = should be converted to strtotime() before sending to this function
     * @param $half_full_value
     */
    function insertCasualLeaveDetails($leave_id , $applied_for_date , $half_full_value){

        $applied_for_date = date('Y-m-d' ,$applied_for_date);
        $sql = " INSERT INTO ".Leave_constants::$TABLE_CASUAL_LEAVE.
                " VALUES ($leave_id , '$applied_for_date' , $half_full_value)";

        $this->db->query($sql);
    }

    function insertLeaveStatus($leave_id , $current_emp , $next_emp , $status){
        $sql = "INSERT INTO ".Leave_constants::$TABLE_LEAVE_STATUS.
            " VALUES ( $leave_id , $current_emp , $next_emp , $status , CURRENT_TIMESTAMP)";

        $this->db->query($sql);
    }

    function insertRestrictedLeaveDetails($leave_id , $applied_for_date){

        $sql = " INSERT INTO ".Leave_constants::$TABLE_RESTRICTED_LEAVE.
            " VALUES (".$leave_id.", '$applied_for_date' )";

        $this->db->query($sql);

    }
    function insertRejectingReason($leave_id , $reason){
        $sql = "INSERT INTO ".Leave_constants::$TABLE_CANCEL_REASON.
            " VALUES (".$leave_id.", '$reason' )";

        $this->db->query($sql);
    }

    function insertCasualRestrictedLeaveForAllEmployee(){
        $sql = "SELECT emp_no FROM emp_basic_details";
        $result = $this->db->query($sql);
        $str = "";
        if($result->num_rows() > 0){
            $result = $result->result();
            foreach($result as $row){
                $str = $str."('$row->id' , 8 , 2 , CURRENT_TIMESTAMP),";
            }
            $len = strlen($str);
            $str[$len-1] = '';
            $this->insertLeaveBalanceWithList($str);
            return true;
        } else return false;
    }

    function insertLeaveBalanceWithList($str){
        $sql = "INSERT INTO ".Leave_constants::$TABLE_LEAVE_BALANCE.
            " VALUES ".$str;
        $this->db->query($sql);
    }


    function insertLeaveBalance($emp_id , $casual , $restricted){
        $sql = "INSERT INTO ".Leave_constants::$TABLE_LEAVE_BALANCE.
            " VALUES('$emp_id',".$casual.",".$restricted.",CURRENT_TIMESTAMP)";
        $this->db->query($sql);
    }

    function insertEarnedLeaveBalance($emp_id , $el ){
        $current_emp = $this->session->userdata('id');
        $sql = " INSERT INTO ".Leave_constants::$TABLE_EARNED_LEAVE_BALANCE.
            " VALUES ('$emp_id',".$el.", CURRENT_TIMESTAMP , '$current_emp')";
        $this->db->query($sql);
    }

    function insertEarnedLeaveDetails($leave_id , $start_date , $end_date , $ltc , $pre_suf){
        $start_date = date('Y-m-d' , strtotime($start_date));
        $end_date = date('Y-m-d' , strtotime($end_date));
        $sql = "INSERT INTO ".Leave_constants::$TABLE_EARNED_LEAVE." VALUES (".$leave_id.
            ",'$start_date','$end_date',".$ltc.",".$pre_suf.")";

        $this->db->query($sql);
    }

    function insertHPLDetails($leave_id , $start_date , $end_date ,$ltc, $pre_suf){
        $start_date = date('Y-m-d' , strtotime($start_date));
        $end_date = date('Y-m-d' , strtotime($end_date));
        $sql = "INSERT INTO ".Leave_constants::$TABLE_HPL_DETAILS." VALUES (".$leave_id.
            ",'$start_date','$end_date',".$ltc.",".$pre_suf.")";

        $this->db->query($sql);
    }

    function insertVacationDetails($leave_id , $start_date , $end_date){
        $start_date = date("Y-m-d" , strtotime($start_date));
        $end_date = date('Y-m-d' , strtotime($end_date));
        $sql = "INSERT INTO ".Leave_constants::$TABLE_VACATION_LEAVE." VALUES (".$leave_id.",'$start_date' , '$end_date')";
        $this->db->query($sql);
    }
    function insertPreviousInsertedRow($leave_id){
        $sql = "INSERT INTO ". Leave_constants::$TABLE_LEAVE_BASIC_DETAILS ."(emp_id ,period ,reason, leave_type , applied_on) SELECT emp_id , period ,".
         "reason ,leave_type , CURRENT_TIMESTAMP FROM ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS." WHERE id = ".$leave_id;

        $this->db->query($sql);

        $sql = "SELECT id FROM ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS." WHERE id=LAST_INSERT_ID()";
        return $this->db->query($sql)->result_array()[0]['id'];
    }

    function insertLeaveBalanceUpdateDetails($emp_id , $emp_by ,$previous_balance ,  $updated_balance , $leave_type){
        $sql = "INSERT INTO ".Leave_constants::$TABLE_LEAVE_BALANCE_UPDATE_DETAILS." VALUES (".
                " '$emp_id' , CURRENT_TIMESTAMP , ".$previous_balance.",".$updated_balance.",".$leave_type.", '$emp_by')";
        $this->db->query($sql);
    }

    function insertJoiningDetails($leave_id , $joining_date , $noon){
        $joining_date = date("Y-m-d" , strtotime($joining_date));
        $sql  = " INSERT INTO ".Leave_constants::$TABLE_LEAVE_JOINING_DETAILS." VALUES (".$leave_id.
                ",'$joining_date',".$noon.",CURRENT_TIMESTAMP)";
        $this->db->query($sql);
    }

    function insertFitnessCertificatePath($leave_id , $path){
        $sql = "INSERT INTO ".Leave_constants::$TABLE_HPL_FITNESS_CERTIFICATE.
                " VALUES (".$leave_id.",'$path')";
        $this->db->query($sql);
    }

    function insertVacationDates($str){
        $sql = " INSERT INTO ".Leave_constants::$TABLE_VACATION_DATES." VALUES ".$str;
        $this->db->query($sql);
    }

    function insertRestrictedDates($str){
        $sql = "INSERT INTO ".Leave_constants::$TABLE_RESTRICTED_HOLIDAYS." VALUES ".$str;
        $this->db->query($sql);
    }

    function insertPrefixSuffixDetails($leave_id , $pre_start_date , $pre_end_date , $suf_start_date ,$suf_end_date){
        if($pre_start_date != null) {
            $pre_start_date = date('Y-m-d', strtotime($pre_start_date));
        }
        if($pre_end_date != null) {
            $pre_end_date = date('Y-m-d', strtotime($pre_end_date));
        }
        if($suf_start_date != null) {
            $suf_start_date = date('Y-m-d', strtotime($suf_start_date));
        }
        if($suf_end_date != null) {
            $suf_end_date = date('Y-m-d', strtotime($suf_end_date));
        }
        $sql = "INSERT INTO ".Leave_constants::$TABLE_PREFIX_SUFFIX_DETAILS." VALUES (".$leave_id.
                " , '$pre_start_date' , '$pre_end_date' , '$suf_start_date' , '$suf_end_date')";
        $this->db->query($sql);
    }

    function insertGeneralStationRelation($general_leave_id , $station_leave_id){
        $sql = "INSERT INTO ".Leave_constants::$TABLE_GENERAL_STATION_RELATION." VALUES ("
                .$general_leave_id.",".$station_leave_id.")";
        $this->db->query($sql);
    }
/** END : Insertion part */
    /**
     * @param $emp_id
     * @return bool
     */
    function getLeaveBalanceUpdateHistory($emp_id){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_LEAVE_BALANCE_UPDATE_DETAILS." WHERE emp_id = '$emp_id'";
        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            return $result->result();
        } else return false;
    }

    function stationLeaveAssociatedWithThisLeave($leave_id){
        $sql = "SELECT station_leave_id FROM ".Leave_constants::$TABLE_GENERAL_STATION_RELATION.
                " WHERE general_leave_id = ".$leave_id;
        $result = $this->db->query($sql);
        if($result->num_rows()>0){
            return $result->result()[0]->station_leave_id;
        } else return false;
    }

    function getPrefixSuffixDetails($leave_id){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_PREFIX_SUFFIX_DETAILS." WHERE leave_id = ".$leave_id;
        $result = $this->db->query($sql);
        if($result->num_rows()>0)
            return $result->result();
        else return false;
    }

    function getFitnessCertificatePath($leave_id){
        $sql = "SELECT fitness_certificate_path AS path FROM ".Leave_constants::$TABLE_HPL_FITNESS_CERTIFICATE.
                " WHERE leave_id = ".$leave_id;
        $result = $this->db->query($sql);
        if($result->num_rows()>0)
            return $result->result();
        else return false;
    }

    function getJoiningDetails($leave_id){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_LEAVE_JOINING_DETAILS." WHERE leave_id = ".$leave_id;
        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            return $result->result();
        } else return false;

    }

    function get_restricted_holidays_dates(){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_RESTRICTED_HOLIDAYS;
        $result = $this->db->query($sql);
        if($result->num_rows() > 0)
            return $result->result();
        else return array();
    }

    function getVacationDates(){
        $current_year = date('Y');
        $start_date = date('Y-m-d',strtotime('01-01-'.$current_year));
        $end_date = date('Y-m-d',strtotime('31-12-'.$current_year));
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_VACATION_DATES." WHERE start_date >= '$start_date' and end_date <= '$end_date'";
        $result = $this->db->query($sql);
        if($result->num_rows()) {
            return $result->result();
        } else return false;
    }

    function getAllVacationDatesInTable(){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_VACATION_DATES;
        $result = $this->db->query($sql);
        if($result->num_rows() > 0)
            return $result->result();
        else return false;
    }

    // only for casual and restricted
    function getLeaveBalanceOfEmployee($emp_id){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_LEAVE_BALANCE." WHERE emp_id ='$emp_id'";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            return $query->result_array()[0];
        }
        else
            return false;
    }

    function getEarnedLeaveBalance($emp_id){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_EARNED_LEAVE_BALANCE.
            " WHERE  emp_id = '$emp_id'";
        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            return $result->result_array()[0];
        } else return false;
    }

    function getVacationLeaveBalance($emp_id){
        $sql = "SELECT * from ".Leave_constants::$TABLE_VACATION_LEAVE_BALANCE.
            " where emp_id = '$emp_id'";

        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            return $result->result_array()[0];
        } else return false;
    }

    function getHPLeaveBalance($emp_id){
        $sql = "SELECT * from ".Leave_constants::$TABLE_HP_LEAVE_BALANCE.
            " where emp_id = '$emp_id'";

        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            return $result->result_array()[0];
        } else return false;
    }

    function getLeaveStatus($leave_id){
        $sql = "SELECT * FROM " . Leave_constants::$TABLE_LEAVE_STATUS .
            " WHERE id = '$leave_id' ORDER BY updated_on DESC";

        $result = $this->db->query($sql);
        if($result->num_rows() >0) {
            $result = $result->result_array();
            $data['status'] = $result[0]['status'];
            $data['fwd_by'] = $result[0]['previous_emp_id'];
            $data['fwd_to'] = $result[0]['current_emp_id'];
            $data['fwd_at'] = $result[0]['updated_on'];
        }
        else $data = null;
        return $data;
    }

    function getPendingAndForwardedLeaveBalance($emp_id , $leave_type , $except_this_leave){
        if($except_this_leave =="%") {
            $sql = "SELECT * FROM " . Leave_constants::$TABLE_LEAVE_BASIC_DETAILS .
                " WHERE emp_id='$emp_id' AND leave_type = " . $leave_type;
        } else {
            $sql = "SELECT * FROM " . Leave_constants::$TABLE_LEAVE_BASIC_DETAILS .
                " WHERE emp_id='$emp_id' AND leave_type = " . $leave_type." AND id != ".$except_this_leave;
        }
        $result = $this->db->query($sql)->result();
        $bal = 0.0;
        foreach($result as $row){
            $status = $this->getLeaveStatus($row->id)['status'];
            if($status == Leave_constants::$PENDING || $status == Leave_constants::$FORWARDED)
                $bal += $row->period;
        }
        return $bal;
    }

    function getCasualLeaveStartEndDateByLeaveID($leave_id){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_CASUAL_LEAVE.
                " WHERE id=".$leave_id." ORDER BY applied_for_date ASC";

        $result = $this->db->query($sql);
        $count = $result->num_rows();
        $result = $result->result_array();
        if($count > 0) {
            $data['start_date'] = $result[0]['applied_for_date'];
            $data['end_date'] = $result[$count - 1]['applied_for_date'];
        }
        else
            $data = null;
        return $data;
    }

    function getRestrictedLeaveAppliedDateByLeaveID($leave_id){

        $sql = "SELECT * FROM ".Leave_constants::$TABLE_RESTRICTED_LEAVE.
            "   WHERE id = ".$leave_id;
        $result = $this->db->query($sql);
        if($result->num_rows() > 0) {
            return $result->result_array()[0];
        }
        else{
            return array();
        }
    }

    function getAllPendingCasualLeavePeriod($emp_id){

        $sql = "SELECT * FROM ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
            " WHERE emp_id = '$emp_id' AND leave_type=".Leave_constants::$TYPE_CASUAL_LEAVE;

        $result = $this->db->query($sql)->result();

        $current_year = date('Y');
        $i = 0;
        $data = array();
        foreach($result as $row){
            $period = $this->getCasualLeaveStartEndDateByLeaveID($row->id);
            $status = $this->getLeaveStatus($row->id)['status'];
            if(date('Y' , strtotime($period['start_date'])) == $current_year &&
            date('Y' , strtotime($period['end_date'])) == $current_year &&
                ($status != Leave_constants::$CANCELED && $status != Leave_constants::$REJECTED) ){
                $data[$i]['start_date'] = $period['start_date'];
                $data[$i]['end_date'] = $period['end_date'];
                $i++;
            }
        }
        return $data;
    }

    function getAllPendingVacationLeavePeriod($emp_id){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_VACATION_LEAVE.
        " WHERE id IN (SELECT t1.id as id FROM " .Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
        " AS t1 INNER JOIN (SELECT * FROM " .Leave_constants::$TABLE_LEAVE_STATUS.
        " GROUP BY id ORDER BY id DESC) as t3 ON t1.id = t3.id WHERE t1.emp_id = '$emp_id'".
        " AND leave_type=".Leave_constants::$TYPE_EARNED_LEAVE." AND t3.status != ".Leave_constants::$CANCELED.
        " AND t3.status != ". Leave_constants::$REJECTED.")";

        $result = $this->db->query($sql);

        if($result->num_rows() > 0){
            return $result->result();
        } else return false;
    }

    function getAllPendingRestrictedLeavePeriod($emp_id){

        $sql = "SELECT * FROM ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
            " WHERE emp_id = '$emp_id' AND leave_type = ".Leave_constants::$TYPE_RESTRICTED_LEAVE;

        $result = $this->db->query($sql)->result();
        $i = 0;
        $data = array();
        $current_year = date("Y");
        foreach($result as $row){
            $applied_date = $this->getRestrictedLeaveAppliedDateByLeaveID($row->id);
            $status = $this->getLeaveStatus($row->id)['status'];
            if(sizeof($applied_date) > 0 && date("Y" , strtotime($applied_date['applied_for_date'])) == $current_year &&
                ($status != Leave_constants::$CANCELED && $status != Leave_constants::$REJECTED)){
                $data[$i]['applied_for_date'] = $applied_date['applied_for_date'];
                $data[$i]['id'] = $row->id;
                $i++;
            }
        }
        return $data;
    }

    function getAllEarnedLeavePeriod($emp_id){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_EARNED_LEAVE.
            " WHERE id IN (SELECT t1.id as id FROM " .Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
            " AS t1 INNER JOIN (SELECT * FROM " .Leave_constants::$TABLE_LEAVE_STATUS.
            " GROUP BY id ORDER BY id DESC) as t3 ON t1.id = t3.id WHERE t1.emp_id = '$emp_id'".
            " AND leave_type=".Leave_constants::$TYPE_EARNED_LEAVE." AND t3.status != ".Leave_constants::$CANCELED.
            " AND t3.status != ". Leave_constants::$REJECTED.")";
        $result = $this->db->query($sql);

        if($result->num_rows() > 0){
            return $result->result();
        } else return false;

    }

    function getAllHPLPeriod($emp_id){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_HPL_DETAILS.
            " WHERE id IN (SELECT t1.id as id FROM " .Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
            " AS t1 INNER JOIN (SELECT * FROM " .Leave_constants::$TABLE_LEAVE_STATUS.
            " GROUP BY id ORDER BY id DESC) as t3 ON t1.id = t3.id WHERE t1.emp_id = '$emp_id'".
            " AND leave_type=".Leave_constants::$TYPE_HPL." AND t3.status != ".Leave_constants::$CANCELED.
            " AND t3.status != ". Leave_constants::$REJECTED.")";
        $result = $this->db->query($sql);

        if($result->num_rows() > 0){
            return $result->result();
        } else return false;
    }

    function getVacationPeriod($emp_id){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_VACATION_LEAVE.
            " WHERE id IN (SELECT t1.id as id FROM " .Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
            " AS t1 INNER JOIN (SELECT * FROM " .Leave_constants::$TABLE_LEAVE_STATUS.
            " GROUP BY id ORDER BY id DESC) as t3 ON t1.id = t3.id WHERE t1.emp_id = '$emp_id'".
            " AND leave_type=".Leave_constants::$TYPE_VACATION_LEAVE." AND t3.status != ".Leave_constants::$CANCELED.
            " AND t3.status != ". Leave_constants::$REJECTED.")";
        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            return $result->result();
        } else return false;
    }
    /**
     * @param $leave_type
     * @param $emp_id
     * @return bool
     */
    function getLeaveHistoryOfEmployee($leave_type , $emp_id){

        $sql = " SELECT * FROM ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
            " WHERE emp_id = '$emp_id' AND leave_type like ".$leave_type;

        $result = $this->db->query($sql);
        $data = array();
        if($result->num_rows() > 0){
            return  $result->result();
        }
        else return $data;
    }


    /**
     * @param $emp_id
     * @param $status = status of leave approved/rejected
     * @param $type
     * @return mixed
     */
    function getApprovedRejectedLeaveByEmployee($emp_id, $status , $type)
    {
        $sql = "SELECT id   FROM " . Leave_constants::$TABLE_LEAVE_STATUS .
            " WHERE current_emp_id = '$emp_id' and status = $status GROUP BY id";

        $result = $this->db->query($sql)->result_array();

        $sql = "SELECT id FROM designations";
        $res = $this->db->query($sql)->result();
        $flg = false;
        if($type == ""){
            $of_emp = NULL;
            $designation = NULL;
        } else {
            foreach ($res as $row) {
                if ($row->id == $type) {
                    $designation = $type;
                    $of_emp = NULL;
                    $flg = true;
                    break;
                }
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
                $emp_of_applying_leave = $this->getLeaveDetailsByID($row['id'])['emp_id'];
                if($designation == Leave_users_details_model::get_user_designation($emp_of_applying_leave)){
                    $data[$j] = $row['id'];
                    $j++;
                }
            }
        }

        else if($of_emp != NULL){
            $j = 0;
            foreach($result as $row){
                $emp_of_applying_leave = $this->getLeaveDetailsByID($row['id'])['emp_id'];
                if($of_emp == $emp_of_applying_leave){
                    $data[$j] = $row['id'];
                    $j++;
                }
            }
        }
        //print_r($this->getLeaveDetailsFromArrayOfLeaveId($data));
        //echo "<br><br>";
        return $this->getLeaveDetailsFromArrayOfLeaveId($data);
    }

    function getForwardedLeaveByEmployee($emp_id , $type)
    {
        $forward = Leave_constants::$FORWARDED;
        $sql = "SELECT id   FROM " . Leave_constants::$TABLE_LEAVE_STATUS .
            " WHERE previous_emp_id = '$emp_id' and status = $forward GROUP BY id";

        $result = $this->db->query($sql)->result_array();
        $sql = "SELECT id FROM designations";
        $res = $this->db->query($sql)->result();
        $flg = false;
        if($type == ""){
            $of_emp = NULL;
            $designation = NULL;
        } else {
            foreach ($res as $row) {
                if ($row->id == $type) {
                    $designation = $type;
                    $of_emp = NULL;
                    $flg = true;
                    break;
                }
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
                $emp_of_applying_leave = $this->$this->getLeaveDetailsByID($row['id'])['emp_id'];
                if($designation == Leave_users_details_model::get_user_designation($emp_of_applying_leave)){
                    $data[$j] = $row['id'];
                    $j++;
                }
            }
        }
        else if($of_emp != NULL){
            $j = 0;
            foreach($result as $row){
                $emp_of_applying_leave = $this->$this->getLeaveDetailsByID($row['id'])['emp_id'];
                if($of_emp == $emp_of_applying_leave){
                    $data[$j] = $row['id'];
                    $j++;
                }
            }
        }
        return $this->getLeaveDetailsFromArrayOfLeaveId($data);
    }


    function getDetailLeaveHistoryOfEmployee($leave_type , $emp_id){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
                " WHERE emp_id = '$emp_id' AND leave_type = ".$leave_type;

        $result = $this->db->query($sql);
        $data = array();
        if($result->num_rows() > 0){
            $result = $result->result();
            foreach($result as $row){
                $data['applied_on'] = $row->applied_on;
                $data['id'] = $row->id;
                $data['emp_id'] = $row->emp_id;
                $data['period'] = $row->period;
                $data['reason'] = $row->reason;
                $data['leave_type'] = $row->leave_type;
                $status = $this->getLeaveStatus($row->id);
                $data['status'] = $status['status'];
                $data['currently_at'] = $status['fwd_to'];
                $data['approved_on'] = $status['fwd_at'];
            }
            return $data;
        } else return false;
    }

    function getLeaveDetailsFromArrayOfLeaveId($array){
        $data = array();
        $i = 0;
        if($array){
            foreach($array as $row){
                $leave_details = $this->getLeaveDetailsByID($row);
                $leave_status = $this->getLeaveStatus($row);
                //var_dump($leave_details);
                if(sizeof($leave_details)>0) {
                    $data[$i]['id'] = $leave_details['id'];
                    $data[$i]['emp_id'] = $leave_details['emp_id'];
                    $data[$i]['applied_on'] = $leave_details['applied_on'];
                    $data[$i]['period'] = $leave_details['period'];
                    $data[$i]['reason'] = $leave_details['reason'];
                    $data[$i]['leave_type'] = $leave_details['leave_type'];
                    $data[$i]['status'] = $leave_status['status'];
                    $data[$i]['currently_at'] = $leave_status['fwd_to'];
                    $data[$i]['approved_on'] = $leave_status['fwd_at'];
                    $i++;
                }
            }
            return $data;
        }
        return false;
    }

    function getCasualLeaveDetails($leave_id){

        $sql = "SELECT * FROM ".Leave_constants::$TABLE_CASUAL_LEAVE.
            " WHERE id = ".$leave_id." ORDER BY applied_for_date ASC";

        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            return $result->result();
        }
        else return array();

    }

    function getRestrictedLeaveDetails($leave_id){

        $sql = "SELECT * FROM ".Leave_constants::$TABLE_RESTRICTED_LEAVE.
            " WHERE id = ".$leave_id;

        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            return $result->result_array();
        }
        else return array();

    }

    function getEarnedLeaveDetails($leave_id){

        $sql = "SELECT start_date , end_date , ltc , prefix_suffix FROM ".Leave_constants::$TABLE_EARNED_LEAVE.
                " WHERE id = ".$leave_id;
        $result = $this->db->query($sql);

        if($result->num_rows() > 0){
            return $result->result();
        } else return false;
    }

    function getHPLDetails($leave_id){
        $sql = "SELECT start_date , end_date , ltc , prefix_suffix FROM ".Leave_constants::$TABLE_HPL_DETAILS.
            " WHERE id = ".$leave_id;
        $result = $this->db->query($sql);

        if($result->num_rows() > 0){
            return $result->result();
        } else return false;
    }

    function getVacationDetails($leave_id){
        $sql = "SELECT start_date , end_date FROM ".Leave_constants::$TABLE_VACATION_LEAVE.
                " WHERE id = ".$leave_id;

        $result = $this->db->query($sql);
        if($result->num_rows() > 0)
            return $result->result();
        else return false;
    }

    function getLeaveDetailsByID($leave_id){
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
                " WHERE id = ".$leave_id;

        $result = $this->db->query($sql);

        if($result->num_rows()> 0){
            return $result->result_array()[0];
        }
        else return array();
    }


    function getLeaveStatusBeforeWaitingCancellation($leave_id){

        $sql = "SELECT * FROM " . Leave_constants::$TABLE_LEAVE_STATUS .
            " WHERE id = '$leave_id' ORDER BY updated_on DESC";

        $result = $this->db->query($sql);
        if($result->num_rows() > 1){
            return $result->result_array()[1]['status'];
        } else return null;
    }



    function getCancellableLeave($emp_id  , $leave_type){
        $sql = "SELECT t1.* FROM ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
                " AS t1 INNER JOIN (SELECT * FROM ".Leave_constants::$TABLE_LEAVE_STATUS.
                " GROUP BY id ORDER BY id DESC) as t3 ON t1.id = t3.id WHERE emp_id = '$emp_id'".
                " AND leave_type=".$leave_type." AND ( t3.status = ".Leave_constants::$PENDING." OR t3.status = ".
                Leave_constants::$APPROVED." OR t3.status = ".Leave_constants::$FORWARDED.
                " OR t3.status = ".Leave_constants::$NOT_SENT.")";

        $result =$this->db->query($sql);
        if($result->num_rows() > 0){
            return $result->result();
        } else return array();
    }

    function getJoinableLeave($emp_id , $leave_type){

        $sql = "SELECT t1.* ,t3.current_emp_id as approved_by FROM ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
            " AS t1 INNER JOIN (SELECT * FROM ".Leave_constants::$TABLE_LEAVE_STATUS.
            " GROUP BY id ORDER BY id DESC) as t3 ON t1.id = t3.id WHERE emp_id = '$emp_id'".
            " AND leave_type=".$leave_type." AND  t3.status = ".Leave_constants::$APPROVED;

        $result = $this->db->query($sql);
        if($result->num_rows() >0 ){
            return $result->result();
        } else return false;
    }

    function getRejectingReason($leave_id){
        $sql = "SELECT reason FROM ".Leave_constants::$TABLE_REJECTING_REASON." WHERE id = ".$leave_id;
        $result = $this->db->query($sql);

        if($result->num_rows() > 0){
            return $result->result()[0]->reason;
        } else return null;
    }

    function getPendingLeaveAtEmployee($emp_id , $leave_type){
        $sql = "SELECT t1.* , t3.status FROM ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS.
            " AS t1 INNER JOIN (SELECT * FROM ".Leave_constants::$TABLE_LEAVE_STATUS.
            " GROUP BY id ORDER BY id DESC) as t3 ON t1.id = t3.id WHERE t3.current_emp_id = '$emp_id'".
            " AND leave_type=".$leave_type." AND ( t3.status = ".Leave_constants::$PENDING." OR t3.status = ".
            Leave_constants::$WAITING_CANCELLATION." OR t3.status = ".Leave_constants::$FORWARDED." OR ".
            " t3.status =".Leave_constants::$WAITING_JOINING_APPROVAL." OR t3.status = ".Leave_constants::$JOINING_REQUEST_FORWARDED." )";

        $result = $this->db->query($sql);

        if($result->num_rows()>0)
            return $result->result();
        else return false;
    }


/** START :- Update leave details */
    /**
     * @param $emp_id
     * @param $leave_type
     * @param $period
     */
    function updateLeaveBalance($emp_id , $leave_type , $period){
        $col_name = null;
        if($leave_type == Leave_constants::$TYPE_CASUAL_LEAVE){
            $col_name = "`casual_leave`";
            $sql = "UPDATE ".Leave_constants::$TABLE_LEAVE_BALANCE." SET "
                .$col_name." = ".$period." WHERE emp_id = '$emp_id'";
        } else if($leave_type == Leave_constants::$TYPE_RESTRICTED_LEAVE){
            $col_name = "`restricted_leave`";
            $sql = "UPDATE ".Leave_constants::$TABLE_LEAVE_BALANCE." SET "
                .$col_name." = ".$period." WHERE emp_id = '$emp_id'";
        } else if($leave_type == Leave_constants::$TYPE_EARNED_LEAVE){
            $sql = "UPDATE ".Leave_constants::$TABLE_EARNED_LEAVE_BALANCE.
                " SET `balance` = ".$period." WHERE emp_id = '$emp_id'";
        } else if($leave_type == Leave_constants::$TYPE_HPL){
            $sql = "UPDATE ".Leave_constants::$TABLE_HP_LEAVE_BALANCE.
                    " SET `balance` = ".$period." WHERE emp_id = '$emp_id'";
        } else if($leave_type == Leave_constants::$TYPE_VACATION_LEAVE){
            $sql = " UPDATE ".Leave_constants::$TABLE_VACATION_LEAVE_BALANCE.
                    " SET `balance` = ".$period." WHERE emp_id = '$emp_id'";
        }
        $this->db->query($sql);
    }

    /**
     * @param $leave_id
     * @param $leave_type
     * @param $period
     * @param $reason
     */
    function updateLeaveDetails($leave_id , $leave_type , $period , $reason){

        /** First delete from child table then update into main leave table */
        if($leave_type == Leave_constants::$TYPE_CASUAL_LEAVE) {
            $sql = "DELETE FROM " . Leave_constants::$TABLE_CASUAL_LEAVE . " WHERE id = ".$leave_id;
            $this->db->query($sql);
        } else if($leave_type == Leave_constants::$TYPE_RESTRICTED_LEAVE){
            $sql = "DELETE FROM " . Leave_constants::$TABLE_RESTRICTED_LEAVE . " WHERE id = ".$leave_id;
            $this->db->query($sql);
        } else if($leave_type == Leave_constants::$TYPE_EARNED_LEAVE){
            $sql = "DELETE FROM ".Leave_constants::$TABLE_EARNED_LEAVE." WHERE id = ".$leave_id;
            $this->db->query($sql);
        }
        $sql = "UPDATE ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS." SET period=".
            $period.", reason = '$reason' WHERE id = ".$leave_id;
        $this->db->query($sql);
    }

    function updatePrefixSuffixDetails($leave_id , $pre_start_date , $pre_end_date , $suf_start_date , $suf_end_date){
        if($pre_start_date != null) {
            $pre_start_date = date('Y-m-d', strtotime($pre_start_date));
        }
        if($pre_end_date != null) {
            $pre_end_date = date('Y-m-d', strtotime($pre_end_date));
        }
        if($suf_start_date != null) {
            $suf_start_date = date('Y-m-d', strtotime($suf_start_date));
        }
        if($suf_end_date != null) {
            $suf_end_date = date('Y-m-d', strtotime($suf_end_date));
        }
        $sql = "UPDATE ".Leave_constants::$TABLE_PREFIX_SUFFIX_DETAILS." SET `prefix_start_date` = '$pre_start_date'".
                " , `prefix_end_date` = '$pre_end_date' , `suffix_start_date` = '$suf_start_date' ,".
                " `suffix_end_date` = '$suf_end_date' WHERE leave_id = ".$leave_id;
        $this->db->query($sql);
    }

    function updateEarnedLeaveDetails($leave_id , $start_date , $end_date , $ltc , $prefix_suffix){
        $start_date = date("Y-m-d" , strtotime($start_date));
        $end_date = date('Y-m-d' , strtotime($end_date));
        $sql = "UPDATE ".Leave_constants::$TABLE_EARNED_LEAVE." SET `start_date` = '$start_date',".
                " `end_date` = '$end_date' , `ltc` = ".$ltc." ,`prefix_suffix` = ".$prefix_suffix." WHERE id = ".$leave_id;
        $this->db->query($sql);
    }

    function updateHPLDetails($leave_id , $start_date , $end_date , $ltc , $pre_suf){
        $start_date = date("Y-m-d" , strtotime($start_date));
        $end_date = date('Y-m-d' , strtotime($end_date));
        $sql = "UPDATE ".Leave_constants::$TABLE_HPL_DETAILS." SET `start_date` = '$start_date',".
            " `end_date` = '$end_date' , `ltc` = ".$ltc." ,`prefix_suffix` = ".$pre_suf." WHERE id = ".$leave_id;
        $this->db->query($sql);
    }

    function updateVacationDetails($leave_id , $start_date , $end_date){
        $start_date = date("Y-m-d" , strtotime($start_date));
        $end_date = date('Y-m-d' , strtotime($end_date));
        $sql = " UPDATE ".Leave_constants::$TABLE_VACATION_LEAVE." SET `start_date` = '$start_date' , `end_date` = '$end_date'".
                " WHERE id = ".$leave_id;
        $this->db->query($sql);
    }

    function simpleUpdateLeaveDetails($leave_id , $reason , $period){
        $sql = "UPDATE ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS." SET `period`=".$period.
            " `reason` = '$reason' WHERE id = ".$leave_id;
        $this->db->query($sql);
    }

    function updateLeavePeriod($leave_id , $period){

        $sql = "UPDATE ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS." SET `period` = ".$period." WHERE id = ".$leave_id;
        $this->db->query($sql);

    }

    function updateCasualLeaveId($old_leave_id , $new_leave_id , $date){
        $date = date('Y-m-d' ,strtotime($date));
        $sql = "UPDATE ".Leave_constants::$TABLE_CASUAL_LEAVE." SET `id` = ".$new_leave_id.
                " WHERE id=".$old_leave_id." AND applied_for_date = '$date'";
        $this->db->query($sql);

    }

    function updateOrInsertLeaveBalance($emp_id , $casual_leave , $restricted_leave){
        $sql = "SELECT casual_leave FROM ".Leave_constants::$TABLE_LEAVE_BALANCE." WHERE emp_id = '$emp_id'";
        $result = $this->db->query($sql);
        if($result->num_rows() > 0) {
            $sql = "UPDATE " . Leave_constants::$TABLE_LEAVE_BALANCE . " SET `casual_leave` = " . $casual_leave .
                " , `restricted_leave`=" . $restricted_leave . " WHERE emp_id = '$emp_id'";
            $this->db->query($sql);
        } else {
            $sql = "INSERT INTO ".Leave_constants::$TABLE_LEAVE_BALANCE.
                    " VALUES('$emp_id',".$casual_leave.",".$restricted_leave.",CURRENT_TIMESTAMP)";
            $this->db->query($sql);
        }
    }

    function updateOrInsertEarnedLeaveBalance($emp_id , $bal , $emp_by){
        $sql = "SELECT emp_id FROM ".Leave_constants::$TABLE_EARNED_LEAVE_BALANCE." WHERE emp_id = '$emp_id'";
        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            $sql = " UPDATE ".Leave_constants::$TABLE_EARNED_LEAVE_BALANCE." SET `balance` = ".$bal.
                    " `updated_on` = CURRENT_TIMESTAMP `update_by` = '$emp_by' WHERE emp_id = '$emp_id'";
            $this->db->query($sql);
        } else{
            $sql = "INSERT INTO ".Leave_constants::$TABLE_EARNED_LEAVE_BALANCE." VALUES ( '$emp_id' ,".$bal.
                    " , CURRENT_TIMESTAMP , '$emp_by')";
            $this->db->query($sql);
        }
    }

    function updateOrInsertVacationLeaveBalance($emp_id , $bal , $emp_by){
        $sql = "SELECT emp_id FROM ".Leave_constants::$TABLE_VACATION_LEAVE_BALANCE." WHERE emp_id = '$emp_id'";
        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            $sql = "UPDATE ".Leave_constants::$TABLE_VACATION_LEAVE_BALANCE." SET `balance` = ".$bal.
                    " ,`updated_on` = CURRENT_TIMESTAMP , `updated_by` = '$emp_by' WHERE emp_id = '$emp_id'";
            $this->db->query($sql);
        } else {
            $sql = " INSERT INTO ".Leave_constants::$TABLE_VACATION_LEAVE_BALANCE." VALUES ('$emp_id', ".$bal.
                    " , CURRENT_TIMESTAMP , '$emp_by' )";
            $this->db->query($sql);
        }
    }

    function updateOrInsertHPLeaveBalance($emp_id , $bal , $emp_by){
        $sql = "SELECT emp_id FROM ".Leave_constants::$TABLE_HP_LEAVE_BALANCE." WHERE emp_id = '$emp_id'";
        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            $sql = "UPDATE ".Leave_constants::$TABLE_HP_LEAVE_BALANCE." SET `balance` = ".$bal.
                " ,`updated_on` = CURRENT_TIMESTAMP , `updated_by` = '$emp_by' WHERE emp_id = '$emp_id'";
            $this->db->query($sql);
        } else {
            $sql = " INSERT INTO ".Leave_constants::$TABLE_HP_LEAVE_BALANCE." VALUES ('$emp_id', ".$bal.
                " , CURRENT_TIMESTAMP , '$emp_by' )";
            $this->db->query($sql);
        }
    }

    function updateEarnedLeaveBalance($emp_id , $el){
        $sql = "UPDATE ".Leave_constants::$TABLE_EARNED_LEAVE_BALANCE.
            " SET `balance` = ".$el." , `updated_on`=CURRENT_TIMESTAMP WHERE emp_id = '$emp_id'";
        $this->db->query($sql);
    }

    function updateVacationDates($id , $start_date , $end_date , $reason){
        $emp_id = $this->session->userdata('id');
        $sql = "UPDATE ".Leave_constants::$TABLE_VACATION_DATES." SET `start_date` = '$start_date' , `end_date` = '$end_date' ,".
                " `updated_on` = CURRENT_TIMESTAMP , `updated_by` ='$emp_id' , `type_of_vacation` = '$reason' WHERE id =".$id;
        $this->db->query($sql);
    }

    function updateRestrictedLeaveDates($id , $date , $reason){
        $emp_id = $this->session->userdata('id');
        $sql = " UPDATE ".Leave_constants::$TABLE_RESTRICTED_HOLIDAYS." SET `date` = '$date' ,"
        ."`reason_of_leave` = '$reason' , `updated_on` = CURRENT_TIMESTAMP , `updated_by` = '$emp_id' WHERE id = ".$id;
        $this->db->query($sql);
    }
/** END: Update part */

/** START :- Delete part start */
    /**
     * @param $id
     */
    function deleteVacationDates($id){
        $sql = "DELETE FROM ".Leave_constants::$TABLE_VACATION_DATES." WHERE id = ".$id;
        $this->db->query($sql);
    }

    function deleteRestrictedDates($id){
        $sql = "DELETE FROM ".Leave_constants::$TABLE_RESTRICTED_HOLIDAYS." WHERE id = ".$id;
        $this->db->query($sql);
    }

    function deletePreviousLeaveBalance(){
        $sql = "DELETE FROM ".Leave_constants::$TABLE_LEAVE_BALANCE." WHERE 1";
        $this->db->query($sql);
    }

    function deleteLeave($leave_id , $leave_type){
        $sql = "DELETE FROM ".Leave_constants::$TABLE_LEAVE_STATUS.
            " WHERE id = ".$leave_id;
        $this->db->query($sql);
        $sql = "DELETE FROM ".Leave_constants::$TABLE_CANCEL_REASON.
            " WHERE id = ".$leave_id;
        $this->db->query($sql);

        if($leave_type == Leave_constants::$TYPE_CASUAL_LEAVE){
            $sql = "DELETE FROM ".Leave_constants::$TABLE_CASUAL_LEAVE." WHERE id = ".$leave_id;
            $this->db->query($sql);
        } else if($leave_type == Leave_constants::$TYPE_RESTRICTED_LEAVE){
            $sql = "DELETE FROM ".Leave_constants::$TABLE_RESTRICTED_LEAVE." WHERE id = ".$leave_id;
            $this->db->query($sql);
        } else if($leave_type == Leave_constants::$TYPE_EARNED_LEAVE){
            $sql = "DELETE FROM ".Leave_constants::$TABLE_EARNED_LEAVE." WHERE id = ".$leave_id;
            $this->db->query($sql);
        } else if($leave_type == Leave_constants::$TYPE_HPL){
            $sql = "DELETE FROM ".Leave_constants::$TYPE_HPL." WHERE id = ".$leave_id;
            $this->db->query($sql);
        } else if($leave_type == Leave_constants::$TYPE_VACATION_LEAVE){
            $sql = "DELETE FROM ".Leave_constants::$TYPE_VACATION_LEAVE." WHERE id = ".$leave_id;
            $this->db->query($sql);
        }
        $sql = "SELECT * FROM ".Leave_constants::$TABLE_GENERAL_STATION_RELATION." WHERE general_leave_id = ".$leave_id;
        $result = $this->db->query($sql);
        $sql = " DELETE FROM ".Leave_constants::$TABLE_GENERAL_STATION_RELATION." WHERE general_leave_id = ".$leave_id;
        $this->db->query($sql);
        if($result->num_rows() > 0){
            $result = $result->result_array()[0];
            $station_id = $result['station_leave_id'];
            leave_station_model::delete_station_leave($station_id);
        }
        $sql = "DELETE FROM ".Leave_constants::$TABLE_GENERAL_STATION_RELATION." WHERE general_leave_id = ".$leave_id;
        $this->db->query($sql);
        $sql = "DELETE FROM ".Leave_constants::$TABLE_PREFIX_SUFFIX_DETAILS." WHERE leave_id = ".$leave_id;
        $this->db->query($sql);
        $sql =  "DELETE FROM ".Leave_constants::$TABLE_LEAVE_BASIC_DETAILS." WHERE id = ".$leave_id;
        $this->db->query($sql);

    }

    function queryDemand($designation=null , $dept=null , $emp_id=null , $dept_type=null){

        $sql = "select ud.id ,lbu.leave_type, lbu.updated_on , lbu.updated_by , lbu.previous_balance ,
                lbu.updated_balance ,salutation , first_name , middle_name , last_name from ".
                Leave_constants::$TABLE_LEAVE_BALANCE_UPDATE_DETAILS." as lbu inner join user_details as
                ud on lbu.emp_id = ud.id where lbu.emp_id in (select ebd.emp_no from emp_basic_details as ebd
                inner join user_details as ud inner join departments on  ebd.emp_no = ud.id and
                departments.id = ud.dept_id where ebd.designation like '$designation' and ebd.emp_no
                like '$emp_id' and  ud.dept_id like '$dept' and departments.type like '$dept_type')";
        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            return $result->result();
        } else return false;
    }
/** END:- Delete part */


}