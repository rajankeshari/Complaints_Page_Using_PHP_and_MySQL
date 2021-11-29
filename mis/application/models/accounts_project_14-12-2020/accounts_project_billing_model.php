<?php 
	
	class Accounts_project_billing_model extends CI_model{

		function __construct()
		{
			// Call the Model constructor
			parent::__construct(array('ft','project_so', 'project_admin','dean_rnd','ar_project'));
			$this->load->model ('user_model');
		}

		// update project data from edit project form...
		function update_running_trans($table, $data)
		{
			foreach ($data as $key => $value) {
				if(!in_array($key, array('transaction_id','project_no','status')))
				{
					$this->db->set('approved_amount', $value);
					$this->db->set('status', $data['status']);
					$this->db->where(array('transaction_id' => $data['transaction_id'], 'head_name' => $key));
					$this->db->update($table);
				}
			}
			// update table 'accounts_project_running_trans' 
			// update approved amount by project_so
		}

		//Delete entry from accounts_project_running_trans and add entry to accounts_project_trans_history...
		function delete_running_trans($history,$status)
		{
			$query1 = $this->db->get_where('accounts_project_running_trans', array('project_no' => $history['project_no'], 'transaction_id' => $history['transaction_id']));
			$existing = $query1->result_array();

			$temp = array();
			foreach ($existing as $arr) {
				$abc = array();
				foreach ($arr as $key => $value) {
					if($key=='status')
						$abc['status'] = $status;
					else if ($key == 'admin_entry' && $value != '0000-00-00 00:00:00') {
					}
					else
					{
						$abc[$key]=$value;
					}
				}
				array_push($temp, $abc);
			}

			$this->db->insert_batch('accounts_project_trans_history',$temp);
			$this->db->delete('accounts_project_running_trans', array('transaction_id' => $history['transaction_id']));
		}	

		// insert 'data' into table 
		function add($table, $data)
		{
			$this->db->insert($table, $data);
		}

		// add values to accounts_project_funds table on approval by admin
		function addExpenditure($data, $project_no)
		{
			foreach ($data as $key => $value)
			{
				if((float)$value != 0 && $value != ''){
					$this->db->set('amount_spent', 'amount_spent'.'+'.(float)$value, FALSE);
					$this->db->set('emp_id', $this->session->userdata('id'));
					$this->db->where('head_name', $key);	
					$this->db->where('project_no', $project_no);
					$this->db->update('accounts_project_funds');
				}
			}
				
		}

		function getExpenditureByProjectNo($project_no)
		{
			$query = $this->db->get_where('accounts_project_actual_expenditure', array('project_no' => $project_no));
			return $query->num_rows();
		}

		//Return entries from accounts_project_running_trans related to the project no. according to status($auth)...
		function get_run_trans_details($project_no,$auth)
		{
			if($auth=='SO')
				$status = '"SO_Processing" OR status = "Admin_Rejected"';
			else if($auth=='Admin')
				$status = '"Admin_Processing" OR status = "Dean_Rejected"';
			else if($auth=='Dean')
				$status = '"Dean_Processing"';
			$query =  $this->db->query('
				SELECT *
				FROM accounts_project_running_trans
				WHERE project_no = "'.$project_no.'" AND (status = '.$status.')' 
			);
			$data = $query->result_array();
			return $data;
		}

		// get bills by emp id
		function get_bills_by_empId($bill_type)
		{
			$empId = $this->session->userdata('id');
			if($bill_type == 'All')
			{
				$query = $this->db->query("

							SELECT DISTINCT(transaction_id), project_no, status, admin_entry
							from accounts_project_trans_history 
							WHERE project_no IN(
								SELECT DISTINCT(project_no) FROM accounts_project_details
								WHERE pi_id= '$empId'
								UNION
								SELECT DISTINCT(project_no) FROM accounts_project_p_co_i
								WHERE p_co_i_id = '$empId')
							UNION
							SELECT DISTINCT(transaction_id), project_no, status, admin_entry
							from accounts_project_running_trans 
							WHERE project_no IN(
								SELECT DISTINCT(project_no) FROM accounts_project_details
								WHERE pi_id= '$empId'
								UNION
								SELECT DISTINCT(project_no) FROM accounts_project_p_co_i
								WHERE p_co_i_id = '$empId')
					");
			}
			else
			{
				$query = $this->db->query("

							SELECT DISTINCT(transaction_id), project_no, status, admin_entry
							from accounts_project_trans_history 
							WHERE status='$bill_type' and project_no IN(
								SELECT DISTINCT(project_no) FROM accounts_project_details
								WHERE pi_id= '$empId'
								UNION
								SELECT DISTINCT(project_no) FROM accounts_project_p_co_i
								WHERE p_co_i_id = '$empId')
							UNION
							SELECT DISTINCT(transaction_id), project_no, status, admin_entry
							from accounts_project_running_trans 
							WHERE status='$bill_type' and project_no IN(
								SELECT DISTINCT(project_no) FROM accounts_project_details
								WHERE pi_id= '$empId'
								UNION
								SELECT DISTINCT(project_no) FROM accounts_project_p_co_i
								WHERE p_co_i_id = '$empId')
					");
			}
			$data['bills'] = $query->result_array();
			return $data;
		}

		// get all bills for so/admin
		function get_bills_all($bill_type)
		{
			if($bill_type == 'All')
			{
				$query = $this->db->query("

							SELECT DISTINCT(transaction_id), project_no, status
							from accounts_project_trans_history 
							UNION
							SELECT DISTINCT(transaction_id), project_no, status
							from accounts_project_running_trans 
					");
			}
			else
			{
				$query = $this->db->query("

							SELECT DISTINCT(transaction_id), project_no, status
							from accounts_project_trans_history 
							WHERE status='$bill_type'
							UNION
							SELECT DISTINCT(transaction_id), project_no, status
							from accounts_project_running_trans 
							WHERE status='$bill_type'
					");
			}
			$data['bills'] = $query->result_array();
			return $data;
		}


		//Return entry from accounts_project_running_trans based on transaction id...
		function get_bill_by_id($id)
		{
			$query = $this->db->get_where('accounts_project_running_trans', array('transaction_id' => $id));

			return $query->result_array();

		}
		//Return entry from accounts_project_trans_files based on transaction id...
		function get_bill_files_by_id($id)
		{
			$query = $this->db->get_where('accounts_project_trans_files', array('transaction_id' => $id));

			return $query->result_array();
		}

		//Handle reject from admin, change status...
		function admin_reject($data)
		{
			$query  = $this->db->query('UPDATE accounts_project_running_trans SET status = "'.$data['status'].'",remarks = "'.$data['remarks'].'" WHERE transaction_id = "'.$data['transaction_id'].'"');
		}

		//Reject bill by SO...
		function so_reject($data)
		{
			$query1 = $this->db->get_where('accounts_project_running_trans', array('transaction_id' => $data['transaction_id']));
			$existing = $query1->result_array();
			
			$history = array();
			foreach ($existing as $arr) {
				$temp = array(
					'transaction_id' => $data['transaction_id'],
					'project_no' => $data['project_no'],
					'head_name' => $arr['head_name'],
					'claimed_amount' => $arr['claimed_amount'],
					'faculty_entry' => $arr['faculty_entry'],
					'status' => $data['status'],
					'remarks' => $data['remarks']
				);
				array_push($history, $temp);
			}

			$this->db->insert_batch('accounts_project_trans_history',$history);
			$this->db->delete('accounts_project_running_trans', array('transaction_id' => $data['transaction_id']));
		}

		//Get history of transaction...
		function get_history_by_project($project_no)
		{
			$this->db->order_by("admin_entry","DESC");
			$query = $this->db->get_where('accounts_project_trans_history',array('project_no' => $project_no));			
			// echo $this->db->last_query();
			return $query->result_array();
		}
		function get_history_by_id($transaction_id)
		{
			$this->db->order_by("admin_entry","DESC");
			$query = $this->db->get_where('accounts_project_trans_history',array('transaction_id' => $transaction_id));
			return $query->result_array();
		}

		function getAuthorityDetails()
		{
			$query = $this->db->get_where('user_auth_types',array('auth_id' => 'project_so'));
			$SO = $this->user_model->getById($query->row_array()['id']);
			$SO_Name = $SO->salutation.' '.$SO->first_name.' '.$SO->middle_name.' '.$SO->last_name;

			$query = $this->db->get_where('user_auth_types',array('auth_id' => 'project_admin'));
			$Admin = $this->user_model->getById($query->row_array()['id']);
			$Admin_Name = $Admin->salutation.' '.$Admin->first_name.' '.$Admin->middle_name.' '.$Admin->last_name;

			$query = $this->db->get_where('user_auth_types',array('auth_id' => 'dean_rnd'));
			$Dean = $this->user_model->getById($query->row_array()['id']);
			$Dean_Name = $Dean->salutation.' '.$Dean->first_name.' '.$Dean->middle_name.' '.$Dean->last_name;

			$data['authority'] = [
				'SO' => $SO_Name,
				'Admin' => $Admin_Name,
				'Dean' => $Dean_Name,
			];

			return $data;
		}
	}