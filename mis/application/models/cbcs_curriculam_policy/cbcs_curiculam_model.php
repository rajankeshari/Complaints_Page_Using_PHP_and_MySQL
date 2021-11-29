<?php
class cbcs_curiculam_model extends CI_model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}
		
	public function get_credit_point(){
		//$result=$this->db->query("SELECT * FROM `cbcs_credit_points_policy`");
		$result=$this->db->query("SELECT a.*,b.name as cname FROM cbcs_credit_points_policy a 
									INNER JOIN cbcs_courses b ON b.id=a.course_id
									WHERE b.`status`='1'");
		$result=$result->result();
		return $result;
	}

	public function get_course_component($session_yr,$discipline){
		$result=$this->db->query("SELECT * FROM `cbcs_course_component` where course_id='$discipline' and status='Active' and effective_year='$session_yr'");
		$result=$result->result();
		return $result;
	}

	public function get_curriculam_data(){
		//$result=$this->db->query("SELECT *,A.id AS aid,A.remarks AS a_remarks,concat(B.wef,'(',B.course_id,')') AS credit_point,C.name AS c_name FROM `cbcs_curriculam_policy` AS A JOIN `cbcs_credit_points_policy` AS B ON A.cbcs_credit_points_policy_id=B.id JOIN `cbcs_course_component` AS C ON A.course_comp=C.id group by A.id");
		
		$result=$this->db->query("SELECT *,A.id AS aid,A.remarks AS a_remarks, CONCAT(B.wef,'(',CC.name,')') AS credit_point,C.name AS c_name
FROM `cbcs_curriculam_policy` AS A
JOIN `cbcs_credit_points_policy` AS B ON A.cbcs_credit_points_policy_id=B.id
JOIN `cbcs_course_component` AS C ON A.course_comp=C.id
INNER JOIN cbcs_courses CC ON CC.id=B.course_id
GROUP BY A.id");
		$result=$result->result();
		return $result;
	}

	public function get_credit_point_ajax($id){
		$result=$this->db->query("SELECT * FROM `cbcs_credit_points_policy` WHERE `id`='$id'");
		$result=$result->result();
		return $result;
	}

	public function check_duplicate($ccp){
		$result=$this->db->query("SELECT * FROM `cbcs_curriculam_policy` WHERE `cbcs_credit_points_policy_id`='$ccp'");

		return $result->num_rows();
	}
	
	public function insert_curiculam($data){
		if($this->db->insert('cbcs_curriculam_policy',$data))
            return TRUE;
        else
            return FALSE;
	}

	// public function insert_backup($id){

	// }


	public function update_curiculam($data,$id){
		
        $query = $this->db->query("INSERT INTO cbcs_curriculam_policy_backup SELECT * FROM cbcs_curriculam_policy WHERE id='$id'");

        // $sql = "update cbcs_credit_points_policy_backup set action='".$action."' where id=".$id;
        // $query = $this->db->query($sql);

		$this->db->where('id',$id);
		if($this->db->update('cbcs_curriculam_policy',$data))
            return TRUE;
        else
            return FALSE;

	}

	public function delete_curiculam($id){
		$this->db->query("INSERT INTO cbcs_curriculam_policy_backup SELECT * FROM cbcs_curriculam_policy WHERE cbcs_credit_points_policy_id='$id'");
		$this->db->query("UPDATE `cbcs_curriculam_policy_backup` SET `action`='delete'");
		if($this->db->query("DELETE FROM cbcs_curriculam_policy WHERE cbcs_credit_points_policy_id='$id'"))
			return TRUE;
		else
			return FALSE;
	}


	public function get_edit_credit_point($id){
		$result=$this->db->query("SELECT * FROM `cbcs_credit_points_policy` WHERE `id`='$id'");
		$result=$result->result();
		return $result;
	}

	public function get_edit_curriculam_data($id){
		$result=$this->db->query("SELECT *,A.id AS aid,A.remarks AS a_remarks,concat(B.wef,'(',B.course_id,')') AS credit_point,C.name AS c_name FROM `cbcs_curriculam_policy` AS A JOIN `cbcs_credit_points_policy` AS B ON A.cbcs_credit_points_policy_id=B.id JOIN `cbcs_course_component` AS C ON A.course_comp=C.id WHERE `cbcs_credit_points_policy_id`='$id' group by A.id");
		$result=$result->result();
		return $result;
	}
}
?>