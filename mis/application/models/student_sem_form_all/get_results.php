<?php
class Get_results extends CI_Model
{
	var $result = 'result_status';
		
	
	function getSemesterDetailsById($sid){
		
		$q=$this->db->query("select * from (SELECT *,0+right(`resultdata`.`sem_code`,1) as `b` FROM `resultdata` WHERE admn_no ='".$sid."' ORDER BY 0+right(`resultdata`.`sem_code`,1) ASC, `resultdata`.`session` desc ,`resultdata`.`examtype` desc limit 1000) `a` group by `a`.`sem_code` order by b asc");
		if($q->num_rows() > 0){
			 return $q->result();
		}
	}
	function newSemDetail($sid,$course)
	{
		$this->load->model('student_grade_sheet/student_grade_model', '', TRUE);
		$q=$this->db->query("SELECT 0+right(`tabulation1`.`sem_code`,1) as `sem`,ROUND(`gpa`,2) as gpa,`passfail`,`remarks` FROM `tabulation1` WHERE       `adm_no` LIKE '".$sid."' GROUP BY `sem_code`,`passfail` order by `gpa`, `passfail` ")->result();
		$res=array();
             foreach($q as $qq)
             {

             	  $res[(int) ($qq->sem)]['gpa']=$qq->gpa;
             	  $res[(int) ($qq->sem)]['passfail']=$qq->passfail;
             	  $res[(int) ($qq->sem)]['remarks']=$qq->remarks;
             }
         $q=$this->db->query(" SELECT `semester`,ROUND(`gpa`,2) AS `gpa`,`status`,session_yr,session,dept,course,branch,type,semester FROM `final_semwise_marks_foil` WHERE
                               `admn_no` LIKE '".$sid."' AND (`course` LIKE '".$course."' or `course` LIKE 'COMM') ORDER BY `status` ")->result();
            foreach($q as $qq)
             {
			 // added to have checking validation whether result declration done then only result  to be shown  wherever rq.	 
				 switch($qq->type){
				     case 'R':(($qq->course=='JRF' && $qq->sesssion='Winter')?$type='jrf_spl':$type='regular'); break;
					 case 'S':$type='spl'; break;
					 case 'O':$type='other'; break;
					 case 'E':$type='espl'; break;
					 case 'J':$type='jrf'; break;
				 }
			 $rdec = $this->student_grade_model->get_result_declaration($qq->dept,$qq->course,($qq->course=='COMM'?'comm':$qq->branch), ($qq->course=='JRF'?'-1':$qq->semester),
			 ($type), $qq->session_yr, $qq->session, 
			 ($sec=($qq->course=='COMM'|| $qq->course=='comm')? $qq->branch : null ));
			  if(!empty($rdec)){ 
                        $chk_partial=$chk_partial=$this->student_grade_model->check_partial_stu($rdec->id,$sid);
                      //print_r($data['chk_partial']);die();
                    $check =true;
                    if($chk_partial){
                       if ($chk_partial->status != 'D'){
                              $check = false;
                          }
                      }
                        }
                        if (!empty($rdec)&& $check ){
				//if($qq->session_yr<>'2016-2017') {   // fixed code
				
             	  $res[$qq->semester]['gpa']=$qq->gpa;
             	  $res[$qq->semester]['passfail']=$qq->status[0];
             	  $res[$qq->semester]['remarks']=$qq->status;
				}
             }
		return $res;
	}
	
		/*function getSemesterDetails($sid,$semid){
				if($this->db->table_exists($this->result)){
				$q=$this->db->get_where($this->result,array('admission_no'=>$sid,'semster'=>$semid));
				if($q->num_rows() >0){
					return $q->result;
					}
				}
				return false;
			}
				/////Get GPA PER SEMSTER parameter $sid is Student Id and $semid is Semester id
		
		/*function getGPAperSemester($sid,$semid){
			if($this->db->table_exists($this->result)){
			$q=$this->db->query("select credit_hr,(sessional_m + theory_m + practical_m) as total from ".$this->result."  where admission_no='".$sid."' and semster='".$semid."'");
				if($q->num_rows() > 0){
					$q=$q->result();
					
				$i=0;
				$s=1;
				$chr=1;
				//print_r($q); die();
			foreach($q as $r){
					  $tm=$this->getMarks($this->get_numeric($r->total));
					   $j=$this->get_numeric($r->credit_hr);
					  $s = ($j*$tm*$s);
					    $chr = ($j * $chr);
					
				}	
				return $s/$chr;
				}else{
					return false;
					}
			}
			return false;
		}
		
		function getGPAperSemester($sid,$semid){
				//echo $sid;
				if($this->db->table_exists($this->result)){
				$q=$this->db->select('subject_id')->get_where($this->result,array('admission_no'=>$sid,'semster'=>$semid));
				if($q->num_rows() > 0){
						$d=$q->result_array();	 
						return " -: ".$d[0]['subject_id']; 
						
					}
				}
				
			}
		
		
	
	private function getMarks($n){
			if($n>=91 and $n<=100){
					$d = 10;
			}else if($n>=81 and $n<=90){
					$d = 9;
			}else if($n>=71 and $n<=80){
					$d = 8;
			}else if($n>=61 and $n<=70){
					$d = 7;
			}else if($n>=51 and $n<=60){
					$d = 6;
			}else if($n>=41 and $n<=50){
					$d = 5;
			}else if($n>=35 and $n<=40){
					$d = 4;
			}else{
				$d=0;	
			}
			return $d;
	}
	private function get_numeric($val) {
			if (is_numeric($val)) {
				return $val + 0;
		  }
		  	  return 0;
	} 
	///////////////////////////////////////////////////////////////////////////////
	
	*/
}

?>