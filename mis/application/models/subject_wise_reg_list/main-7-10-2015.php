<?php

class Main extends CI_Model
{
        
    function getTotalRegCourseBydept($id,$session,$session_year){
        $q=$this->db->query("select course_aggr_id, count(course_aggr_id) as total_stu,semester,course_id,branch_id from reg_regular_form join user_details on reg_regular_form.admn_no = user_details.id  where user_details.dept_id=? and reg_regular_form.session=? and reg_regular_form.session_year=? and reg_regular_form.hod_status='1' and reg_regular_form.acad_status='1' group by course_aggr_id,semester order by semester ASC",array($id,$session,$session_year));
        if($q->num_rows() > 0){
            return $q->result();
        }
        return false;
    }
    
     function getTotalcommStuBygroup($id,$session,$session_year,$semester,$group){
        $q=$this->db->query("select count(course_aggr_id) as total_stu from reg_regular_form join user_details on reg_regular_form.admn_no = user_details.id  where user_details.dept_id=? and reg_regular_form.session=? and reg_regular_form.session_year=? and reg_regular_form.semester=? and section=? and reg_regular_form.hod_status='1' and reg_regular_form.acad_status='1'",array($id,$session,$session_year,$semester,$group));
        if($q->num_rows() > 0){
            return $q->row();
        }
        return false;
    }
    
    
    function getsubjectByaggr_id($aggrId,$semester){
      $q=$this->db->query("select s.id,s.subject_id,s.name,cs.sequence from course_structure as cs join subjects as `s` on cs.id=s.id where cs.aggr_id=? and cs.semester=? and 0+cs.sequence REGEXP '^[0-9]+$'",array($aggrId,$semester));
      if($q->num_rows() >0){
          return $q->result();
      }
      return false;
    }
    
   function getOfferedEle($aggr_id,$y){
        $q=$this->db->get_where('optional_offered',array('aggr_id'=>$aggr_id,'batch'=>$y));
          if($q->num_rows() >0){
          return $q->result();
      }
      return false;
    }
    
    function getsubjectByid($id){
        $q=$this->db->get_where('subjects',array('id'=>$id));
          if($q->num_rows() >0){
          return $q->row();
      }
      return false;
    }
    
    function countElestu($sid,$session,$sessionY){
        
        $q=$this->db->query("select count(rs.form_id) as total_stu from reg_regular_form as rs join reg_regular_elective_opted as reo on rs.form_id=reo.form_id where reo.sub_id=? and rs.hod_status='1' and rs.acad_status='1' and rs.`session`=? and rs.session_year=?",array($sid,$session,$sessionY));
        if($q->num_rows() >0){
          return $q->row();
      }
      return false;
        
    }
    protected function get_numeric($val) {
				if (is_numeric($val)) {
					return $val + 0;
				}
				return 0;
			}
    
    function offeredsubject(){
        echo "hello";
    }
}
?>