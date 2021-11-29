<?php 
class Form_admin_model extends CI_model{
	
	function getregularformbyId($data){
		
		
		$q= "select * from reg_regular_form a where a.admn_no=? order by a.session_year desc,cast(a.semester as unsigned)  desc, date(a.timestamp) desc ";
		$qu = $this->db->query($q,array($data['admn_no']));
		
		if($qu->num_rows() > 0)
			return $qu->result();
		return false;
	}
	
	function getotherformbyId($data){
		
		$q= "select * from reg_other_form a where a.admn_no=? order by a.session_year desc,cast(a.semester as unsigned)  desc , date(a.timestamp) desc ";
		$qu = $this->db->query($q,array($data['admn_no']));
		if($qu->num_rows() > 0){
			return $qu->result();
		}
		else{
			$q= "select * from reg_summer_form a where a.admn_no=? order by a.session_year desc,cast(a.semester as unsigned)  desc , date(a.timestamp) desc ";
		$qu = $this->db->query($q,array($data['admn_no']));
		if($qu->num_rows() > 0){
			return $qu->result();
		}
		else{
			return false;
		}
		}
		
	}
	
	function getexamformbyId($data){
		
		$q= "select * from reg_exam_rc_form a where a.admn_no=? order by a.session_year desc,cast(a.semester as unsigned)  desc , date(a.timestamp) desc ";
		$qu = $this->db->query($q,array($data['admn_no']));
		if($qu->num_rows() > 0)
			return $qu->result();
		return false;
	}
	
	function getstudept($id){
		$qu=$this->db->get_where('user_details',array('id'=>$id));
		return $qu->row()->dept_id;
	}
	
	
	function getmapdes($map_id,$sub_id){
		$q = $this->db->get_where('subject_mapping_des',array('map_id'=>$map_id,'sub_id'=>$sub_id));
		if($q->num_rows() >0)
			return $q->row();
		return false;
	}
	
	function markschek($map_id,$sub_id,$type){
		$q = $this->db->get_where('marks_master',array('sub_map_id'=>$map_id,'subject_id'=>$sub_id,'type'=>$type));
			if($q->num_rows() >0)
			return $q->row()->id;
		return false;
	}
	
	function marksdeschek($id,$admn_no){
		$q = $this->db->get_where('marks_subject_description',array('marks_master_id'=>$id,'admn_no'=>$admn_no));
			if($q->num_rows() >0)
			return $q->row();
		return false;
	}
	function marksdesfilter($id){
		$q = $this->db->get_where('marks_subject_description',array('marks_master_id'=>$id));
			if($q->num_rows() >0)
			return $q->row();
		return false;
	}
	function insertMarksDes($data){
		if($this->db->insert('marks_subject_description',$data))
			return $this->db->insert_id();
		return false;
		
	}
	
	function updateMarksDes($data,$id){
		if($this->db->update('marks_subject_description',$data,array('id'=>$id)));
			return true;
		return false;
		
	}
	
	function getSubjectById($id) {
        $q = $this->db->get_where('subjects', array('id' => $id));
        if ($q->num_rows($q)) {
 
            return $q->row();
        }
        return false;
    }
	
	
	function getStuSectionData($sy,$id){
		
		$q = $this->db->get_where('stu_section_data', array('session_year' => $sy,'admn_no'=>$id));
        if ($q->num_rows($q)) {
 
            return $q->row();
        }
        return false;
	}
	
	function getStuGroup($sy,$sec){
		
		$q = $this->db->get_where('section_group_rel', array('session_year' => $sy,'section'=>$sec));
        if ($q->num_rows($q)) {
 
            return $q->row();
        }
        return false;
	}
	
	function GetStuDetailsById($id){
		$q=$this->db->select('stu_academic.admn_no, concat_ws(" ", user_details.first_name , user_details.middle_name , user_details.last_name ) as name,cs_courses.name as course,cs_branches.name as branch,stu_academic.semester,user_details.photopath,departments.name as department,stu_academic.course_id,stu_academic.branch_id,user_details.dept_id')
		->join('stu_academic','stu_academic.admn_no=user_details.id')
		->join('departments','user_details.dept_id=departments.id')
		->join('cs_courses','stu_academic.course_id=cs_courses.id','left')
		->join('cs_branches','stu_academic.branch_id=cs_branches.id','left')
		->get_where('user_details', array('stu_academic.admn_no' => $id));
		//echo $this->db->last_query();
        if ($q->num_rows($q)) {
 
            return $q->row();
        }
        return false;
	}
	//rawurldecode($this->input->post('aggrId')),$this->input->post('course_id'),$this->input->post('semester'),$this->input->post('id'),$this->input->post('sy'),$this->input->post('s')
	function GetStudentRegForm($aggrId,$course_id,$admn_no,$sessionYear,$session){
		
			$q=$this->db->query('select * from reg_regular_form a where a.session_year=? and a.`session`=? and a.admn_no=? and a.course_aggr_id=? and (a.hod_status="0" or a.hod_status="1") and (a.acad_status="0" or a.acad_status="1")',[$sessionYear,$session,$admn_no,$aggrId]);
				
			if($q->num_rows() > 0)
				return $q->row();
			return false;
		
	}
	
	function GetStuSection($sy,$id){
		$q=$this->db->select('section_group_rel.group,section_group_rel.section,section_group_rel.session_year')
		->join('section_group_rel','section_group_rel.session_year=stu_section_data.session_year and section_group_rel.section=stu_section_data.section')
		->get_where('stu_section_data',['stu_section_data.session_year'=>$sy,'stu_section_data.admn_no'=>$id]);

		//echo $this->db->last_query();
		if($q->num_rows() >0)
			return $q->row();
		return false;
	}


	function GetStuPassFailStatus($admn_no,$sem){
			$q="SET @admn_no = ?";
			$qu=$this->db->query($q,[$admn_no]);
			$q="SET @sem = ?";
			$qu=$this->db->query($q,[$sem]);
			
			$q="select z.* from(
			(
			SELECT B.*
			FROM (
			SELECT a.`status` AS passfail, a.exam_type, NULL AS sem_code, a.semester AS sem,a.session_yr, GROUP_CONCAT(b.sub_code) as Subject,GROUP_CONCAT(b.mis_sub_id) as mis_sub_id
			FROM final_semwise_marks_foil a join final_semwise_marks_foil_desc b on a.id=b.foil_id and b.grade = 'F'
			WHERE a.admn_no=@admn_no and  a.course<>'MINOR' and a.semester=@sem		
			GROUP BY a.session_yr ,a.session,a.semester,a.type			
			ORDER BY a.session_yr desc ,  a.semester DESC,   a.tot_cr_pts desc)B
			GROUP BY B.sem) 
			UNION (
			SELECT A.*
			FROM (
			SELECT a.passfail, a.examtype AS exam_type,a.sem_code, CAST(REVERSE(a.sem_code) AS UNSIGNED) AS sem,a.ysession as session_yr,GROUP_CONCAT(a.subje_code) as Subject, null as mis_sub_id
			FROM tabulation1 a
			WHERE a.adm_no=@admn_no and a.sem_code not like 'PREP%' and  right(a.sem_code,1)=(if(@sem='10','X',@sem) ) and a.passfail like '%f%'
			GROUP BY a.ysession,a.sem_code, a.examtype, a.wsms			
			ORDER BY a.ysession desc,sem DESC, a.wsms desc ,a.totcrpts desc ,a.examtype DESC)A
			GROUP BY A.sem_code)
			)z group by z.sem";

			$qu=$this->db->query($q);
			if($qu->num_rows > 0)
				return $qu->row();
			return false;
	}

	function getSubjectbyIsmSubId($sub_id,$aggrId,$semester){

			$qu=$this->db->query('select * from subjects join course_structure on subject.id=course_structure.id where subject.subject_id=?  and course_structure.semester=? limit 1',[$sub_id,$semster]);

			if($qu->num_rows > 0)
				return $qu->row(); 
			return false;
	}

	function updateStuAcademic($admn_no,$data){
		$this->db->update('stu_academic',$data,['admn_no'=>$admn_no]);
		return true;	
	}
	
	function SaveInDynamicTable($table,$data){
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}
	
}
?> 