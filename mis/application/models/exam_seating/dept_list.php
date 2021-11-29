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

			
			$department_list['nlhc']='New Lecture Hall';
			$department_list['penman']='Penman Auditorium';
			$department_list['olhc']='Old Lecture Hall';

			foreach($department_list_object->result() as $row)
			{
				
				if($row->type=="academic")
					$department_list[$row->id]=$row->name;
			}
			return $department_list;

		}
	}
?>