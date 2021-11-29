<?php

class Subject_mapping_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		
		$menu=array();
		//auth ==> hod
		//$menu['hod']=array();
		//$menu['hod']["Course Mapping"] =array();
		//$menu['hod']["Course Mapping"] = site_url('subject_mapping/cbcs_departmentwise/');
		
		//$menu['ft']=array();
	//	$menu['ft']["Course Mapping Status"] = site_url('subject_mapping/faculty_sm_status');
                
        $menu['exam_da1']['Exam Report']=array();
		$menu['exam_da1']['Exam Report']["Course Mapping Status"] = site_url('subject_mapping/faculty_sm_status');
                
        $menu['admin_exam']['Exam Report']=array();
		$menu['admin_exam']["Course Mapping Status"] = site_url('subject_mapping/faculty_sm_status');
		
		
		//$menu['adug']=array();
		//$menu['adug']["Course Mapping"] =array();
		//$menu['adug']["Course Mapping"] = site_url('subject_mapping/cbcs_departmentwise/');
		
		
		#auth ==> ttc
		//$menu['ttc']=array();
		// $menu['ttc']["Course Mapping"] =array();
		// $menu['ttc']["Course Mapping"]= site_url('subject_mapping/cbcs_departmentwise/');
		 
		 #auth ==> dugc
		//$menu['dugc']=array();
		// $menu['dugc']["Course Mapping"] =array();
		// $menu['dugc']["Course Mapping"]= site_url('subject_mapping/faculty_sm_status');
		 
		 #auth ==> adpg
		//$menu['adpg']=array();
		// $menu['adpg']["Course Mapping"] =array();
		// $menu['adpg']["Course Mapping"]= site_url('subject_mapping/cbcs_departmentwise/');
		 
		 #auth ==> dpgc
		$menu['dpgc']=array();
		 $menu['dpgc']["Course Mapping"] =array();
		 $menu['dpgc']["Course Mapping"]= site_url('subject_mapping/faculty_sm_status');
		 
		
		
		
		return $menu;
	}
	
}
/* End of file menu_model.php */
/* Location: mis/application/models/employee/menu_model.php */