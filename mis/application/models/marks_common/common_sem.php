<?php 
class Common_sem extends CI_Model{
  
    function sectionGroup($session_year){
	//	 echo  'session'.$session_year; die();
	    	//echo "inmodel";
          $q= $this->db->get_where("SELECT * FROM section_group_rel WHERE session_year='2016-2017'");
          if($q->num_rows() >0){
            return $q->row();
        	}
        return false;
    }
  
  function getDeptById($id){
	  $d=$this->db->get_where('departments',array('id'=>$id));
	   if($d->num_rows() >0){
          return $d->row();
      }
      return false;    
  }
}


?>