<?php
	/**
      * Author: Raj (rajthegreat80)
     */
	class Dept_list extends CI_Model{

		function __construct() {
        	parent::__construct();
   		}

		public function department_list()
		{
			$department_list_object=$this->db->get("departments");

			$department_list[]=array();


			foreach($department_list_object->result() as $row)
			{
					$department_list[$row->id]=$row->name;
			}
			
			return $department_list;

		}
	}
?>