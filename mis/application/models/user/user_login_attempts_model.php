<?php

class User_login_attempts_model extends CI_Model
{

	var $table = 'user_login_attempts';
        var $table_log = 'login_logout_log';
        
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function insert($data)
	{
		$this->db->insert($this->table,$data);
		//$this->db->insert($this->table_log,$data);
	}
        
        function get_log_in_maxID($id)
        {
            $q=$this->db->select_max('log_id')->where('user_id',$id)->get($this->table_log);
            if($q->num_rows>0)
                return $q->row()->log_id;
            return FALSE;
        }
        
        function insert_log($data)
        {
            $this->db->insert($this->table_log,$data);
			//echo"hh". $this->db->last_query();exit;
            return true;
        }
        function update_log($data,$con)
        {
            $this->db->update($this->table_log,$data,$con);
            return true;
        }
        function get_logg($con)
        {
            $q=$this->db->get_where($this->table_log,$con);
            if($q->num_rows >0){
                return $q->result();
            }
            return false;
        }
        
        function get_login_details()
        {
            $this->db->select('*');
            $this->db->from('login_logout_log');
	    $this->db->where("date(logged_in_time)",date("Y-m-d"));  //add this line for displaying the logged in person list of today only	
             $query = $this->db->get();

            if ( $query->num_rows() > 0 )
            {
                 return $query->result();
                
            }

   
        }
        function get_login_details_bydate($dfrom='',$dto='',$user='')
        {
            $q="select * from login_logout_log where 1=1";
           if($dfrom !="1970-01-01" && $dto !="1970-01-01"){  
$q.="  and date(logged_in_time) between CAST('".$dfrom."' AS DATE) AND CAST('".$dto."' AS DATE)";
     }
     if($user){
         $q.=" and user_id='".$user."'";
         }
         $q.=" ORDER BY log_id DESC";
             
        $query = $this->db->query($q);


            if ( $query->num_rows() > 0 )
            {
                 return $query->result();
                
            }
        
        
}
}
/* End of file user_login_attempts_model.php */
/* Location: mis/application/models/user/user_login_attempts_model.php */