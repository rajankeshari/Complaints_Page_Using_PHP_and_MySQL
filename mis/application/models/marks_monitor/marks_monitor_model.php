<?php

class Marks_monitor_model extends CI_Model
{
	private $sub_m = 'subject_mapping';
    private $sub_m_des = 'subject_mapping_des';
    private $mm = 'marks_master';
    private $subject ='subjects';
    
    function getMappingDesById($id){
		//echo 12; die;
		//$b=$this->db->get_where($this->sub_m_des,array('map_id'=>$id,'M'=>1));
                $b=$this->db->get_where($this->sub_m_des,array('map_id'=>$id));
		return $b->result();
	}
	 function getMappingById($id){
		//echo 12; die;
		$b=$this->db->get_where($this->sub_m,array('map_id'=>$id));
		return $b->row();
	}
        
        function check_status($map_id,$type){
          $subjects = $this->getMappingDesById($map_id);
             $totalS =  count($subjects);
           $leftSub = array();
                foreach($subjects as $s){
                    //echo " ".$s->sub_id;
                    $list = $this->getSubjectfromMarksMaster($map_id,$s->sub_id,$type);
                   if(count($list) > 0){
                    foreach($list as $l){
                        if($l->status == 'Y'){
                            if(($key = array_search($l->subject_id, $leftSub)) !== false) {
                                   unset($messages[$key]);
                                }
                                break;
                            
                        }else if($l->status == 'N'){
                            if((array_search($l->subject_id, $leftSub)) == false) {
                                  array_push($leftSub, $l->subject_id);
                                }
                          
                        }
                    }
                   }else{
                        array_push($leftSub, $s->sub_id."_Not Started.");
                   }
                }
                return $leftSub;
            
        }
        
        function check_status_by_subject($map_id,$type,$sub){
            $status = false;
            $list = $this->getSubjectfromMarksMaster($map_id,$sub,$type);
                   if(count($list) > 0){
                        foreach($list as $l){
                           if($l->status == 'Y'){
                               $status=true; 
                          }
                        }                    
                   }else{
                        $status=false;
                   }
                   return $status;
        }
        
        function getSubjectfromMarksMaster($map_id,$sub, $type){
              
                $b=$this->db->get_where($this->mm,array('subject_id'=>$sub,'type'=>$type,'sub_map_id'=>$map_id));
                //echo $this->db->last_query();
		return $b->result();
        }
        
        function getSubById($id){
            $q=$this->db->get_where($this->subject,array('id'=>$id));
            if($q->num_rows() > 0)
                return $q->row();
            return false;
        }
        
        function getUserDetails($id){
        $q=$this->db->get_where('user_details',array('id'=>$id));
        if($q->num_rows() > 0)
            return $q->row();
        return false;
        
        
    }
    
    function get_summer_map_from_table($data_all)
    {
        if($data_all->course_id<>'comm'){
        $myquery = "select a.form_id,b.admn_no,c.subject_id,c.name,d.semester,d.aggr_id,e.dept_id,b.course_id,b.branch_id from reg_summer_subject a
inner join reg_summer_form b on a.form_id=b.form_id
inner join subjects c on c.id=a.sub_id
inner join course_structure d on d.id=a.sub_id
inner join user_details e on e.id=b.admn_no
where b.session_year=? and b.`session`=?
and e.dept_id=? and d.aggr_id=? and b.course_id=? and b.branch_id=? and d.semester=?
group by b.admn_no,a.sub_id" ;
        $query = $this->db->query($myquery,array($data_all->session_year,$data_all->session,$data_all->dept_id,$data_all->aggr_id,$data_all->course_id,$data_all->branch_id,$data_all->semester));
        }
        else{
            $myquery = "select a.form_id,b.admn_no,c.subject_id,c.name,d.semester,d.aggr_id,e.dept_id,b.course_id,b.branch_id from reg_summer_subject a
inner join reg_summer_form b on a.form_id=b.form_id
inner join subjects c on c.id=a.sub_id
inner join course_structure d on d.id=a.sub_id
inner join user_details e on e.id=b.admn_no
inner join stu_section_data f on (f.admn_no=b.admn_no and f.session_year=b.session_year)
where b.session_year=? and b.`session`=?
and d.aggr_id=? and f.section=? and d.semester=?
group by b.admn_no,a.sub_id" ;
        $query = $this->db->query($myquery,array($data_all->session_year,$data_all->session,$data_all->aggr_id,$data_all->section,($data_all->semester.'_'.$data_all->group)));
        }
        
       //echo $this->db->last_query();die();

        if ($query->num_rows() > 0) {
            return $query->result();
             
        } else {
            return FALSE;
        }
        
    }
    
    function get_number_of_students_regular($syear,$sess,$cid,$bid,$sem,$aggrid){
        $myquery="select count(a.admn_no)as no_of_student from reg_regular_form a 
where a.session_year=? and a.`session`=?
and a.hod_status='1' and a.acad_status='1'
and a.course_id=? and a.branch_id=? and a.semester=? and a.course_aggr_id=?";
        $query = $this->db->query($myquery,array($syear,$sess,$cid,$bid,$sem,$aggrid));
      //  echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row();
             
        } else {
            return FALSE;
        }
    }
    
    function get_number_of_students_prep($syear,$sess,$sem,$aggrid){
        $myquery="select count(a.admn_no)as no_of_student from reg_regular_form a 
where a.session_year=? and a.`session`=?
and a.hod_status='1' and a.acad_status='1'
and a.semester=? and a.course_aggr_id=?";
        $query = $this->db->query($myquery,array($syear,$sess,$sem,$aggrid));
      //  echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row();
             
        } else {
            return FALSE;
        }
    }
    
        function get_number_of_students_common($syear,$sess,$sem,$sec,$aggrid){
        $myquery="select count(a.admn_no)as no_of_student from reg_regular_form a 
            inner join stu_section_data b on (a.admn_no=b.admn_no and a.session_year=b.session_year)
where a.session_year=? and a.`session`=?
and a.hod_status='1' and a.acad_status='1'
and a.semester=? and b.section=? and a.course_aggr_id=?";
        $query = $this->db->query($myquery,array($syear,$sess,$sem,$sec,$aggrid));
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row();
             
        } else {
            return FALSE;
        }
    }
    
    
    
    
    
    
    
    
}