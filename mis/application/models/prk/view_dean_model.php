<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class view_dean_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function get_prk_types()	{
		// Get the different publication types
		$query = " SELECT * FROM prk_types WHERE 1 ";
		$result = $this->db->query($query)->result();
		$data = array();
		$data['prk_types'] = array();
		$i = 1;
		foreach ($result as $pub){
			$data['prk_types'][$i] = $pub->type_name;
			$i++;
		}
		$data['prk_type_size'] = $i - 1;
		return $data;
	}
	public function getDepartmentList(){
		$query = "SELECT name FROM departments WHERE type = 'academic'";
		$result = $this->db->query($query)->result();
		$data = array();
		$data['dept'] = array();
		$i = 1;
		foreach($result as $pub){
			$data['dept'][$i] = $pub->name;
			$i++;
		}
		$data['count'] = $i-1;
		return $data;
	}
	public function getauthor($dept)
	{
		$basic_query = "SELECT id,concat_ws(' ',salutation,first_name,middle_name,last_name) AS name FROM user_details WHERE dept_id IN (SELECT DISTINCT id FROM departments WHERE name = '$dept' ) AND id IN (SELECT emp_no FROM emp_basic_details WHERE auth_id = 'ft') order by   concat_ws(' ',first_name,middle_name,last_name) ";
		
		return $this->db->query($basic_query)->result_array();
		/*print_r($result);die();*/

		// $data = array();
		// $data['auth'] = array();
		// $i = 1;
		// foreach($result as $pub){
		// 	$data['auth'][$i] = $pub->name;
		// 	$i++;
		// }
		// $data['fac'] = $i-1;
		// return $data;
	}
	
}