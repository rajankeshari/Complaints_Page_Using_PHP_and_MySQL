<?php

class Emp_report_model extends CI_Model
{
        var $table = 'departments';
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
        
        function get_depts()
	{
		
                $query = $this-> db-> get('departments'); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;

	}
        
        function get_desig()
	{
		
                $query = $this-> db-> get('designations'); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;

	}
        
        function get_states()
	{
		
                $query = $this-> db-> get('indian_states'); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;

	}
        function get_dept_name($id)
        {
            $this->db->select('name');
            $this->db->from('departments');
            $this->db->where('id',$id);
            $query=$this->db->get();
            if($query->num_rows() > 0)
			return $query->row();
		else
			return false;

        }
        function get_designation_name($id)
        {
            $this->db->select('name');
            $this->db->from('designations');
            $this->db->where('id',$id);
            $query=$this->db->get();
            if($query->num_rows() > 0)
			return $query->row();
		else
			return false;

        }
        
        function getData($dept_nm,$desig,$faculty,$category,$dtfrom,$dtto,$state,$nwork,$gender)
	{
		$sql= " SELECT
  `user_details`.`id`,
  `user_details`.`first_name`,
  `user_details`.`middle_name`,
  `user_details`.`last_name`,
  `user_details`.`sex`,
  `user_details`.`category`,
  `user_details`.`email`,
  `user_details`.`dept_id`,
  `user_other_details`.`mobile_no`,
  `emp_basic_details`.`auth_id`,
  `emp_basic_details`.`designation`,
  `emp_basic_details`.`retirement_date`,
  `emp_basic_details`.`employment_nature`,
  `user_address`.`state`
FROM
  `user_details`
  INNER JOIN `user_other_details` ON `user_details`.`id` =
    `user_other_details`.`id`
  INNER JOIN `users` ON `user_details`.`id` =
    `users`.`id`
  INNER JOIN `emp_basic_details` ON `user_details`.`id` =
    `emp_basic_details`.`emp_no`
  INNER JOIN `user_address` ON `user_details`.`id` = `user_address`.`id` where 1=1  ";

    $sql .= " AND users.status='A'";            
            if ($dept_nm)
			{
					$sql .= " AND user_details.dept_id='".$dept_nm."'";
			}
            if ($desig)
			{
					$sql .= " AND `emp_basic_details`.`designation`='".$desig."'";
			}
            if ($faculty)
			{
					$sql .= " AND  `emp_basic_details`.`auth_id`='".$faculty."'";
			}
            if ($category)
			{
					$sql .= " AND  `user_details`.`category`='".$category."'";
			}
            if ($dtfrom!='1970-01-01' && $dtto!='1970-01-01')
			{
					$sql .= " AND  `emp_basic_details`.`retirement_date` BETWEEN '".$dtfrom."' AND '".$dtto."'";
			}
                        
            if ($state)
			{
					$sql .= " AND  `user_address`.`state`='".$state."'";
			}
            if ($nwork)
			{
					$sql .= " AND  `emp_basic_details`.`employment_nature`='".$nwork."'";
			}
            if ($gender)
			{
					$sql .= " AND  `user_details`.`sex`='".$gender."'";
			}
                                
                        
			
			
                       // print_r($sql);
                        //die();
                        
			$query = $this->db->query("$sql group by `user_details`.`id`");

			if($query->num_rows() == 0)	return FALSE;
			return $query->result();
		
	}

	
}

/* End of file faculty_details_model.php */
/* Location: mis/application/models/faculty_details_model.php */