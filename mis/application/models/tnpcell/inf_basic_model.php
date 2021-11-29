<?php

class Inf_basic_model extends CI_Model
{
	var $table_inf_courses = 'inf_courses';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function add_course($course_id)
	{
		$query = $this->db->query("INSERT INTO `inf_courses`(`course_id`) VALUES (?)",$course_id);
    if($this->db->affected_rows()){
      return true;  
    }
		else{
      return false;
    }
	}	


  function delete_course($course_id)
  {
    $query = $this->db->query("DELETE FROM `inf_courses` WHERE course_id=? LIMIT 1",$course_id);
    if($this->db->affected_rows()){
      return true;  
    }
    else{
      return false;
    }
  }

  function add_sector($sector_name)
  {
    $query = $this->db->query("INSERT INTO `jnf_allowed_company_sectors`(`sector_name`) VALUES (?)",$sector_name);
    if($this->db->affected_rows()){
      return true;  
    }
    else{
      return false;
    }
  }

  function delete_sector($sector_id)
  {
    $query = $this->db->query("DELETE FROM `jnf_allowed_company_sectors` WHERE sector_id=? LIMIT 1",$sector_id);
    if($this->db->affected_rows()){
      return true;
    }
    else{
      return false;
    }
  }

  function add_category($category_name)
  {
    $query = $this->db->query("INSERT INTO `jnf_company_category`(`category_name`) VALUES (?)",$category_name);
    if($this->db->affected_rows()){
      return true;  
    }
    else{
      return false;
    }
  }

  function delete_category($category_id)
  {
    $query = $this->db->query("DELETE FROM `jnf_company_category` WHERE category_id=? LIMIT 1",$category_id);
    if($this->db->affected_rows()){
      return true;
    }
    else{
      return false;
    }
  }
  function company_name()
  {

      $month=date('m');
      $year=date('Y');
      if($month>=7)
      $sess=$year."-".($year+1);
      else
      $sess=($year-1)."-".$year;
      $query=$this->db->query("SELECT company_id,company_name,session FROM `jnf_user_details` WHERE session like '$sess' ")->result();//Kuldeep 11.8.17
      return $query;
  }
  function poc_details($comp_id)
  {
       $month=date('m');
       $year=date('Y');
       if($month>=7)
       $sess=$year."-".($year+1);
       else
       $sess=($year-1)."-".$year; 
       $query=$this->db->query("SELECT * FROM `jnf_user_details` NATURAL JOIN `jnf_users` WHERE company_id like '$comp_id' and session like '$sess'")->row();
       return $query;
  }

  function poc_details_for_recruiter($comp_id)
  {
       $query=$this->db->query("SELECT * FROM `jnf_user_details` NATURAL JOIN `jnf_users` WHERE company_id like '$comp_id'")->row();
       return $query;
  }
  
  function company_detail($comp_id)
  {
        $data=array();
        $query=$this->db->query("SELECT * FROM `inf_company_details` where company_id like '$comp_id' ")->result(); //inf_company Kuldeep 18.10.17
        $data['category']=$query[0]->category;
        $data['industry']=$query[0]->industry;
        $data['job_designation']=$query[0]->job_designation;
        $data['job_description']=$query[0]->job_description;
        $data['job_posting']=$query[0]->job_posting;
        $query=$this->db->query("SELECT * FROM `inf_eligible_branches` where company_id like '$comp_id' ")->result();
        $i=0;
        $data['course_branch_id']=array();
         foreach ($query as $key => $value) {
            $data['course_branch_id'][$i]=$value->course_branch_id;
            $i++;
         }
         $query=$this->db->query("SELECT * FROM `inf_logistics` where company_id like '$comp_id' ")->result();
          $data['ppt_room']=$query[0]->ppt_room;
          $data['laptop']=$query[0]->laptop;
          $data['projector']=$query[0]->projector;
          $data['printer']=$query[0]->printer;
          $data['interview_room']=$query[0]->interview_room;
          $data['any_other']=$query[0]->any_other;
          
          $query=$this->db->query("SELECT * FROM `inf_salary` where company_id like '$comp_id' ")->result();
          $data['stipend']=$query[0]->stipend;
          $data['relocation']=$query[0]->relocation; //Kuldeep 19.8.17
          
          $query=$this->db->query("SELECT course_id,10marks as marks10,12marks as marks12,UG,PG FROM `inf_selectioncutoff` where company_id like '$comp_id' ")->result();

           $data['inf_selection_process']=array(); //dinesh
           $courses = $this->db->query("SELECT * FROM inf_courses as jc 
            INNER JOIN cs_courses as cc ON cc.id = jc.course_id WHERE 1;")->result();
           foreach ($courses as $key => $value) {
             $data['inf_selection_process'][$value->id]['10marks']='';
             $data['inf_selection_process'][$value->id]['12marks']='';
             $data['inf_selection_process'][$value->id]['UG']='';
             $data['inf_selection_process'][$value->id]['PG']='';
           }
           foreach ($query as $key => $que) {
           $data['inf_selection_process'][$que->course_id]['10marks'] = $que->marks10;
           $data['inf_selection_process'][$que->course_id]['12marks'] = $que->marks12;
           $data['inf_selection_process'][$que->course_id]['UG']=$que->UG;
           $data['inf_selection_process'][$que->course_id]['PG']=$que->PG;
          
           }

   /*-------------------------------------dinesh--------------------------------------------*/



            $query=$this->db->query("SELECT * FROM `inf_selectionprocess` where company_id like '$comp_id' ")->result();
            $data['shortlist_resume']=$query[0]->shortlist_resume;
            $data['written_tech']=$query[0]->written_tech;
            $data['written_ntech']=$query[0]->written_ntech;
            $data['gd']=$query[0]->gd;
            $data['tech_interview']=$query[0]->tech_interview;
            $data['hr_interview']=$query[0]->hr_interview;
            $data['year_gap']=$query[0]->year_gap;
            $data['mode_interview']=$query[0]->mode_interview;
            $data['mode_written']=$query[0]->mode_written;
            $data['total_round']=$query[0]->total_round;
            if($data['total_round']==0)
              $data['total_round']='';
            $data['number_of_offer']=$query[0]->number_of_offer;
            $data['bond']=$query[0]->bond;
            $data['bond_details']=$query[0]->bond_details;
            $data['duration']=$query[0]->duration;
            $data['back_log']=$query[0]->back_log;//15 may
        return $data;
  }
}

/* End of file emp_current_entry_model.php */
/* Location: Codeigniter/application/models/employee/emp_current_entry_model.php */
