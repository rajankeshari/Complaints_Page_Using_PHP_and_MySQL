<?php


class Leave_tour_purpose_of_visit_model extends CI_Model {
    
    

    function __construct() {
        parent::__construct();
    }

    

    
 
    function check_status($pov){

    $myquery = "select * from  leave_official_tour_purpose_of_visit where purpose_of_visit=?";

    $query = $this->db->query($myquery,array($pov));

                if ($query->num_rows() > 0){
                 //return TRUE;
                return $query->row();

                } 
                else {
                return FALSE;
                }
    }
	
	function insert_leave_official_tour_purpose_of_visit($data)
        {
            if ($this->db->insert('leave_official_tour_purpose_of_visit', $data))
                    return $this->db->insert_id();
                else
                    return FALSE;
        }
		
	function get_previous_tour_purpose_of_visit_details(){
      
    $myquery="select * from leave_official_tour_purpose_of_visit";
        
                $query=$this->db->query($myquery);

                if($query->num_rows()>0){
                      return $query->result();
                }
                else{
                return false;
                }

    }
	
	function get_tour_purpose_of_visit_details($ar){
      
    $myquery="select * from leave_official_tour_purpose_of_visit where id=?";
        
                $query=$this->db->query($myquery,$ar);

                if($query->num_rows()>0){
                      return $query->result();
                }
                else{
                return false;
                }

    }
   
   
   function delete_leave_official_tour_purpose_of_visit($id){
	 
	   
    	$this->db->where('id',$id);
    	$this->db->delete('leave_official_tour_purpose_of_visit');   
    	
  	}
	
	function edit_leave_official_tour_purpose_of_visit($new){
		
    	$this->db->where('id',$new['id']);
    	if($this->db->update('leave_official_tour_purpose_of_visit',$new)){
    		
    		return true;
    	}
    	else{
    		return false;
    	}
		
		
    	//$this->db->where('id',$new['id']);
    	//$this->db->update('leave_types_master',$new);
    	
    }

     
}