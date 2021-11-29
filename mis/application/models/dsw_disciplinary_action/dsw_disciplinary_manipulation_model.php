<?php 

class Dsw_disciplinary_manipulation_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function form_insert($data){
		$this->db->insert('dsw_disciplinary_action_student_details', $data);
		
	}

	function action_types(){
		$query = $this->db->get('dsw_disciplinary_action_types');
		return $query->result_array();
	}

	function delete_student_id($id){
		$this->db->where('reference_number_of_the_case', $id);
		$this->db->delete('dsw_disciplinary_action_student_details');   
	}

	//KEEP DELETED CASE RECORD
	function deleted_case($data){
		$this->db->insert('dsw_deleted_case', $data);
	}
	
	function replace_data($data){
		$this->db->replace('dsw_disciplinary_action_student_details',$data);   
	}

	function insert_edited($data){
		$this->db->insert('dsw_edited_actions', $data);
	}

	function add_punishment($data){
		$this->db->insert('dsw_disciplinary_action_types', $data);
	}

	function punishment_delete_reason($data){
		$this->db->insert('dsw_deleted_punishments', $data);
	}

	function delete_punishment($id){
		$this->db->where('id', $id);
		$this->db->delete('dsw_disciplinary_action_types');
	}

	/*function insert_implementation_status($data){
		$this->db->insert('dsw_implementation_status', $data);
	}*/

	function update_implementation_status($data){
		$this->db->replace('dsw_implementation_status',$data);
	}

	/*function insert_payment_status($data){
		$this->db->insert('dsw_payment_status', $data);
	}*/

	function update_payment_status($data){
		$this->db->replace('dsw_payment_status',$data);
	}
}

?>