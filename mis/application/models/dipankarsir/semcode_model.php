<?php

class Semcode_model extends CI_Model
{
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
        
	 function insert_record($data)
        {
            if($this->db->insert('dip_m_semcode',$data))
			return TRUE;
		else
			return FALSE;
        }
        
        function check_exists($d,$c,$b)
        {
           $query = $this->db->get_where('dip_m_semcode',array('deptmis'=>$d,'course'=>$c,'branch'=>$b));    
           if($query->num_rows() > 0){
                  return true;
             }
		else{
                  return false;
               }
        }
        function getsemcode_all()
        {
            $query = $this-> db-> get('dip_m_semcode'); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        
		
     
        
        
}
?>
