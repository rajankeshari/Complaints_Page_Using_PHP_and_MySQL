<?php
class cbcs_curriculam_master_model extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
		date_default_timezone_set('Asia/Calcutta');
	}

	public function get_credit_point_master(){
		$result=$this->db->query("SELECT * FROM `cbcs_credit_points_master`");
		$result=$result->result();
		return $result;
	}
	public function get_credit_point_master_deptwise($deptid){
	
		//$result=$this->db->query("SELECT * FROM `cbcs_credit_points_master` where dept_id='$deptid' ");
		
		$result=$this->db->query("SELECT a.*,b.name AS cname FROM `cbcs_credit_points_master` a 
INNER JOIN cbcs_courses b ON b.id=a.course_id where dept_id='$deptid' ");
		$result=$result->result();
		return $result;
	}

	/*public function get_course_component(){
		$result=$this->db->query("SELECT * FROM `cbcs_course_component`");
		$result=$result->result();
		return $result;
	}*/
	
	public function get_course_component($session_yr,$discipline){
		
		$result=$this->db->query("SELECT * FROM `cbcs_course_component` where course_id='$discipline' and status='Active' and effective_year='$session_yr'");
		$result=$result->result();
		return $result;
	}
	
	public function get_curriculam_master_data(){
		$result=$this->db->query("SELECT *,A.remarks AS a_remarks,concat(B.wef,'(',B.dept_id,'/',B.course_id,'/',B.branch_id,')') AS credit_point_master FROM `cbcs_curriculam_master` AS A JOIN `cbcs_credit_points_master` AS B ON A.cbcs_credit_points_master=B.id JOIN `cbcs_course_component` AS C ON A.course_comp=C.id group by A.id");
		$result=$result->result();
		return $result;
	}
	
	public function get_curriculam_master_data_deptwise($deptid){
		//$result=$this->db->query("SELECT *,A.remarks AS a_remarks,concat(B.wef,'(',B.dept_id,'/',B.course_id,'/',B.branch_id,')') AS credit_point_master FROM `cbcs_curriculam_master` AS A JOIN `cbcs_credit_points_master` AS B ON A.cbcs_credit_points_master=B.id JOIN `cbcs_course_component` AS C ON A.course_comp=C.id where B.dept_id='$deptid' group by A.id");
		
		$result=$this->db->query("SELECT *,A.remarks AS a_remarks, CONCAT(B.wef,'(',upper(B.dept_id),'/',UPPER(CASE WHEN B.course_id='jrf' THEN 'PHD'  ELSE B.course_id END),'/',upper(B.branch_id),')') AS credit_point_master
FROM `cbcs_curriculam_master` AS A
JOIN `cbcs_credit_points_master` AS B ON A.cbcs_credit_points_master=B.id
JOIN `cbcs_course_component` AS C ON A.course_comp=C.id
WHERE B.dept_id='$deptid'
GROUP BY A.id");
		$result=$result->result();
		return $result;
	}

	public function get_credit_point_master_ajax($id){
		$result=$this->db->query("SELECT * FROM `cbcs_credit_points_master` WHERE `id`='$id'");
		$result=$result->result();
		return $result;
	}

	public function insert_curiculam_master($data){
		if($this->db->insert('cbcs_curriculam_master',$data))
			return true;
		else
			return false;
	}

	public function delete_curiculam_master($id){
		$this->db->query("INSERT INTO cbcs_curriculam_master_backup SELECT * FROM cbcs_curriculam_master WHERE cbcs_credit_points_master='$id'");
		$user_id=$this->session->userdata('id');
		$date=date('Y-m-d H:i:s');
		$this->db->query("UPDATE `cbcs_curriculam_master_backup` SET `action`='delete',`last_updated_by`='$user_id'");
		if($this->db->query("DELETE FROM cbcs_curriculam_master WHERE cbcs_credit_points_master='$id'"))
			return TRUE;
		else
			return FALSE;
	}

	public function get_edit_credit_point_master($id){
		$result=$this->db->query("SELECT * FROM `cbcs_credit_points_master` WHERE id='$id'");
		$result=$result->result();
		return $result;
	}

	public function get_edit_curriculam_master_data($id){
		$result=$this->db->query("SELECT *,A.id AS aid,A.remarks AS a_remarks,concat(B.wef,'(',B.dept_id,'/',B.course_id,'/',B.branch_id,')') AS credit_point_master FROM `cbcs_curriculam_master` AS A JOIN `cbcs_credit_points_master` AS B ON A.cbcs_credit_points_master=B.id JOIN `cbcs_course_component` AS C ON A.course_comp=C.id WHERE cbcs_credit_points_master='$id' group by A.id");
		$result=$result->result();
		return $result;
	}

	public function update_curiculam_master($data,$id){

		$query = $this->db->query("INSERT INTO cbcs_curriculam_master_backup SELECT * FROM cbcs_curriculam_master WHERE id='$id'");

        // $sql = "update cbcs_credit_points_policy_backup set action='".$action."' where id=".$id;
        // $query = $this->db->query($sql);

		$this->db->where('id',$id);
		if($this->db->update('cbcs_curriculam_master',$data))
            return TRUE;
        else
            return FALSE;
	}

	public function check_duplicate_master($ccp){
		$result=$this->db->query("SELECT * FROM `cbcs_curriculam_master` WHERE `cbcs_credit_points_master`='$ccp'");

		return $result->num_rows();
	}

	//Import Data
	
	
    function insert_fee_structure_all($data,$type)
	{
           
           
		$this->db->insert_batch('cbcs_curriculam_master',$data['values']);
                //$this->db->last_query();
                //die("insert data");
	}
}

?>