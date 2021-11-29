<?php

class Consultant_disbursement_details_model extends CI_Model
{

	var $table = 'consultancy_disbursement_member';
	var $table1 = 'consultancy_disbursement_staff';
	var $table2 = 'consultancy_disbursement';
	var $table3 = 'consultancy_disbursement_member_mod';
	var $table4 = 'consultancy_disbursement_staff_mod';
	var $table5 = 'consultancy_memo';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	function insert_member($data)
	{
		$this->db->insert($this->table,$data);
	}
	function insert_member_mod($sr_no)
	{
		$this->db->query("INSERT INTO consultancy_disbursement_member_mod
						SELECT * FROM consultancy_disbursement_member 
						WHERE sr_no='$sr_no';");
		$this->db->query("DELETE FROM consultancy_disbursement_member
						WHERE sr_no='$sr_no';");
	}
	function insert_staff($data1)
	{
		$this->db->insert($this->table1,$data1);
	}
	function insert_staff_mod($sr_no)
	{
		$this->db->query("INSERT INTO consultancy_disbursement_staff_mod
						SELECT * FROM consultancy_disbursement_staff 
						WHERE sr_no='$sr_no';");
		$this->db->query("DELETE FROM consultancy_disbursement_staff 
						WHERE sr_no='$sr_no';");

	}
	function disbursement_approve($sr_no,$status)
	{
		$this->db->query("UPDATE consultancy_disbursement set status='$status' where sr_no='$sr_no';");
	}
	function get_ci($sr_no)
	{
		$this->db->select();
		$this->db->where('sr_no',$sr_no);
		$this->db->where('position','ci');
		$query = $this->db->get('consultancy_member');
		return $query->row();
	}
	function get_default($id_user)
	{
		$query=("SELECT * FROM consultancy_disbursement WHERE consultancy_disbursement.sr_no = '$id_user'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_default_prev($id_user,$mod)
	{
		$query=("SELECT * FROM consultancy_disbursement_modification 
			WHERE consultancy_disbursement_modification.sr_no = '$id_user' AND consultancy_disbursement_modification.modification_value = '$mod'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_disbursement($sr_no)
	{
		$this->db->select();
		$this->db->where('sr_no',$sr_no);
		$query = $this->db->get($this->table2);
		return $query->row();

	}
	function get_disbursement_prev($id_user)
	{
		//print_r($id_user);
		$query=("SELECT * FROM consultancy_disbursement_modification
			WHERE consultancy_disbursement_modification.sr_no = '$id_user'
			ORDER BY  consultancy_disbursement_modification.modification_value desc");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_disbursement_all()
	{
		$query=("SELECT * FROM consultancy_disbursement");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_disbursement_ci($id_user)
	{
		$query=("SELECT * FROM consultancy_disbursement 
			INNER JOIN consultancy_disbursement_member ON consultancy_disbursement.consultancy_no = consultancy_disbursement_member.consultancy_no
			WHERE consultancy_disbursement_member.emp_no = '$id_user' AND consultancy_disbursement_member.position = 'ci'
			ORDER BY  consultancy_disbursement.consultancy_no asc");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_disbursement_hod($id_user)
	{
		$query=("SELECT * FROM consultancy_disbursement 
			INNER JOIN consultancy_disbursement_member ON consultancy_disbursement.consultancy_no = consultancy_disbursement_member.consultancy_no
			INNER JOIN user_details ON user_details.id = consultancy_disbursement_member.emp_no
			WHERE user_details.dept_id = '$id_user' AND consultancy_disbursement_member.position = 'ci'
			ORDER BY  consultancy_disbursement.consultancy_no asc");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_consultancy_no($id_user)
	{
		$query=("SELECT * FROM consultancy_proposal_details WHERE consultancy_proposal_details.sr_no = '$id_user'");
		$query1=$this->db->query($query);
		//print_r($id_user);
		return $query1->result();
	}
	function get_consultancy_title($id_user)
	{
		$query=("SELECT * FROM consultancy_charges WHERE consultancy_charges.sr_no = '$id_user'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_member($sr_no)
	{
		$query=("SELECT consultancy_disbursement_member.* FROM consultancy_disbursement_member
					WHERE consultancy_disbursement_member.sr_no = '$sr_no'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_consultant($id_user)
	{

		$query=("SELECT consultancy_disbursement_member.*,user_details.*,departments.*,emp_basic_details.*,designations.name AS designation
					FROM consultancy_disbursement_member
					INNER JOIN user_details ON consultancy_disbursement_member.emp_no = user_details.id
					INNER JOIN departments ON user_details.dept_id = departments.id
					INNER JOIN emp_basic_details ON consultancy_disbursement_member.emp_no = emp_basic_details.emp_no
					INNER JOIN designations ON emp_basic_details.designation = designations.id
					WHERE consultancy_disbursement_member.sr_no = '$id_user'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_consultant_prev($id_user,$mod)
	{

		$query=("SELECT consultancy_disbursement_member_mod.*,user_details.*,departments.*,emp_basic_details.*,designations.name AS designation
					FROM consultancy_disbursement_member_mod
					INNER JOIN user_details ON consultancy_disbursement_member_mod.emp_no = user_details.id
					INNER JOIN departments ON user_details.dept_id = departments.id
					INNER JOIN emp_basic_details ON consultancy_disbursement_member_mod.emp_no = emp_basic_details.emp_no
					INNER JOIN designations ON emp_basic_details.designation = designations.id
					WHERE consultancy_disbursement_member_mod.sr_no = '$id_user' AND consultancy_disbursement_member_mod.modification_value = '$mod'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_staffs($id_user)
	{

		$query=("SELECT consultancy_disbursement_staff.*,user_details.*,departments.*,emp_basic_details.*,designations.name AS designation
					FROM consultancy_disbursement_staff
					INNER JOIN user_details ON consultancy_disbursement_staff.emp_no = user_details.id
					INNER JOIN departments ON user_details.dept_id = departments.id
					INNER JOIN emp_basic_details ON consultancy_disbursement_staff.emp_no = emp_basic_details.emp_no
					INNER JOIN designations ON emp_basic_details.designation = designations.id
					WHERE consultancy_disbursement_staff.sr_no = '$id_user'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_staffs_member($id_user)
	{

		$query=("SELECT consultancy_disbursement_staff.* FROM consultancy_disbursement_staff
					WHERE consultancy_disbursement_staff.sr_no = '$id_user'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_staffs_prev($id_user,$mod)
	{

		$query=("SELECT consultancy_disbursement_staff_mod.*,user_details.*,departments.*,emp_basic_details.*,designations.name AS designation
					FROM consultancy_disbursement_staff_mod
					INNER JOIN user_details ON consultancy_disbursement_staff_mod.emp_no = user_details.id
					INNER JOIN departments ON user_details.dept_id = departments.id
					INNER JOIN emp_basic_details ON consultancy_disbursement_staff_mod.emp_no = emp_basic_details.emp_no
					INNER JOIN designations ON emp_basic_details.designation = designations.id
					WHERE consultancy_disbursement_staff_mod.sr_no = '$id_user' AND consultancy_disbursement_staff_mod.modification_value = '$mod'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function update_pce($data1,$data2)
	{
		$temp1=$data1['gas_previous'];
		$temp2=$data1['fy_gas_previous'];
		$temp3=$data1['total_current_fy'];
		$emp_no=$data2['emp_no'];
		$cons_no=$data2['consultancy_no'];
		//print_r($emp_no);
		/*print_r($temp2);
		print_r($temp3);*/

		$query=("UPDATE `consultancy_disbursement_staff` SET `gas_previous`= $temp1,
				`fy_gas_previous`= $temp2,`total_current_fy`= $temp3
					WHERE `emp_no`= $emp_no AND `consultancy_no` = $cons_no");
		$query1=$this->db->query($query);
		//$this->db->update('consultancy_disbursement_staff', $data1, "emp_no = 806");
	}
	function insert_pce_memo($data3)
	{
		$this->db->insert($this->table5,$data3);
	}
	function delete_pce_memo($cons_no)
	{
		$query=("DELETE FROM `consultancy_memo`
					WHERE `consultancy_no` = $cons_no");
		$query1=$this->db->query($query);
		
	}
	function get_pce_memo($sr_no)
	{
		$query=("SELECT * FROM consultancy_memo WHERE consultancy_memo.sr_no = '$sr_no'");
		$query1=$this->db->query($query);
		return $query1->result();
		
	}
	function delete_disbursement($cons_no)
	{
		$query=("DELETE FROM `consultancy_disbursement`
					WHERE `consultancy_no` = $cons_no");
		$query1=$this->db->query($query);
		
	}
	function delete_disbursement_members($cons_no)
	{
		
		$query2=("DELETE FROM `consultancy_disbursement_member`
					WHERE `consultancy_no` = $cons_no");
		$query3=$this->db->query($query2);
		
	}
	function delete_disbursement_staffs($cons_no)
	{
		$query4=("DELETE FROM `consultancy_disbursement_staff`
					WHERE `consultancy_no` = $cons_no");
		$query5=$this->db->query($query4);
	}
	function get_disbursement_action($sr_no)
	{

		$query=$this->db->query("SELECT consultancy_disbursement.*,consultancy_form_track.*
								 FROM consultancy_disbursement     
                                INNER JOIN consultancy_form_track
                                ON consultancy_disbursement.sr_no=consultancy_form_track.sr_no
                               WHERE  consultancy_disbursement.sr_no='$sr_no'
                                ORDER BY  consultancy_form_track.timestamp desc;");
        			return $query->result();
	}
}

/* End of file post_notice_model.php */
/* Location: mis/application/models/post_notice_model.php */