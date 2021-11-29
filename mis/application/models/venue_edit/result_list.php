<?php
	/**
      * Author: Raj (rajthegreat80)
     */
	class Result_list extends CI_Model{

		function __construct() {
        	parent::__construct();
   		}
		public function result()
		{
			$result_list_object=$this->db->get("venue");

			
			return $result_list_object->result();

		}
	}
?>