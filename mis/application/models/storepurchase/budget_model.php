<?php

class Budget_model extends CI_Model
{
	
  	
	

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
 	
	function get_depts()
	{
            $query = $this->db->query("select a.* from departments a where a.`type`='academic' order by a.`type`,a.name");
            
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
        function get_depts_nonacademic()
	{
            $query = $this->db->query("select a.* from departments a where a.`type`='nonacademic' order by a.`type`,a.name");
            
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
        function insert($data)
	{
		if($this->db->insert('sp_budget',$data))
			return $this->db->insert_id();
		else
			return FALSE;
	}
	
        function get_budget()
	{
                $query = $this->db->query("select * from sp_budget order by sp_created_on desc");
            
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;

	}
        function get_depts_name($nm)
        {
            $query = $this->db->select('name')->get_where('departments', array('id' => $nm));
            if($query->num_rows() > 0)
			return $query->row()->name;
		else
			return false;
        }
        function get_user_dept($id)
        {
            $sql= "select name from departments where id=(select dept_id from user_details where id='".$id."')";
             $query = $this->db->query($sql);
             if($query->num_rows() > 0)
			return $query->row()->name;
		else
			return false;
            
        }
        function check_exists($fy,$dpt)
        {
            $sql= "select * from sp_budget where sp_fin_year='".$fy."' and sp_dept='".$dpt."'";
           
            $query = $this->db->query($sql);
                      
            if($query->num_rows() > 0)
		return true;
	    else
                return false;
        }
        function fetch_fy($id)
        {
            $sql= "select * from sp_budget where sp_budget_id=".$id;
           
            $query = $this->db->query($sql);
                      
            if($query->num_rows() > 0)
		return $query->row();
	    else
                return false;
        }
        function update_sp_budget($data,$id)
        {
            $this->db->update('sp_budget', $data, array('sp_budget_id' => $id));
            return TRUE;
        }
        function insert_into_sp_bud_log_tbl($id)
        {
            $sql= "insert into sp_budget_log  select * from sp_budget where sp_budget_id=".$id;
            $this->db->query($sql);
            
        }
        function show_sp_budget_CFY($id)
	{
			
			//$query = $this->db->query("SELECT * FROM hc_budget where curr_fin_year=(SELECT YEAR(NOW()))");
            
                        $query = $this->db->query("SELECT * FROM sp_budget where sp_budget_id=".$id);
			
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
	}
        
        //-----------------------------------------------------------------
        
        //-----------------get financial year from budget log file----------
        
        function show_sp_budget_LOGFY($id)
	{
			
			//$query = $this->db->query("SELECT * FROM hc_budget where curr_fin_year=(SELECT YEAR(NOW()))");
            
                        $query = $this->db->query("SELECT * FROM sp_budget_log where sp_budget_id=".$id);
			
			if($query->num_rows() > 0)
			{	
				
			return $query->result();
			}
			else
			{
				return FALSE;
			}
	}
        function sp_check_finyear_status($dpt)
	{
		$myquery="select * from sp_budget where sp_dept='".$dpt."' and sp_status='active'";
                $query = $this->db->query($myquery);
                
                if($query->num_rows()>0)
                {
			return	$query->row()->sp_budget_id;
                }
		else
                {
			return FALSE;
                }
	}
        function sp_update_bug_status($id)
        {
            $myquery="update sp_budget set sp_status='inactive' where sp_budget_id=".$id;
            $this->db->query($myquery);
            return true;
        }
}
