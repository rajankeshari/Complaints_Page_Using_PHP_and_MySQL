<?php

class Employee_model extends CI_Model
{
	public function get_ltc_types()
	{
		$query = " SELECT * FROM ltc_types";
		return $this->db->query($query)->result();
	}
	public function get_block_years()
	{
		$query = " SELECT year_range FROM ltc_block_years ";
		return $this->db->query($query)->result();
	}
	public function get_family_details($id)
	{
		$query = " SELECT name,relationship,dob,sno FROM emp_family_details WHERE active_inactive = 'Active' AND emp_no = '$id' ";
		return $this->db->query($query)->result();
	}
	public function get_ltc_name_by_id($id)
	{
		$query = " SELECT type FROM ltc_types WHERE id = '$id' ";
		return $this->db->query($query)->result();
	}
	public function get_departments_of_given_type($dept_type)
	{
		$query = " SELECT id,name FROM departments WHERE type = '$dept_type' ";
		return $this->db->query($query)->result();
	}
	public function get_designation_by_department_id($dept_id)
	{
		$query = $this->db->query("SELECT DISTINCT designations.id AS id, designations.name AS name FROM designations INNER JOIN user_details INNER JOIN emp_basic_details ON designations.id = emp_basic_details.designation AND user_details.id = emp_basic_details.emp_no WHERE user_details.dept_id = '".$dept_id."' ORDER BY designations.name;");
		return $query->result();
	}
	public function get_emp_name_by_designation_and_department($designation, $dept)
	{
		$query = " SELECT concat(salutation,' ',first_name,' ',middle_name,' ',last_name) AS name,id FROM user_details WHERE dept_id = '$dept' AND id IN (SELECT emp_no FROM emp_basic_details WHERE designation = '$designation' ) ";
		return $this->db->query($query)->result();
	}
	public function get_joining_date_of_employee($emp_no)
	{
		$query = " SELECT joining_date FROM emp_basic_details WHERE emp_no = '$emp_no' ";
		$date = $this->db->query($query)->result();
		return $date[0]->joining_date;
	}
	public function get_ltc_taken_in_past($data)
	{
		$emp_no = $data['emp_no'];
		$type_of_ltc = $data['ltc_type'];
		$block_year = $data['block_year'];
		$query = " SELECT ltc_no,type_of_ltc,journey_start_date AS start_date FROM ltc_application WHERE emp_no = '$emp_no' AND block_year = '$block_year' AND status != '2' AND status != '6' ";
		return $this->db->query($query);
	}
	public function get_members_for_given_ltc_no($ltc_no)
	{
		$query = " SELECT emp_no,sno FROM ltc_availing_members WHERE ltc_no = '$ltc_no' ";
		return $this->db->query($query)->result();
	}
	public function get_dependent_name($emp_no,$sno)
	{
		$query = " SELECT name FROM emp_family_details WHERE emp_no = '$emp_no' AND sno = '$sno' ";
		$name = $this->db->query($query)->result();
		return $name[0]->name;
	}
	public function insert_ltc_application($data)
	{
		$emp_no = $data['emp_no'];
		$type_of_ltc = $data['ltc_type'];
		$block_year = $data['block_year'];
		$place_of_visit = $data['place_of_visit'];
		$journey_start_date = $data['start_date'];
		$journey_end_date = $data['end_date'];
		$leave_type = $data['leave_type'];
		$no_of_days_el_taken = $data['no_of_days_el_taken'];
		$el_taken = $data['el_taken'];
		$status = 0;

		$query = " INSERT INTO ltc_application (emp_no,el_taken,no_of_days_el_taken,journey_start_date,journey_end_date,leave_type,type_of_ltc,block_year,place_of_visit,status) VALUES ('$emp_no','$el_taken','$no_of_days_el_taken','$journey_start_date','$journey_end_date','$leave_type','$type_of_ltc','$block_year','$place_of_visit','$status') ";
		$this->db->query($query);
	}
	public function get_max_application_id($emp_no)
	{
		$query = " SELECT max(ltc_no) AS max_id FROM ltc_application WHERE emp_no = '$emp_no' ";
		$result = $this->db->query($query)->result();
		return $result[0]->max_id;
	}
	public function insert_family_members($data,$max_id)
	{
		$query = " INSERT INTO ltc_availing_members (ltc_no,emp_no,sno) VALUES ";
		$emp_no = $data['emp_no'];
		$flag = false;
		for ($i = 0; $i <= $data['count']; $i++)
		{
			if ($data[$i] == true)
			{
				if ($flag == false)
				{
					$query .= " ('$max_id','$emp_no','$i') ";
					$flag = true;
				}
				else if ($flag == true)
					$query .= " ,('$max_id','$emp_no','$i') ";
			}
		}
		echo $query;
		var_dump($data);
		$this->db->query($query);
	}
	public function get_applied_in_current_year_status($data,$sno,$start_date_year)
	{
		$emp_no = $data['emp_no'];
		$query = " SELECT * FROM ltc_availing_members WHERE emp_no = '$emp_no' AND sno = '$sno' AND ltc_no IN ( SELECT ltc_no FROM ltc_application WHERE emp_no = '$emp_no' AND YEAR(start_date) = '$start_date_year' AND status != '2') ";
		return $this->db->query($query)->num_rows();
	}
	public function forward_approval_or_reject($current_employee,$next_employee,$ltc_no,$remarks)
	{
		$query = " INSERT INTO ltc_status (ltc_no,current_employee,next_employee,remarks) VALUES ('$ltc_no','$current_employee','$next_employee','$remarks') ";
		$this->db->query($query);
	}
	public function get_all_ltc_of_employee($emp_no)
	{
		$query = " SELECT * FROM ltc_application WHERE emp_no = '$emp_no' ";
		return $this->db->query($query);
	}
	public function get_ltc_type_name_by_id($type_of_ltc)
	{
		$query = " SELECT type FROM ltc_types WHERE id = '$type_of_ltc' ";
		$result = $this->db->query($query)->result();
		return $result[0]->type;
	}
	public function get_member_name_by_emp_id_and_sno($emp_no,$sno)
	{
		$query = " SELECT name FROM emp_family_details WHERE emp_no = '$emp_no' AND sno = '$sno' ";
		$result = $this->db->query($query)->result();
		return $result[0]->name;
	}
	public function get_member_relationship($emp_no,$sno)
	{
		$query = " SELECT relationship FROM emp_family_details WHERE emp_no = '$emp_no' AND sno = '$sno' ";
		$result = $this->db->query($query)->result();
		return $result[0]->relationship;
	}
	public function get_ltc_details_for_given_ltc_no($ltc_no)
	{
		$query = " SELECT * FROM ltc_application WHERE ltc_no = '$ltc_no' ";
		return $this->db->query($query);
	}
	public function get_ltc_for_approval_by_current_employee($emp_id)
	{
		$query = "SELECT * FROM ltc_application WHERE ltc_no IN ( SELECT ltc_no FROM ltc_status WHERE id IN (SELECT id FROM ltc_status WHERE next_employee = '$emp_id' AND action_taken = '0') )";
		return $this->db->query($query);
	}
	public function get_emp_name_by_emp_no($emp_no)
	{
		$query = " SELECT concat(salutation,' ',first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE id = '$emp_no' ";
		$name = $this->db->query($query)->result();
		return $name[0]->name;
	}
	public function get_ltc_status_id_for_approval($next_employee,$ltc_no,$action_taken)
	{
		$query = " SELECT id FROM ltc_status WHERE ltc_no = '$ltc_no' AND next_employee = '$next_employee' AND action_taken = '$action_taken' ";
		$result = $this->db->query($query)->result();
		if ($this->db->query($query)->num_rows != 0)
			return $result[0]->id;
		else
			return 0;
	}
	public function get_auth_id_for_current_emp($emp_id)
	{
		$query = " SELECT auth_id FROM user_auth_types WHERE id = '$emp_id' ";
		return $this->db->query($query)->result();
	}
	public function get_ltc_no_by_id($id)
	{
		$query = " SELECT ltc_no FROM ltc_status WHERE id = '$id' ";
		$result = $this->db->query($query)->result();
		return $result[0]->ltc_no;
	}
	public function get_emp_no_by_ltc_no($ltc_no)
	{
		$query = " SELECT emp_no FROM ltc_application WHERE ltc_no = '$ltc_no' ";
		$result = $this->db->query($query)->result();
		return $result[0]->emp_no;
	}
	public function approve_ltc($ltc_no,$id,$emp_no)
	{
		$query = " UPDATE ltc_application SET status = '2' WHERE ltc_no = '$ltc_no' ";
		$this->db->query($query);

		$query = " UPDATE ltc_status SET action_taken = '1' WHERE id = '$id' ";
		$this->db->query($query);

		$query = " INSERT INTO ltc_status (ltc_no,current_employee,next_employee,action_taken) VALUES ('$ltc_no','$emp_no','$emp_no','1') ";
		$this->db->query($query);
	}
	public function approve_cancellation_ltc($ltc_no,$id,$emp_no)
	{
		$query = " UPDATE ltc_application SET status = '6' WHERE ltc_no = '$ltc_no' ";
		$this->db->query($query);

		$query = " UPDATE ltc_status SET action_taken = '1' WHERE id = '$id' ";
		$this->db->query($query);

		$query = " INSERT INTO ltc_status (ltc_no,current_employee,next_employee,action_taken) VALUES ('$ltc_no','$emp_no','$emp_no','1') ";
		$this->db->query($query);
	}
	public function see_if_already_approved($ltc_no)
	{
		$query = " SELECT status FROM ltc_application WHERE ltc_no = '$ltc_no' ";
		$result = $this->db->query($query)->result();
		return $result[0]->status;
	}
	public function reject_ltc($ltc_no,$id,$emp_no,$reason)
	{
		$query = " UPDATE ltc_application SET status = '3' WHERE ltc_no = '$ltc_no' ";
		$this->db->query($query);

		$query = " UPDATE ltc_status SET action_taken = '1' WHERE id = '$id' ";
		$this->db->query($query);

		$query = " INSERT INTO ltc_status (ltc_no,current_employee,next_employee,action_taken,remarks) VALUES ('$ltc_no','$emp_no','$emp_no','1','$reason') ";
		$this->db->query($query);
	}
	public function set_action_taken_for_current_id($id,$action_taken)
	{
		$query = " UPDATE ltc_status SET action_taken = '$action_taken' WHERE id = '$id' ";
		$this->db->query($query);
	}
	public function set_status_of_ltc($ltc_no,$status)
	{
		$query = " UPDATE ltc_application SET status = '$status' WHERE ltc_no = '$ltc_no' ";
		$this->db->query($query);
	}
	public function get_last_responding_employee($ltc_no)
	{
		$query = " SELECT next_employee FROM ltc_status WHERE ltc_no = '$ltc_no' AND action_taken = '1' AND id IN (SELECT MAX(id) FROM ltc_status WHERE ltc_no = '$ltc_no' AND action_taken = '1') ";
		$result = $this->db->query($query)->result();
		if ($this->db->query($query)->num_rows() != 0)
			return $result[0]->next_employee;
		else
			return false;
	}
	public function get_last_non_responding_employee($ltc_no)
	{
		$query = " SELECT next_employee FROM ltc_status WHERE ltc_no = '$ltc_no' AND action_taken = '0' AND id IN (SELECT MAX(id) FROM ltc_status WHERE ltc_no = '$ltc_no' AND action_taken = '0') ";
		$result = $this->db->query($query)->result();
		if ($this->db->query($query)->num_rows() != 0)
			return $result[0]->next_employee;
		else
			return false;
	}
	public function see_if_already_responded($ltc_status_id)
	{
		$query = " SELECT action_taken FROM ltc_status WHERE id = '$ltc_status_id' ";
		$result = $this->db->query($query)->result();
		return $result[0]->action_taken;
	}
	public function get_ltc_responded_by_current_employee($emp_id)
	{
		$query = "SELECT * FROM ltc_application WHERE ltc_no IN ( SELECT ltc_no FROM ltc_status WHERE id IN (SELECT id FROM ltc_status WHERE next_employee = '$emp_id' AND action_taken = '1') )";
		return $this->db->query($query);
	}
	public function get_el_taken_by_emp_no($emp_no)
	{
		$query = " SELECT el_balance FROM ltc_el_balance WHERE emp_no = '$emp_no' ";
		$result = $this->db->query($query)->result();
		return $result[0]->el_balance;
	}

	public function get_cancellable_ltc($emp_no)
	{
		$query = " SELECT * FROM ltc_application WHERE emp_no = '$emp_no' AND (status = '0' OR status = '2' OR status = '4') ";
		return $this->db->query($query);
	}
	public function get_places($ltc_type)
	{
		$query = " SELECT * FROM ltc_places_of_visit WHERE ltc_type = '$ltc_type' AND status = '1' ";
		if ($this->db->query($query)->num_rows > 0)
			return $this->db->query($query)->result();
		return 0;
	}
	public function get_all_leaves()
	{
		$query = " SELECT * FROM leave_types ";
		return $this->db->query($query)->result();
	}
}

?>