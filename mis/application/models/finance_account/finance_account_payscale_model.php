<?php

/**
 * Author: Anuj
*/
class Finance_account_payscale_model extends CI_Model {
    
    

    function __construct() {
        parent::__construct();
    }
        
        public function get_payscale() 
        {

            $myquery = "select distinct pay_band,pay_band_description from pay_scales";
            $query = $this->db->query($myquery);
             if ($query->num_rows() > 0) {
                 return $query->result();

             } else {
                 return FALSE;
             }
            
            
        }
           public function get_basic_pay_empwise($id) 
        {

            $myquery = "SELECT a.*,b.*,DATE_FORMAT(a.effective_from,'%d %b %Y') AS edate
            FROM emp_pay_details a
            INNER JOIN pay_scales b ON a.pay_code=b.pay_code
            WHERE a.emp_no=?";
            $query = $this->db->query($myquery,array($id));
            
          //  echo $this->db->last_query();
             if ($query->num_rows() > 0) {
                 return $query->row();

             } else {
                 return FALSE;
             }
            
            
        }
        public function get_basic_pay_empwise_from_temp_table($id) 
        {

            $myquery = "select a.*,b.pay_band,b.pay_band_description,b.grade_pay,DATE_FORMAT(a.effective_from,'%d %b %Y') AS effdate from emp_pay_details_temp a
inner join pay_scales b on a.pay_code=b.pay_code 
where a.emp_no=?";
            $query = $this->db->query($myquery,array($id));
            
          //  echo $this->db->last_query();
             if ($query->num_rows() > 0) {
                 return $query->row();

             } else {
                 return FALSE;
             }
            
            
        }
        public function get_payscale_validation() 
        {

            $myquery = "select a.*,concat(b.first_name,' ',b.middle_name,' ',b.last_name) as emp_name from emp_pay_details_validation a
inner join user_details b on b.id=a.emp_no where a.pay_scale_basic_pay='Pending'";
            $query = $this->db->query($myquery);
             if ($query->num_rows() > 0) {
                return $query->result();

             } else {
                 return FALSE;
             }
            
            
        }
        
        function update_emp_pay_details($data,$con)
        {
            if($this->db->update('emp_pay_details',$data,$con))
            {
                       return true;
            } 
               return false;
        }
        
        function update_validation_status($data,$con)
        {
            if($this->db->update('emp_pay_details_validation',$data,$con))
            {
                       return true;
            } 
               return false;
        }
        
        
        function insert_emp_history($data)
	{
		if($this->db->insert('emp_pay_details_history',$data))
			return TRUE;
		else
			return FALSE;
	}
        function insert_rejection_reason($data)
        {
            if($this->db->insert('emp_pay_details_reject_reason',$data))
			return TRUE;
		else
			return FALSE;
        }
           function insert_newdata_for_validation($data)
	{
		if($this->db->insert('emp_pay_details_temp',$data))
			return TRUE;
		else
			return FALSE;
	}
           function insert_for_approval($data)
	{
		if($this->db->insert('emp_pay_details_validation',$data))
			return TRUE;
		else
			return FALSE;
	}
        function get_grade_pay($id)
        {
            $myquery = "select * from pay_scales where pay_band=?";
            $query = $this->db->query($myquery,array($id));
             if ($query->num_rows() > 0) {
                 return $query->result();
             } else {
                 return FALSE;
             }
        }
        
          function insert_batch($data)
	{
		if($this->db->insert_batch('pay_scales',$data))
			return TRUE;
		else
			return FALSE;
	}
        
        function delete_from_temp($id)
        {
            $this->db->where('emp_no', $id);
            $this->db->delete('emp_pay_details_temp');
        }
         function getNotificationUser($id) {
        $q = $this->db->get_where('user_auth_types', array('auth_id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
    
    function get_validation_status()
    {
        $myquery = "SELECT a.*, CONCAT(b.first_name,' ',b.middle_name,' ',b.last_name) AS emp_name, DATE_FORMAT(a.created_date,'%d %b %Y') AS edate
from
(select * from emp_pay_details_reject_reason  group by emp_no ,created_date   order  by emp_no,created_date  desc )a
INNER JOIN user_details b ON b.id=a.emp_no 
group by a.emp_no ";
            $query = $this->db->query($myquery);
            echo $this->db->last_query();die();
             if ($query->num_rows() > 0) {
                 return $query->result();
             } else {
                 return FALSE;
             }
    }


        
			
        
    
}