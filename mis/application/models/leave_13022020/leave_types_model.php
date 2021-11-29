<?php


class Leave_types_model extends CI_Model {
    
    

    function __construct() {
        parent::__construct();
    }

    

    
 
    function check_status($nol,$description){

    $myquery = "select * from  leave_types_master where nature_of_leave=? or description=?";

    $query = $this->db->query($myquery,array($nol,$description));

                if ($query->num_rows() > 0){
                 //return TRUE;
                return $query->row();

                } 
                else {
                return FALSE;
                }
    }
	
	function insert_leave_types($data)
        {
            if ($this->db->insert('leave_types_master', $data))
                    return $this->db->insert_id();
                else
                    return FALSE;
        }
		
	function get_previous_leave_type_details(){
      
    $myquery="select * from leave_types_master";
        
                $query=$this->db->query($myquery);

                if($query->num_rows()>0){
                      return $query->result();
                }
                else{
                return false;
                }

    }
	
	function get_leave_types($ar){
      
    $myquery="select * from leave_types_master where id=?";
        
                $query=$this->db->query($myquery,$ar);

                if($query->num_rows()>0){
                      return $query->result();
                }
                else{
                return false;
                }

    }
   
   
   function delete_leave_types_master($id){
	   $this->db->where('id',$id);
    	$query=$this->db->get('leave_types_master');
    	$result=$query->result()[0];
    	$result->operation='D';
    	$result->operated_on=date('Y-m-d H:i:s');
    	$result->operated_by=$this->session->userdata('id');
    	/*************--Updating acc_emp_ac_status--****************/
    	$this->db->where('id',$id);
    	$this->db->delete('leave_types_master');
    	
    	/***********--Inserting data in acc_emp_ac_status_backup--*********/
    	$this->db->insert('leave_types_master_backup',$result);
	   //echo $this->db->last_query();die();
	   
	   
	   
  		
    	//$this->db->where('id',$id);
    	//$this->db->delete('leave_types_master');	
    	
  	}
	
	function edit_leave_types($new){
		
    	$this->db->where('id',$new['id']);
    	$query=$this->db->get('leave_types_master');
    	$result=$query->result()[0];

    	$this->db->where('id',$new['id']);
    	if($this->db->update('leave_types_master',$new)){
    		$result->operation='E';
    		$result->operated_on=date('Y-m-d H:i:s');
    		$result->operated_by=$this->session->userdata('id');
    		
    		$this->db->insert('leave_types_master_backup',$result);
    		//echo $this->db->last_query();die();
    		return true;
    	}
    	else{
    		return false;
    	}
		
		
    	//$this->db->where('id',$new['id']);
    	//$this->db->update('leave_types_master',$new);
    	
    }

     
}