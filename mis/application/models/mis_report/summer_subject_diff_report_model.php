<?php

class summer_subject_diff_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_student($syear)
    {
       
          
      $sql = "select b.admn_no,a.dept,a.course,a.branch,group_concat(b.mis_sub_id SEPARATOR ', ')as mis_sub_id from final_semwise_marks_foil a
inner join final_semwise_marks_foil_desc b on a.id=b.foil_id
where a.session_yr=? and b.grade='F' 
group by b.admn_no
order by b.admn_no,b.mis_sub_id;";
        
       /* $sql = "select b.admn_no,a.dept,a.course,a.branch,b.mis_sub_id from final_semwise_marks_foil a
inner join final_semwise_marks_foil_desc b on a.id=b.foil_id
where a.session_yr=? and b.grade='F' 
order by b.admn_no;";*/

        $query = $this->db->query($sql,array($syear));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
   // function get_summer_information($syear,$adm,$subid){
   function get_summer_information($syear,$adm){
         $sql = "select a.admn_no,GROUP_CONCAT(b.sub_id SEPARATOR ', ') as summ_sub_id from reg_summer_form a
inner join reg_summer_subject b on a.form_id=b.form_id
where a.session_year=? and a.`session`='Summer' and a.admn_no=? and a.hod_status<>'2' and a.acad_status<>'2'
group by a.admn_no
order by b.sub_id
";
        /*$sql ="select a.admn_no,b.sub_id as summ_sub_id from reg_summer_form a
inner join reg_summer_subject b on a.form_id=b.form_id
where  a.hod_status<>'2' and a.acad_status<>'2' and a.`session`='Summer' 
and a.session_year=? and a.admn_no=? and b.sub_id=?";*/

       // $query = $this->db->query($sql,array($syear,$adm,$subid));
         $query = $this->db->query($sql,array($syear,$adm));

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    
    
    
    
    

}

?>