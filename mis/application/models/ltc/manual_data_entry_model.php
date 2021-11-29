<?

class Manual_data_entry_model extends CI_Model
{
	public function get_ltc_types()
	{
		$query = " SELECT * FROM ltc_types";
		return $this->db->query($query)->result();
	}
	public function get_all_leaves()
	{
		$query = " SELECT * FROM leave_types ";
		return $this->db->query($query)->result();
	}
	public function get_block_years()
	{
		$query = " SELECT year_range FROM ltc_block_years ";
		return $this->db->query($query)->result();
	}
	public function get_employee_details($emp_no)
	{
		$query = " SELECT * FROM user_details WHERE id = '$emp_no' ";
		return $this->db->query($query)->result();
	}
	public function get_joining_date_of_employee($emp_no)
	{
		$query = " SELECT joining_date FROM emp_basic_details WHERE emp_no = '$emp_no' ";
		$date = $this->db->query($query)->result();
		return $date[0]->joining_date;
	}
	public function get_ltc_name_by_id($id)
	{
		$query = " SELECT type FROM ltc_types WHERE id = '$id' ";
		return $this->db->query($query)->result();
	}
	public function get_family_details($id)
	{
		$query = " SELECT name,relationship,dob,sno FROM emp_family_details WHERE emp_no = '$id' ";
		return $this->db->query($query)->result();
	}
	public function get_emp_designation_by_emp_no($emp_no)
	{
		$query = " SELECT name FROM designations WHERE id IN (SELECT designation FROM emp_basic_details WHERE emp_no = '$emp_no' )";
		$result = $this->db->query($query)->result();
		return $result[0]->name;
	}
	public function get_emp_department_by_emp_no($emp_no)
	{
		$query = " SELECT name FROM departments WHERE id IN (SELECT dept_id FROM user_details WHERE id = '$emp_no') ";
		$result = $this->db->query($query)->result();
		return $result[0]->name;
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
		$status = 2;

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
		$this->db->query($query);
	}
}

?>