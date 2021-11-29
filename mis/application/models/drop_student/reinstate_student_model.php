<?php

class Reinstate_student_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function add_reinstate_student_details($data){
         if($this->db->insert('reinstate_student_status',$data))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function check_exists($id){
        $sql="select * from reinstate_student_status a where a.admn_no=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_all_drop_tables(){
              $sql="SELECT table_name FROM INFORMATION_SCHEMA.tables  WHERE table_schema='mis_40_50' and TABLE_NAME LIKE 'drop%'";
              $query = $this->db->query($sql);

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
         
         }
      

function get_all_drop_student_list(){

    $sql="select * from drop_student_status";
              $query = $this->db->query($sql);

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }

}
 function update_users_table_status_leave($admn_no){
	 $sql = "UPDATE users SET STATUS='A' WHERE id=?";
        $query = $this->db->query($sql,array($admn_no));
        
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
	 
 }
 function fetch_from_drop_table($admn_no){
				$sql="select * from drop_student_status where admn_no=?";
              $query = $this->db->query($sql,array($admn_no));

              if ($this->db->affected_rows() > 0) {
                    return $query->row();
                } else {
                    return false;
                }
	 
 }
 function save_to_drop_reinstate($data2){
	 if($this->db->insert('drop_reinstate_student_status',$data2))
            return $this->db->insert_id();
        else
            return FALSE;
	 
 }
 function delete_from_drop_table($admn_no){
	 $sql = "Delete from drop_student_status where admn_no=?";
        $query = $this->db->query($sql,array($admn_no));
        
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
	 
 }
 function get_all_reinstate_student_list(){
	 $sql="select * from reinstate_student_status";
              $query = $this->db->query($sql);

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
 }



}

?>