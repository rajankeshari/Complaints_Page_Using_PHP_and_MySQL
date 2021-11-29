<?php
	/**
      * Author: Raj (rajthegreat80)
     */
	class Seating extends CI_Model{

		function __construct() {
        	parent::__construct();
   		}

		public function get_class($dept_id,$class_name)
		{
			$this->db->where('dept_id',$dept_id);
			$this->db->where('room_no',$class_name);
			$class=$this->db->get("venue");
			$class= $class->result();
			return $class[0];

		}
	}
?>