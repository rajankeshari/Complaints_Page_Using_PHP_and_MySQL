<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class Rank_modal extends CI_Model
{
    
    private $course ='courses';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
        
         function getCourseDurationById($id){
            
           $q=$this->db->get_where($this->course, array('id'=>$id)); 
           if($q->row()->duration)
               $sem = ($q->row()->duration * 2);
           return (int)$sem;
        
        }
        
        function getStuList(){
         $sem=$this->getCourseDurationById($this->input->post('course'));
            $r=array();$s=array();$p=array();
        $qu="select A.admn_no,name from ((select a.id as admn_no,concat_ws(' ',a.first_name,a.middle_name,a.last_name ) as name from user_details a join reg_regular_form b on a.id=b.admn_no where a.dept_id='".$this->input->post('dept')."' and b.course_id='".$this->input->post('course')."' ANd b.semester='$sem' )
				union
				(select a.id as admn_no,concat_ws(' ',a.first_name,a.middle_name,a.last_name ) as name from user_details a join reg_regular_form b on a.id=b.admn_no where a.dept_id='".$this->input->post('dept')."' and b.course_id='".$this->input->post('course')."' ANd b.semester like '%".$sem."%') )A order by A.admn_no
		   ";
            $q=$this->db->query($qu);
            
           if($q->num_rows() > 0)
             return  $q->result();
           
            
           return false;
            
        }
        
        function getTabRes($admn_no){
            $this->load->model('exam_tabulation/exam_tabulation_model','tabu');
            $sem=$this->getCourseDurationById($this->input->post('course'));
           $branch = $this->getbranchByAdmn_no($admn_no);
           $branch= $branch->branch_id; 
            for($i=1;$i<=$sem; $i++){
                if($i == 10){
                     $q="select a.ysession,a.examtype,a.totcrhr,a.totcrpts,a.gpa,a.ctotcrhr,a.ctotcrpts,a.ogpa,a.remarks,a.passfail from tabulation1 as a where a.adm_no=? and right(a.sem_code,1)='X'   and a.examtype=((select max(a.examtype) as m from tabulation1 as a where a.adm_no=? and right(a.sem_code,1)='X'  )) limit 1";
                     $qu=$this->db->query($q,array($admn_no,$admn_no));
                }else{
                     $q="select a.ysession,a.examtype,a.totcrhr,a.totcrpts,a.gpa,a.ctotcrhr,a.ctotcrpts,a.ogpa,a.remarks,a.passfail from tabulation1 as a where a.adm_no=? and right(a.sem_code,1)=?   and a.examtype=((select max(a.examtype) as m from tabulation1 as a where a.adm_no=? and right(a.sem_code,1)=?  )) limit 1";
                     $qu=$this->db->query($q,array($admn_no,$i,$admn_no,$i));
                }
                if($qu->num_rows() > 0){
                    $r[$i]=$qu->row();
                    if($r[$i]->passfail == 'F' || $r[$i]->passfail == 'f'){
                       $etype= $r[$i]->examtype;
                        $rr=$this->getOtherStuRes($admn_no,$i,$etype); 
                            
                        if($rr){
                              unset($r[$i]);
           
                          
                          $r[$i]->ysession = $rr->session_yr;
                          $r[$i]->examtype = $rr->type; 
                          $r[$i]->totcrhr = $rr->tot_cr_hr;
                          $r[$i]->totcrpts =$rr->tot_cr_pts;
                          $r[$i]->gpa = number_format($rr->tot_cr_pts/$rr->tot_cr_hr,2);
                          $r[$i]->ctotcrhr = 0;
                          $r[$i]->ctotcrpts =0;
                          $r[$i]->ogpa = 0;
                          if($rr->status == 'FAIL'){
                              $r[$i]->remarks = $this->failIn($rr->id);
                              $r[$i]->passfail = 'F';
                          } else{
                          $r[$i]->remarks = 'Pass';
                          $r[$i]->passfail = 'P';
                          }
                        }
                    }
                }else{
                    
                      $rr=$this->tabu->getSubjectsByAdminNo($branch,$i,$admn_no);  
                      if($rr){
                          
                          //$r[$i] = $rr;
                          $tocrpt=0;
                          $tohr=0; $f=0; $s='Fail In ';
                          foreach($rr as $rf){
                             $tocrpt=$tocrpt+$rf->totcrdthr;
                             $tohr = $tohr+$rf->credit_hours;
                            if($rf->grade == 'F'){ $f++ ; $s.= $rf->sub_code." "; }
                            
                            
                          }
                          
                          $r[$i]->ysession = $rr[0]->session_year;
                          $r[$i]->examtype = 'R'; 
                          $r[$i]->totcrhr = $tohr;
                          $r[$i]->totcrpts = $tocrpt;
                          $r[$i]->gpa = number_format($tocrpt/$tohr,2);
                          $r[$i]->ctotcrhr = 0;
                          $r[$i]->ctotcrpts =0;
                          $r[$i]->ogpa = 0;
                          if($f > 0){
                              $r[$i]->remarks = $s;
                              $r[$i]->passfail = 'F';
                          } else{
                          $r[$i]->remarks = 'Pass';
                          $r[$i]->passfail = 'P';
                          }
                          
                      } else{ $r[$i]=  [];}
                }
            }
            return $r;
            
        }
        
        private function getbranchByAdmn_no($id){
           
           $r=$this->db->get_where('stu_academic',array('admn_no'=>$id));
           if($r->num_rows() >0){
               return $r->row();
           }
       }
       
       private function getOtherStuRes($admn_no,$sem,$etype){
           
           $q="select * from final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' and `type`=(select `type` from final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' )" ;
           $r=$this->db->query($q);
            if($r->num_rows() >0){
               $re= $r->row();
               if($re->type == 'O'){
                  $etype++;
                   $re->type=$etype;
               }else if($re->type=='O'){
                   $etype++; $etype++;
                   $re->type=etype;
               }
               return $re;
           }
           return false;
           
       }
        private function failIn($id){
             $r=$this->db->get_where('final_semwise_marks_foil_desc',array('grade'=>'F','foil_id'=>$id));
           if($r->num_rows() >0){
               $fi= $r->result();
               $re='Fail in ';
               foreach($fi as $f){
                   $re.=$f->sub_code." ";
               }
                   return re;
           }
        }
}