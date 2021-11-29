
<?php
class Project_accounts extends CI_Model
{

	var $table = 'consultancy_disbursement_member';
	var $table1 = 'consultancy_disbursement_staff';
	var $table2 = 'consultancy_disbursement';
	var $table3 = 'consultancy_disbursement_member_mod';
	var $table4 = 'consultancy_disbursement_staff_mod';
	var $table5 = 'consultancy_memo';
	var $table6 = 'consultancy_project_account';
	var $table7 = 'consultancy_project_emp';
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	function get_disbursement_form()
	{
		$query=("SELECT sr_no,consultancy_no,consultancy_title,timestamp1,modification_value
					FROM consultancy_disbursement
					WHERE status = 103 ");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_project_account_form()
	{
		$query=("SELECT A.sr_no,A.timestamp,A.modification_value,B.status,B.consultancy_no,B.consultancy_title
				FROM (SELECT consultancy_project_account.sr_no,consultancy_project_account.timestamp,consultancy_project_account.modification_value
					FROM consultancy_project_account) as A
				INNER JOIN 
					(SELECT sr_no,consultancy_no,consultancy_title,timestamp1,modification_value,status
					FROM consultancy_disbursement) as B
				ON
					A.sr_no = B.sr_no
				");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_consultant($id_user)
	{
		$query=("SELECT consultancy_disbursement_member.*,user_details.*,departments.*,  `user_other_details`.`bank_accno` 
					FROM consultancy_disbursement_member
					INNER JOIN user_details ON consultancy_disbursement_member.emp_no = user_details.id
					INNER JOIN departments ON user_details.dept_id = departments.id
					INNER JOIN user_other_details ON consultancy_disbursement_member.emp_no = user_other_details.id
					
					WHERE consultancy_disbursement_member.sr_no = '$id_user'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_staffs($id_user)
	{
		$query=("SELECT consultancy_disbursement_staff.*,user_details.*,departments.*,  `user_other_details`.`bank_accno` 
					FROM consultancy_disbursement_staff
					INNER JOIN user_details ON consultancy_disbursement_staff.emp_no = user_details.id
					INNER JOIN departments ON user_details.dept_id = departments.id
					INNER JOIN user_other_details ON consultancy_disbursement_staff.emp_no = user_other_details.id
				
					WHERE consultancy_disbursement_staff.sr_no = '$id_user'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function insert_detail($data)
	{
		$this->db->insert($this->table6,$data);
	}
	function insert_emp($data)
	{
		foreach ($data as $key => $data1) {
			$this->db->insert($this->table7,$data1);
		}
	}
	function get_detail($sr_no)
	{
			$query=("SELECT consultancy_project_account.*
					FROM consultancy_project_account
					WHERE consultancy_project_account.sr_no = '$sr_no'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_detail_all()
	{
			$query=("SELECT consultancy_project_account.*,consultancy_charges.*
					FROM consultancy_project_account
					INNER JOIN consultancy_charges
					ON consultancy_charges.sr_no = consultancy_project_account.sr_no");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function get_emp($sr_no)
	{
		$query=("SELECT consultancy_project_emp.*,user_details.*,departments.*
					FROM consultancy_project_emp
					INNER JOIN user_details ON consultancy_project_emp.emp_no = user_details.id
					INNER JOIN departments ON user_details.dept_id = departments.id
					INNER JOIN user_other_details ON consultancy_project_emp.emp_no = user_other_details.id
				
					WHERE consultancy_project_emp.sr_no = '$sr_no'");
		$query1=$this->db->query($query);
		return $query1->result();
	}
	function edit_detail($sr_no)
	{
		$table = 'consultancy_project_account_mod';
		$query = $this->db->where('sr_no',$sr_no)->get($this->table6);
		
		if($query->num_rows() == 0 ) return FALSE;
		else $ans = $query->row_array();
		$this->db->insert($table, $ans);
	}
	function edit_emp($sr_no)
	{
		$table = 'consultancy_project_emp_mod';
		//print_r($data);
		$query = $this->db->where('sr_no',$sr_no)->get($this->table7);
		if($query->num_rows() == 0 ) return FALSE;
		else $ans1 = $query->result();
		foreach($ans1 as $ans)
		{
			
			$this->db->insert($table, $ans);
		}
	}
	function update_detail($data)
	{
		$this->db->where('sr_no',$data['sr_no']);
		$this->db->update($this->table6,$data);
	}
	function update_emp($data)
	{
		foreach ($data as $key => $data1) {
			print_r($data1);
			$this->db->where( array('sr_no' => $data1['sr_no'], 'emp_no' => $data1['emp_no']));
			$this->db->update($this->table7,$data1);
		}
	}
	function getStatus($sr_no)
	{
		$query=("SELECT status
					FROM consultancy_disbursement
					WHERE sr_no = '$sr_no' ");
		$query1=$this->db->query($query);
		return $query1->row()->status;
	}
}
?>