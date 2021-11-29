<?php

class Main extends CI_Model
{
        
    function getTotalRegCourseBydept($id,$session,$session_year){
        $q=$this->db->query("select course_aggr_id, count(course_aggr_id) as total_stu,semester,course_id,branch_id from reg_regular_form join user_details on reg_regular_form.admn_no = user_details.id  where user_details.dept_id=? and reg_regular_form.session=? and reg_regular_form.session_year=? and reg_regular_form.hod_status='1' and reg_regular_form.acad_status='1'  group by course_aggr_id,semester order by semester asc",array($id,$session,$session_year));
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
    
    function getOfferedEle($aggr_id,$y,$session='Monsoon'){                                                                                                                                                                                                          
        $q=$this->db->get_where('optional_offered',array('aggr_id'=>$aggr_id,'batch'=>$y));
       
        if($q->num_rows() >0){
            $sub = $q->result();
            $i=0;
            foreach($sub as $s){
                $c=$this->db->get_where('course_structure',array('aggr_id'=>$aggr_id,'id'=>$s->id))->row();
                if($session=='Monsoon' && $c->semester%2 == '0'){
                        $data[$i]->id=$c->id;
                $i++;}else if($session=='Winter' && $c->semester%2 == '1'){
                        $data[$i]->id=$c->id;
                $i++;}
            }
         
            return $data;
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
    protected function gethonourdeptId($dept,$sess){
        $q=$this->db->query("select distinct(branch_id) as branch from stu_academic as sa inner join hm_form as hf on sa.admn_no=hf.admn_no where hf.dept_id=? and hf.session_year=?",array($dept,$sess));
        if($q->num_rows() >0){
            return $q->result();
        }else
            return false;
    }
    function honoursub($dept,$sess){
      //  print_r($dept).print_r($sess); die();
        $p=false;
       $this->load->model('course_structure/basic_model','',true);
        $q=$this->gethonourdeptId($dept, $sess);
        $e =0;
        if(is_array($q)){
        foreach($q as $r){
            
            $aggr=$this->basic_model->get_latest_aggr_id('honour',$r->branch,'honour_'.$r->branch.'_'.str_replace("-", "_", $sess));
        
            if(!empty($aggr)){
                $p[$e]['aggr_id']=$aggr[0]->aggr_id;
                $p[$e]['branch']=$r->branch;
            //semester hardcoded//
            $p[$e]['subject']=$this->getsubjectByaggr_id($aggr[0]->aggr_id, '5');
            }
            
        $e++;}}
        if($p){
        return $p;
        }else{ return false;}
        
    }
     protected function getminordepId($dept,$sess){

        $q=$this->db->query("select distinct(hd.branch_id) as branch from hm_form as hf join hm_minor_details as hd on hf.form_id= hd.form_id where hd.dept_id=? and hf.session_year=?",array($dept,$sess));
        if($q->num_rows() >0){
            return $q->result();
        }
    }
    function minorsub($dept,$sess){
      //  print_r($dept).print_r($sess); die();
       $this->load->model('course_structure/basic_model','',true);
        $q=$this->getminordepId($dept, $sess);
        $e =0;
      //  print_r($q); die();
        foreach($q as $r){
            
            $aggr=$this->basic_model->get_latest_aggr_id('minor',$r->branch,'minor_'.$r->branch.'_'.str_replace("-", "_", $sess));
        
            if(!empty($aggr)){
                $p[$e]['aggr_id']=$aggr[0]->aggr_id;
                $p[$e]['branch']=$r->branch;
            //semester hardcoded//
            $p[$e]['subject']=$this->getsubjectByaggr_id($aggr[0]->aggr_id, '5');
            }
            
      $e++;}
      
        if($p){
        return $p;
        }else{ return false;}
        
    }
    function totalminorStu($dept,$sess,$branch){
         $q=$this->db->query("select count(hd.branch_id) as stu from hm_form as hf join hm_minor_details as hd on hf.form_id= hd.form_id where hd.dept_id=? and hf.session_year=? and hd.branch_id=? and hf.minor='1' and hf.minor_hod_status='Y'",array($dept,$sess,$branch));
        if($q->num_rows() >0){
            return $q->row();
        }
    }
    
    function totalhonourStu($dept,$sess,$branch){
         $q=$this->db->query("select count(form_id) as stu from hm_form as hf join stu_academic as sa on hf.admn_no=sa.admn_no where hf.dept_id=? and hf.session_year=? and sa.branch_id=? and hf.honours='1' and  hf.honour_hod_status='Y'",array($dept,$sess,$branch));
        if($q->num_rows() >0){
            return $q->row();
        }
    }
    /*
     * @getStudentNamebatchwise
     */
    function getStudentNamebatchwise($aggr,$session_year,$session,$semester,$branch){
        $q=$this->db->query("select a.admn_no,b.first_name,b.middle_name,b.last_name from reg_regular_form as a join user_details as b on a.admn_no=b.id where a.course_aggr_id=? and a.session_year=? and a.session=? and semester=? and a.hod_status='1' and a.acad_status='1' and a.branch_id=?",array($aggr,$session_year,$session,$semester,$branch));
        if($q->num_rows() > 0){
            return $q->result();
        }
        return false;
    }
    
    function gethonourStudent($dept_id,$session_year,$branch){
       $q= $this->db->query("select a.admn_no, c.first_name,c.middle_name,c.last_name from hm_form as a join stu_academic as b on a.admn_no=b.admn_no 
join user_details as c on b.admn_no=c.id 
where a.honours='1' 
and a.honour_hod_status='Y' 
and a.dept_id=?
and b.branch_id=? 
and a.session_year=?
and b.semester='5'",array($dept_id,$branch,$session_year));
        if($q->num_rows() > 0){
            return $q->result();
        }
        return false;
        
    }
    
    function getminorStudent($dept_id,$session_year,$branch){
      $q=$this->db->query("select a.admn_no, c.first_name,c.middle_name,c.last_name,a.dept_id from hm_form as a join hm_minor_details as b on a.form_id=b.form_id 
join user_details as c on a.admn_no=c.id 
where a.minor='1' 
and a.minor_hod_status='Y' 
and b.dept_id=? 
and b.branch_id=? 
and a.session_year=?
and a.semester='5'",array($dept_id,$branch,$session_year));
   // echo $this->db->last_query(); die();
      if($q->num_rows()> 0){
            return $q->result();
        }
        return false;
    }    
    
  function getTotalOtherStu($sub,$session,$session_year){
     // echo $sub; die();
    return $q=$this->db->query("select count(admn_no) as total_stu from reg_other_form as a join reg_other_subject as b 
on a.form_id=b.form_id where 
a.hod_status='1'
and a.acad_status='1'
and a.session_year=?
and a.`session`=?
and b.sub_id=?",array($session_year,$session,$sub))->row();     
  }
    
}
?>