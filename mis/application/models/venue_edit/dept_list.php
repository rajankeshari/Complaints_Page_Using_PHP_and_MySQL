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
			$department_list_object=$this->db->get("cbcs_departments");

			$department_list[]=array();

			
			$department_list['nlhc']='New Lecture Hall';
			$department_list['penman']='Penman Auditorium';
			$department_list['olhc']='Old Lecture Hall';
			$department_list['cc']='Computer Center';

			foreach($department_list_object->result() as $row)
			{
				
				if($row->type=="academic" and $row->status=='1')
					$department_list[$row->id]=$row->name;
			}
			return $department_list;

		}
		
		
		function department_list_deptwise($id, $auth) {
			

        $department_list_object=$this->db->get("cbcs_departments");
		$department_list_deptwise[]=array();

			if ($auth=='hod' or $auth=='ttc'){
			//$department_list_deptwise['nlhc']='New Lecture Hall';
			//$department_list_deptwise['penman']='Penman Auditorium';
			//$department_list_deptwise['olhc']='Old Lecture Hall';
		
			foreach($department_list_object->result() as $row)
			{
			
				if($row->type=="academic" and $row->status=="1" and $row->id=="$id")
					$department_list_deptwise[$row->id]=$row->name;						
				
			}
			}
			
			if($auth=='ttch'){
			$department_list_deptwise['nlhc']='New Lecture Hall';
			$department_list_deptwise['penman']='Penman Auditorium';
			$department_list_deptwise['olhc']='Old Lecture Hall';
			$department_list_deptwise['cc']='Computer Center';
		
			foreach($department_list_object->result() as $row)
			{
			
				if($row->type=="academic" and $row->status=="1")
					$department_list_deptwise[$row->id]=$row->name;						
				
			}
			}
			return $department_list_deptwise;
    }
	}
?>