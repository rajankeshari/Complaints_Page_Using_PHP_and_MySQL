<?php

class Consultancy_proposal_approve_model extends CI_Model
{

	//var $table1 = 'consultancy_client_details';
	//var $table = 'consultancy_proposal_details';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	function addConsultancyNo($sr_no,$consultancy_no)
	{
		$query=("UPDATE consultancy_proposal_details set consultancy_no = '$consultancy_no' where sr_no = '$sr_no'; ");
		$query1=$this->db->query($query);
		
	}

	function get_default($sr_no,$payment_no)
	{
		
		$query=("SELECT consultancy_client_details.*,consultancy_proposal_details.*,consultancy_testing_type.*,consultancy_client_type.*
					FROM consultancy_client_details
					INNER JOIN consultancy_proposal_details
					 ON consultancy_client_details.sr_no = consultancy_proposal_details.sr_no
					 INNER JOIN consultancy_testing_type
					 ON consultancy_testing_type.sr_no = consultancy_proposal_details.sr_no
					 INNER JOIN consultancy_client_type
					 ON consultancy_client_type.sr_no = consultancy_proposal_details.sr_no
					WHERE consultancy_proposal_details.sr_no='$sr_no'
					AND consultancy_proposal_details.payment_no='$payment_no';");
		$query1=$this->db->query($query);
		
		return $query1->row();
		/*$query=("SELECT user_details.first_name AS first_name, 
				user_details.middle_name AS middle_name,
				user_details.last_name AS last_name, user_details.email AS email,
				user_address.contact_no AS contact_no, departments.name AS 
				dept_name, emp_basic_details.emp_no AS emp_no, emp_basic_details.designation AS 
				designation_id, designations.id AS desig_id, designations.name AS designation
				FROM user_details
				INNER JOIN user_address ON user_details.id = user_address.id
				INNER JOIN departments ON user_details.dept_id = departments.id
				INNER JOIN emp_basic_details ON user_details.id = emp_basic_details.emp_no
				INNER JOIN designations ON emp_basic_details.designation = designations.id
				WHERE user_details.id = '$id_user'");
		$query1=$this->db->query($query);

		return $query1->result();*/
	}
	function get_default_prev($sr_no,$payment_no,$modification_value='')
	{
		if($modification_value=='')
		{
			$query=("SELECT consultancy_client_details_modification.*,consultancy_proposal_detail_modification.*,
						consultancy_testing_type_modification.*,consultancy_client_type_modification.*
					FROM consultancy_client_details_modification
					INNER JOIN consultancy_proposal_detail_modification
					 ON consultancy_client_details_modification.sr_no = consultancy_proposal_detail_modification.sr_no
					 AND consultancy_client_details_modification.modification_value = consultancy_proposal_detail_modification.modification_value
					 INNER JOIN consultancy_testing_type_modification
					 ON consultancy_testing_type_modification.sr_no = consultancy_proposal_detail_modification.sr_no
					 AND consultancy_testing_type_modification.modification_value = consultancy_proposal_detail_modification.modification_value
					
					 INNER JOIN consultancy_client_type_modification
					 ON consultancy_client_type_modification.sr_no = consultancy_proposal_detail_modification.sr_no
					AND consultancy_client_type_modification.modification_value = consultancy_proposal_detail_modification.modification_value
					WHERE consultancy_proposal_detail_modification.sr_no='$sr_no'
					AND consultancy_proposal_detail_modification.payment_no='$payment_no';");
		$query1=$this->db->query($query);
		
		return $query1->result();
		}
		else
		{
			$query=("SELECT consultancy_client_details_modification.*,consultancy_proposal_detail_modification.*,
						consultancy_testing_type_modification.*,consultancy_client_type_modification.*
					FROM consultancy_client_details_modification
					INNER JOIN consultancy_proposal_detail_modification
					 ON consultancy_client_details_modification.sr_no = consultancy_proposal_detail_modification.sr_no
					 AND consultancy_client_details_modification.modification_value = consultancy_proposal_detail_modification.modification_value
					 INNER JOIN consultancy_testing_type_modification
					 ON consultancy_testing_type_modification.sr_no = consultancy_proposal_detail_modification.sr_no
					 AND consultancy_testing_type_modification.modification_value = consultancy_proposal_detail_modification.modification_value
					
					 INNER JOIN consultancy_client_type_modification
					 ON consultancy_client_type_modification.sr_no = consultancy_proposal_detail_modification.sr_no
					AND consultancy_client_type_modification.modification_value = consultancy_proposal_detail_modification.modification_value
					WHERE consultancy_proposal_detail_modification.sr_no='$sr_no'
					AND consultancy_proposal_detail_modification.payment_no='$payment_no'
					AND consultancy_proposal_detail_modification.modification_value='$modification_value';");
			$query1=$this->db->query($query);
		
			return $query1->row();
		}
		
		/*$query=("SELECT user_details.first_name AS first_name, 
				user_details.middle_name AS middle_name,
				user_details.last_name AS last_name, user_details.email AS email,
				user_address.contact_no AS contact_no, departments.name AS 
				dept_name, emp_basic_details.emp_no AS emp_no, emp_basic_details.designation AS 
				designation_id, designations.id AS desig_id, designations.name AS designation
				FROM user_details
				INNER JOIN user_address ON user_details.id = user_address.id
				INNER JOIN departments ON user_details.dept_id = departments.id
				INNER JOIN emp_basic_details ON user_details.id = emp_basic_details.emp_no
				INNER JOIN designations ON emp_basic_details.designation = designations.id
				WHERE user_details.id = '$id_user'");
		$query1=$this->db->query($query);

		return $query1->result();*/
	}
	function approve($sr_no,$payment_no,$status)
	{
		$this->db->query("UPDATE consultancy_proposal_details set status='$status' where sr_no='$sr_no' && payment_no='$payment_no';");


	}
	function put_receipt($data2)
	{
		$this->db->insert('consultancy_receipt',$data2);
	}
	function put_receipt_edited($data2)
	{
		$payment_no=$data2['payment_no'];
		$sr_no=$data2['sr_no'];
		$table = 'consultancy_receipt_modification';
		$query = $this->db->where('sr_no',$data2['sr_no'])->where('payment_no',$data2['payment_no'])->get('consultancy_receipt');
		
		if($query->num_rows() == 0 ) return FALSE;
		else $ans = $query->row_array();
		
		$query1=$this->db->query("SELECT max(modification_value) as max
						FROM consultancy_receipt_modification 
						where sr_no='$sr_no' and payment_no='$payment_no'") ;
		$query1=$query1->result();
		
		if(!is_null($query1) && $ans['modification_value'] == $query1[0]->max )
		{
			$this->db->where('sr_no',$sr_no);
			$this->db->where('payment_no',$payment_no);
			$this->db->where('modification_value',$query1[0]->max);
			$this->db->update($table,$ans);
		}
		else
		$this->db->insert($table, $ans);
		$this->db->where('sr_no',$sr_no);
		$this->db->where('payment_no',$payment_no);
		$this->db->update('consultancy_receipt',$data2);
	}
	function get_receipt($sr_no,$payment_no)
	{
		$this->db->where('sr_no',$sr_no);
		$this->db->where('payment_no',$payment_no);
		$this->db->select();
		$query=$this->db->get('consultancy_receipt');
		return $query->row();
	}
	function get_receipt_prev($sr_no,$payment_no,$modification_value='')
	{
		if($modification_value=='')
		{
			$this->db->where('sr_no',$sr_no);
			$this->db->where('payment_no',$payment_no);
			$this->db->select();
			$query=$this->db->get('consultancy_receipt_modification');
			return $query->result();
		}
		else
		{
			$this->db->where('sr_no',$sr_no);
			$this->db->where('payment_no',$payment_no);
			$this->db->where('modification_value',$modification_value);
			$this->db->select();
			$query=$this->db->get('consultancy_receipt_modification');
			return $query->row();
		}
	}
	function get_default_all($sr_no)
	{
		$query=("SELECT consultancy_receipt.filepath as filepath,consultancy_receipt.modification_value as modv, consultancy_proposal_details.*
					FROM consultancy_receipt
					RIGHT JOIN consultancy_proposal_details
					 ON consultancy_receipt.sr_no = consultancy_proposal_details.sr_no
					 AND consultancy_proposal_details.payment_no=consultancy_receipt.payment_no
					WHERE consultancy_proposal_details.sr_no='$sr_no'
					ORDER BY consultancy_receipt.payment_no  asc ;");
		$query1=$this->db->query($query);
		
		return $query1->result();
	}
	function get_all_payment()
	{
		$query=("SELECT consultancy_charges.*,consultancy_proposal_details.*
					FROM consultancy_charges
					INNER JOIN consultancy_proposal_details
					 ON consultancy_charges.sr_no = consultancy_proposal_details.sr_no
					ORDER BY consultancy_charges.sr_no desc, 
					consultancy_proposal_details.payment_no desc ;");
		$query1=$this->db->query($query);
		return $query1->result();
		
	}


}

/* End of file post_notice_model.php */
/* Location: mis/application/models/post_notice_model.php */