<?php 
class Marks_admin_model extends CI_model{
	
	function getregularformbyId($data){
		
		$qu=$this->db->get_where('reg_regular_form',array('admn_no'=>$data['admn_no'],'session_year'=>$data['session_year'],'session'=>$data['session']));
		
		if($qu->num_rows() > 0)
			return $qu->result();
		return false;
	}
	
	function getotherformbyId($data){
		
		$qu=$this->db->get_where('reg_other_form',array('admn_no'=>$data['admn_no'],'session_year'=>$data['session_year'],'session'=>$data['session']));
		if($qu->num_rows() > 0)
			return $qu->result();
		return false;
	}
	
	function getexamformbyId($data){
		
		$qu=$this->db->get_where('reg_exam_rc_form',array('admn_no'=>$data['admn_no'],'session_year'=>$data['session_year'],'session'=>$data['session']));
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
	
	function GetSubjectSemester($id){
		$q = $this->db->get_where('course_structure', array('id' => $id));
        if ($q->num_rows($q)) {
 
            return $q->row();
        }
        return false;
	}
        function get_stu_exam_details_exam($admn_no,$form_id)
    {
          
        /*$sql = "select b.dept_id,a.course_id,a.branch_id,a.semester,'regular'as exam_type,a.session_year,a.`session`,a.section from reg_regular_form a inner join user_details b on a.admn_no=b.id where admn_no=? and form_id=?";*/

        $sql="SELECT b.dept_id,a.course_id,a.branch_id,a.semester,'spl' AS exam_type,a.session_year,a.`session`,c.section
FROM reg_exam_rc_form a
INNER JOIN user_details b ON a.admn_no=b.id
left join stu_section_data c on (c.admn_no=a.admn_no and c.session_year=a.session_year)
WHERE a.admn_no=? AND a.form_id=?";
        $query = $this->db->query($sql,array($admn_no,$form_id));
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_stu_exam_details_other($admn_no,$form_id)
    {
          
        /*$sql = "select b.dept_id,a.course_id,a.branch_id,a.semester,'regular'as exam_type,a.session_year,a.`session`,a.section from reg_regular_form a inner join user_details b on a.admn_no=b.id where admn_no=? and form_id=?";*/

        $sql="SELECT b.dept_id,a.course_id,a.branch_id,a.semester,'other' AS exam_type,a.session_year,a.`session`,c.section
FROM reg_other_form a
INNER JOIN user_details b ON a.admn_no=b.id
left join stu_section_data c on (c.admn_no=a.admn_no and c.session_year=a.session_year)
WHERE a.admn_no=? AND a.form_id=?";
        $query = $this->db->query($sql,array($admn_no,$form_id));
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_stu_exam_details($admn_no,$form_id)
    {
          
        /*$sql = "select b.dept_id,a.course_id,a.branch_id,a.semester,'regular'as exam_type,a.session_year,a.`session`,a.section from reg_regular_form a inner join user_details b on a.admn_no=b.id where admn_no=? and form_id=?";*/

        $sql="SELECT b.dept_id,a.course_id,a.branch_id,a.semester,'regular' AS exam_type,a.session_year,a.`session`,c.section
				FROM reg_regular_form a INNER JOIN user_details b ON a.admn_no=b.id 
				left join stu_section_data c on (c.admn_no=a.admn_no and c.session_year=a.session_year)
				WHERE a.admn_no=? AND a.form_id=?";
        $query = $this->db->query($sql,array($admn_no,$form_id));
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	
	
}
?>