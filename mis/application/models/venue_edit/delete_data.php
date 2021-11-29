<?php
	/**
      * Author: Raj (rajthegreat80)
     */
	class Delete_data extends CI_Model{


		public function del()
		{
			$dept_id=$this->input->post('dept_id');
			$class_name=$this->input->post('class_name');

		$db=$this->load->database();
		$this->db->where('dept_id',$dept_id);
		$this->db->where('room_no',$class_name);
		$this->db->delete('venue');
		}
	}
?>