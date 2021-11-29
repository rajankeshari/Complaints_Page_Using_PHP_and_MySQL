<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Rank_modal extends CI_Model {

    private $course = 'courses';

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getCourseDurationById($id) {

        $q = $this->db->get_where($this->course, array('id' => $id));
        if ($q->row()->duration)
            $sem = ($q->row()->duration * 2);
        return (int) $sem;
    }

    function getStuList() {
        $sem = $this->getCourseDurationById($this->input->post('course'));
        $r = array();
        $s = array();
        $p = array();
        $qu = "select A.admn_no,name from ((select a.id as admn_no,concat_ws(' ',a.first_name,a.middle_name,a.last_name ) as name from user_details a join reg_regular_form b on a.id=b.admn_no where a.dept_id='" . $this->input->post('dept') . "' and b.course_id='" . $this->input->post('course') . "' ANd b.semester='$sem' and b.branch_id='" . $this->input->post('branch') . "' )
				union
				(select a.id as admn_no,concat_ws(' ',a.first_name,a.middle_name,a.last_name ) as name from user_details a join reg_exam_rc_form b on a.id=b.admn_no where a.dept_id='" . $this->input->post('dept') . "' and b.course_id='" . $this->input->post('course') . "' ANd b.semester like '%" . $sem . "%' and b.branch_id='" . $this->input->post('branch') . "' ) )A order by A.admn_no
		   ";
        //  echo $qu; 
        $q = $this->db->query($qu);

        if ($q->num_rows() > 0)
            return $q->result();


        return false;
    }

	function getIncomming_minor($branch, $sem, $sess_yr , $dept,$admn_no=null ){
		 if ($admn_no != null) {
            if (substr_count($admn_no, ',') > 0) {
                $admn_no = "'" . implode("','", explode(',', $admn_no)) . "'";
                $replacer1 = "  and hf2.admn_no in(" . $admn_no . ") ";
                  $secure_array = array('1', '1', 'Y', 5, ($dept), $branch,($sess_yr), $sem, '1' );
            } else {
                $replacer1 = " and hf2.admn_no=? ";
                  $secure_array = array('1', '1', 'Y', 5, ($dept), $branch,$admn_no,($sess_yr), $sem, '1' );
            }
        } else {
            $replacer1 = "";
             $secure_array = array('1', '1', 'Y', 5, ($dept ), $branch, ($sess_yr ), $sem, '1');
        }
            
		$sql="(select  A.admn_no,concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name) as name , A.branch_id , A.semester,
				  A.minor_agg_id,A.minor,A.minor_hod_status,st.branch_id as own_branch,st.course_id as own_course,dpt.name as dept_name ,A.dept_id
                 from 
                ( select hf2.minor_hod_status,hf2.minor,hm_minor_details.minor_agg_id, hf2.semester ,hf2.admn_no,hf2.dept_id,hm_minor_details.dept_id as from_dept,branch_id from hm_form hf2  
                    inner join hm_minor_details on hm_minor_details.form_id=hf2.form_id 
                          and hm_minor_details.offered=? and hf2.minor=? and hf2.minor_hod_status=?  and hf2.semester>=?  
								  and hm_minor_details.dept_id=?  and hm_minor_details.branch_id=?  ".$replacer1."
								   group by hf2.admn_no
                    )A 
					inner join  reg_regular_form rgf on rgf.admn_no=A.admn_no and rgf.session_year=? and rgf.semester=?  and rgf.acad_status=? 	
					 inner join user_details ud on ud.id=A.admn_no                                                                
				 
					 inner join stu_academic st on st.admn_no=A.admn_no
					 left join departments dpt on dpt.id =A.dept_id)";
		  $q = $this->db->query($sql, $secure_array );

        if ($q->num_rows() > 0)
            return $q->result();
		else {
			$sql="(select  A.admn_no,concat_ws(' ',ud.first_name,ud.middle_name,ud.last_name) as name , A.branch_id , A.semester,
				  A.minor_agg_id,A.minor,A.minor_hod_status,st.branch_id as own_branch,st.course_id as own_course,dpt.name as dept_name ,A.dept_id
                 from 
                ( select hf2.minor_hod_status,hf2.minor,alumni_hm_minor_details.minor_agg_id, hf2.semester ,hf2.admn_no,hf2.dept_id,alumni_hm_minor_details.dept_id as from_dept,branch_id from alumni_hm_form hf2  
                    inner join alumni_hm_minor_details on alumni_hm_minor_details.form_id=hf2.form_id 
                          and alumni_hm_minor_details.offered=? and hf2.minor=? and hf2.minor_hod_status=?  and hf2.semester>=?  
								  and alumni_hm_minor_details.dept_id=?  and alumni_hm_minor_details.branch_id=?  ".$replacer1."
								   group by hf2.admn_no
                    )A 
					inner join  alumni_reg_regular_form rgf on rgf.admn_no=A.admn_no and rgf.session_year=? and rgf.semester=?  and rgf.acad_status=? 	
					 inner join alumni_user_details ud on ud.id=A.admn_no                                                                
				 
					 inner join alumni_stu_academic st on st.admn_no=A.admn_no
					 left join alumni_departments dpt on dpt.id =A.dept_id)";
					  $q = $this->db->query($sql, $secure_array );
			if ($q->num_rows() > 0)
            return $q->result(); else return false;
			
		}

	}
	
	
	
		function getLatestSem_minor($branch,$sess_yr , $dept,$admn_no=null ){
		 if ($admn_no != null) {
            if (substr_count($admn_no, ',') > 0) {
                $admn_no = "'" . implode("','", explode(',', $admn_no)) . "'";
                $replacer1 = "  and hf2.admn_no in(" . $admn_no . ") ";
                  $secure_array = array('1', '1', 'Y', 5, ($dept), $branch,($sess_yr), '1' );
            } else {
                $replacer1 = " and hf2.admn_no=? ";
                  $secure_array = array('1', '1', 'Y', 5, ($dept), $branch,$admn_no,($sess_yr),  '1' );
            }
        } else {
            $replacer1 = "";
             $secure_array = array('1', '1', 'Y', 5, ($dept ), $branch, ($sess_yr ),  '1');
        }
            
		$sql="(select  rgf.semester
                 from 
                ( select hf2.minor_hod_status,hf2.minor,hm_minor_details.minor_agg_id, hf2.semester ,hf2.admn_no,hf2.dept_id,hm_minor_details.dept_id as from_dept,branch_id from hm_form hf2  
                    inner join hm_minor_details on hm_minor_details.form_id=hf2.form_id 
                          and hm_minor_details.offered=? and hf2.minor=? and hf2.minor_hod_status=?  and hf2.semester>=?  
								  and hm_minor_details.dept_id=?  and hm_minor_details.branch_id=?  ".$replacer1."
                    )A 
					inner join  reg_regular_form rgf on rgf.admn_no=A.admn_no and rgf.session_year=? and rgf.acad_status=? 	 order by (0+rgf.semester) desc limit 1
				
				 
				)";
		  $q = $this->db->query($sql, $secure_array );

        if ($q->num_rows() > 0)
            return $q->row();
		else {
			$sql="(select  rgf.semester
                 from 
                ( select hf2.minor_hod_status,hf2.minor,alumni_hm_minor_details.minor_agg_id, hf2.semester ,hf2.admn_no,hf2.dept_id,alumni_hm_minor_details.dept_id as from_dept,branch_id from alumni_hm_form hf2  
                    inner join alumni_hm_minor_details on alumni_hm_minor_details.form_id=hf2.form_id 
                          and alumni_hm_minor_details.offered=? and hf2.minor=? and hf2.minor_hod_status=?  and hf2.semester>=?  
								  and alumni_hm_minor_details.dept_id=?  and alumni_hm_minor_details.branch_id=?  ".$replacer1."
                    )A 
					inner join  alumni_reg_regular_form rgf on rgf.admn_no=A.admn_no and rgf.session_year=? and rgf.acad_status=? 	 order by (0+rgf.semester) desc limit 1
					)";
					  $q = $this->db->query($sql, $secure_array );
			if ($q->num_rows() > 0)
            return $q->row(); else return false;
			
		}

	}
	
	
    ////RANK LIST MODUELS MAIN FUNCTIOM ////

    function GetStuSatus($sy, $ssem, $esem, $d, $c, $b, $exam_sp=null, $adm_no = null) {
        
		
	if($exam_sp==null){	
		if ($adm_no != null && $adm_no != '') {
            $data = array('session_year' => $sy, 'admn_no' => $adm_no, 'acad_status' => '1', 'semester' => $esem);
        } else {
            //$data = array('session_year'=>$sy,'course_id'=>$c,'branch_id'=>$b,'acad_status'=>'1','semester'=>$esem);
            if ($b == 'comm')
                $data = array('session_year' => $sy, 'course_aggr_id like' => '%comm%', 'acad_status' => '1', 'semester' => $esem);
            else
                $data = array('session_year' => $sy, 'course_id' => $c, 'branch_id' => $b, 'acad_status' => '1', 'semester' => $esem);
        }

        $this->db->select('admn_no,concat_ws(" ",user_details.first_name,user_details.middle_name,user_details.last_name ) as name,user_details.dept_id,reg_regular_form.course_id,reg_regular_form.branch_id', false);
        $this->db->order_by('admn_no');
		$this->db->group_by('admn_no');
        $this->db->join('user_details', 'reg_regular_form.admn_no=user_details.id');
        $q = $this->db->get_where('reg_regular_form', $data);

      // echo $this->db->last_query(); die();
        if ($q->num_rows() > 0)
            return $q->result();
        else{
			
		$this->db->select('admn_no,concat_ws(" ",alumni_user_details.first_name,alumni_user_details.middle_name,alumni_user_details.last_name ) as name,alumni_user_details.dept_id,alumni_reg_regular_form.course_id,
		alumni_reg_regular_form.branch_id', false);
        $this->db->order_by('admn_no');
        $this->db->join('alumni_user_details', 'alumni_reg_regular_form.admn_no=alumni_user_details.id');
        $q = $this->db->get_where('alumni_reg_regular_form', $data);	
		
		 // echo $this->db->last_query(); die();
		   if ($q->num_rows() > 0)
            return $q->result(); else return false;
		} 
	}
	else {
			if ($adm_no != null && $adm_no != '') {
            $data = array('session_year' => $sy, 'admn_no' => $adm_no, 'acad_status' => '1','semester' => $esem );
        } else {            
            if ($b == 'comm')
                $data = array('session'=>$exam_sp,  'session_year' => $sy, 'course_aggr_id like' => '%comm%', 'acad_status' => '1','semester' => $esem);
            else
                $data = array('session'=>$exam_sp, 'session_year' => $sy, 'course_id' => $c, 'branch_id' => $b, 'acad_status' => '1','semester' => $esem);
        }

        $this->db->select('admn_no,concat_ws(" ",user_details.first_name,user_details.middle_name,user_details.last_name ) as name,user_details.dept_id,reg_regular_form.course_id,reg_regular_form.branch_id', false);
        $this->db->order_by('admn_no');
		$this->db->group_by('admn_no');
        $this->db->join('user_details', 'reg_regular_form.admn_no=user_details.id');
        $q = $this->db->get_where('reg_regular_form', $data);

      // echo $this->db->last_query(); die();
        if ($q->num_rows() > 0)
            return $q->result();
        else{
			
		$this->db->select('admn_no,concat_ws(" ",alumni_user_details.first_name,alumni_user_details.middle_name,alumni_user_details.last_name ) as name,alumni_user_details.dept_id,alumni_reg_regular_form.course_id,
		alumni_reg_regular_form.branch_id', false);
        $this->db->order_by('admn_no');
        $this->db->join('alumni_user_details', 'alumni_reg_regular_form.admn_no=alumni_user_details.id');
        $q = $this->db->get_where('alumni_reg_regular_form', $data);	
		
		 // echo $this->db->last_query(); die();
		   if ($q->num_rows() > 0)
            return $q->result(); else return false;
		} 
		
		
	}
	
	
        
    }
	
	  function getLatestSem($sy,  $d, $c, $b, $adm_no = null) {
        if ($adm_no != null && $adm_no != '') {
            $data = array('session_year' => $sy, 'admn_no' => $adm_no, 'acad_status' => '1');
        } else {
            
            if ($b == 'comm')
                $data = array('session_year' => $sy, 'course_aggr_id like' => 'comm%', 'acad_status' => '1');
            else
                $data = array('session_year' => $sy, 'course_id' => $c, 'branch_id' => $b, 'acad_status' => '1');
        }

        $this->db->select('reg_regular_form.semester', false);
        $this->db->order_by('(0+semester)','desc');        
        $q = $this->db->get_where('reg_regular_form', $data);

        //echo $this->db->last_query(); die();
        if ($q->num_rows() > 0)
            return $q->row();
        else{
			
	   $this->db->select('reg_regular_form.semester', false);
           $this->db->order_by('(0+semester)','desc');        
        $q = $this->db->get_where('reg_regular_form', $data);
		   if ($q->num_rows() > 0)
            return $q->row();
		} 

        return false;
    }
	
	
/*
    //==================other student=================
    function GetStuSatus_other($sy, $ssem, $esem, $d, $c, $b, $adm_no = null) {
		 $sem_str= " and FIND_IN_SET('" . $esem . "' ,rg.semester) ";
        if ($adm_no != null && $adm_no != '') {
            $data = array('session_year' => $sy, 'admn_no' => $adm_no, 'acad_status' => '1', 'semester' => $esem);
        } else {
            $data = array('session_year' => $sy, 'course_id' => $c, 'branch_id' => $b, 'acad_status' => '1', 'semester' => $esem);
        }

        $this->db->select('admn_no,concat_ws(" ",user_details.first_name,user_details.middle_name,user_details.last_name ) as name,user_details.dept_id,reg_other_form.course_id,reg_other_form.branch_id', false);
        $this->db->order_by('admn_no');
        $this->db->join('user_details', 'reg_other_form.admn_no=user_details.id');
        $q = $this->db->get_where('reg_other_form', $data);

        //echo $this->db->last_query(); die();
        if ($q->num_rows() > 0)
            return $q->result();
		else{
			  $this->db->select('admn_no,concat_ws(" ",alumni_details.first_name,alumni_details.middle_name,alumni_details.last_name ) as name,alumni_details.dept_id,alumni_reg_other_form.course_id,alumni_reg_other_form.branch_id', false);
        $this->db->order_by('admn_no');
        $this->db->join('alumni_details', 'alumni_reg_other_form.admn_no=alumni_details.id');
        $q = $this->db->get_where('alumni_reg_other_form', $data);
		  if ($q->num_rows() > 0)
            return $q->result();
		}


        return false;
    }

	
	   //==================other student=================
	   
	   */
    function GetStuSatus_other($sy, $ssem, $esem, $d, $c, $b, $adm_no = null) {
		 $sem_str= " and FIND_IN_SET('" . $esem . "' ,reg_other_form.semester) ";
        if ($adm_no != null && $adm_no != '') {
			$admn_str= " and  reg_other_form.admn_no= ?";
            $data = array('session_year' => $sy,  'acad_status' => '1','admn_no' => $adm_no);
			$str="";
			
        } else {
			$str=" and  reg_other_form.course_id=?  and  reg_other_form.branch_id = ? ";
			$admn_str= "";
            $data = array('session_year' => $sy, 'course_id' => $c, 'branch_id' => $b, 'acad_status' => '1');
        }
        
		$sql=" select reg_other_form.admn_no,concat_ws(' ',user_details.first_name,user_details.middle_name,user_details.last_name ) as name,user_details.dept_id,reg_other_form.course_id,reg_other_form.branch_id 
		      from reg_other_form join user_details on reg_other_form.admn_no=user_details.id and 
			  reg_other_form.session_year= ?  $str and  reg_other_form.acad_status = ? $admn_str    $sem_str
			   order  by reg_other_form.admn_no
		       
		";
		
		  $query = $this->db->query($sql, $data);
        //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
            return $query->result();
        
            
		else{
			 $sem_str= " and FIND_IN_SET('" . $esem . "' ,alumni_reg_other_form.semester) ";
			 if ($adm_no != null && $adm_no != '') {
			$admn_str= " and  alumni_reg_other_form.admn_no= ?";
            $data = array('session_year' => $sy,  'acad_status' => '1','admn_no' => $adm_no);
			$str="";
			
        } else {
			$str=" and  reg_other_form.course_id=?  and  reg_other_form.branch_id = ? ";
				$admn_str= "";
            $data = array('session_year' => $sy, 'course_id' => $c, 'branch_id' => $b, 'acad_status' => '1', 'semester' => $esem);
        }
			
			 $sql=" select alumni_reg_other_form.admn_no,concat_ws(' ',alumni_user_details.first_name,alumni_user_details.middle_name,alumni_user_details.last_name ) as name,alumni_user_details.dept_id,alumni_reg_other_form.course_id,alumni_reg_other_form.branch_id 
		      from alumni_reg_other_form join alumni_user_details on alumni_reg_other_form.admn_no=alumni_user_details.id and 
			  alumni_reg_other_form.session_year= ?  $str and  alumni_reg_other_form.acad_status = ? $admn_str   $sem_str
			  order  by alumni_reg_other_form.admn_no
		       
		";
		
		  $query = $this->db->query($sql, $data);
		  //echo $this->db->last_query(); die();
		  if ($query->num_rows() > 0) return $query->result();else return false;
		}


       
    }
	
	
	
	
	
    //=================other studnet end
	
	
	 function GetStuSatus_exam_form($sy, $ssem, $esem, $d, $c, $b, $adm_no = null) {
		 $sem_str= " and FIND_IN_SET('" . $esem . "' ,reg_exam_rc_form.semester) ";
        if ($adm_no != null && $adm_no != '') {
			$admn_str= " and  reg_exam_rc_form.admn_no= ?";
            
			  $data = array('session_year' => $sy,  'acad_status' => '1','admn_no' => $adm_no);
			  $str="";
			
        } else {
			$admn_str= "";
			  $str=" and  reg_exam_rc_form.course_id=?  and  reg_exam_rc_form.branch_id => ? ";
            $data = array('session_year' => $sy, 'course_id' => $c, 'branch_id' => $b, 'acad_status' => '1');
        }
        
		$sql="select reg_exam_rc_form.admn_no,concat_ws(' ',user_details.first_name,user_details.middle_name,user_details.last_name ) as name,user_details.dept_id,reg_exam_rc_form.course_id,reg_exam_rc_form.branch_id 
		      from reg_exam_rc_form join user_details on reg_exam_rc_form.admn_no=user_details.id and 
			  reg_exam_rc_form.session_year= ? $str and  reg_exam_rc_form.acad_status = ? $admn_str    $sem_str
			  order  by reg_exam_rc_form.admn_no
		       
		";
		
		  $query = $this->db->query($sql, $data);
      
        if ($query->num_rows() > 0)
            return $query->result();
        
            
		else{
			 $sem_str= " and FIND_IN_SET('" . $esem . "' ,alumni_reg_exam_rc_form.semester) ";
			 if ($adm_no != null && $adm_no != '') {
			$admn_str= " and  alumni_reg_exam_rc_form.admn_no= ?";
            $data = array('session_year' => $sy,  'acad_status' => '1','admn_no' => $adm_no);
			  $str="";
        } else {
			$admn_str= "";
			  $str=" and  alumni_reg_exam_rc_form.course_id=?  and  alumni_reg_exam_rc_form.branch_id => ? ";
            $data = array('session_year' => $sy, 'course_id' => $c, 'branch_id' => $b, 'acad_status' => '1');
        }
			
			 $sql=" select alumni_reg_exam_rc_form.admn_no,concat_ws(' ',alumni_user_details.first_name,alumni_user_details.middle_name,alumni_user_details.last_name ) as name,alumni_user_details.dept_id,alumni_reg_exam_rc_form.course_id,alumni_reg_exam_rc_form.branch_id 
		      from alumni_reg_exam_rc_form join alumni_user_details on alumni_reg_exam_rc_form.admn_no=alumni_user_details.id and 
			  alumni_reg_exam_rc_form.session_year= ? $str and  alumni_reg_exam_rc_form.acad_status = ? $admn_str   $sem_str
			  order  by alumni_reg_exam_rc_form.admn_no
		       
		";
		
		  $query = $this->db->query($sql, $data);
		  //  echo $this->db->last_query(); die();
		  if ($query->num_rows() > 0) return $query->result();else return false;
		}


        
    }
	
	
	
	//==================summer student=================
    function GetStuSatus_summer($sy, $ssem, $esem, $d, $c, $b, $adm_no = null) {
      

        $this->db->select('reg_summer_form.admn_no,concat_ws(" ",user_details.first_name,user_details.middle_name,user_details.last_name ) as name,user_details.dept_id,reg_summer_form.course_id,reg_summer_form.branch_id', false);
     
		$this->db->from('reg_summer_form'); 
        $this->db->join('user_details', 'reg_summer_form.admn_no=user_details.id');		
		
		if ($adm_no != null && $adm_no != ''){
			$this->db->where('reg_summer_form.session_year',$sy);
			$this->db->where('reg_summer_form.admn_no',$adm_no);
			$this->db->where('reg_summer_form.acad_status','1');
			$where = "(reg_summer_form.semester='".$esem."' or reg_summer_form.semester='".($esem%2==0?$esem-1:$esem+1)."')";
			$this->db->where($where);
            
			
		}
		else{
        $this->db->where('reg_summer_form.session_year',$sy);
        $this->db->where('reg_summer_form.course_id',$c);
        $this->db->where('reg_summer_form.branch_id',$b);
		$this->db->where('reg_summer_form.acad_status','1');
        $where = "(reg_summer_form.semester='".$esem."' or reg_summer_form.semester='".($esem%2==0?$esem-1:$esem+1)."')";
			$this->db->where($where);
		}
		$this->db->order_by('reg_summer_form.admn_no');
		$query = $this->db->get(); 
    if($query->num_rows() != 0)
    {
        return $query->result();
    }
    else
    {
          $this->db->select('alumni_reg_summer_form.admn_no,concat_ws(" ",alumni_user_details.first_name,alumni_user_details.middle_name,alumni_user_details.last_name ) as name,
		  alumni_user_details.dept_id,alumni_reg_summer_form.course_id,alumni_reg_summer_form.branch_id', false);
     
		$this->db->from('alumni_reg_summer_form'); 
        $this->db->join('alumni_user_details', 'alumni_reg_summer_form.admn_no=alumni_user_details.id');
		
		if ($adm_no != null && $adm_no != ''){
			$this->db->where('alumni_reg_summer_form.session_year',$sy);
			$this->db->where('alumni_reg_summer_form.admn_no',$adm_no);
			$this->db->where('alumni_reg_summer_form.acad_status','1');
		   $where = "(alumni_reg_summer_form.semester='".$esem."' or alumni_reg_summer_form.semester='".($esem%2==0?$esem-1:$esem+1)."')";
			$this->db->where($where);
		}
		else{
        $this->db->where('alumni_reg_summer_form.session_year',$sy);
        $this->db->where('alumni_reg_summer_form.course_id',$c);
        $this->db->where('alumni_reg_summer_form.branch_id',$b);
		$this->db->where('alumni_reg_summer_form.acad_status','1');
        $where = "(alumni_reg_summer_form.semester='".$esem."' or alumni_reg_summer_form.semester='".($esem%2==0?$esem-1:$esem+1)."')";
			$this->db->where($where);
		}
		$this->db->order_by('alumni_reg_summer_form.admn_no');
		$query = $this->db->get(); 
		// echo $this->db->last_query();
		if($query->num_rows() != 0)
    {
        return $query->result();
    }else return false;
    }
	 	
		
		

   
    }

    //=================other studnet end
	
	
	
	
	
	

	
	
    ////RANK LIST MODUELS MAIN FUNCTIOM ////

    function getTabRes($admn_no, $Hstatus = null, $Mstatus = null) {

        $this->load->model('exam_tabulation/exam_tabulation_model', 'tabu');
        $sem = $this->getCourseDurationById($this->input->post('course'));
        $branch = $this->getbranchByAdmn_no($admn_no);
        $branch = $branch->branch_id;
        for ($i = 1; $i <= $sem; $i++) {
            if ($i == 10) {
                $q = "select a.ysession,a.examtype,a.totcrhr,a.totcrpts,a.gpa,a.ctotcrhr,a.ctotcrpts,a.ogpa,a.remarks,a.passfail from tabulation1 as a where a.adm_no=? and right(a.sem_code,1)='X'   and a.examtype=((select max(a.examtype) as m from tabulation1 as a where a.adm_no=? and right(a.sem_code,1)='X'  )) limit 1";
                $qu = $this->db->query($q, array($admn_no, $admn_no));
            } else {

                $q = "SELECT a.ysession,a.examtype,a.totcrhr,a.totcrpts,a.gpa,a.ctotcrhr,a.ctotcrpts,a.ogpa,a.remarks,a.passfail,a.sem_code FROM tabulation1 AS a WHERE a.adm_no=? AND RIGHT(a.sem_code,1)=? and a.sem_code not like 'PREP%' order by a.ysession desc,a.examtype desc, a.wsms desc limit 1";

                $qu = $this->db->query($q, array($admn_no, $i));

                //echo $this->db->last_query(); die();// if($adm_no == '2013MT0229') die();
            }
            if ($qu->num_rows() > 0) {
                $r[$i] = $qu->row();
                if ($r[$i]->passfail == 'F' || $r[$i]->passfail == 'f') {
                    $etype = $r[$i]->examtype;
                    $rr = $this->getOtherStuRes($admn_no, $i, $etype);
                    //  echo $this->db->last_query(); die();

                    if ($rr) {
                        unset($r[$i]);

                        //   echo  $rr->session_yr; 

                        $r[$i]->ysession = $rr->session_yr;
                        $r[$i]->examtype = $rr->type;
                        $r[$i]->totcrhr = $rr->tot_cr_hr;
                        $r[$i]->totcrpts = $rr->tot_cr_pts;
                        $r[$i]->gpa = number_format($rr->tot_cr_pts / $rr->tot_cr_hr, 2);
                        $r[$i]->ctotcrhr = 0;
                        $r[$i]->ctotcrpts = 0;
                        $r[$i]->ogpa = 0;
                        if ($rr->status == 'FAIL') {
                            $r[$i]->remarks = $this->failIn($rr->id);
                            $r[$i]->passfail = 'F';
                        } else {
                            $r[$i]->remarks = 'Pass';
                            $r[$i]->passfail = 'P';
                        }
                    }
                }
            } else {

                //$rr=$this->tabu->getSubjectsByAdminNo($branch,$i,$admn_no);  
                $rr = $this->tabu->getSubjectsByAdminNo_With_without_hons($branch, $i, $admn_no, $Hstatus, $session_year = false);
                if ($rr) {

                    //$r[$i] = $rr;
                    $tocrpt = 0;
                    $tohr = 0;
                    $f = 0;
                    $s = 'Fail In ';
                    foreach ($rr as $rf) {
                        $tocrpt = $tocrpt + $rf->totcrdthr;
                        $tohr = $tohr + $rf->credit_hours;
                        if ($rf->grade == 'F') {
                            $f++;
                            $s.= $rf->sub_code . " ";
                        }
                    }

                    $r[$i]->ysession = $rr[0]->session_year;
                    $r[$i]->examtype = 'R';
                    $r[$i]->totcrhr = $tohr;
                    $r[$i]->totcrpts = $tocrpt;
                    $r[$i]->gpa = number_format($tocrpt / $tohr, 2);
                    $r[$i]->ctotcrhr = 0;
                    $r[$i]->ctotcrpts = 0;
                    $r[$i]->ogpa = 0;
                    if ($f > 0) {
                        $r[$i]->remarks = $s;
                        $r[$i]->passfail = 'F';
                        $rr = $this->getOtherStuRes($admn_no, $i, $etype);
                        if ($rr) {
                            unset($r[$i]);


                            $r[$i]->ysession = $rr->session_yr;
                            $r[$i]->examtype = $rr->type;
                            $r[$i]->totcrhr = $rr->tot_cr_hr;
                            $r[$i]->totcrpts = $rr->tot_cr_pts;
                            $r[$i]->gpa = number_format($rr->tot_cr_pts / $rr->tot_cr_hr, 2);
                            $r[$i]->ctotcrhr = 0;
                            $r[$i]->ctotcrpts = 0;
                            $r[$i]->ogpa = 0;
                            if ($rr->status == 'FAIL') {
                                $r[$i]->remarks = $this->failIn($rr->id);
                                $r[$i]->passfail = 'F';
                            } else {
                                $r[$i]->remarks = 'Pass';
                                $r[$i]->passfail = 'P';
                            }
                        }
                    } else {
                        $r[$i]->remarks = 'Pass';
                        $r[$i]->passfail = 'P';
                    }
                } else {
                    $r[$i] = [];
                }
            }
        }
        return $r;
    }

    function getStuListSemWise($sem, $sy, $dept, $course, $branch) {


        $qu = "select A.admn_no,name from ((select a.id as admn_no,concat_ws(' ',a.first_name,a.middle_name,a.last_name ) as name from user_details a join reg_regular_form b on a.id=b.admn_no where a.dept_id='" . $dept . "' and b.course_id='" . $course . "' ANd b.semester='$sem' and b.branch_id='" . $branch . "' and b.session_year='" . $sy . "' )
				union
				(select a.id as admn_no,concat_ws(' ',a.first_name,a.middle_name,a.last_name ) as name from user_details a join reg_exam_rc_form b on a.id=b.admn_no where a.dept_id='" . $dept . "' and b.course_id='" . $course . "' ANd b.semester like '%" . $sem . "%' and b.branch_id='" . $branch . "'  and b.session_year='" . $sy . "' ) 
				union
				(select a.id as admn_no,concat_ws(' ',a.first_name,a.middle_name,a.last_name ) as name from user_details a join reg_other_form b on a.id=b.admn_no where a.dept_id='" . $dept . "' and b.course_id='" . $course . "' ANd b.semester like '%" . $sem . "%' and b.branch_id='" . $branch . "'  and b.session_year='" . $sy . "' ) )A order by A.admn_no
		   ";
        //echo $qu; 
        $q = $this->db->query($qu);

        if ($q->num_rows() > 0)
            return $q->result();


        return false;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




/*modified by @rituraj @30.5.18 to get rid of summer/other result's errors. this Function is important because its  shared by tabulation ,
 student gradesheet performance,rank,placement modules for ogpa calculation*/

 function getTabResSemesterRange($admn_no, $startSem, $endsem, $sy, $dept, $course) {
           //if(strtoupper($admn_no)=='2012JE0606') {echo $admn_no.','. $startSem.','. $endsem.','. $sy.','. $dept.','. $course; die();}
        $_POST['session_year'] = $sy;
        $_POST['dept'] = $dept;
        $_POST['course'] = $course;
        $this->load->model('exam_tabulation/exam_tabulation_model', 'tabu');
        //$sem=$this->getCourseDurationById($this->input->post('course'));
        $branch = $this->getbranchByAdmn_no($admn_no);
        $branch = $branch->branch_id;
        if ($startSem > $endsem) { //echo "the Range of Semester is not Correct.";
            return 0;
        }
        $s = 'Fail In ';
        for ($i = $startSem; $i <= $endsem; $i++) 
		{         
            $r[$i] = (object) array();
			
			// added rituraj					
            if ($i == 10) {
                $q = "SELECT a.ysession,a.examtype,a.totcrhr,a.totcrpts,a.gpa,a.ctotcrhr,a.ctotcrpts,a.ogpa,a.remarks,a.passfail,a.sem_code FROM tabulation1 AS a WHERE a.adm_no=? AND RIGHT(a.sem_code,1)='X' and a.sem_code not like 'PREP%' order by a.ysession desc,a.examtype desc, a.wsms desc limit 1";
                $qu = $this->db->query($q, array($admn_no, $admn_no));
		        $row = $qu->row(); 
				//if (isset($row)) 
						if($qu->num_rows()>0)
					$etype = $row->examtype;// added rituraj
                  else
				  {
					     $q = "SELECT a.ysession,a.examtype,a.totcrhr,a.totcrpts,a.gpa,a.ctotcrhr,a.ctotcrpts,a.ogpa,a.remarks,a.passfail,a.sem_code FROM alumni_tabulation1 AS a WHERE a.adm_no=? AND RIGHT(a.sem_code,1)='X' and a.sem_code not like 'PREP%' order by a.ysession desc,a.examtype desc, a.wsms desc limit 1";
                $qu = $this->db->query($q, array($admn_no, $admn_no));
		        $row = $qu->row(); 
				if (isset($row)) 
					$etype = $row->examtype;// added rituraj
				  }
				  
				  $mis_not_found=$qu->num_rows();
				
			  
            }else{
               $found_from_mis=$this->tabu->getStudentStatusFromMIS_param ($admn_no,$i);              
               if($found_from_mis==0){
				 
                $q = "SELECT a.ysession,a.examtype,a.totcrhr,a.totcrpts,a.gpa,a.ctotcrhr,a.ctotcrpts,a.ogpa,a.remarks,a.passfail,a.sem_code FROM tabulation1 AS a WHERE a.adm_no=? AND RIGHT(a.sem_code,1)=? and a.sem_code not like 'PREP%' order by a.ysession desc,a.examtype desc, a.wsms desc limit 1";
                $qu = $this->db->query($q, array($admn_no, $i));
					$row = $qu->row();
					//if (isset($row))
						if($qu->num_rows()>0)
						$etype = $row->examtype;// added rituraj
					else{
						//echo'getStudentStatusFromMIS_param';die();
						    $q = "SELECT a.ysession,a.examtype,a.totcrhr,a.totcrpts,a.gpa,a.ctotcrhr,a.ctotcrpts,a.ogpa,a.remarks,a.passfail,a.sem_code FROM alumni_tabulation1 AS a WHERE a.adm_no=? AND RIGHT(a.sem_code,1)=? and a.sem_code not like 'PREP%' order by a.ysession desc,a.examtype desc, a.wsms desc limit 1";
                $qu = $this->db->query($q, array($admn_no, $i));
			//  echo $this->db->last_query(); echo' <pre>'; print_r($r ); echo '<pre>'; die();
					$row = $qu->row();
					
					if (isset($row))
						$etype = $row->examtype;// added rituraj
					}
					
               //if($i==5){ print_r($qu->num_rows()); echo 'tt';  die();}
                // if($i==5){echo $this->db->last_query(); die()};
                //if($i==3){ echo 'tt'; die();}
                $mis_not_found=$qu->num_rows();
                }else{
                    $mis_not_found=0;													                
				}
				// if($i==5){echo 'etype'.$etype; die();}
               }
			   
        //   if($i==5 && strtolower($admn_no)=='2012je1252'){print_r($mis_not_found);  die();}
		      //if($i==5 && strtolower($admn_no)=='2012je1364'){print_r($mis_not_found);  die();}
		  //if( strtoupper($admn_no)=='2012JE0606'){   echo $this->db->last_query(); echo' <pre>'; print_r($r ); echo '<pre>'; die();}
		  
		  // end
              if($mis_not_found > 0)
			  {         	
                $rr = $qu->row();
                $r[$i]->ysession = $rr->ysession;
                $r[$i]->examtype = $rr->examtype;
                $r[$i]->totcrhr = $rr->totcrhr;
                $r[$i]->totcrpts = $rr->totcrpts;
                $r[$i]->gpa = number_format($rr->totcrpts / $rr->totcrhr, 2);
                $r[$i]->ctotcrhr = $rr->totcrhr + ($r[$i - 1]->ctotcrhr);
                $r[$i]->ctotcrpts = $rr->totcrpts + ( $r[$i - 1]->ctotcrpts);
                $r[$i]->ogpa = number_format(($rr->totcrpts + ( $r[$i - 1]->ctotcrpts)) / ($rr->totcrhr + ($r[$i - 1]->ctotcrhr)), 2);
                $r[$i]->passfail = $rr->passfail;
                if ($r[$i]->passfail == 'F' || $r[$i]->passfail == 'f') 
				{
                    $etype = $r[$i]->examtype;
                    $f = 0;
                    $rr = $this->getSummerStuRes($admn_no, $i, $etype);
                    if ($rr) 
					{
                        unset($r[$i]);
                        $r[$i]->ysession = $rr->session_yr;
                        $r[$i]->examtype = $rr->type;
                        $r[$i]->totcrhr = $rr->tot_cr_hr;
                        $r[$i]->totcrpts = $rr->tot_cr_pts;
                        $r[$i]->gpa = number_format($rr->tot_cr_pts / $rr->tot_cr_hr, 2);
                        $r[$i]->ctotcrhr = $rr->tot_cr_hr + ($r[$i - 1]->ctotcrhr);
                        $r[$i]->ctotcrpts = $rr->tot_cr_pts + ( $r[$i - 1]->ctotcrpts);
                        $r[$i]->ogpa = number_format(($rr->tot_cr_pts + ( $r[$i - 1]->ctotcrpts)) / ($rr->tot_cr_hr + ($r[$i - 1]->ctotcrhr)), 2);
                        if ($rr->status == 'FAIL') 
						{
                            $r[$i]->remarks = $this->failIn($rr->id);
                            $r[$i]->passfail = 'F';
                            $f++;
                        } else 
						{
                            $r[$i]->remarks = 'Pass';
                            $r[$i]->passfail = 'P';
                        }
                    }
					else
					{
                     //  if($i==7){print_r($mis_not_found);  die();}
                       $rr = $this->getOtherStuRes($admn_no, $i, $etype);
                        // echo $this->db->last_query(); die();
                       if($rr)
					   {
                            //unset($r[$i]);
                            //   echo  $rr->session_yr; 

                            $r[$i]->ysession = $rr->session_yr;
                            $r[$i]->examtype = $rr->type;
                            $r[$i]->totcrhr = $rr->tot_cr_hr;
                            $r[$i]->totcrpts = $rr->tot_cr_pts;
                            $r[$i]->gpa = number_format($rr->tot_cr_pts / $rr->tot_cr_hr, 2);
                            $r[$i]->ctotcrhr = $rr->tot_cr_hr + ($r[$i - 1]->ctotcrhr);
                            $r[$i]->ctotcrpts = $rr->tot_cr_pts + ( $r[$i - 1]->ctotcrpts);
                            $r[$i]->ogpa = number_format(($rr->tot_cr_pts + ( $r[$i - 1]->ctotcrpts)) / ($rr->tot_cr_hr + ($r[$i - 1]->ctotcrhr)), 2);
                            if ($rr->status == 'FAIL')
							{
                                $r[$i]->remarks = $this->failIn($rr->id);
                                $r[$i]->passfail = 'F';
                            } else 
							{
                                $r[$i]->remarks = 'Pass';
                                $r[$i]->passfail = 'P';
                            }
                        }
                    }
                }
                
             }
			 else 
			 {
              // echo $i; die();   ///  if($i==5) echo 'tt'; die();
              $rr = $this->tabu->getSubjectsByAdminNo_With_without_hons($branch, $i, $admn_no, 'N', $session_year=false );
		          
			//  if($i==8 && strtolower($admn_no)=='2012je0606'){   echo $this->db->last_query(); echo' <pre>'; print_r($rr ); echo '<pre>'; die();}				
              //if($i==5)  { echo $this->db->last_query(); die();}
              // if($i==3) { echo' <pre>'; print_r($rr ); echo '<pre>'; }
              //echo' <pre>'; 	print_r($rr[2] ); echo '<pre>'; die();
			 
               if ($endsem >= 5 && $i >= 5) 
			   {				   				   
                 // With honour result;
                $rh = $this->HonourRes($branch, $i, $admn_no, 'N', $session_year = false, $r);					
			    $r[$i]->honour = $rh;
               }
			  //if($i==7) {echo $this->db->last_query(); echo' <pre>'; print_r($r[$i]->honour );echo '<pre>';die();}
                //if($i==5 && strtolower($admn_no)=='2012je1364'){   echo $this->db->last_query(); echo' <pre>'; print_r($r[$i]->honour ); echo '<pre>';die();}
				// if($i==8 && strtolower($admn_no)=='14je000182'){   echo $this->db->last_query(); echo' <pre>'; print_r($r[$i]->honour ); echo '<pre>'; die();}				
                // With honour result;
                // echo ($i == 4)?$this->db->last_query():"";                   				   				   
                if ($rr) 
				{
					
                  //if($i==8 && strtolower($admn_no)=='14je000182'){  echo' <pre>'; 	print_r($rr ); echo '<pre>'; die();}
                  //$r[$i] = $rr;
                  $tocrpt = 0;
                  $tohr = 0;
                  $f = 0;
                  //  $s = 'Fail In ';					
				  //echo $s ;
				   
                  foreach ($rr as $rf) 
				  {
                   $tocrpt = $tocrpt + $rf->totcrdthr;
                   $tohr = $tohr + $rf->credit_hours;
                   if ($rf->grade == 'F')
					   {
                          $f++;
                          $s.= $rf->sub_code."[sem-".$i."],";
                       }																	
                   }					
				 // echo  $r[$i]->honour->fail_papers.'<br/>';	die();				
			       if($r[$i]->honour!='N/a'&&($endsem >= 5 && $i >= 5))
				   {                        
			        if($f>0) $r[$i]->honour->passfail='F';
					 if ($r[$i]->honour->fail_papers !="") 
					  {
                             $f++;
                             $s.= $r[$i]->honour->fail_papers." ";							 
                       }					   
					   
					  if(number_format($r[$i]->honour->totcrpts/$r[$i]->honour->totcrhr,2)<5)
					  { 
					         $f++;$s.= trim($s)=="Fail In"?" Aggr[sem-".$i."](Hons.)": "& Aggr[sem-".$i."](Hons.)";
					  }
					   
					}					
					if(number_format($tocrpt / $tohr, 2)<5)
                    { 
						 $f++;
						 $s.= trim($s)=="Fail In"?" Aggr[sem-".$i."]": "& Aggr[sem-".$i."]";
					}																						                      
                    $r[$i]->ysession = $rr[0]->session_year;
                    $r[$i]->examtype = 'R';
                    $r[$i]->totcrhr = $tohr;
                    $r[$i]->totcrpts = $tocrpt;
                    $r[$i]->gpa = number_format($tocrpt / $tohr, 2);
                    $r[$i]->ctotcrhr = $tohr + ($r[$i - 1]->ctotcrhr);
                    $r[$i]->ctotcrpts = $tocrpt + ( $r[$i - 1]->ctotcrpts);
                    $r[$i]->ogpa = number_format(($tocrpt + ( $r[$i - 1]->ctotcrpts)) / ($tohr + ($r[$i - 1]->ctotcrhr)), 2);
                    if ($rr->status == 'FAIL')
					{
                        $r[$i]->remarks = $this->failIn($rr->id);
                        $r[$i]->passfail = 'F';
                        $f++;
                    } else
					{
                        $r[$i]->remarks = 'Pass';
                        $r[$i]->passfail = 'P';
                    }
																			
                    // echo $f; die();					
					//if($i==8 && $admn_no=='2012je0543'){  echo' <pre>'; print_r($r ); echo '<pre>'; die();}
			
					
					// checking after regular exam
                    if ($f > 0)
    				{						
                       //if($i==7 && $admn_no=='2012je1364'){ echo $f;}	
					    $etype = $r[$i]->examtype;// added rituraj
                        $rr = $this->getSummerStuRes($admn_no, $i, $etype);																		
						//if($i==6 && strtoupper($admn_no)=='14JE000097'){ echo $this->db->last_query();  echo' <pre>'; print_r($rr ); echo '<pre>'; die();}			  						 
                        // if($endsem >=5 && $i >=5){ echo "<pre>"; print_r($rr); echo "</pre>"; die(); }
                      if ($rr) 
					  {
						  $f=0;
						//	if($i==5 && strtolower($admn_no)=='14je000540'){   echo $this->db->last_query(); echo' <pre>'; print_r($rr ); echo '<pre>'; die();}
                        //unset($r[$i]);
                        if ($endsem >= 5 && $i >= 5)
						{
                            // With honour result;
                           $rh = $this->HonourRes($branch, $i, $admn_no, 'N', $session_year = false, $r);
                           $r[$i]->honour = $rh;
						//   if($i==6 && strtoupper($admn_no)=='14JE000097'){ echo $this->db->last_query(); echo' <pre>'; print_r($r[$i]->honour ); echo '<pre>';die();}			  						 
                        }													
                         // With honour result;
                        $r[$i]->ysession = $rr->session_yr;
                        $r[$i]->examtype = $rr->type;
                        $r[$i]->totcrhr = $rr->core_tot_cr_hr;
                        $r[$i]->totcrpts = $rr->core_tot_cr_pts;
                        $r[$i]->gpa = number_format($rr->core_tot_cr_pts / $rr->core_tot_cr_hr, 2);
                        $r[$i]->ctotcrhr = $rr->core_tot_cr_hr + ($r[$i - 1]->ctotcrhr);
                        $r[$i]->ctotcrpts = $rr->core_tot_cr_pts + ( $r[$i - 1]->ctotcrpts);
                        $r[$i]->ogpa = number_format(($rr->core_tot_cr_pts + ( $r[$i - 1]->ctotcrpts)) / ($rr->core_tot_cr_hr + ($r[$i - 1]->ctotcrhr)), 2);                            
                        if ($rr->status == 'FAIL') 
						{
                                $r[$i]->remarks = $this->failIn($rr->id);
                                $r[$i]->passfail = 'F';
                                $f++;
                         } 
						 else 
						 {							
                                $r[$i]->remarks = 'Pass';
                                $r[$i]->passfail = 'P';
                         }
                         // if($i==6 && strtoupper($admn_no)=='14JE000097'){ echo $this->db->last_query();  echo' <pre>'; print_r($r ); echo '<pre>'; die();}			  						 
                        } 
						else 
						{
                           
                            $f = 0;
                            $r[$i]->remarks = $s;
                            $r[$i]->passfail = 'F';
                            $rr = $this->getOtherStuRes($admn_no, $i, $etype);
						//	if($i==7 && $admn_no=='2012je1364'){ echo 'etype'.$etype; die();}	
							/*if($etype==null){
				  
				           $q="
							SELECT B.*
								FROM (
								SELECT  a.type ,a.semester as sem 
								FROM final_semwise_marks_foil a
								WHERE a.admn_no=?  and  a.course<>'MINOR' AND (a.semester!= '0' and a.semester!='-1' and a.semester=?) 								 
								GROUP BY a.session_yr,a.session,a.semester,a.exam_type								
								ORDER BY a.session_yr desc  ,  a.semester DESC,   a.tot_cr_pts desc)B
								GROUP BY B.sem ";
								$qu = $this->db->query($q, array($admn_no, $i));
								//if($i==5){echo $this->db->last_query(); die();}
								if($qu->num_rows()>0){
								$row = $qu->row();if (isset($row))$etype = $row->type;
								}
								$rr = $this->getOtherStuRes_spl_case($admn_no, $i, $etype);
				            }
							else 
							$rr = $this->getOtherStuRes($admn_no, $i, $etype);
							*/
                           //if($i==8 && $admn_no=='2012je0543'){ echo $this->db->last_query();}						 
						  //  if($i==7 && $admn_no=='2012je1364'){   echo $this->db->last_query(); echo' <pre>'; print_r($r ); echo '<pre>'; die();}
						//  if($i==5 && $admn_no=='2012je1364'){   echo $this->db->last_query(); echo' <pre>'; print_r($r ); echo '<pre>'; die();}
						   if ($rr) 
						   {
                                //unset($r[$i]);
                             $r[$i]->ysession = $rr->session_yr;
                             $r[$i]->examtype = $rr->type;
                             $r[$i]->totcrhr = $rr->tot_cr_hr;
                             $r[$i]->totcrpts = $rr->tot_cr_pts;
                             $r[$i]->gpa = number_format($rr->tot_cr_pts / $rr->tot_cr_hr, 2);
                             $r[$i]->ctotcrhr = $rr->tot_cr_hr + ($r[$i - 1]->ctotcrhr);
                             $r[$i]->ctotcrpts = $rr->tot_cr_pts + ( $r[$i - 1]->ctotcrpts);
                             $r[$i]->ogpa = number_format(($rr->tot_cr_pts + ( $r[$i - 1]->ctotcrpts)) / ($rr->tot_cr_hr + ($r[$i - 1]->ctotcrhr)), 2);
                             if ($rr->status == 'FAIL') 
							 {
                                    $r[$i]->remarks = $this->failIn($rr->id);
                                    $r[$i]->passfail = 'F';
                                    $f++;
                              } 
							  else 
							  {
                                    $r[$i]->remarks = 'Pass';
                                    $r[$i]->passfail = 'P';
                              }
                                //gpa validation check    
                            if($r[$i]->honour!='N/a'&&($endsem >= 5 && $i >= 5))
				             {
								  if($f>0) $r[$i]->honour->passfail='F'; 
					           if ($r[$i]->honour->fail_papers !="") 
					            {
                                   $f++;
                                   $s.= $r[$i]->honour->fail_papers." ";							        
                                }
					            if(number_format($r[$i]->honour->totcrpts/$r[$i]->honour->totcrhr,2)<5)
					            { 
					             $f++;$s.= trim($s)=="Fail In"?" Aggr[sem-".$i."](Hons.)": "& Aggr[sem-".$i."](Hons.)";
								 
					            }					   
					          }					
					          if(number_format($tocrpt / $tohr, 2)<5)
                              { 
						       $f++;$s.= trim($s)=="Fail In"?" Aggr[sem-".$i."]": "& Aggr[sem-".$i."]";
							   $r[$i]->passfail = 'F';
					          }								
                               if(trim($s)!="Fail In"){							  
							    $r[$i]->remarks = $s;                                
							   }							  
                             //end	                        
                            }
                          }
					 }
                     //if($i==5 && strtolower($admn_no)=='2012je1252'){   echo $this->db->last_query(); echo' <pre>'; print_r($rr ); echo '<pre>'; die();} 						   
                    if ($f > 0) 
					{	 //echo $f; die();
				        
                      //$etype = $r[$i]->examtype;// added rituraj				
                      $rr = $this->getSummerStuRes($admn_no, $i, $etype);
                      if ($rr)
     				  {
						  $f=0;
                       //unset($r[$i]);
                       if ($endsem >= 5 && $i >= 5) 
					   {
                           // With honour result;
                            $rh = $this->HonourRes($branch, $i, $admn_no, 'N', $session_year = false, $r);
                            $r[$i]->honour = $rh;
                        }
                            // With honour result;
						$r[$i]->ysession = $rr->session_yr;
						$r[$i]->examtype = $rr->type;
						$r[$i]->totcrhr = $rr->core_tot_cr_hr;
						$r[$i]->totcrpts = $rr->core_tot_cr_pts;
						$r[$i]->gpa = number_format($rr->core_tot_cr_pts / $rr->core_tot_cr_hr, 2);
						$r[$i]->ctotcrhr = $rr->core_tot_cr_hr + ($r[$i - 1]->ctotcrhr);
						$r[$i]->ctotcrpts = $rr->core_tot_cr_pts + ( $r[$i - 1]->ctotcrpts);
						$r[$i]->ogpa = number_format(($rr->core_tot_cr_pts + ( $r[$i - 1]->ctotcrpts)) / ($rr->core_tot_cr_hr + ($r[$i - 1]->ctotcrhr)), 2);
						if ($rr->status == 'FAIL') 
						{
							$r[$i]->remarks = $this->failIn($rr->id);
							$r[$i]->passfail = 'F';
							$f++;
						} 
						else 
						{
							$r[$i]->remarks = 'Pass';
							$r[$i]->passfail = 'P';
						}						
					  }
				      //riturajcode						
					  if($f > 0)
					  {							
                       // $r[$i]->remarks = $s;
                       //$r[$i]->passfail = 'F';
					 //   $etype = $r[$i]->examtype;// added rituraj
                         $rr = $this->getOtherStuRes($admn_no, $i, $etype);
                       //if($i==8 && $admn_no=='2012je0543'){ echo $this->db->last_query();}
						// if($i==5 && strtolower($admn_no)=='2012je1252'){ echo $f ; die();}
						//if($i==5 && strtolower($admn_no)=='2012je1252'){   echo $this->db->last_query(); echo' <pre>'; print_r($rr ); echo '<pre>'; die();} 						  


					   if ($rr) 
						{
							$f=0;
                          //unset($r[$i]);
							$r[$i]->ysession = $rr->session_yr;
							$r[$i]->examtype = $rr->type;
							$r[$i]->totcrhr = $rr->tot_cr_hr;
							$r[$i]->totcrpts = $rr->tot_cr_pts;
							$r[$i]->gpa = number_format($rr->tot_cr_pts / $rr->tot_cr_hr, 2);
							$r[$i]->ctotcrhr = $rr->tot_cr_hr + ($r[$i - 1]->ctotcrhr);
							$r[$i]->ctotcrpts = $rr->tot_cr_pts + ( $r[$i - 1]->ctotcrpts);
							$r[$i]->ogpa = number_format(($rr->tot_cr_pts + ( $r[$i - 1]->ctotcrpts)) / ($rr->tot_cr_hr + ($r[$i - 1]->ctotcrhr)), 2);
							if ($rr->status == 'FAIL') 
							{
								$r[$i]->remarks = $this->failIn($rr->id);
								$r[$i]->passfail = 'F';
								$f++;
							} 
							else 
							{
								$r[$i]->remarks = 'Pass';
								$r[$i]->passfail = 'P';
							}						
					      }						
						// end
					    }	
                     } 
					 else 
					 { 
					   // donothing      
					 }
                  } 
				  else 
				  {
					// first time mIS entry for any semester in foil
                   
                   $rr = $this->getOtherStuRes($admn_no, $i, $etype);
				   
			/*	  if($etype==null){
				  
				  $q="
							SELECT B.*
								FROM (
								SELECT  a.type ,a.semester as sem 
								FROM final_semwise_marks_foil a
								WHERE a.admn_no=?  and  a.course<>'MINOR' AND (a.semester!= '0' and a.semester!='-1' and a.semester=?) 								 
								GROUP BY a.session_yr,a.session,a.semester,a.exam_type								
								ORDER BY a.session_yr desc  ,  a.semester DESC,   a.tot_cr_pts desc)B
								GROUP BY B.sem ";
								$qu = $this->db->query($q, array($admn_no, $i));
								//if($i==5){echo $this->db->last_query(); die();}
								if($qu->num_rows()>0){
								$row = $qu->row();if (isset($row))$etype = $row->type;
								}
					$rr = $this->getOtherStuRes_spl_case($admn_no, $i, $etype);			
				  }
				  else 
					$rr = $this->getOtherStuRes($admn_no, $i, $etype);*/
				   
					// echo $etype; die();
                      //if($i==5)  { echo $this->db->last_query(); die();}
					
                    if ($rr)
    				{
						 $f=0;
					//	if($i==5 && $admn_no=='2012je1364'){ echo  $rr->type; die();}
                        //unset($r[$i]);												
                        $r[$i]->ysession = $rr->session_yr;
                        $r[$i]->examtype =$rr->type;
                        $r[$i]->totcrhr = $rr->tot_cr_hr;
                        $r[$i]->totcrpts = $rr->tot_cr_pts;
                        $r[$i]->gpa = number_format($rr->tot_cr_pts / $rr->tot_cr_hr, 2);
                        $r[$i]->ctotcrhr = $rr->tot_cr_hr + ($r[$i - 1]->ctotcrhr);
                        $r[$i]->ctotcrpts = $rr->tot_cr_pts + ( $r[$i - 1]->ctotcrpts);
                        $r[$i]->ogpa = number_format(($rr->tot_cr_pts + ( $r[$i - 1]->ctotcrpts)) / ($rr->tot_cr_hr + ($r[$i - 1]->ctotcrhr)), 2);
                        if ($rr->status == 'FAIL') 
						{
                            $r[$i]->remarks = $this->failIn($rr->id);
                            $r[$i]->passfail = 'F';
                            $f++;
                        } 
						else 
						{
                            $r[$i]->remarks = 'Pass';
                            $r[$i]->passfail = 'P';
                        }
						//if($i==5 && $admn_no=='2012je1364'){   echo $this->db->last_query(); echo' <pre>'; print_r($r ); echo '<pre>'; die();}                                             
                    } 
                    if ($f > 0) 
					{
                       $rr = $this->getSummerStuRes($admn_no, $i, $etype);
                       if ($rr)
    				   {
							$f=0;
                         //unset($r[$i]);
                            $r[$i]->ysession = $rr->session_yr;
                            $r[$i]->examtype = $rr->type;
                            $r[$i]->totcrhr = $rr->core_tot_cr_hr;
                            $r[$i]->totcrpts = $rr->core_tot_cr_pts;
                            $r[$i]->gpa = number_format($rr->core_tot_cr_pts / $rr->core_tot_cr_hr, 2);
                            $r[$i]->ctotcrhr = $rr->core_tot_cr_hr + ($r[$i - 1]->ctotcrhr);
                            $r[$i]->ctotcrpts = $rr->core_tot_cr_pts + ( $r[$i - 1]->ctotcrpts);
                            $r[$i]->ogpa = number_format(($rr->core_tot_cr_pts + ( $r[$i - 1]->ctotcrpts)) / ($rr->core_tot_cr_hr + ($r[$i - 1]->ctotcrhr)), 2);
                            if ($rr->status == 'FAIL') 
							{
                                $r[$i]->remarks = $this->failIn($rr->id);
                                $r[$i]->passfail = 'F';
                            } 
							else 
							{
                                	$r[$i]->remarks = 'Pass';
                                	$r[$i]->passfail = 'P';
                            }
							
                        }
                    } 
					else 
					{
                       // $r[$i] = [];
                    }
					
					// end first time mIS entry for any semester in foil
                }
            }
        } //echo $s; die();
     return $r;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



// not in use right now
function single_use_of_words($str) {
  $words = explode(',', $str);
  $words = array_unique($words);
  return implode(' ', $words);
}
    private function getbranchByAdmn_no($id) {

        $r = $this->db->get_where('stu_academic', array('admn_no' => $id));
        if ($r->num_rows() > 0) {
            return $r->row();
        }
    }

    private function HonourRes($branch, $i, $admn_no, $Hstatus, $session_year = false, $h) {

        $sH = $this->getHonourStatus($admn_no);
        $r = (object) array();
        if ($sH) {
            $rh = $this->tabu->getSubjectsByAdminNo_With_without_hons($branch, $i, $admn_no, 'Y', $session_year = false);

            // echo "<br>";
          //   echo $this->db->last_query();die();

            $tocrpt = 0;
            $tohr = 0;
            $f = 0;
            //$s = 'Fail In ';
			//$s = 'Fail In ';
			
            foreach ($rh as $rf) {								
                $tocrpt = $tocrpt + $rf->totcrdthr;
                $tohr = $tohr + $rf->credit_hours;
                if ($rf->grade == 'F' ) {
                    $f++;
				}
					if($rf->course_id=='honour' && $rf->grade == 'F' )                    
					$s.= $rf->sub_code. ($rf->course_id=='honour'?"(H)[sem-".$i."]":"") . ",";					
                }
				
				
			// echo $s ;
            $r->fail_papers=$s; 
            $r->ysession = $rh[0]->session_year;
            $r->examtype = 'R';
            $r->totcrhr = $tohr;
            $r->totcrpts = $tocrpt;
            $r->gpa = number_format($tocrpt / $tohr, 2);
            if ($i == 5) {
                $r->ctotcrhr = $tohr + ($h[$i - 1]->ctotcrhr);
                $r->ctotcrpts = $tocrpt + ( $h[$i - 1]->ctotcrpts);
                $r->ogpa = number_format($r->ctotcrpts / $r->ctotcrhr, 2);
            } else {
                $r->ctotcrhr = $tohr + ($h[$i - 1]->honour->ctotcrhr);
                $r->ctotcrpts = $tocrpt + ( $h[$i - 1]->honour->ctotcrpts);
                $r->ogpa = number_format(($tocrpt + ( $h[$i - 1]->honour->ctotcrpts)) / ($tohr + ($h[$i - 1]->honour->ctotcrhr)), 2);
            }
            if ($f > 0) {
                $rr = $this->getSummerStuRes($admn_no, $i, $etype);
                  //    if($i==6 && strtoupper($admn_no)=='14JE000097'){ echo $this->db->last_query();  echo' <pre>'; print_r($r ); echo '<pre>'; die();}			  						    
                if ($rr) {
                    unset($r);
                    $rrr = $this->totalPtsHrs($rr->id);

                    $tohr = $rrr->cr_hr;
                    $tocrpt = $rrr->cr_pts;
                    $r->ysession = $rr->session_yr;
                    $r->examtype = $rr->type;
                    $r->totcrhr = $rr->tot_cr_hr;
                    $r->totcrpts = $rr->tot_cr_pts;
                    $r->gpa = number_format($rr->tot_cr_pts / $rr->tot_cr_hr, 2);
                    if ($i == 5) {
                        $r->ctotcrhr = $rr->tot_cr_hr + ($h[$i - 1]->ctotcrhr);
                        $r->ctotcrpts = $rr->tot_cr_pts + ( $h[$i - 1]->ctotcrpts);
                        $r->ogpa = number_format(($rr->tot_cr_pts + ( $h[$i - 1]->ctotcrpts)) / ($rr->tot_cr_hr + ($h[$i - 1]->ctotcrhr)), 2);
                    } else {

                        $r->ctotcrhr = $tohr + ($h[$i - 1]->honour->ctotcrhr);
                        $r->ctotcrpts = $tocrpt + ( $h[$i - 1]->honour->ctotcrpts);
                        $r->ogpa = number_format(($tocrpt + ( $h[$i - 1]->honour->ctotcrpts)) / ($tohr + ($h[$i - 1]->honour->ctotcrhr)), 2);
                    }
                    if ($rr->status == 'FAIL') {
                        $r->remarks = $this->failIn($rr->id);
                        $r->passfail = 'F';
                        $f++;
                    } else {
                        $r->remarks = 'Pass';
                        $r->passfail = 'P';
                    }
                }
            } else {
                $r->remarks = 'Pass';
                $r->passfail = 'P';
            }
        } else {
            $r = 'N/a';
        }
        return $r;
    }

    private function getHonourStatus($admn_no) {
        $r = $this->db->get_where('hm_form', array('admn_no' => $admn_no, 'honours' => '1', 'honour_hod_status' => 'Y'));
        if ($r->num_rows() > 0) {
            return $r->row();
        }
        return false;
    }

    private function getOtherStuRes($admn_no, $sem, $etype) {
        
        $q = "   select x.* from 
		           ( select * from final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' and `type`=
                 (select `type` from final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' and `type`<>'R' order by `session_yr` desc,`type` desc limit 1 ) and status='PASS' order by id desc limit 1)x
				 union
				 select y.* from (
				 select * from alumni_final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' and `type`=
				 (select `type` from alumni_final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' and `type`<>'R' order by `session_yr` desc,`type` desc limit 1 ) and status='PASS' order by id desc limit 1
				 )y
				
				";
        $r = $this->db->query($q);
		// echo $this->db->last_query(); die();
        if ($r->num_rows() > 0) {
            $re = $r->row();
            if ($re->type == 'O') {
                $etype++;
                $re->type = $etype;
            } else if ($re->type == 'O') {
                $etype++;
                $etype++;
                $re->type = $etype;
            }
            return $re;
        }
		
		
		
		// added by rituraj @15-5-18  to make getting latest status of other type same added to transcript 
		else {
			 $q = "select x.* from 
			 (select * from final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' and `type`=
                (select `type` from final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' and `type`<>'R' order by `session_yr` desc,`type` desc limit 1 ) order by id desc limit 1)x   
				
				union
				select y.* from 
				(select * from alumni_final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' and `type`=
                (select `type` from alumni_final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' and `type`<>'R' order by `session_yr` desc,`type` desc limit 1 ) order by id desc limit 1)y   
				";
        $r = $this->db->query($q);
        if ($r->num_rows() > 0) {
            $re = $r->row();
            if ($re->type == 'O') {
                $etype++;
                $re->type = $etype;
            } else if ($re->type == 'O') {
                $etype++;
                $etype++;
                $re->type = $etype;
            }
            return $re;
        }
		}	
		// end 
		
           return false;
    }

	 private function getOtherStuRes_spl_case($admn_no, $sem, $etype) {
        $q="                   select x.* from
		                   (
		                      SELECT B.*
								FROM (
								SELECT  *
								FROM final_semwise_marks_foil a
								WHERE a.admn_no='$admn_no'  and  a.course<>'MINOR' AND (a.semester!= '0' and a.semester!='-1' and a.semester='$sem') 
								 and a.type='$etype'
								 and  (a.core_status='PASS'  and  a.status='PASS')
								GROUP BY a.session_yr,a.session,a.semester,a.exam_type								
								ORDER BY a.session_yr desc  ,  a.semester DESC,   a.tot_cr_pts desc)B
								GROUP BY B.semester )x

                               union
							    select y.* from
		                   (
		                      SELECT B.*
								FROM (
								SELECT  *
								FROM alumni_final_semwise_marks_foil a
								WHERE a.admn_no='$admn_no'  and  a.course<>'MINOR' AND (a.semester!= '0' and a.semester!='-1' and a.semester='$sem') 
								 and a.type='$etype'
								 and  (a.core_status='PASS'  and  a.status='PASS')
								GROUP BY a.session_yr,a.session,a.semester,a.exam_type								
								ORDER BY a.session_yr desc  ,  a.semester DESC,   a.tot_cr_pts desc)B
								GROUP BY B.semester )y
							   
							   

								";				
        //echo $q; die();
        $r = $this->db->query($q);
		
        if ($r->num_rows() > 0) {
            $re = $r->row();            
            return $re;
        }
		// added by rituraj @15-5-18  to make getting latest status of other type same added to transcript 
		else {
			   $q=             "  select x.* from
		                   (
			                    SELECT B.*
								FROM (
								SELECT  *
								FROM final_semwise_marks_foil a
								WHERE a.admn_no='$admn_no'  and  a.course<>'MINOR' AND (a.semester!= '0' and a.semester!='-1' and a.semester='$sem') 
								 and a.type='$etype'								 
								GROUP BY a.session_yr,a.session,a.semester,a.exam_type								
								ORDER BY a.session_yr desc  ,  a.semester DESC,   a.tot_cr_pts desc)B
								GROUP BY B.semester)x
								
								union
								  select y.* from
		                   (
						   SELECT B.*
								FROM (
								SELECT  *
								FROM final_semwise_marks_foil a
								WHERE a.admn_no='$admn_no'  and  a.course<>'MINOR' AND (a.semester!= '0' and a.semester!='-1' and a.semester='$sem') 
								 and a.type='$etype'								 
								GROUP BY a.session_yr,a.session,a.semester,a.exam_type								
								ORDER BY a.session_yr desc  ,  a.semester DESC,   a.tot_cr_pts desc)B
								GROUP BY B.semester)
								";				
        
        $r = $this->db->query($q);
        if ($r->num_rows() > 0) {
            $re = $r->row();       
            return $re;
          }
		}	
		// end 
		
           return false;
    }

	
	
	
    /*   private function getSummerStuRes($admn_no,$sem,$etype){

      $q="select * from final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' and `type`='R' and session='Summer'  order by `session_yr` desc limit 1 " ;
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

      } */

    private function getSummerStuRes($admn_no, $sem, $etype) {

        $q = "select x.* from (select id,session_yr,`session`,dept,course,branch,semester,admn_no,tot_cr_hr,tot_cr_pts,core_tot_cr_hr,core_tot_cr_pts,
ctotcrpts,core_ctotcrpts,ctotcrhr,core_ctotcrhr,gpa,core_gpa,cgpa,core_cgpa,`status`,core_status,hstatus,repeater,
  'SM' as type ,exam_type,final_status from final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' and `type`='R' and session='Summer'  order by `session_yr` desc limit 1)x

union
select y.* from (select id,session_yr,`session`,dept,course,branch,semester,admn_no,tot_cr_hr,tot_cr_pts,core_tot_cr_hr,core_tot_cr_pts,
ctotcrpts,core_ctotcrpts,ctotcrhr,core_ctotcrhr,gpa,core_gpa,cgpa,core_cgpa,`status`,core_status,hstatus,repeater,
  'SM' as type ,exam_type,final_status from alumni_final_semwise_marks_foil where admn_no='$admn_no' and semester='$sem' and `type`='R' and session='Summer'  order by `session_yr` desc limit 1)y
  ";
        $r = $this->db->query($q);
        if ($r->num_rows() > 0) {
            $re = $r->row();
            if ($re->type == 'O') {
                $etype++;
                $re->type = $etype;
            } else if ($re->type == 'O') {
                $etype++;
                $etype++;
                $re->type = $etype;
            }
            return $re;
        }
        return false;
    }

    private function totalPtsHrs($id) {
        $this->db->select('sum(cr_pts) as cr_pts, sum(cr_hr) as cr_hr');
        $r = $this->db->get_where('final_semwise_marks_foil_desc', array('foil_id' => $id));
        //	echo $this->db->last_query();
        if ($r->num_rows() > 0) {
            return $r->row();
        }
		else{
			$this->db->select('sum(cr_pts) as cr_pts, sum(cr_hr) as cr_hr');
        $r = $this->db->get_where('alumni_final_semwise_marks_foil_desc', array('foil_id' => $id));
        //	echo $this->db->last_query();
        if ($r->num_rows() > 0) {
            return $r->row();
        }
			
			
		}
		

        return ['cr_pts' => 0, "cr_hr" => 0];
    }

    private function failIn($id) {
        $r = $this->db->get_where('final_semwise_marks_foil_desc', array('grade' => 'F', 'foil_id' => $id));
        if ($r->num_rows() > 0) {
            $fi = $r->result();
            $re = 'Fail in ';
            foreach ($fi as $f) {
                $re.=$f->sub_code . " ";
            }
            return $re;
        }
		else{
			
			$r = $this->db->get_where('alumni_final_semwise_marks_foil_desc', array('grade' => 'F', 'foil_id' => $id));
		       if ($r->num_rows() > 0) {
            $fi = $r->result();
            $re = 'Fail in ';
            foreach ($fi as $f) {
                $re.=$f->sub_code . " ";
            }
            return $re;
        }	
			
		}
		
		
    }

    function get_minor_plist($sy, $dept, $course, $branch, $sem) {
        $sql = "  
		
select p.admn_no/*,q.offered,q.minor_agg_id*/ from (
select x.* from hm_form x where x.admn_no in (
select a.admn_no from final_semwise_marks_foil a where a.session_yr=? and a.`session`='Winter'
and a.dept=? and a.course=? and a.branch=? and a.semester=? and a.status='PASS')
group by x.admn_no)p
inner join hm_minor_details q on q.form_id=p.form_id
where q.offered='1'
order by p.admn_no

";



        $query = $this->db->query($sql,array($sy, $dept, $course, $branch, $sem));
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
           $sql = "  
		
select p.admn_no/*,q.offered,q.minor_agg_id*/ from (
select x.* from hm_form x where x.admn_no in (
select a.admn_no from alumni_final_semwise_marks_foil a where a.session_yr=? and a.`session`='Winter'
and a.dept=? and a.course=? and a.branch=? and a.semester=? and a.status='PASS')
group by x.admn_no)p
inner join hm_minor_details q on q.form_id=p.form_id
where q.offered='1'
order by p.admn_no

";



        $query = $this->db->query($sql,array($sy, $dept, $course, $branch, $sem));
        if ($query->num_rows() > 0) {
            return $query->result();
        } 
        }
    }
    function get_minor_result($admn_no,$sem){
        $sql = "select a.admn_no,concat_ws(' ',g.first_name,g.middle_name,g.last_name)as stu_name,a.grade,CASE WHEN a.grade = 'F' THEN 'F' ELSE 'P' END AS passfail,d.*,e.semester,e.aggr_id,b.session_year,b.`session`,(d.credit_hours*f.points) as cpts from marks_subject_description a 
inner join marks_master b on a.marks_master_id=b.id
inner join subject_mapping c on c.map_id=b.sub_map_id
inner join subjects d on d.id=b.subject_id
inner join course_structure e on e.id=b.subject_id
inner join grade_points f on f.grade=a.grade
inner join user_details g on g.id=a.admn_no
WHERE e.aggr_id LIKE '%minor%' and a.admn_no=? AND c.semester=?";



        $query = $this->db->query($sql,array($admn_no,$sem));
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            //return false;
			$sql = "select a.admn_no,concat_ws(' ',g.first_name,g.middle_name,g.last_name)as stu_name,a.grade,CASE WHEN a.grade = 'F' THEN 'F' ELSE 'P' END AS passfail,d.*,e.semester,e.aggr_id,b.session_year,b.`session`,(d.credit_hours*f.points) as cpts from marks_subject_description a 
inner join marks_master b on a.marks_master_id=b.id
inner join subject_mapping c on c.map_id=b.sub_map_id
inner join subjects d on d.id=b.subject_id
inner join course_structure e on e.id=b.subject_id
inner join grade_points f on f.grade=a.grade
inner join alumni_user_details g on g.id=a.admn_no
WHERE e.aggr_id LIKE '%minor%' and a.admn_no=? AND c.semester=?";



        $query = $this->db->query($sql,array($admn_no,$sem));
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        }
    }
    function stu_name($id){
        $sql = "select CONCAT_WS(' ',g.first_name,g.middle_name,g.last_name) AS stu_name 
from user_details g  where g.id=?";



        $query = $this->db->query($sql,array($id));
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->row()->stu_name;
        } else {
            //return false;
			$sql = "select CONCAT_WS(' ',g.first_name,g.middle_name,g.last_name) AS stu_name 
fromalumni_user_details g  where g.id=?";



        $query = $this->db->query($sql,array($id));
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->row()->stu_name;
        } else {
            return false;
        }
        }
    }
	
	
	
	
    function get_final_foil_status($sy,$did,$cid,$bid,$sem,$stype){
        
        if($stype=='cgpa'){ $order_by=" order by a.core_cgpa desc "; }
        
        if($stype=='hons'){ $order_by=" and a.hstatus='Y' order by a.cgpa desc "; }
        
       /* $sql = "select a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,a.core_cgpa,a.cgpa,a.`session`,a.hstatus from final_semwise_marks_foil a
inner join user_details b on a.admn_no=b.id
where a.session_yr=? and a.dept=?
and a.course=? and a.branch=?
and a.semester=? and a.`status`='PASS'".$order_by;



        $query = $this->db->query($sql,array($sy,$did,$cid,$bid,$sem_end));
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            //return false;
			$sql = "select a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as stu_name,a.core_cgpa,a.cgpa,a.`session`,a.hstatus from alumni_final_semwise_marks_foil a
inner join alumni_user_details b on a.admn_no=b.id
where a.session_yr=? and a.dept=?
and a.course=? and a.branch=?
and a.semester=? and a.`status`='PASS'".$order_by;



        $query = $this->db->query($sql,array($sy,$did,$cid,$bid,$sem_end));
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        }
		
		*/
			
		
			
	  if($sem<>null){
        for ($i = $sem; $i >= 1; $i--) {
            $lst.=$i . ($i == 1 ? "" : ",");
            $lst_old.= ($i == 10?"'X'":$i) . ($i == 1 ? "" : ",");
        }
        //echo  $lst ; die();
        if (substr_count($lst, ',') > 0) {
            $s_replace = " and a.semester in (" . $lst . ")";
            $s_replace_old = " and right(a.sem_code,1) in (" . $lst_old . ")";
        } else {
            $s_replace = "  and a.semester ='" . $lst . "' ";
            $s_replace_old = "  and right(a.sem_code,1) ='" . $lst_old . "' ";
        }
		  }
		
			
			
		
	  if ($cid == 'comm')
		  $commstr=" and course_aggr_id like  '%comm%' ";
	  
		  if($stype=='cgpa'){
    	   $order_by=" order by y.core_cgpa desc ";         
		   $order_by2=" order by g.core_cgpa desc ";         
            $select_passfail=" a.core_status as  passfail  ";
		  }
		  else if($stype=='hons'){
    		$order_by=" order by y.cgpa desc "; 
			$order_by2=" order by g.cgpa desc "; 
		    $select_passfail=" a.status as  passfail ";
		  }
		
$sql="SELECT v.*, (@rank := @rank +1) AS rank 
from
(
select   concat_ws(' ',u.salutation,u.first_name,u.middle_name,u.last_name) as  stu_name, g.*    FROM  
     (select y.*,  rg.session as  sess, rg.session_year as  sessyr  from
 
  (SELECT SUM(IF ((TRIM(x.passfail='F') OR TRIM(x.passfail)='FAIL' OR TRIM(x.passfail)='fail'), 1, 0)) AS count_status  ,x.*

from

(
SELECT B.*
FROM (


select p.* from(
SELECT  a.admn_no,  $select_passfail , a.type, NULL AS sem_code, a.semester AS sem,a.session_yr , a.cgpa,a.core_cgpa, a.gpa, a.core_gpa,a.course,a.branch,a.dept
,a.actual_published_on ,(case  when  a.tot_cr_hr<>a.core_tot_cr_hr  then 'Y' else 'N' end )  as hstatus ,a.session   
FROM final_semwise_marks_foil_freezed a
WHERE   a.course<>'MINOR'  and a.course<>'JRF'  $s_replace  and  a.`type`='R' and a.session<>'Summer'
and  (a.course='comm' or a.course=?) and (a.branch in('A','B','C','D','E','F','G','H') or a.branch=?)
/*GROUP BY a.session_yr,a.session,a.semester,a.exam_type */
ORDER BY  a.admn_no, a.session_yr desc  ,  a.semester DESC,  a.actual_published_on desc limit  10000000 )p


UNION 


			SELECT A.*
			FROM (
			SELECT a.adm_no as  admn_no, a.passfail ,a.examtype AS type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession as session_yr,
			a.ogpa as cgpa , 	a.ogpa as core_cgpa, a.gpa, a.gpa as core_gpa, null as course, null as branch, null as dept, null as actual_published_on,
              'N' AS hstatus,a.wsms as session
			FROM tabulation1 a
			WHERE a.sem_code not like 'PREP%'  $s_replace_old  /* and  a.adm_no='14JE000638'*/
			AND a.sem_code in (
SELECT d.semcode
FROM dip_m_semcode d
WHERE  (d.course=? or d.course='comm') AND   (d.branch=? or d.branch='comm' ))
and  a.examtype='R'
			/*GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms			*/
			ORDER BY a.adm_no,a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC limit 1000000000)A



 )B
GROUP BY B.admn_no, B.session_yr,B.sem    ORDER BY  B.admn_no, B.session_yr desc  ,  B.sem DESC limit  10000000  )

x group by x.admn_no

)y

join  reg_regular_form rg  on rg.admn_no=y.admn_no    and  rg.session_year=?   and  rg.course_id=? and branch_id=?    and rg.`session`= (case when ?%2=0 then 'Winter' else 'Monsoon' end)  and rg.semester=? 
 and  rg.acad_status= '1'  $commstr 
 having    count_status=0
  $order_by   limit 1000000)g
left join  user_details u  on  u.id=g.admn_no    $order_by2   limit 1000000 )v,
( select  @rank := 0) SQLVars  ";		
     
	  $query = $this->db->query($sql,array($cid,$bid,$cid,$bid,$sy,$cid,$bid,$sem,$sem));
	  
     //  echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }		
			
    
	}
	
	
	
	
	
    

}

