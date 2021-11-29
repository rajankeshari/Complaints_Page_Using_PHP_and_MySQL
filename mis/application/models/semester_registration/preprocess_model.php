<?php
	/**
      * Author: Raj (rajthegreat80)
     */
	class Preprocess_model extends CI_Model{

		function __construct() {
        	parent::__construct();
   		}
		public function department_list()
		{
			$department_list_object=$this->db->get("departments");

			$department_list[]=array();

			foreach($department_list_object->result() as $row)
			{
				
				if($row->type=="academic")
					$department_list[$row->id]=$row->name;
			}
			return $department_list;

		}

		public function course_list()
		{
			$course_list_object=$this->db->get("courses");

			$course_list[]=array();
			
			foreach($course_list_object->result() as $row)
			{
				
					$course_list[$row->id]=$row->name;
			}
			return $course_list;

		}
		public function session_year_list()
		{
			$session_year_list_object=$this->db->get("reg_regular_form");

			$session_year_list[]=array();
			
			foreach($session_year_list_object->result() as $row)
			{
					if(in_array($row->session_year,$session_year_list))
						continue;
					$session_year_list[] = $row->session_year ;
			}
			//sort($session_year_list);
			return $session_year_list;

		}	

		public function branch_list()
		{
			$branch_list_object=$this->db->get("cs_branches");

			$branch_list[]=array();
			
			foreach($branch_list_object->result() as $row)
			{
					if(in_array($row->name,$branch_list))
						continue;
					$branch_list[$row->id] = $row->name ;
			}
			return $branch_list;

		}
		
		public function getName($id){
			$q=$this->db->query("select concat_ws(' ',a.first_name,a.middle_name,a.last_name) as name from user_details as a where a.id=?",array($id));
			//echo $this->db->last_query();
			if($q->num_rows() > 0)
				return $q->row()->name;
			return false;
		}
		
	}