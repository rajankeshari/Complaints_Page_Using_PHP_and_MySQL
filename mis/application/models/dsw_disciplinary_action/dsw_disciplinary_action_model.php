<?php 

	class dsw_disciplinary_action_model extends CI_Model{

		function __construct(){
			parent::__construct();
		}

		function user_details($admn_no){
			$this->db->where('id', $admn_no);
			$query = $this->db->get('user_details');
			return $query->row();
		}

		function stu_academic($admn_no){
			$this->db->select('course_id, branch_id, semester');
			$this->db->where('admn_no', $admn_no);
			$query = $this->db->get('stu_academic');
			return $query->row();
		}

		function courses($course_id){
			$this->db->where('id', $course_id);
			$query = $this->db->get('courses');
			return $query->row();
		}

		function branches($branch_id){
			$this->db->where('id', $branch_id);
			$query = $this->db->get('branches');
			return $query->row();
		}

		function user_other_details($admn_no){
			$this->db->where('id', $admn_no);
			$query = $this->db->get('user_other_details');
			return $query->row();
		}

		function user_address_hostel($admn_no){
			$this->db->where('id', $admn_no);
			$this->db->where('type','present');
			$query = $this->db->get('user_address');
			return $query->row();
		}

		function user_address_home($admn_no){
			$this->db->where('id', $admn_no);
			$this->db->where('type','permanent');
			$query = $this->db->get('user_address');
			return $query->row();
		}

		function stuadsuser($admn_no){
			//$this->db->select('Email, Mobile_No');
			$this->db->where('Adm_no', $admn_no);
			$query = $this->db->get('stuadsuser');
			return $query->row();
		}

		function get_hod_userid($dept_id) {
			$this->db->select('user_details.id');
			$this->db->from('user_details');
			$this->db->join('user_auth_types', 'user_details.id = user_auth_types.id', 'inner');
			$this->db->where('dept_id', $dept_id);
			$this->db->where('auth_id', 'hod');
			$query = $this->db->get();
				
			return $query->row();
		}

		function get_dept_id_hod($hod_id) {
			$this->db->select('dept_id');
			$this->db->from('user_details');
			$this->db->where('id', $hod_id);
			$query = $this->db->get();
			return $query->row();
		}

		function details_by_department($dept_id) {
			$this->db->where('dept_id', $dept_id);
			$query = $this->db->get('dsw_disciplinary_action_student_details');
			return $query->result();
		}

		//GETTING DETAILS FROM DSW DISCIPLINARY ACTION TABLE

		function get_details_dsw_table($ref){
			//$this->db->select('	admission_no, student_name,details_of_the_case,	date_of_final_notice');
			$this->db->where('reference_number_of_the_case', $ref);
			$query = $this->db->get('dsw_disciplinary_action_student_details');
			return $query->row();
		}

		function get_details_dsw_table_with_admn_no($admn_no){
			$this->db->where('admission_no', $admn_no);
			$this->db->order_by('reference_number_of_the_case', 'DESC');
			$query = $this->db->get('dsw_disciplinary_action_student_details');
			return $query->row();
		}

		//STUDENT PREVIOUS ACTIVITY

		function prev_activity($admn_no){
			$this->db->where('admission_no', $admn_no);
			$query = $this->db->get('dsw_disciplinary_action_student_details');
			return $query->result();
		}

		//ALL ACTIVITY TAKEN TILL NOW

		function all_activity(){
			//$this->db->where('admission_no', $admn_no);
			$this->db->order_by('reference_number_of_the_case', 'DESC');
			$query = $this->db->get('dsw_disciplinary_action_student_details');
			return $query->result();
		}

		function activity_by_id($user_id){
			$this->db->where('admission_no',$user_id);
			$this->db->order_by('reference_number_of_the_case', 'DESC');
			$query = $this->db->get('dsw_disciplinary_action_student_details');
			return $query->result();
		}

		function show_punishment($id){
			$this->db->where('id',$id);
			$query = $this->db->get('dsw_disciplinary_action_types');
			return $query->row();
		}


		function all_reference_numbers(){
		$this->db->select('reference_number_of_the_case');
		$query = $this->db->get('dsw_disciplinary_action_student_details');
		return $query->result_array();
	}



		//ALL PUNISHMENT TYPES TILL NOW 
		function all_punishments(){
			//$this->db->where('admission_no', $admn_no);
			$query = $this->db->get('dsw_disciplinary_action_types');
			return $query->result();
		}

		function history($data){

			// $this->db->select('admission_no');
			// $this->db->where('time>=', "2018-02-01");
			// //$this->db->where('time<=', $end_date);
			// $query = $this->db->get($analyse_by);
			$s="2018-02-01";
			//print_r($data);
			$table=$data['analyse_by'];
			$start=$data['start_date'];
			$end=$data['end_date']." 23:59:59";

			if (($table == 'dsw_disciplinary_action_student_details') || ($table == 'dsw_edited_actions') || ($table == 'dsw_deleted_case')) {
				$q="SELECT * FROM `$table` WHERE `time`<='$end'&&`time`>='$start'";
				# code...
			} elseif(($table == 'dsw_implementation_status') || ($table == 'dsw_payment_status')) {
				$q="SELECT * FROM `$table` WHERE `time_of_update`<='$end'&&`time_of_update`>='$start'";
				# code...
			}
			

			//print_r($this->db->query($q)->result_array());
			return $this->db->query($q)->result(); 

			
		}

	}

 ?>