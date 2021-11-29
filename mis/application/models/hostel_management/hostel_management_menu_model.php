<?php

class Hostel_Management_Menu_Model extends CI_Model
{
	function __construct()
	{
		// Calling the Model parent constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();

		$menu['deo']=array();
		$menu['deo']['Hostel Management']=array();
		$menu['deo']['Hostel Management']['Assign Hostel Warden'] = site_url('course_structure/elective_offered_home');
		$menu['deo']['Hostel Management']['Add Hostel'] = site_url('course_structure/elective_offered_home');
		$menu['deo']['Hostel Management']['Edit Hostel'] = site_url('course_structure/elective_offered_home');
		
		$menu['hod']=array();
		$menu['hod']['Hostel Management']=array();
		$menu['hod']['Hostel Management']['Hello'] = site_url('course_structure/elective_offered_home');
    	
		return $menu;
	}
}
/* End of file menu_model.php */
/* Location: mis/application/models/course_structure/menu_model.php */