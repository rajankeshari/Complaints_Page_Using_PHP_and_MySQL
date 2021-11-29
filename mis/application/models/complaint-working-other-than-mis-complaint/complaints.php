<?php

class Complaints extends CI_Model
{
	var $table = 'complaint';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
        //modified by @anuj as per change request by mishra sir
	/*function insert($data)
	{
		$this->db->query ("INSERT INTO complaint (user_id, type, location, location_details, problem_details, pref_time, complaint_id) VALUES ('".$data['user_id']."','".$data['type']."','".$data['location']."','".$data['location_details']."','".$data['problem_details']."','".$data['pref_time']."','".$data['complaint_id']."');");
	}*/
        function insert($data)
        {
            if($this->db->insert('complaint',$data))
			return $this->db->insert_id();
		else
			return FALSE;
        }
		function insert_mis($data)
        {
            if($this->db->insert('mis_complaint',$data))
			return $this->db->insert_id();
		else
			return FALSE;
        }
        
	function complaint_list ($status, $supervisor)
	{
		$res = $this->db->query("SELECT * FROM complaint WHERE status = '".$status."' and type='".$supervisor."'ORDER BY date_n_time;");
		return $res;
	}
	function mis_complaint_list ($status,$supervisor)
	{
		if($supervisor=='All')
		$res = $this->db->query("SELECT * FROM mis_complaint WHERE status = '".$status."' ORDER BY date_n_time;");
		else
		$res = $this->db->query("SELECT * FROM mis_complaint WHERE status = '".$status."' and category='".$supervisor."'ORDER BY date_n_time;");
		return $res;
	}
	function all_complaint_list ($supervisor)
	{
		$res = $this->db->query("SELECT * FROM complaint WHERE type='".$supervisor."' ORDER BY date_n_time DESC;");
		return $res;
	}
	function all_mis_complaint_list ($supervisor)
	{
		if($supervisor=='All')
		$res = $this->db->query("SELECT * FROM mis_complaint ORDER BY date_n_time DESC;");
		else
		$res = $this->db->query("SELECT * FROM mis_complaint where category='".$supervisor."' ORDER BY date_n_time DESC;");
		return $res;
	}
	function user_complaint_list ($user_id)
	{
		$res = $this->db->query("SELECT * FROM complaint WHERE user_id='".$user_id."' ORDER BY date_n_time DESC;");
		return $res;
	}
	function user_mis_complaint_list ($user_id)
	{
		$res = $this->db->query("SELECT * FROM mis_complaint WHERE user_id='".$user_id."' ORDER BY date_n_time DESC;");
		return $res;
	}
	function get_complaint_details ($complaint_id)
	{
		$res = $this->db->query("SELECT * FROM complaint WHERE complaint_id = '".$complaint_id."';");
		return $res;		
	}
        
        function get_complaint_details_next ($complaint_id, $status,$type)
	{
		$res = $this->db->query("SELECT * FROM complaint WHERE complaint_id = '".$complaint_id."' and type='".$type."' and status='".$status."';");
		return $res;		
	}
	  function get_mis_complaint_details_next ($complaint_id, $status,$type)
	{
		$res = $this->db->query("SELECT * FROM mis_complaint WHERE complaint_id = '".$complaint_id."' and category='".$type."' and status='".$status."';");
		return $res;		
	}
	function update_complaint ($complaint_id, $status, $fresh_action)
	{	
		$this->db->query("UPDATE complaint SET remarks = '".$fresh_action."', status = '".$status."' WHERE complaint_id = '".$complaint_id."';");
	}
	function update_mis_complaint ($complaint_id, $status, $fresh_action)
	{	
		$this->db->query("UPDATE mis_complaint SET remarks = '".$fresh_action."', status = '".$status."' WHERE complaint_id = '".$complaint_id."';");
	}
	function get_remarks ($complaint_id)
	{
		$res = $this->db->query("SELECT remarks FROM complaint WHERE complaint_id = '".$complaint_id."';");
		foreach ($res->result() as $row) //last
				$action_taken = $row->remarks;
		return $action_taken;		
	}
	function get_mis_remarks ($complaint_id)
	{
		$res = $this->db->query("SELECT remarks FROM mis_complaint WHERE complaint_id = '".$complaint_id."';");
		foreach ($res->result() as $row) //last
				$action_taken = $row->remarks;
		return $action_taken;		
	}
        
        //--------------------------Report Module Function-----------------------
        function get_all_complaints()
        {
            $query = $this->db->query("select * from complaint");
            
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
            
        }
        
        function get_all_complaints_rpt($fdate,$tdate,$status,$type,$loc)
        {
            
        $sql = "select * from complaint where 1=1";
        
        if ($fdate != '1970-01-01' && $tdate != '1970-01-01') 
        {
            $sql .= " AND date(date_n_time) BETWEEN CAST('" . $fdate . "' AS DATE) AND CAST('" . $tdate . "' AS DATE)";
            
        }
        if ($status)
	{
            $sql .= " AND complaint.status='".$status."'";
	}
         if ($type)
	{
            $sql .= " AND complaint.type='".$type."'";
	}
         if ($loc)
	{
            $sql .= " AND complaint.location='".$loc."'";
	}
        
        //echo $sql;
        
        $query = $this->db->query("$sql group by complaint_id");
        if($query->num_rows() == 0)
            return FALSE;
	return $query->result();
            
                        
        }
		function get_all_mis_complaints_rpt($fdate,$tdate,$status,$type)
        {
            
        $sql = "select * from mis_complaint where 1=1";
        
        if ($fdate != '1970-01-01' && $tdate != '1970-01-01') 
        {
            $sql .= " AND date(date_n_time) BETWEEN CAST('" . $fdate . "' AS DATE) AND CAST('" . $tdate . "' AS DATE)";
            
        }
        if ($status)
	{
            $sql .= " AND mis_complaint.status='".$status."'";
	}
         if ($type)
	{
            $sql .= " AND mis_complaint.category='".$type."'";
	}  
        //echo $sql;
        
        $query = $this->db->query("$sql group by complaint_id");
        if($query->num_rows() == 0)
            return FALSE;
	return $query->result();
            
                        
        }
        
        function get_all_complaints_byType($id)
        {
            $query = $this->db->query("select * from complaint where type='".$id."'");
            
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }

        
		
		function get_all_mis_complaints_byType($id)
        {
            $query = $this->db->query("select * from mis_complaint where category='".$id."'");
            
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }

        function get_total_mis_complaints($id)
        {
            $query = $this->db->query("select * from mis_complaint");
            
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        
        function get_complaint_and_type($id)
        {
            $query = $this->db->query("select * from complaint where com_id='".$id."'");
            
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
		function get_mis_complaint_and_type($id)
        {
            $query = $this->db->query("select * from mis_complaint where token='".$id."'");
            
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        
        function get_next_complaint($id,$status,$type)
        {
          //  $query = $this->db->query("select * from complaint where com_id > '".$id."' limit 1");
            
    $query = $this->db->query("select * from complaint where type ='".$type."' and status='".rawurldecode($status)."' and com_id > '".$id."' limit 1");
                if($query->num_rows() > 0)
			return $query->row();
		else
			return false;
        }
		
        function get_next_mis_complaint($id,$status,$type)
        {
          //  $query = $this->db->query("select * from complaint where com_id > '".$id."' limit 1");
            
    $query = $this->db->query("select * from mis_complaint where category='".$type."' and status='".rawurldecode($status)."' and token > '".$id."' limit 1");
	
                if($query->num_rows() > 0)
			return $query->row();
		else
			return false;
        }
        function get_next_all_supervisor_mis_complaint($id,$status,$type)
        {
          //  $query = $this->db->query("select * from complaint where com_id > '".$id."' limit 1");
            
    $query = $this->db->query("select * from mis_complaint where status='".rawurldecode($status)."' and token > '".$id."' limit 1");
	
                if($query->num_rows() > 0)
			return $query->row();
		else
			return false;
        }
        function get_next_all_mis_complaint($id,$status,$type)
        {
          //  $query = $this->db->query("select * from complaint where com_id > '".$id."' limit 1");
            
    $query = $this->db->query("select * from mis_complaint where token > '".$id."' limit 1");
	
                if($query->num_rows() > 0)
			return $query->row();
		else
			return false;
        }
        function get_prev_all_mis_complaint($id,$status,$type)
        {
          //  $query = $this->db->query("select * from complaint where com_id > '".$id."' limit 1");
            
    $query = $this->db->query("select * from mis_complaint where token < '".$id."'order by token desc limit 1");
	
                if($query->num_rows() > 0)
			return $query->row();
		else
			return false;
        }
        function get_next_complaint_next($id,$type)
        {
            $query = $this->db->query("select * from complaint where type='".$type."' and com_id > '".$id."' limit 1");
            
                if($query->num_rows() > 0)
			return $query->row();
		else
			return false;
        }
        function get_prev_complaint($id,$status,$type)
        {
           // $query = $this->db->query("select complaint_id,status from complaint where com_id = (select max(com_id) from  complaint where com_id < '".$id."' limit 1)");
            
             $query = $this->db->query("select * from complaint where type ='".$type."' and status='".rawurldecode($status)."' and com_id < '".$id."' order by com_id desc limit 1");
            
            
                if($query->num_rows() > 0)
			return $query->row();
		else
			return false;
        }
		function get_prev_mis_complaint($id,$status,$type)
        {
           // $query = $this->db->query("select complaint_id,status from complaint where com_id = (select max(com_id) from  complaint where com_id < '".$id."' limit 1)");
            
             $query = $this->db->query("select * from mis_complaint where category='".$type."' and status='".rawurldecode($status)."' and token < '".$id."' order by token desc limit 1");
            
            
                if($query->num_rows() > 0)
			return $query->row();
		else
			return false;
        }
        function get_prev_all_supervisor_mis_complaint($id,$status,$type)
        {
           // $query = $this->db->query("select complaint_id,status from complaint where com_id = (select max(com_id) from  complaint where com_id < '".$id."' limit 1)");
            
             $query = $this->db->query("select * from mis_complaint where status='".rawurldecode($status)."' and token < '".$id."' order by token desc limit 1");
            
            
                if($query->num_rows() > 0)
			return $query->row();
		else
			return false;
        }
        
         function get_prev_complaint_next($id,$type)
        {
            $query = $this->db->query("select complaint_id,status from complaint where type='".$type."' and com_id = (select max(com_id) from  complaint where  type='".$type."' and com_id < '".$id."' limit 1)");
            
                if($query->num_rows() > 0)
			return $query->row();
		else
			return false;
        }
        
}
?>

