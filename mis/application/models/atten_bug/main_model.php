<?php
class Main_model extends CI_Model
{
    function get_group_details($subid,$gid){
        
        $q="select * from prac_group_attendance a where a.group_no=? and sub_id=?";
        $q1=$this->db->query($q,array($gid,$subid));
         return $q1->row()->group_start-1;
        
        
    }
    
    function update_do($data,$con){
       $this->db->where($con);
       $this->db->update('absent_table', $data);
    }
}
?>
