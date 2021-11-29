<?php

class Emp_basic_details_model extends CI_Model
{

	private $tabulation='iitism';
	var $table = 'emp_basic_details';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function insert($data)
	{
		$this->db->insert($this->table,$data);
		// print_r($k);
		// exit;
	}

	function updateById($data,$emp_no)
	{
		$this->db->update($this->table,$data,array('emp_no'=>$emp_no));
	}

	function getEmployeeByID($emp_no = '')
	{
		if($emp_no != '')
		{
			$query = $this->db->where('emp_no = "'.$emp_no.'"','',FALSE)->get($this->table);
			if($query->num_rows() === 1)
				return $query->row();
			else
				return FALSE;
		}
		else
			return FALSE;
	}

	function getAllFacultyId()
	{
      $query = $this->db->query("SELECT emp_no,E.* FROM emp_basic_details AS E INNER JOIN users AS U ON E.emp_no=U.id WHERE (E.auth_id ='ft') and U.auth_id = 'emp' OR U.auth_id = 'daily_emp' ORDER BY E.emp_no"); // Added for 
		// echo $this->db->last_query();
		// exit;
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	function getAllNonFacultyId()
	{
		$query = $this->db->query("SELECT emp_no,E.*FROM emp_basic_details AS E INNER JOIN users AS U ON E.emp_no=U.id WHERE (E.auth_id !='ft' and E.auth_id !='daily_emp' ) and U.auth_id = 'emp' OR U.auth_id = 'daily_emp'ORDER BY E.emp_no"); // Added for 
		// echo $this->db->last_query();
		// exit;
		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	function getAllEmployeesId()
	{


		$query = $this->db->query("SELECT emp_no FROM emp_basic_details AS E INNER JOIN users AS U ON E.emp_no=U.id WHERE U.auth_id = 'emp' or U.auth_id = 'daily_emp' ORDER BY E.emp_no"); // Added for including Temporary MIS users
		//$query = $this->db->query("SELECT emp_no FROM emp_basic_details AS E INNER JOIN users AS U ON E.emp_no=U.id WHERE U.auth_id = 'emp' ORDER BY E.emp_no"); //Original
		// echo $this->db->last_query();
		// exit;
		if($query->num_rows() > 0)
			return $query->result();
	}
	
	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}

	function getPendingDetailsById($emp_no = '')
	{
		if($emp_no == '')
			return FALSE;
		else
		{
			$query=$this->db->where('emp_no',$emp_no)->get('pending_'.$this->table);
			if($query->num_rows() ==1 )	return $query->row();
			return FALSE;
		}
	}

	function insertPendingDetails($data)
	{
		$this->db->insert('pending_'.$this->table,$data);
	}

	function updatePendingDetailsById($data,$emp_no)
	{
		$this->db->update('pending_'.$this->table,$data,array('emp_no'=>$emp_no));
	}

	function deletePendingDetailsWhere($data)
	{
		$this->db->delete('pending_'.$this->table,$data);
	}
	function get_dept_name($id)
	{  
		// echo "reachhh";
		// exit;
		$stmt="select name from departments where id='$id'";
	    $result=$this->db->query($stmt);
	    if($result->num_rows() >0)
	    {	      
	      return $result->result();     
	    }
	    else
	    {
	      return false;
	    }
	}

	function get_designations_name($id)
	{
		$stmt="select name from designations where  id='$id'";
	    $result=$this->db->query($stmt);
	    if($result->num_rows() >0)
	    {	      
	      return $result->result();     
	    }
	    else
	    {
	      return false;
	    }
	}
	function insert_web_faculity($val)
	{ 


      $CI = &get_instance();
      $this->db2 = $CI->load->database($this->tabulation, TRUE);
     if($val[type]=='ft')
     {
      $this->db2->insert('faculty_info',$val);
     } 
     // $st=$this->db2->last_query();
     
	 $stmt="INSERT INTO `web_faculty_info` (`emp_no`,`salutation`,`first_name`,`middle_name`,`last_name`,`dept_name`,`designation`,`type`,`photopath`,`email`,`mobile_no`,`office_no`,`joining_date`,`retirement_date`,`research_interest`,`cv_path`,`pub_path`) VALUES ('$val[emp_no]
       ','$val[salutation]','$val[first_name]','$val[middle_name]','$val[last_name]','$val[dept_name]','$val[designation]','$val[type]','$val[photopath]','$val[email]','$val[mobile_no]','$val[office_no]','$val[joining_date]','$val[retirement_date]','$val[research_interest]','$val[cv_path]','$val[pub_path]');";	
      $insert=$this->db->query($stmt);
      if(true)
      {
      	return "insert";
      }
      else
      {
      	return "not insert";
      }
	}
	public function check_ft()
	{
      $stmt="select e.* from emp_basic_details  t inner join emp_validation_details as e on e.emp_no=t.emp_no where t.auth_id ='ft'";
	    $result=$this->db->query($stmt);
	    if($result->num_rows() >0)
	    {	      
	      return $result->result();     
	    }
	    else
	    {
	      return false;
	    }
	}
	public function check_nft()
	{
      $stmt="select e.* from emp_basic_details  t inner join emp_validation_details as e on e.emp_no=t.emp_no where t.auth_id ='nftn' or t.auth_id='nfta'";
	    $result=$this->db->query($stmt);

	    if($result->num_rows() >0)
	    {	      
	      return $result->result();    
	    }
	    else
	    {
	      return false;
	    }
	}
	public function get_validation($id)
	{
		$stmt="select t.* from  emp_validation_details t where t.emp_no=$id";
	    $result=$this->db->query($stmt);

	    if($result->num_rows()>0)
	    {	      
	      return $result->result_array();     
	    }
	    else
	    {
	      return "no_data";
	    }
	}
	 // checking employee email is already present or not in database for duplicates
    function getUser($userName){  
        $sql =   "select domain_name from emaildata_emp where domain_name like '%".$userName."%' ";
        $query = $this->db->query($sql);
        // echo $this->last_query();
        // exit;
        if ($this->db->affected_rows() > 0) {
        	return true;
        }
        else{
            return false;
        }			
    }
    
    function check_register_emp($id){  // checking employee is registered for email or not

        $sql = "select domain_name,password from emaildata_emp where emp_id='".$id."'";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        }
        else{
            return false;
        }
    }
    
    function check_validated_emp($id){  // checking employee email is activated or not

        $sql = "SELECT Active FROM emaildata_emp WHERE emp_id='".$id."'";
        $query = $this->db->query($sql)->result_array();
        if($query['0']['Active']==1){
            return true;
        }
        else{
            return false;
        }
    }

    function insert_emaildata($data){   // storing employee email data to permanent table emaildata_emp

		if($this->db->insert('emaildata_emp',$data)){
            return TRUE;
        }    
		else{
            return FALSE;
        }		
	}
          
    function insert_email_form_emp($data){ // storing employee basic email data to temporary table email_form_emp

        if($this->db->insert('email_form_emp',$data)){
            return true;
        }
        else{
            return false;
        }
    } 
    function getemaildataByID($emp_no = '')
	{
		if($emp_no != '')
		{
			$query = $this->db->where('emp_id = "'.$emp_no.'"','',FALSE)->get('emaildata_emp');
			// echo $this->db->last_query();
   //          exit;
			if($query->num_rows() === 1)
				return $query->row();
			else
				return FALSE;
		}
		else
			return FALSE;
	}
}

/* End of file emp_basic_details_model.php */
/* Location: mis/application/models/emp_basic_details_model.php */