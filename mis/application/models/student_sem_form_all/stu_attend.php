<?php
 class stu_attend extends CI_Model
 {
 	var $abs_tab='absent_table';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	//Getting session Id
	function get_sessionid($sessionyear,$sess,$branch_id,$course_id,$sem)
	{
         $query = $this->db->query("SELECT session_id
        	FROM session_track WHERE session_year='$sessionyear' and session='$sess' and branch_id='$branch_id' and course_id='$course_id' and semester=$sem ")->result();
        // echo "$sess";
        return $query;
	}
	//Getting map id
	function get_mapid($session,$sessionyear,$aggr_id,$course_id,$branch_id,$sem)
	{
         $query = $this->db->query("SELECT map_id
         	FROM subject_mapping 
         	WHERE session='$session' and session_year='$sessionyear' and aggr_id='$aggr_id' and branch_id='$branch_id'and course_id='$course_id' and semester='$sem'");
         return $query->result();
	}
	//Getting absent date
	function get_absent_date($id,$sessionid,$mapid,$subid,$timesp)
	{
		   $q= $this->db->query("SELECT `date`,`timestamp` from `class_engaged` where session_id=$sessionid and map_id=$mapid
		                        and sub_id='$subid' and `timestamp` <= '$timesp' ")->result();
		    $data=array();
		    //$i=0;
		    foreach ($q as $ss) {
                 $data['admn_no']=$id;
                 $data['map_id']=$mapid;
                 $data['sub_id']=$subid;
                 $data['session_id']=$sessionid;
                 $data['date']=$ss->date;
                 $data['timestamp']=$ss->timestamp;
                 $data['remark']='none';
                 $data['status']='0';
                  $this->db->insert($this->abs_tab,$data);
		    }
		   // print_r($data);
		   
		    return $this->db->_error_message(); 
	}
	//Deleting entry from absent table if present in case of form rejection
	function delete_absent($id,$mapid,$subid,$sessionid)
	{
		$this->db->query("DELETE FROM absent_table WHERE admn_no='$id' and map_id=$mapid 
			              and sub_id='$subid' and session_id=$sessionid");
	}
 }
?>