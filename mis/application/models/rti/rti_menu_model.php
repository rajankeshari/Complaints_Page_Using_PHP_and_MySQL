<?php

class Rti_menu_model extends CI_Model
{
	function __construct()
	{
		// Calling the Model parent constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		
		$menu['hod']=array();
		$menu['hod']['Right To Information']=array();
		$menu['hod']["Right To Information"]["RTI Reply"] = site_url('rti/rtidp_file');
		$menu['hod']['Right To Information']["RTI Filter"] = site_url('rti/rtidp_filter');

		$menu['rti_inc']=array();
		$menu['rti_inc']["RTI File"] = site_url('rti/rti_file');
		$menu['rti_inc']["RTI Filter"] = site_url('rti/rti_filter');
		
		
		/*
		$menu['hod']['Course Structure']=array();
		$userid = $this->session->userdata('id');
    	$menu['hod']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/'.$userid.'');

		$menu['hod']['Course Structure']["Offer Optional Subjects"] = site_url('course_structure/elective_offered_home');
		*/

			
		//$menu['hod']=array();
		//$menu['hod']['Choose Elective']=array();
		//$menu['hod']['Course Structure']["Offer Elective"] = site_url('course_structure/elective_offered_home');
		
		//$menu['stu']=array();
		//$menu['stu']['Course Structure']=array();
		//$menu['stu']["Course Structure"]["Edit Course Structure"] = site_url('course_structure/edit');


		return $menu;
	}
}
/* End of file menu_model.php */
/* Location: mis/application/models/course_structure/menu_model.php */